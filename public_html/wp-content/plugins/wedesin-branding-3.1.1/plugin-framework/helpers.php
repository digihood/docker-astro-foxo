<?php 

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
  
/**
 * Print result with pre tag
 *
 * @param $lang string
 * 
 * @author digihood
 * @return echo string
 */ 

if ( !function_exists('preprint') ) {

    function preprint( $print ) {

        echo '<pre>';

        echo print_r( $print );

        echo '</pre>';

    }
   
}
if ( !function_exists('preprintdie') ) {

    function preprintdie( $print ) {

        echo '<pre>';

        echo print_r( $print );

        echo '</pre>';
        die();

    }
   
}
if ( !function_exists('func_alias') ) {
    function func_alias($target, $original) {
        eval("function $target() { \$args = func_get_args(); return call_user_func_array('$original', \$args); }");
    }
    func_alias('ppp', 'preprintdie');
    func_alias('pp', 'preprint');
}
    /**
     * d1g1 get option 
     *
     * @param $form_prefix 
     * @param $meta_name 
     * @author digihood
     * @return echo string
     */ 
if ( !function_exists('d1g1_get_option') ) {
    function d1g1_get_option($pugin_id, $form_prefix, $meta_name){
  
        $option = get_option ('_d1g1_' . digibrand\framework\Globals::$FWDIGI_PLUGINID . '_' . $form_prefix . '_' . $meta_name);
        return $option;
    }
}
/**
 * Vrátí správnou délku stringu
 *
 * @param $value string
 * 
 * @author digihood
 * @return int
 */ 
if ( !function_exists('valid_leght_string') ) {

    function valid_leght_string($value) {
        $strip = (strip_tags($value));
        $strip = trim($strip);
        $conversion_table = Array(
            'ä'=>'a',
            'Ä'=>'A',
            'á'=>'a',
            'Á'=>'A',
            'à'=>'a',
            'À'=>'A',
            'ã'=>'a',
            'Ã'=>'A',
            'â'=>'a',
            'Â'=>'A',
            'č'=>'c',
            'Č'=>'C',
            'ć'=>'c',
            'Ć'=>'C',
            'ď'=>'d',
            'Ď'=>'D',
            'ě'=>'e',
            'Ě'=>'E',
            'é'=>'e',
            'É'=>'E',
            'ë'=>'e',
            'Ë'=>'E',
            'è'=>'e',
            'È'=>'E',
            'ê'=>'e',
            'Ê'=>'E',
            'í'=>'i',
            'Í'=>'I',
            'ï'=>'i',
            'Ï'=>'I',
            'ì'=>'i',
            'Ì'=>'I',
            'î'=>'i',
            'Î'=>'I',
            'ľ'=>'l',
            'Ľ'=>'L',
            'ĺ'=>'l',
            'Ĺ'=>'L',
            'ń'=>'n',
            'Ń'=>'N',
            'ň'=>'n',
            'Ň'=>'N',
            'ñ'=>'n',
            'Ñ'=>'N',
            'ó'=>'o',
            'Ó'=>'O',
            'ö'=>'o',
            'Ö'=>'O',
            'ô'=>'o',
            'Ô'=>'O',
            'ò'=>'o',
            'Ò'=>'O',
            'õ'=>'o',
            'Õ'=>'O',
            'ő'=>'o',
            'Ő'=>'O',
            'ř'=>'r',
            'Ř'=>'R',
            'ŕ'=>'r',
            'Ŕ'=>'R',
            'š'=>'s',
            'Š'=>'S',
            'ś'=>'s',
            'Ś'=>'S',
            'ť'=>'t',
            'Ť'=>'T',
            'ú'=>'u',
            'Ú'=>'U',
            'ů'=>'u',
            'Ů'=>'U',
            'ü'=>'u',
            'Ü'=>'U',
            'ù'=>'u',
            'Ù'=>'U',
            'ũ'=>'u',
            'Ũ'=>'U',
            'û'=>'u',
            'Û'=>'U',
            'ý'=>'y',
            'Ý'=>'Y',
            'ž'=>'z',
            'Ž'=>'Z',
            'ź'=>'z',
            'Ź'=>'Z'
        );
        $return = strtr($strip, $conversion_table);
        $return = str_replace( array("\r", "\n"), '', $return );
        $num = strlen($return);

        return $num;
    }
}
     /**
     * ziskává SVG soubor 
     *
     * @param url
     * 
     * @author digihood
     * @return 
     */ 
    if( ! function_exists('d1g1_get_svg') ){
        function d1g1_get_svg($url) {
            $arrContextOptions=array(
                "ssl"=>array(
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                ),
            );  
            
            $response = file_get_contents($url , false, stream_context_create($arrContextOptions));
            return $response;

        }
    }

    /**
     * Vrací formátovaný řetězec data založený na daném formátu a vstupním datu.
     *
     * @param string $format Formát výstupního řetězce data.
     * @param int $date Unixový časový údaj reprezentující datum.
     * @throws Exception Pokud funkce wp_date neexistuje a verze PHP je nižší než 7.0.
     * @return string Formátovaný řetězec data.
     */

    if(!function_exists('digi_date')){
        function digi_date($format,$date){
            if(function_exists('wp_date')){
                return wp_date($format,$date);
            }elseif(version_compare(PHP_VERSION, '7.0', '<=')){
                return date($format,$date);
            }
        }
    }