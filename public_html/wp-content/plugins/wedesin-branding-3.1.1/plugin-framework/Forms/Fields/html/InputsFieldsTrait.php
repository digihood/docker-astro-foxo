<?php
namespace digibrand\framework\forms\Fields\html;

if (!defined('ABSPATH')) {
    exit;
}

use digibrand\framework\Globals;
use digibrand\framework\forms\Validation\FieldValidator;

/**
 * trait pro trídu MakerFields
 * podle typu inputu vratí požadovaný html kod
 */

 if(!trait_exists('InputsFieldsTrait')){

    trait InputsFieldsTrait {

       use FieldValidator;
        private function textarea(){
            $required = isset($this->rules['required']) && $this->rules['required'] ? $this->rules['required'] : false;
            $label =  $this->label ? $this->label : false;
            $html = '<div class="form-item '.$this->name.'">';
            if ($this->description) $html .=  '<p>' . $this->description . '</p>';

            $html .= $this->_is_valid();
           
            $html .= $this->set_floating_label('start');
          
            $html .= '<textarea type="' . $this->type . '"  name="' . $this->save_field_name . '" placeholder="' . $this->placeholder . '" ' . $this->atts . ' class="' . $this->class . '" />' . $this->value . '</textarea>';
            if ($label)  $html .= '<label for="' . $this->name . '">' . $this->label . $this->is_required($required) . '</label>';
            $html .= $this->set_floating_label('end');

            if ($this->help_text) $html .= '<p class="help-text">' . $this->help_text . '</p>';
            $html .= '</div>';
            return $html;

        }
        private function text(){
            $required = $this->is_required();
            
            $html = $this->set_for_item_class();
            $html .= '<p><span class="floating-label">';
            if ($this->description) $html  .= '<p>' . $this->description . '</p>';
            $html .=  '<input type="'.$this->type.'" id="'.$this->form_id.'" name="'.$this->save_field_name.'" value="'.$this->value.'" placeholder="'.$this->placeholder.'" '.$this->required($required,1).'>';
            $html .= '<label for="'.$this->name.'">'.$this->label. $required .'</label></span></p>';
           
            $html .= $this->_is_valid();
            $html .= '</div>';
            return $html;
        }
        private function switch(){
            $html = '<div class="form-item '.$this->name.'">';
            $checked =  ($this->value == 1  ? 'checked' : '');

           
            if ($this->label)  {
                $html .= '<label class="switch">';
                $html .= '<input type="checkbox" value="1"  name="' . $this->save_field_name . '" ' . $checked . ' ' . $this->atts . '>';
                $html .= '<span class="slider"></span>';
                $html .= '<h3>' . $this->label . $this->is_required() . '</h3></label>';
            };
            $html .= $this->_is_valid();
            if ($this->description) $html .=  '<p>' . $this->description . '</p>';
            if ($this->help_text) $html .= '<p class="help-text">' . $this->help_text . '</p>';
            $html .= '</div>';

            
            return $html;
        }


        private function editor(){
            //$class = $name;
            $html = '<div class="form-item '.$this->name.'">';
            $settings =   array(
                'wpautop' => true, // use wpautop?
                'media_buttons' => false, // show insert/upload button(s)
                'textarea_name' => $this->save_field_name, // set the textarea name to something different, square brackets [] can be used here
                'textarea_rows' => get_option('default_post_edit_rows', 10), // rows="..."
                'tabindex' => '',
                'editor_css' => '', //  extra styles for both visual and HTML editors buttons,
                'editor_class' => $this->css_class, // add extra class(es) to the editor textarea
                'teeny' => false, // output the minimal editor config used in Press This
                'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
                'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
            );

            if ($this->description) $html  .= '<p>' . $this->description . '</p>';
            
            $editor_id = str_replace(']', '', str_replace('[', '', $this->name));
          
            ob_start();
                wp_editor($this->value ?$this->value : '', $editor_id, $settings);
            $html .= ob_get_contents();
            ob_end_clean();
            if ($this->help_text) $html .= '<p class="help-text">' . $this->help_text . '</p>';

            if (isset($this->args['length']) && $this->args['length']) {
                $html .= "<p>Počet znaků: <span class='word-count' data-fieldname='" . $this->save_field_name . "' id='word-length-" . $this->name . "'>" . FieldValidator::valid_leght_string($this->value) . "</span>  / " . $this->args['length'] . '</p>';
            }
            $html .= '</div>';
            return $html;
        }

        private function select(){
            $options = isset($this->args['options']) ? $this->args['options'] : [];
          
            $html = '';
            if (!empty($options)) {
                $html = '<div class="form-item '.$this->name.'">';
                if ($this->description) $html .= '<p>' . $this->description . '</p>';
                $html .= $this->_is_valid();
                $html .= $this->set_floating_label('start');
                $html .= '<select name="' . $this->save_field_name . '" ' . ($this->multiple !== null ? 'multiple' : '' ). ' class="selectize ' . $this->class . '" placeholder="' . $this->placeholder . '" id="' . $this->name . '">';
                foreach ($options as $option_key => $option_name) {
                    $html .= '<option value="' . $option_key . '" ' . ($option_key == $this->value ? 'selected="selected"' : '') . '>' . $option_name . '</option>';
                }
                $html .= '</select>';
                if ($this->label)  $html .= '<label for="' . $this->name . '">' . $this->label . $this->is_required() . '</label>';
                $html .= $this->set_floating_label('end');
                if ($this->help_text) $html .= '<p class="help-text">' . $this->help_text . '</p>';
                $selectize = isset($this->args['selectize']) ? $this->args['selectize'] : [];
                if (!empty($selectize)) {
                    $html .= '<script>$("#' . $this->name . '").selectize({';
                    
                    foreach ($selectize as $selectize_key => $selectize_value) {
                        $html .= $selectize_key . ': ' . $selectize_value . ',';
                    }

                    $html .= '});</script>';
                    
                }
                $html .= '</div>';            
            }
           
            return $html; 
        }

        private function checkbox(){
            $html = '<div class="form-item '.$this->name.'">';
            $label = isset($this->label) ? $this->label : false;
            //$checked = (!empty($value) ? 'checked' : "");
            if ($this->type == 'checkbox_large') {
                $class = 'checkbox-large ' . $this->css_class;
            }
            $options = isset($this->options['checkboxs']) ? $this->options['checkboxs'] : [];
            if (empty($options)){
                $options[$this->name] = $this->label;
            }
         
            if ($label && $options)  {
                $cc = 0;
                $checked = '';
               
             
               
                foreach ($options as $val => $text) {
                    $checked = '';
                    if(!is_array($this->value)){
                        $this->value = [$val => $this->value];
                    }
                    if(isset($this->value[$val]) && $this->value[$val] == "1"){
                       
                      
                        $checked = 'checked';
                     
                       
                    }
                  
                    $cc++;
                   
                   
                    $html .= '<label class="checkbox ' . (isset($class) && $class ? $class : $this->css_class) . '">';
                    $html .= '<input type="checkbox" '.$checked.' value="1" name="' . $this->save_field_name. (count($options) > 1 ? '['.$val.']' : '') . '"  ' . $this->atts . '>';
                    $html .= '<span class="checkmark"></span>';
                    if ($this->type == 'checkbox_large') $html .= '<h3>';
                    $html .=  $text . $this->is_required() ;
                    if ($this->type == 'checkbox_large') $html .= '</h3>';
                    $html .= '</label>';
                 
                }
            };
            $html .= $this->_is_valid();
            if ($this->description) $html .= '<p>' . $this->description . '</p>';
            if ($this->help_text) $html .= '<p class="help-text">' . $this->help_text . '</p>';
            $html .= '</div>';
            return $html;
        }

        private function radio(){
            $options = isset($this->args['options']) ? $this->args['options'] : [];
            if ($this->type == 'radio_large') {
                $class = 'radio-large ' . $this->css_class;
            }

            if (!empty($options)) {
                $html = '<div class="form-item '.$this->name.'">';
                if ($this->description) $html .= '<p>' . $this->description . '</p>';

                $html .= $this->_is_valid();
                
                $html .= '<div class="radio-wrap">';

                //$html .= '<input type="' . $type . '" value=""  name="' . $save_field_name .'" checked class="hide"> ';
                foreach ($options as $option_key => $option_name) {
                    if ($option_key != '' && $option_name != '') {
                        $checked = $option_key == $this->value ? 'checked' : '';

                        $html .= '<label class="radio ' . (isset($class) && $class ? $class : $this->css_class) . '">';
                        $html .= '<input type="radio" value="' . $option_key . '"  name="' . $this->save_field_name . '" ' . $checked . ' ' . $this->atts . '> ';
                        $html .= '<span class="checkmark"></span>';

                        if ($this->type == 'radio_large') $html .= '<h3>';
                        $html .= is_array($option_name)?  $option_name[0] :  $option_name ;
                        if ($this->type == 'radio_large') $html .= '</h3>';
                        $html .= is_array($option_name)? '<p class="help-text">' . $option_name[1] . '</p>' : '' ;

                        $html .= '</label>';
                    }
                }

                $html .= '</div>';

                if ($this->help_text) $html .= '<p class="help-text">' . $this->help_text . '</p>';
                $html .= '</div>';
                return $html;
            }
        }
        public function box(){
            // Box s informací nebo varováním
            $html = '<div class="form-item '.$this->name.'">';
            $headline = isset($this->args['headline']) ? $this->args['headline'] : false;
            $warning = $this->type == 'warning_box' ? ' warning' : '';
            $icon = Globals::$FWDIGI_PATHTOFWASSET . ($this->type == 'warning_box' ? 'icons/warning-standard-line.svg' : 'icons/info-standard-line.svg');

            
            $html .= '<div class="info-block' . $warning . '">';
            
            $html .= '<span class="icon">' . d1g1_get_svg(  $icon) . '</span>';

            if ($this->headline) $html .= '<h3>' . $this->headline . '</h3>';
            if ($this->description) $html .= '<p>' . $this->description . '</p>';

            $html .= '</div>';
            $html .= '</div>';
            return $html;
        }

        private function range(){
            $max = isset($this->rules['max']) ? 'max="' . $this->rules['max'] . '" ' : '';
            $min = isset($this->rules['min']) ? 'min="' . $this->rules['min'] . '" ' : '';
            $step = isset($this->options['step']) ? 'step="' . $this->options['step'] . '" ' : '';
            $show_attr = isset($this->options['show_attr']) ? $this->options['show_attr'] : false;
            $unit = isset($this->options['unit']) ? $this->options['unit'] : '';
            $wrap_class = $unit ? 'val-right-large' : 'val-right';
            $wrap_class = $show_attr == true ? $wrap_class . ' show-attr' : $wrap_class;
            // přidání validace a uložení změny jednotek
         //   $validation_data = d1g1Session::get_session( self::$form_id.'_validation' );
            // $unit_value = isset($validation_data[$this->name.'_unit']['value']) ? $validation_data[$this->name.'_unit']['value'] : "";
            // $unit_value = (empty($unit_value) && d1g1FrontForm::get_data($name.'_unit') ? d1g1FrontForm::get_data($name.'_unit') : $unit_value);
                $html = '<div class="form-item '.$this->name.'">';
                if ($this->label) $html .= '<p>' . $this->label  . '</p>';
                
                $html .= $this->_is_valid();

                if ($this->description) $html .= '<p>' . $this->description . '</p>';

                $html .= '<div class="range-wrap ' . $wrap_class . '">';
                $html .= '<input name="' . $this->save_field_name . '" type="range" class="range" ' . $max . $min . $step . ' value="' . $this->value . '">';
                if ($unit) $html .= '<div class="input-group">';
                $html .= '<input class="outval small" type="number" ' . $max . $min . $step . ' value="' . $this->value . '" source="[name=' . $this->save_field_name . ']">';
                if (is_array($unit)){
                    $html .= '<div class="select-button small"><select name="'.$this->save_field_name.'_unit">';
                    foreach ($unit as $unit_key => $unit_name) {
                        $html .= '<option value="' . $unit_key . '" '.(isset($unit_value) && $unit_key == $unit_value ? 'selected="selected"' : '').'>' . $unit_name . '</option>';
                    }
                    $html .= '</select></div>';
                } else {
                    $html .= '<span class="input-group-label">' . $unit . '</span>';
                }
                if ($unit) $html .= '</div>';
                $html .= '</div>';

                if ($this->help_text) $html .= '<p class="help-text">' . $this->help_text . '</p>';
                $html .= '</div>';
                return $html;
        }

        private function url(){
         
            $html = '<div class="form-item '.$this->name.'">';
            if ($this->description) $html .= '<p>' . $this->description . '</p>';

            $html .= $this->_is_valid();

            $html .= '<div class="input-group">';
            $html .= '<span class="input-group-label">' . d1g1_get_svg(Globals::$FWDIGI_PATHTOFWASSET . "icons/link-line.svg") . '</span>';
            $html .= $this->set_floating_label('start');
          
            $html .= '<input type="url" value="' . $this->value . '" name="' . $this->save_field_name . '" placeholder="' . $this->placeholder . '" ' . $this->atts . ' class="' . $this->class . '" />';

            if ($this->label)  $html .= '<label for="' . $this->name . '">' . $this->label . $this->is_required() . '</label>';

            $html .= $this->set_floating_label('end');
            $html .= '</div>';

            if ($this->help_text) $html .= '<p class="help-text">' . $this->help_text . '</p>';
            $html .= '</div>';
            return $html;
        }


        private function file(){
            $html = '<div class="form-item '.$this->name.'">';
            if ($this->description) $html .= '<p>' . $this->description . '</p>';

            $html .= $this->_is_valid();

            $html .= '<div class="input-group">';
            $html .= '<span class="input-group-label">' . d1g1_get_svg(Globals::$FWDIGI_PATHTOFWASSET . "icons/link-line.svg") . '</span>';
            $html .= $this->set_floating_label('start');
            $html .= '<input type="file" name="' . $this->save_field_name . '" placeholder="' . $this->placeholder . '" ' . $this->atts . ' class="' . $this->class . '" />';
            $html .= $this->set_floating_label('end');
            $html .= '<a href="#" id="d1g1-media-upload" class="button">' . d1g1_get_svg(Globals::$FWDIGI_PATHTOFWASSET . "icons/upload-cloud-line.svg") . 'Nahrát</a>';

            $html .= '</div>';
            
            if ($this->help_text) $html .= '<p class="help-text">' . $this->help_text . '</p>';
            if ($this->value) {
                $html .= '<p><div class=upload-img-preview>';
                
                $html .= '<a href="#" class="button white icon">' . d1g1_get_svg(Globals::$FWDIGI_PATHTOFWASSET . "icons/trash-line.svg") . 'Odstranit</a>';
                $html .= '</div></p>';
            }
            $html .= '</div>';
            return $html;
        }

        private function image(){
            //echo '<div id="media-uploader-' . $name . '" data-target="' . $name . '" data-limit="1" class="dropzone ' . $class . '"></div>';
            //echo '<input type="hidden" name="' . $name . '" value="' . $value . '" ' . $atts . ' />';
            //if (!empty($placeholder)) echo '<p>' . $placeholder . '</p>';
            $html = '<div class="form-item '.$this->name.'">';
            if ($this->description) $html .= '<p>' . $this->description . '</p>';

            $html .= $this->_is_valid();
            
            $html .= '<div class="input-group">';
            $html .= '<span class="input-group-label">' . d1g1_get_svg(Globals::$FWDIGI_PATHTOFWASSET . "icons/link-line.svg") . '</span>';
            $html .= $this->set_floating_label('start');

            $html .= '<input type="url" id="d1g1-media-url" value="' . $this->value . '" name="' . $this->save_field_name . '" placeholder="' . $this->placeholder . '" ' . $this->atts . ' class="' . $this->class . '" />';

            if ($this->label)  $html .= '<label for="' . $this->name . '">' . $this->label . $this->is_required() . '</label>';

            $html .= $this->set_floating_label('end');

            $html .= '<a href="#" id="d1g1-media-upload" class="button">' . d1g1_get_svg(Globals::$FWDIGI_PATHTOFWASSET . "icons/upload-cloud-line.svg") . 'Nahrát</a>';


            $html .= '</div>';

            if ($this->help_text) $html .= '<p class="help-text">' . $this->help_text . '</p>';
            
            if ($this->value) {
                $html .= '<p><div class=upload-img-preview>';
                $html .= '<img src="' . $this->value . '" width="300" height="200" alt="alt">';
                $html .= '<a href="#" class="button white icon">' . d1g1_get_svg(Globals::$FWDIGI_PATHTOFWASSET . "icons/trash-line.svg") . 'Odstranit</a>';
                $html .= '</div></p>';
            }
            $html .= '</div>';
            return $html;
        }


        private function date(){
            $label = isset($this->label) ? $this->label : false;
            /*if ($type == 'date') {
                        $now = date("Y-m-d");
                        (empty($value) ? $value = $now : '');
                        $value = date("Y-m-d", strtotime($value));
                    }*/

            $icon = $this->type == 'time' ? "icons/clock-line.svg" : "icons/calendar-line.svg";
            $html = '<div class="form-item '.$this->name.'">';
            if ($this->description) $html .=  '<p>' . $this->description . '</p>';
            
            $html .= $this->_is_valid();

            $html .=  '<div class="input-group">';
            $html .=  '<span class="input-group-label">' . d1g1_get_svg(Globals::$FWDIGI_PATHTOFWASSET . $icon) . '</span>';
            $html .= $this->set_floating_label('start');
            $html .=  '<input type="' . $this->type . '" id="' . $this->name . '" name="' . $this->save_field_name . '" value="' . $this->value . '">';
            if ($this->label)  $html .=  '<label for="' . $this->name . '">' . $this->label . $this->is_required() . '</label>';
            $html .= $this->set_floating_label('end');
            $html .=  '</div>';

            if ($this->help_text) $html .=  '<p class="help-text">' . $this->help_text . '</p>';
            $html .=  '</div>';
            return $html;
        }

        private function color(){
           
            $default = $this->value ? $this->value : '';
            $label = $this->label ? $this->label : false;
            $html = '<div class="form-item '.$this->name.'">';
            if ($label)  $html .= '<p>' . $label . $this->is_required() . '</p>';
            if ($this->description) $html .= '<p>' . $this->description . '</p>';
            $html .= $this->_is_valid();

            $html .= '<p><input type="text" class="color-picker" data-alpha-enabled="true" data-default-color="' . $default . '" id="' . $this->name . '" name="' . $this->save_field_name . '" value="' . $this->value . '"></p>';

            if ($this->help_text) $html .= '<p class="help-text">' . $this->help_text . '</p>';
            $html .= '</div>';
            return $html;
        }

        private function html(){
            // Vlasní html obsah
            $html = '<div class="form-item '.$this->name.'">';
            $html .= $this->custom_html ? $this->custom_html : false;
            $html .= '</div>';
            return $html;
        }
        private function set_floating_label($type){
            switch ($type) {
                case 'start':
                    return $this->floating_label_start;
                    break;
                case 'end':
                    return $this->floating_label_end;
                    break;
            }
        }



        private function set_for_item_class(){
            
            if(isset($this->options['width']) && $width = $this->options['width']){
                if(in_array($width,['half','full'])){
                    return '<div class="form-item '.$this->name.' inline-'.$width.'">';
                }
            }
            return '<div class="form-item '.$this->name.'">';
        }
        /**
         * Is required
         * 
         * @param boolean $required
         * @return string|void  
         */
        private function is_required(){
            $required = isset($this->rules['required']) && $this->rules['required'] ? $this->rules['required'] : false;
            if($required){
                return '<span class="required">*</span>';
            }
        }
        /**
         * Is valid
         *  pokud je pole vyžadovane a je prázdné vratí span s classou error.  
         * @return string|void
         * 
         **/
        private function _is_valid(){
            if (!$this->is_valid) {
                $html = '<span class="form-error">' . __('Toto pole je vyžadované, prosíme, vyplňte ho.', 'textdomain') . '</span>';
                return $html;
            }
        }

        
    }

}