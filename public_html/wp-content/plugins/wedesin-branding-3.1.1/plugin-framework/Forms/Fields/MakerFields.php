<?php
namespace digibrand\framework\forms\Fields;
use digibrand\framework\d1g1Session;
use digibrand\framework\globals;
if (!defined('ABSPATH')) {
    exit;
}

/**
 * MakerFields
 *  trait pro vytváření polí
 * 
 */

if (!class_exists('MakerFields')) {

    class MakerFields extends FieldsVariables  {

        use Inputselector;

        private $save_field_name = '';
        /**
         * html fieldu
         * @var string
         */
        public $html = '';

        public $section_id = '';

        public $order = '';

        /**
         * vytvoření inputu
         * 
         * @param array $form_config
         * @param string $form_id
         * @return string
         */
        public function __construct($form_config = [], $form_id = '' , $section_id = '',$order = '') {
            $this->form_id = $form_id;
            $this->section_id = $section_id;
            $this->order = $order;
            $this->set_variables($form_config);
            $this->html .= $this->make_field();
         
           
         
            
          
        }

        /**
         * získaní html fieldu
         * 
         * @return string
         */
        public function get_fields(){
            return $this->html;
        }

        /**
         * vytvoření inputu
         * 
         * @return string
         */
        private function make_field(){
            $html = '';
            if($this->type != null && $this->form_id != null && $this->name != null){
                $html .= self::select_input($this->type);
               
            }
            return $html;
        }


        /**
         * Nastavení proměnných pro sestavení pole
         * 
         * @param array $form_config
         * @return void
         */
        private function set_variables($form_config = []){
           
            foreach ($form_config as $key => $value) {
                if($key == 'rules'){
                    $this->$key = self::set_rules($value);
                }else{
                    $this->$key = $value;
                }
            
            }
            
            
            $this->save_field_name = Globals::$FWDIGI_PLUGINID . '_'. $this->form_id .'_'. $this->name;
            $this->check_valid_in_session();
            
            global $post;
            if($post){
                $this->value = get_post_meta($post->ID, $this->save_field_name, true);
            }else{
                $this->value = d1g1Session::get_session($this->name) !== false ? d1g1Session::get_session($this->name) : (get_option($this->save_field_name) ? get_option($this->save_field_name) : $this->value);
            }
           
        }
        
        private static function set_rules($rules){
            $rules = explode('|',$rules);
            $return = [];
            foreach($rules as $rule){
                $rul = explode(':',$rule);
                if(is_array($rul)){
                    $return[$rul[0]] = (isset($rul[1]) && $rul[1] ? $rul[1] : true); 
                }else{
                    $return[$rul] = true;
                }
            }
            return $return;
            
        }


        private function check_valid_in_session(){
            $valid = d1g1Session::get_session('valid-'.$this->name);
            if(isset($valid['required']) && $valid['required'] === false){
                $this->is_valid = $valid['required'];
               
            }
        }
    }

  
   
}