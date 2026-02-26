<?php
namespace digibrand\framework\forms\Fields;
use digibrand\framework\forms\Fields\html\InputsFieldsTrait;

if (!defined('ABSPATH')) {
    exit;
}
/**
 * trait pro trídu MakerFields
 * podle typu inputu vratí požadovaný html kod
 */

 if(!trait_exists('Inputselector')){
    
    trait Inputselector {
       
        use InputsFieldsTrait;

        private  $floating_label_start = "<span class='floating-label'>";
        
        private  $floating_label_end = "</span>";

        public  function select_input($type){
            $html = '';
           
            switch ($type) {
                case 'select':
                    $html = $this->select();
                    break;
                case 'button':
                case 'checkbox':
                case 'checkbox_large':
                    $html = $this->checkbox();
                    break; 
                case 'color':
                    $html = $this->color();
                    break;
                case 'date':
                case 'datetime-local':
                case 'time':
                case 'month':
                case 'week':
                    $html = $this->date();
                    break;
                case 'file':
                    $html = $this->file();
                    break;
                case 'image':
                    $html = $this->image();
                    break;
                case 'month':
                case 'radio':
                case 'radio_large':
                    $html = $this->radio();
                    break;
                case 'range':
                    $html = $this->range();
                    break;
                case 'hidden':
                case 'reset':
                case 'search':
                case 'submit':
                case 'number':
                case 'password':
                case 'tel':
                case 'email':
                case 'text':
                    $html = $this->text();
                    break;
                case 'time':
                case 'url':
                    $html = $this->url();
                    break;
                case 'week':
                case 'switch':
                    $html = $this->switch();
                    break;
                case 'textarea':
                    $html = $this->textarea();
                    break;
                case 'editor':
                    $html = $this->editor();
                    break;
                case 'info_box':
                case 'warning_box':
                    $html = $this->box();
                    break;
                case 'html':
                    $html = $this->html();
                    break;
                case 'repeater':
                   
                    $html = '';
                    foreach ($this->fields as $key => $value) {
                        $field = new MakerFields($value,$this->form_id);
                           $html .= $field->get_fields();
                    }
                    return $html;
                    //$repeater = new MakerFields($this);
                    //$html = 'ahooooooj'; //$this->html();
                    break;

            }
           
            return $html;
        }
     

        private static function form_item($end ){

        }


      
    }
 }