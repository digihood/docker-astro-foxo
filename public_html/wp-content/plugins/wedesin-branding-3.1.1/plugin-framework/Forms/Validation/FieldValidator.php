<?php 
namespace digibrand\framework\forms\Validation;

if (!defined('ABSPATH')) {
    exit;
}

if(!trait_exists('FieldValidator')){
   trait FieldValidator {

        /**
         * required
         * 
         * @param $value
         * @return void
         */
        public static function required($value,$html = false){
            if($value == ''){
                return false;
            }else{
                if($html){
                    return 'required';
                }
                return true;
            }
        }

        /**
         * string
         * 
         * @param $value
         * @return void
         */
        public static function string($value){
            if(is_string($value)){
                return true;
            }else{
                return false;
            }

        }

        /**
         * email
         * 
         * @param $value
         * @return void
         */
        public static function email($value){
            if(filter_var($value, FILTER_VALIDATE_EMAIL)){
                return true;
            }else{
                return false;
            }
        }

        /**
         * url
         * 
         * @param $value
         * @return void
         */
        public static function url($value){
            if(filter_var($value, FILTER_VALIDATE_URL)){
                return true;
            }else{
                return false;
            }
        }

        /**
         * numeric
         * 
         * @param $value
         * @return void
         */
        public static function numeric($value){
            if(is_numeric($value)){
                return true;
            }else{
                return false;
            }
        }

        /**
         * same
         * 
         * // připravená funkce pro porovnání dvou polí
         * @param $value
         * @param $field
         * @param $values
         * @return void
         */

        public static function same($value,$field,$values){
            if($value == $values[$field[1]]){
                return true;
            }else{
                return false;
            }
        }

        /** 
         * date 
         * 
         * @param $value
         * @return void
         */
        public static function date($value){
            if(preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $value, $matches)){
                if(checkdate($matches[2], $matches[3], $matches[1])){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
        /**
         * file
         * 
         * @param $value
         * @return void
         */
        public static function file($value,$rule){
             
           
            if(is_file($value)){
                $file_type =   pathinfo($value, PATHINFO_EXTENSION);     
              
                if(isset($rule[1])){
                  
                    if(strpos($rule[1], $file_type) !== false){
                        return true;
                    }else{
                        return false;
                    }
                }
                return true;
            }else{
                return false;
            }
        }

        /**
         * image
         * 
         * @param $value
         * @return void
         */
        public static function image($value){
            if(getimagesize($value)){
                return true;
            }else{
                return false;
            }
        }

        /**
         * mime
         * 
         * @param $value
         * @param $mime
         * @return void
         */
        public static function mime($value,$mime){
            if(is_file($value)){
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime_type = finfo_file($finfo, $value);
                finfo_close($finfo);
                if($mime_type == $mime){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

        /**
         * required_if
         * 
         * @param $value
         * @param $field
         * @param $Request
         * @return void
         */
        public static function required_if($value,$field,$Request){
            if($Request[$field] != ''){
                if($value == ''){
                    return false;
                }else{
                    return true;
                }
            }else{
                return true;
            }
        }

        /**
         * size
         * 
         * @param $value
         * @param $size
         * @return void
         */
        public static function size($value,$size){
            if(is_numeric($value)){
                return self::size_numeric($value,$size);
            }elseif(is_string($value)){
                return self::size_string($value,$size);
            }elseif(is_file($value)){
                return self::size_file($value,$size);
            }
        }

        /**
         * size_numeric
         * 
         * @param $value
         * @param $size
         * @return void
         */
        public static function size_numeric($value,$size){
            if($value == $size){
                return true;
            }else{
                return false;
            }
        }

        /**
         * size_string
         * 
         * @param $value
         * @param $size
         * @return void
         */
        public static function size_string($value,$size){
            if(self::valid_leght_string($value) == $size){
                return true;
            }else{
                return false;
            }
        }

        /**
         * size_file
         * 
         * @param $value
         * @param $size
         * @return void
         */

        public static function size_file($value,$size){
            if(filesize($value) == $size){
                return true;
            }else{
                return false;
            }   
        }

        /**
         * max value 
         * 
         * @param $value
         * @param $max
         */
        public static function max($value,$max,$type){
           
            switch ($type) {
                case 'number':
                    return self::max_numeric($value,$max);
                    break;
                default:
                return self::max_string($value,$max);
                    break;
            }
           
        }

        /**
         * max_numeric
         * 
         * @param $value
         * @param $max
         * @return void
         */

        public static function max_numeric($value,$max){
            if($value <= $max){
                return true;
            }else{
                return false;
            }
        }

        /**
         * max_string
         * 
         * @param $value
         * @param $max
         * @return void
         */
        public static function max_string($value,$max){
            if(self::valid_leght_string($value) <= $max){
                return true;
            }else{
                return false;
            }
        }

        /**
         * min value 
         * 
         * @param $value
         * @param $min
         * @return void
         */

        public static function min($value,$min,$type){
            switch ($type) {
                case 'number':
                    return self::min_numeric($value,$min);
                    break;
                default:
                return self::min_string($value,$min);
                    break;
            }
 
        }

        /**
         * min_numeric
         * 
         * @param $value
         * @param $min
         * @return void
         */
        public static function min_numeric($value,$min){
            
            if($value >= $min){
                return true;
            }else{
                return false;
            }

        }

        /**
         * min_string
         * 
         * @param $value
         * @param $min
         * @return void
         */
        public static function min_string($value,$min){
            if(self::valid_leght_string($value) >= $min){
                return true;
            }else{
                return false;
            }
        }

         /**
         * validace textu pole
         *
         * @param $value
         *        
         * 
         * @author digihood
         * 
         */ 
        public static function valid_leght_string($value) {
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

   /**
    * Checks if a string is numeric and a string representation of a number.
    *
    * @param mixed $str The input string to evaluate.
    * @return bool Returns `true` if the input string is numeric and a string representation of a number, `false` otherwise.
    */
    static function is_numeric_string($str) {
        return is_numeric($str) && is_string($str + 0);
    }
   }

}