<?php

/**
 * Vlastní styly pluginu
 *
 * 
 * @author digihood
 */ 

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if ( !class_exists('AdminUpdatesD1G1') ) {

    class AdminUpdatesD1G1 {

        /**
         * 
         * Constructor
         *
         * @date	21/1/21
         * @since	1.0
         *
         * @return	void
         */
        public function __construct()
        {
    
            //add description
			add_filter('admin_footer_text', [$this,'d1g1_remove_footer_admin'] );
            //change login url
			add_filter( 'login_headerurl', [$this,'d1g1_homepage_url'] );
        
        }
    
        /**
         * 
         * Constructor
         *
         * @date	21/1/21
         * @since	2.0
         *
         * @return	echo
         */
        public function d1g1_remove_footer_admin () {

			if ( is_eng_d1g1( ) ) {
				echo '<p>Website by <a href="https://www.digihood.cz/en/" target="_blank">digihood</a>. If you have any questions, please send an email to <a href="mailto:hello@digihood.cz" target="_blank">info@digihood.cz</a>.';
			} else {
				echo '<p>Webové stránky vytvořil <a href="https://www.digihood.cz/" target="_blank">digihood</a>. Pokud máte jakékoliv otázky, napište na <a href="mailto:hello@digihood.cz" target="_blank">info@digihood.cz</a>.';
			}

		} 

        /**
         * 
         * Přidá odkaz na domácí stránky
         * @since	2.0
         *
         * @return	string
         */
        public function d1g1_homepage_url() {
			return 'https://www.digihood.cz/';
		}
    
    }
    
    new AdminUpdatesD1G1;

}
