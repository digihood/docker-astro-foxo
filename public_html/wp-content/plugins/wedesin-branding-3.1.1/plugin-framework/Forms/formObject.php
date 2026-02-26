<?php

namespace digibrand\framework\Forms;
use digibrand\admin\fields\d1g1PluginField;
use digibrand\framework\Forms\formObjectSection;
use digibrand\framework\Globals;

if (!defined('ABSPATH')) exit;

/**
 * Objekt s kompletním formulářem
 *
 * 
 * @author Digihood
 */
class formObject {
    /**
     * nadpis formuláře
     *
     * @var string
     */
    private $headline;
        
    /**
     * popisek formuláře
     *
     * @var string
     */
    private $description;
        
    /**
     * id formuláře
     *
     * @var string
     */
    private $form_id;

    /**
     * enctype formuláře
     *
     * @var string
     */
    private  $enctype = 'multipart/form-data';

    /**
     * data formuláře
     *
     * @var array
     */
    private  $form_datas = [];

    /**
     * pole formuláře
     *
     * @var array
     */
    private $fields = [];

    /**
     * data formuláře
     *
     * @var object
     */
    private  $sections;

    /**
     * data repeateru
     * 
     * @var object
     */
    private $repeaters;

    //sestavení celého objektu s formulářem
    function __construct($form_id, $cpt = false) {
    
        $this->form_id = $form_id;
        $form_datas = $cpt ? d1g1PluginField::get_fields_cpt_form($form_id) : d1g1PluginField::get_fields_form($form_id);
        
        if (!empty($form_datas)) {
            $this->create_object($form_datas);
        }
    }

/**************************************************************************************************
 * 
 * Sekce  funkcí k návratu objektu
 * 
**************************************************************************************************/

    /**
    * 	Vrátí nadpis formuláře
    * 
    * 	@author digihood
    * 	@return string
    */
    public function get_headline() {
        return $this->headline;
    }

    /**
    * 	Vrátí id formuláře
    * 
    * 	@author digihood
    * 	@return string
    */
    public function get_form_id() {
        return $this->form_id;
    }

    /**
    * 	Vrátí popisek formuláře
    * 
    * 	@author digihood
    * 	@return string
    */
    public function get_description() {
        return $this->description;
    }

    /**
    * 	Vrátí enctype formuláře
    * 
    * 	@author digihood
    * 	@return string
    */
    public function get_enctype() {
        return $this->enctype;
    }

    /**
    * 	Vrátí všechna data formuláře formuláře
    * 
    * 	@author digihood
    * 	@return array
    */
    public function get_form_datas() {
        return $this->form_datas;
    }

    /**
    * 	Vrátí seznam všech fieldů bez uzavření v sekcích formuláře
    * 
    * 	@author digihood
    * 	@return array
    */
    public function get_fields() {
        return $this->fields;
    }


    /**
    * 	Vrátí v poli všechny sekce ve formuláři v poli
    * 
    * 	@author digihood
    * 	@return array
    */
    public function get_sections() {
        return $this->sections;
    }

    /**
     * Vrátí v poli všechny repeatery ve formuláři v poli
     * 
     * @return array
     */
    public function get_repeaters() {
        return $this->repeaters;
    }



/**************************************************************************************************
 * 
 * Sekce pomocných funkcí k vytvoření objektu
 * 
**************************************************************************************************/
    /**
    * 	Nastaví proměné objektu formuláře 
    *
    * 	@param $form_datas = []
    * 
    * 	@author digihood
    * 	@return boolean
    */
    private function create_object( $form_datas) { 
        //preprint($form_datas);
        if (empty($form_datas)) return;
        $this->headline = isset($form_datas['headline']) ? $form_datas['headline'] : "";
        $this->description = isset($form_datas['description']) ? $form_datas['description'] : "";
        $this->enctype = isset($form_datas['enctype']) ? $form_datas['enctype'] :'multipart/form-data';
        $this->form_datas = $form_datas;
        $this->fields = $this->set_all_fields();
        $this->sections = $this->set_section(); // tady bude objekt na vytváření sekcí obecně
        $this->repeaters = $this->set_repeater(); // tady bude objekt na vytváření sekcí repeaterů
    }

    /**
    * 	Vyhledá všechny pole ve formuláři a uloží do jednoho pole
    * 
    * 	@author digihood
    * 	@return array
    */
    private function set_all_fields() {
        if (!isset($this->form_datas['sections']) || !is_array($this->form_datas['sections'])) return [];
        $all_fields = [];
        foreach ($this->form_datas['sections'] as $sections_id => $section_datas) {
            if (isset($section_datas['fields'])) {
                foreach ($section_datas['fields'] as $key => $field) {
                    $field['section_id'] = $sections_id;
                    $field['section_string'] = Globals::$FWDIGI_PLUGSLUG . '_' . $this->form_id . '_'. $field['name'];     
                    $all_fields[] = $field;
                   
                   
                  
                }
            }
        }
      
        return $all_fields;
    }

    /**
    * 	Sestaví do objektu jednotlivé sekce a vrátí jako pole všech sekcí
    * 
    * 	@author digihood
    * 	@return array
    */
    private function set_section() {
        if (!isset($this->form_datas['sections']) || !is_array($this->form_datas['sections'])) return [];
        $all_sections = [];
        foreach ($this->form_datas['sections'] as $sections_id => $section_datas) {
            $section_datas['sections_id'] = $sections_id;
            $section_datas['form_id'] = $this->form_id;
            $all_sections[] = new formObjectSection($section_datas);
        }
        
        return $all_sections;
    }
    
    /**
     * Sestaví do objektu jednotlivé repeater a vrátí jako pole všech repeaterů
     * 
     * @author digihood
     * @return array
     */
    private function set_repeater() {
        if (!isset($this->form_datas['repeaters']) || !is_array($this->form_datas['repeaters'])) return [];
        $all_repeaters = [];
        foreach ($this->form_datas['repeaters'] as $repeaters_id => $repeater_datas) {
            $repeater_datas['repeaters_id'] = $repeaters_id;
            $repeater_datas['form_id'] = $this->form_id;
            $all_repeaters[] = new formObjectSection($repeater_datas);
        }
        
        return $all_repeaters;
    }


    /**
     * setavi do stringu cele html formuláře.
     * 
     * @author digihood
     * @return string
     */

    public function get_form_html(){
        $formHtml = new formHtml($this);
        return $formHtml->render();
    }

    /**
     * Vrátí html formuláře pro cpt
     * 
     * @return string
     */
    public function get_form_html_cpt(){
        $formHtml = new formHtml($this);
       
        return $formHtml->render_cpt();
    }
}