<?php
namespace digibrand\framework\forms\Fields;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * pomocná třída pro vytváření polí
 * 
 * 
 */

 if (!class_exists('FieldsVariables')) {

    class FieldsVariables{
            
        /**
         * id formulaře 
         * @var int
         */
        protected $form_id;

        /**
         * akce pole
         * 
         * @var string
         */
        protected $action;
        /**
         * název pole
         * @var string
         */
        protected $name;

        /**
         * popisek pole
         * @var string
         */
        protected $label;

        /**
         * nadpis pole
         * @var string
         */
        protected $headline;

        /**
         * typ pole
         * @var string
         */
        protected $type;

        /**
         * hodnota pole
         * @var string
         */
        protected $value;

        /**
         * placeholder pole
         * @var string
         */
        protected $placeholder;

        /**
         * popis pole
         * @var string
         */
        protected $description;
        /**
         * pravidla pole
         * @var string
         */
        protected $rules;


        /**
         * možnosti pole
         * @var array
         */
        protected $options;


        /**
         * zobrazení required message   
         * @var boolean
         */

        protected $is_valid = true;
        /**
         * možnost více hodnot
         * @var bool
         */
        protected $multiple;

        /**
         * třída pole
         * @var string
         */
        protected $class;

        /**
         * data pole
         * @var array
         */
        protected $data;

        /**
         * nastavení typu ukladaní pole
         * @var string
         */
        protected $saveAs;
        
        /**
         * nastavení checked
         * @var string
         */

        protected $checked;
        
        /**
         * nastavení atributů
         * @var string
         */

        protected $atts;

        /**
         * nastavení help textu
         * @var string
         */

        protected $help_text;  

        /**
         * pole pro nastavení fieldu
         * @var array
         */

        protected $fields;

        /**
         * pole pro nastaveni sloupcu
         * @var array
         */

        protected $colums;

        /**
         * nastavení floating labelu
         * @var string
         */
        protected $floating_label;


        /**
         * nastavení class pro css 
         * @var string
         */
        protected $css_class;

        /**
         * nastavení atributů
         * @var string
         */
        protected $args;

        /**
         * nastavení show attr
         * @var string
         */
        protected $show_attr;

        /**
         *  custom html
         * @var string
         */
        protected $custom_html;


        protected  $pole;
    
    }
}