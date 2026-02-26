<?php

namespace digibrand\framework\Forms;
use digibrand\framework\forms\Fields\MakerFields;
/**
 * Classes
 *
 * 
 * @author Digihood
 */
class formObjectSection {
    /**
     * nadpis sekce
     *
     * @var string
     */
    private $headline;
        
    /**
     * popisek sekce
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
     * id sekce
     *
     * @var string
     */
    private $section_id;
        
    /**
     * data sekce formuláře
     *
     * @var array
     */
    private $section_data = [];

    /**
     * pole v sekci
     *
     * @var array
     */
    private $fields = [];

    function __construct($section_data) {
        $this->create_object($section_data);
    }

/**************************************************************************************************
 * 
 * Sekce  funkcí k návratu objektu
 * 
**************************************************************************************************/

    /**
    * 	Vrátí nadpis sekce
    * 
    * 	@author digihood
    * 	@return string
    */
    public function get_headline() {
        return $this->headline;
    }

    /**
    * 	Vrátí popisek sekce
    * 
    * 	@author digihood
    * 	@return string
    */
    public function get_description() {
        return $this->description;
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
    * 	Vrátí id sekce
    * 
    * 	@author digihood
    * 	@return string
    */
    public function get_section_id() {
        return $this->section_id;
    }

    /**
     * vrátí všechny fields
     * 
     * @author digihood
     * @return array
     */
    public function get_fields() {
        return $this->fields;
    }

    /**
     * vrátí všechny section_data
     * 
     * @author digihood
     * @return array
     */

    public function get_section_data() {
        return $this->section_data;
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
    private function create_object( $section_data) { 
        if (empty($section_data)) return;
        $this->section_data = $section_data;
        $this->headline = isset($section_data['headline']) ? $section_data['headline'] : "";
        $this->description = isset($section_data['description']) ? $section_data['description'] : "";
        $this->section_id = isset($section_data['section_id']) ? $section_data['section_id'] :"";
        $this->form_id = isset($section_data['form_id']) ? $section_data['form_id'] :"";
        $this->fields = $this->set_section_fields();
    }

    /**
    * 	Vyhledá všechny pole ve formuláři a uloží do jednoho pole
    * 
    * 	@author digihood
    * 	@return array
    */
    private function set_section_fields() {
        if (!isset($this->section_data['fields']) || !is_array($this->section_data['fields'])) return [];
        $all_fields = [];
      
        foreach ($this->section_data['fields'] as $key => $single_field) {
           
            $field = new MakerFields($single_field, $this->form_id, $this->section_data['sections_id'], $key);
            $all_fields[] = $field->get_fields();
        }
       
        return $all_fields;
    }

}