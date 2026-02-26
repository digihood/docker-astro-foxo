<?php

/**
 * Třída SetValidationRules
 *
 * Tato třída se používá k nastavení pravidel validace formulářových polí.
 */
namespace digibrand\framework\Forms\Validation;

use digibrand\admin\fields\d1g1PluginField;
use digibrand\framework\globals;
use digibrand\framework\Forms\formObject;
class SetValidationRules
{

    public $form_id = '';   // ID formuláře
    public $names = [];     // Názvy polí
    public $rules = [];     // Pravidla validace
    public $values = [];    // Hodnoty polí
    public $types = [];     // Typy polí
    public $save_type = [];  // Typ uložení hodnot jednotlivých poly
    public $action;
    private $cpt = false;

    /**
     * Konstruktor třídy SetValidationRules
     *
     * @param mixed $Request  Objekt reprezentující požadavek
     */
    public function __construct($Request, $cpt = false)
    {
        $this->cpt = $cpt;

        if (isset($Request['form_id'])) {
            $this->form_id = $Request['form_id'];
        }
        $this->set_rules($Request);
    }

    /**
     * Metoda pro nastavení pravidel validace
     *
     * @param mixed $Request  Objekt reprezentující požadavek
     */
    public function set_rules($Request)
    {
        foreach ($Request as $keys => $value) {
            $key = explode('_', $keys);
          
            if ($key[0] == globals::$FWDIGI_PLUGINID) {
                $this->build_rules($key);
                $this->set_values($key, $value);
            }
        }
    }
    public function build_rules($key){
       
        $name_array = $key;
        unset($name_array[0]);
        $name_key = implode('_', $name_array);
        unset($key[0]);
        unset($key[1]);
      
        $this->rules[$name_key] = $this->get_section_field((implode('_',$key)));
        
    }

    public function set_values($key,$value){
        unset($key[0]);
        $key = implode('_', $key);
        $this->values[$key] = $value;
    }
    /**
     * Metoda pro získání pravidel validace pro dané pole
     *
     * @param string $section  Název pole
     *
     * @return mixed           Pravidla validace pole
     */
    public function get_section_field($field_name)
    {
        $form = d1g1PluginField::get_fields_form($this->form_id);
        $form_2 = new formObject($this->form_id, $this->cpt);
        $form_2 = $form_2->get_fields();
        if($form_2){
            $key = array_search($field_name, array_column($form_2, 'name'));
            $this->names[$form_2[$key]['name']] = $form_2[$key]['label'];
            $this->save_type[$this->form_id.'_'.$form_2[$key]['name']] = (isset($form_2[$key]['saveAs'] ) && $form_2[$key]['saveAs'] ? $form_2[$key]['saveAs'] : null);
            $this->action = isset($form['action']) && $form['action']? $form['action'] : '';
            if (isset($form_2[$key]['rules']) && $form_2[$key]['rules']) {
                $this->types[$form_2[$key]['name']] = $form_2[$key]['type'];
            
            
                return ['rule_string' => $form_2[$key]['rules'],'rule' => explode('|', $form_2[$key]['rules'])];
            } else {
                return null;
            }
        }else{
            return null;
        }
    }

    public function get_type_field($name){
        if(isset($this->types[$name])){
            return $this->types[$name];
        }
    }

    public function get_save_type($name){
        if(isset($this->save_type[$name])){
            return $this->save_type[$name];
        }
       
    }

    public function get_action(){
        if(isset($this->action)){
            return $this->action;
        }
    }
}


