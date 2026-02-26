<?php 
namespace digibrand\framework;
use digibrand\framework\Globals;
/**
 * Digihood sessions
 *
 * 
 * @author digihood
 */ 

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'd1g1Session' ) )
{
	class d1g1Session
	{
		public function __construct()
		{
            
        }
        
        public static function start_session(){
            if(!session_id() ){ 
                session_start( );
            }
           
        }
        public static function end_session(){
            session_write_close();
        }
        /**
         * Vytvoření session
         *
         * @param $name = jméno session
         * @param $value = hodnota
         * 
         * @author digihood
         * @return true/false
         */ 
        public static function add_session( $name, $value="" ) 
        {
            $_SESSION[$name] = $value;
            return true;
        }

        /**
         * Kontrola session
         *
         * @param $name = jméno session
         * @param $value = hodnota
         * 
         * @author digihood
         * @return true/false
         */ 
        public static function check_session( $name, $value="" ) 
        {
            if ( isset( $_SESSION[$name] ) ) 
            {
                if ( $value !== "" ) 
                {
                    if ( $_SESSION[$name] == $value ) 
                    {
                        return true;
                    }                    
                } else {
                    if ( $_SESSION[$name] == 'error' || $_SESSION[$name] == 'success' || $_SESSION[$name] == 'fail') 
                    {
                        return true;
                    } else {

                    }
                }
            }
            return false;
        }

        /**
         * Získat session
         *
         * @param $name = jméno session
         * 
         * @author digihood
         * @return true/false
         */ 
        public static function get_session( $name ) 
        {
            if ( isset( $_SESSION[$name] ) ) 
            {
                return $_SESSION[$name];
            }
            return false;

        }

        /**
         * Odebrat session
         *
         * @param $name = jméno session
         * 
         * @author digihood
         * @return true/false
         */ 
        public static function remove_session( $name ) 
        {   
            if ( isset( $_SESSION[$name] ) ) 
            { 
                unset($_SESSION[$name]);
                return true;
            }
            return false;
        }

        /**
         * kontrola aktivace sessin
         *
         * @param $name = jméno session
         * 
         * @author digihood
         * @return true/false
         */ 
        public static function chceck_all_sesion() {

            if (isset($_SESSION)){

                return $_SESSION;
                }
                echo 'neni sessions';
                
        }

        /**
         * přidat zprávu
         *
         * @param $message = obsah zprávy
         * @param $errormsq = zda je to chybová hláška
         * 
         * @author digihood
         * @return true/false
         */ 
        public static function show_message($session, $mess)
        {
            $type = 'success';
            $class = 'notice-success';
            if ( self::check_session($session)) {
                if (self::get_session($session) == 'fail' || self::get_session($session) == 'error'){
                    $class = 'notice-error';
                    $type = self::get_session($session);
                }else if (self::get_session($session) == 'warning') {
                    $class = 'notice-warning';
                    $type = self::get_session($session);
                }
                $content = $mess[$type];
                if(empty($content)) $content = self::default_message($type);
                echo '<div class="notice '.$class.' d1g1-notice is-dismissible">'. 
                    '<p>'.$content.'</p>
                    <button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Skrýt toto upozornění.', Globals::$FWDIGI_PLUGINID ).'</span></button>
                </div>';
                // echo '<div id="message" class="updated fade">';

            }
          
        } 
        /**
         * Vrátí základní zprávu, pokud není žádná vyplněná
         *
         * @param $type = Typ zprávy (success, error, fail)
         * 
         * @author digihood
         * @return string
         */ 
        private static function default_message($type) {
            if ($type == 'success'){
                return __("Nastavení bylo úspěšně aktualizováno", Globals::$FWDIGI_PLUGINID);
            } else {
                return __("Nastavení se nepodařilo uložit. Zkuste to, prosím, znovu.", Globals::$FWDIGI_PLUGINID);
            }
        }
        
        /**
         * zobrazi všechny session
         *
         * @param $form_ids = ID formuláře nebo jména sessions
         * 
         * @author digihood
         * @return true/false
         */ 
        public static function display_notice($form_ids) {
            if ($form_ids){
                if (is_array($form_ids)) {
                    foreach ($form_ids as $form_id => $mess) {
                        self::show_message($form_id, $mess);
                    }
                }
            }
        }
            
    }
 }