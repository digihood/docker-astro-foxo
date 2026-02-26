<?php

namespace digibrand\framework\forms\Fields;


/**
 * Classes
 *
 * 
 * @author Digihood
 */
class RepeaterFieldsHtml extends FieldsVariables {

    use Inputselector;

    function __construct() {

    }


     /**
     * vytvoření hlavičky repeateru
     * 
     * @return string
     */
    public function header_form(){
        $html = file_get_contents(__DIR__.'/html/repHeader.php', true);
        return $html;
    }


    /**
     * vytvoření footeru repeateru
     * 
     * @return string
     */

    public function footer_form(){
        $html = file_get_contents(__DIR__.'/html/repFooter.php', true);
        return $html;
    }

}