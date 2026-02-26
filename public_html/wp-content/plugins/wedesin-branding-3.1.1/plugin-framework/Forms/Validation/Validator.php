<?php

/**
 * Třída Validator
 *
 * Tato třída se používá k validaci formulářových polí na základě definovaných pravidel.
 */

namespace digibrand\framework\Forms\Validation;

use digibrand\framework\Forms\Validation\SetValidationRules;
use digibrand\framework\d1g1Session;
use D1g1Notice;

class Validator
{

    use FieldValidator;

    protected $rules;     // Pravidla validace
    protected $verdict;   // Výsledek validace
    protected $messages;  // Zprávy o chybách
    protected $is_valid = true;  // Příznak platnosti formuláře

    /**
     * Konstruktor třídy Validator
     *
     * @param SetValidationRules $rules   Objekt obsahující pravidla validace
     * @param mixed              $Request Objekt reprezentující požadavek
     */
    public function __construct(SetValidationRules $rules, $Request)
    {
        $this->rules = $rules;
        $this->validate($Request);
    }

    /**
     * Metoda pro validaci formuláře
     *
     * @param mixed $Request Objekt reprezentující požadavek
     */
    protected function validate($Request)
    {   
       
        foreach ($this->rules->rules as $key => $rule) {
            $this->validate_field($rule, str_replace($this->rules->form_id.'_',"",$key), $this->rules->values[$key], $Request);
        }
        $this->check_verdict();
        if ($this->messages) {
            $this->save_values_to_session($this->rules->values);
         
            // add_action('admin_notices', [$this, 'build_notices']);
            D1g1Notice::warning($this->messages);
            $this->is_valid = false;

        }
    }
    /**
     * Metoda pro uložení hodnot do session
     * @param array $values
     * @return void
     * @author digihood
     */
    protected function save_values_to_session($values)
    {   
        foreach ($values as $key => $value) {
            d1g1Session::add_session($key, $value);
        }
    }
    /**
     * Metoda pro vytvoření upozornění s chybami
     */
    public function build_notices()
    {
        // ob_start();
?>

        <div class="notice notice-warning d1g1-notice is-dismissible">
            <?php
            foreach ($this->messages as $field => $messages) {
                foreach ($messages as $message) {
            ?>
                    <p><?= $message; ?></p>
            <?php
                }
            }
            ?>
            <button type="button" class="notice-dismiss"><span class="screen-reader-text">Skrýt toto upozornění.</span></button>
        </div>
<?php
        // $output = ob_get_contents();
        // ob_end_clean();
        // return $output;
    }

    /**
     * Metoda pro ověření výsledku validace
     */
    protected function check_verdict(){
        $Messages = include(__DIR__ . '/Messages.php');
        if($this->verdict){
            foreach ($this->verdict as $key => $field) {
                if(isset($field['required'])){
                    d1g1Session::add_session('valid-'.$key,$field);
                }
                if($field){
                    foreach ($field as $type => $verdict) {
                        if (!$verdict) {
                            $trans['one'] = $this->rules->names[$key];
                            if(isset($this->rules->rules[$key]['rule'][1]) && $this->rules->rules[$key]['rule'][1]){
                                $exp = explode(':',$this->rules->rules[$key]['rule'][1]);
                                if(isset($exp[1])){
                                    $trans['two'] = $this->rules->names[$exp[1]];
                                }
                            }
                            if (isset($Messages[$type]) && $Messages[$type]) {
                                $this->messages[$key][$type] = $this->replaceKeysInString($Messages[$type], $trans);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Metoda pro validaci jednoho pole
     *
     * @param string $rule       Pravidla validace pro pole
     * @param string $fieldname  Název pole
     * @param mixed  $value      Hodnota pole
     * @param mixed  $Request    Objekt reprezentující požadavek
     */
    protected function validate_field($rule, $fieldname, $value, $Request)
    {   
       if(isset($rule['rule_string'])){
            $rule = explode('|', $rule['rule_string']);
            foreach ($rule as $key => $rule) {
                $explode_rule = explode(':', $rule);
                if ($explode_rule[0]) {
                    $this->verdict[$fieldname][$explode_rule[0]] = $this->check_rule($explode_rule, $value, $Request, $fieldname);
                }
            }
        }
    }

    /**
     * Metoda pro ověření jednoho pravidla validace
     *
     * @param array $rule     Pole obsahující pravidlo validace
     * @param mixed $value    Hodnota pole
     * @param mixed $Request  Objekt reprezentující požadavek
     *
     * @return bool           Výsledek ověření
     */
    protected function check_rule($rule, $value, $Request, $fieldname = null)
    {
        switch ($rule[0]) {
            case 'required':
                return $this->required($value);
                break;
            case 'string':
                return  $this->string($value);
                break;
            case 'min':
                $type = $this->rules->get_type_field($fieldname);
                return $this->min($value, $rule[1], $type);
                break;
            case 'max':
                $type = $this->rules->get_type_field($fieldname);
                return $this->max($value, $rule[1], $type);
                break;
            case 'email':
                return $this->email($value);
                break;
            case 'url':
                return $this->url($value);
                break;
            case 'number':
                return $this->numeric($value);
                break;
            case 'same':
                return $this->same($value, $rule, $this->rules->values);
                break;
            case 'date':
                return $this->date($value);
                break;
            case 'size':
                return  $this->size($value, $rule[1]);
                break;
            // case 'file':
            //     return $this->file($value,$rule);
            //     break;
            // case 'image':
            //     return $this->image($value);
            //     break;


            // case 'mimes':
            //     return $this->mimes($value, $rule[1]);
            //     break;
            // case 'mimetypes':
            //     return  $this->mimetypes($value, $rule[1]);
            //     break;
            // case 'required_if':
            //     return $this->required_if($value, $rule[1], $Request);
            //     break;
            // case 'required_unless':
            //     return $this->required_unless($value, $rule[1], $Request);
            //     break;
            // case 'required_with':
            //     return  $this->required_with($value, $rule[1], $Request);
            //     break;
        }
    }

    /**
     * Metoda pro nahrazení proměnných ve stringu
     *
     * @param string $string  String obsahující proměnné ve tvaru ":key"
     * @param array  $values  Pole obsahující hodnoty proměnných ve tvaru "key => value"
     *
     * @return string         Výsledný string s nahrazenými proměnnými
     */
    function replaceKeysInString($string, $values)
    {
        $replacements = array();
        foreach ($values as $key => $value) {
            $replacements[':' . $key] = $value;
        }
        return strtr($string, $replacements);
    }


    /**
     * Metoda pro odstranění obsahu ve hranatých závorkách
     *
     * @param string $string  String obsahující hranaté závorky
     *
     * @return string         Výsledný string bez obsahu v hranatých závorkách
     */
    function removeBracketContent($string)
    {
        $pattern = '/\[[^\]]*\]/';
        $string = preg_replace($pattern, '', $string);
        return $string;
    }

    /**
     * Metoda pro získání stavu validity formuláře
     *
     * @return bool  Stav validity formuláře
     */
    public function is_valid()
    {
        return $this->is_valid;
    }
}
