<?php
namespace digibrand\framework\Forms\saving;
use digibrand\framework\Forms\Validation\SetValidationRules;
use digibrand\framework\Forms\Validation\Validator;
use digibrand\framework\Globals;
use digibrand\admin\fields\d1g1digibrandField;
use digibrand\framework\Forms\formObject;
use D1g1Notice;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Hlavní třida pro ukladaní dat z formuláře
 * 
 */

if (!class_exists('saveForm')) {
    class saveForm
    {


        private $type_notices = '';
        private $save = true;
        /**
         * Constructor function that adds an action to save_handler when the admin initializes.
         *
         */
        public function __construct(){
            add_action( 'admin_init', [$this, 'save_handler'] );
            add_action('save_post', [$this, 'save_handler'], 10,1);
        }
        
        /**
         * Tato PHP funkce zpracovává uložení dat formuláře po ověření.
         *
         * @throws None
         * @return void
         */

        public function save_handler($post_id = null){
         
           
        
            if(isset($_REQUEST['submit']) &&
                $_REQUEST['submit'] == Globals::$FWDIGI_PLUGINID.'-submit' ||
                isset($_REQUEST['post_type']) &&
                get_post_types()[$_REQUEST['post_type']] && 
                isset($_REQUEST['_wpnonce'])
            ){
                $request = $this->save_switch_and_checkbox($_REQUEST);  
                $Rules = new  SetValidationRules($request, $post_id ?? null);
                $validator = new Validator($Rules,$request);
                if($validator->is_valid()){
                    if($Rules->get_action()){
                        do_action(Globals::$FWDIGI_PLUGINID.'_before_save', $Rules);
                    }else{
                        foreach($Rules->values as $keys => $value){
                            foreach($request as $save_name=> $data){
                                
                                if(str_replace(Globals::$FWDIGI_PLUGINID.'_','',$save_name) == $keys){
                                
                                    $save_type = $Rules->get_save_type($keys);
                                    $this->save_by_type($save_type,$save_name,$value,$request['post_ID'] ?? null);
                                }
                            }

                        }
                      
                    }
                    do_action(Globals::$FWDIGI_PLUGINID.'_after_save', $Rules);
                    D1g1Notice::success( __('Nastavení bylo uloženo', 'digi-framework'),true );
                }
                
            }
           
        }   

        /**
         * Uloží danou hodnotu podle typu.
         *
         * @param string $save_type Typ uložení k provedení. Buď 'meta' nebo 'options'.
         * @param string $save_name Název proměnné, kterou se má uložit.
         * @param mixed $value Hodnota k uložení.
         * @throws Exception Jestliže je poskytnut neplatný typ uložení.
         * @return mixed Výsledek operace uložení, pokud existuje.
         */

        protected function save_by_type($save_type,$save_name,$value,$post_id = null){
            switch ($save_type) {
                case 'meta':
                   
                 
                    return $this->meta_save($post_id,$save_name,$value);
                    break;
                case 'options':
                 
                   
                        return $this->options_save($save_name,$value);
                       // $this->type_notices = 'success';
                        //add_action('admin_notices', [$this, 'build_notices']);
                    
                    break;
              
            }
        }
        /**
         * Uložení data do options
         * 
         * @param string $save_name Název proměnné, kterou se má uložit.
         * @param mixed $value Hodnota k uložení.
         */
        protected function options_save($save_name,$value){
         
            return update_option($save_name,$value);
        }

        /**
         * Uložení data do meta
         * 
         * @param string $save_name Název proměnné, kterou se má uložit.
         * @param mixed $value Hodnota k uložení.
         */
        public function meta_save($post_id,$save_name,$value){
                
                return update_post_meta($post_id,$save_name,$value);
            
           
        }

        /**
     * Metoda pro vytvoření upozornění s chybami
     */
    public function build_notices(){
            // ob_start();
    ?>

            <div class="notice notice-<?= $this->type_notices; ?> d1g1-notice is-dismissible">
                <?php 
                    switch ($this->type_notices) {
                        case 'error':
                            echo '<p>'. __('Nastavení nebylo uloženo', 'digi-framework'). '</p>';
                            break;
                        
                        case 'success':
                            echo '<p>'. __('Nastavení bylo uloženo', 'digi-framework'). '</p>';
                            break;
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
     * Metoda pro uložení dat z checkboxu a switch
     * 
     * @param array $request pole s daty z formuláře
     * @return array $request pole s daty z formuláře
     */
    public function save_switch_and_checkbox($request){
        if(!isset($request['form_id'])){
           
            return $request;
        }
        $object = new formObject($request['form_id']);
        foreach($object->get_fields() as $key => $value){
            if(isset($request[$value['section_string']])){
             
                if( $value['type'] === 'checkbox' || $value['type'] === "switch"){
                   
                    if(is_array($request[$value['section_string']])){
                        foreach($request[$value['section_string']] as $section_key => $data){
                            if(isset($value['options']['checkboxs']) && is_array($value['options']['checkboxs'])){
                                foreach($value['options']['checkboxs'] as $checkbox_keys =>  $checkbox){
                                    if($data !== $checkbox_keys){
                                        if(!isset($request[$value['section_string']][$checkbox_keys])){
                                            $request[$value['section_string']][$checkbox_keys] = 0;
                                        }
                                    }
                                }
                            }else{
                               
                            }
                        }
                    }
                }
               
            }else{
               
                if( $value['type'] == 'checkbox' || $value['type'] === "switch" ){
                    
                    if(isset($value['options']['checkboxs']) && is_array($value['options']['checkboxs'])){
                        foreach($value['options']['checkboxs'] as $checkbox_keys =>  $checkbox){
                            if(!isset($request[$value['section_string']][$checkbox_keys])){
                                $request[$value['section_string']][$checkbox_keys] = 0;
                            }
                        }
                    }else{
                        $request[$value['section_string']] = 0;
                    }
                }
            }
        }
    
        return $request;
    }
        
    }
    new saveForm;
}