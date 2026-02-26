<?php 
/**
 * Popis třídy
 *
 * 
 * @author Wedesin
 */ 

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'digiWPdie' ) )
{
	class digiWPdie
	{

        //private $variable; 

		public function __construct()
		{
            add_filter( 'wp_die_handler', function( $handler ) {
                return ! is_admin() ? [$this,'themed_wp_die_handler'] : $handler;
            }, 10 );
        }

        
        
        
        /**
         * Use a custom wp_die() handler.
         */
        function themed_wp_die_handler( $message, $title = '', $args = array() ) {
            $defaults = array( 'response' => 500 );
            $r = wp_parse_args($args, $defaults);
        
            if ( function_exists( 'is_wp_error' ) && is_wp_error( $message ) ) {
                $errors = $message->get_error_messages();
                switch ( count( $errors ) ) {
                    case 0 :
                        $message = '';
                        break;
                    case 1 :
                        $message = $errors[0];
                        break;
                    default :
                        $message = "<ul>\n\t\t<li>" . join( "</li>\n\t\t<li>", $errors ) . "</li>\n\t</ul>";
                        break;
                }
        
            } else {
                $message = strip_tags( $message );
            }
        
            require_once __DIR__ . '/wp-die.php';
        
            die();
        }
    }
     new digiWPdie;
}