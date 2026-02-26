<?php

/**
 * Vlastní styly pluginu
 *
 * 
 * @author digihood
 */ 

 use digibrand\framework\Globals;

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if ( !class_exists('CustomScriptsStylesD1G1') ) {

    class CustomScriptsStylesD1G1 {

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
    
            //scripty v administraci
            add_action( 'admin_enqueue_scripts', [$this, 'add_admin_scripts'] );

            //admin bar branding
            add_action( 'wp_footer', [$this, 'admin_bar_branding']);

            //add login style
			add_action( 'login_enqueue_scripts', [$this,'d1g1_login_stylesheet'] );
        
        }
    
        /**
		 * Registrace vlastního scriptu
		 *
		 * @param none
		 * 
		 * @author digihood
		 * @return echo/false
		 */ 
        public function add_admin_scripts( ) {
    
            //Register style
            $admin_style = filemtime(  Globals::$FWDIGI_PATHSLUG . 'assets/styles/d1g1hood-admin.css' );
            wp_enqueue_style( 'd1g1-admin', Globals::$FWDIGI_URL . 'assets/styles/d1g1hood-admin.css', array( ), $admin_style, 'all' );
                        
        }

        /**
		 * Add front end branding
		 *
		 * @param none
		 * 
		 * @author digihood
		 * @return echo/false
		 */ 
		public function admin_bar_branding( ) { 
            if(is_user_logged_in() == false) return;
            ?>
			<style>
			#wpadminbar #wp-admin-bar-wp-logo>.ab-item .ab-icon:before {
				content: ' ' !important;
				background: url( <?php  echo Globals::$FWDIGI_URL ?>assets/icons/digihood_icon-white.svg) no-repeat !important;
				width: 22px !important;
				height: 22px !important;
				display: block;
				background-size: 95% !important;
				background-position: center;
			}
			#wpadminbar .quicklinks .ab-sub-wrapper .menupop.hover>a, #wpadminbar .quicklinks .menupop ul li a:focus, #wpadminbar .quicklinks .menupop ul li a:focus strong, #wpadminbar .quicklinks .menupop ul li a:hover, #wpadminbar .quicklinks .menupop ul li a:hover strong, #wpadminbar .quicklinks .menupop.hover ul li a:focus, #wpadminbar .quicklinks .menupop.hover ul li a:hover, #wpadminbar .quicklinks .menupop.hover ul li div[tabindex]:focus, #wpadminbar .quicklinks .menupop.hover ul li div[tabindex]:hover, #wpadminbar li #adminbarsearch.adminbar-focused:before, #wpadminbar li .ab-item:focus .ab-icon:before, #wpadminbar li .ab-item:focus:before, #wpadminbar li a:focus .ab-icon:before, #wpadminbar li.hover .ab-icon:before, #wpadminbar li.hover .ab-item:before, #wpadminbar li:hover #adminbarsearch:before, #wpadminbar li:hover .ab-icon:before, #wpadminbar li:hover .ab-item:before, #wpadminbar.nojs .quicklinks .menupop:hover ul li a:focus, #wpadminbar.nojs .quicklinks .menupop:hover ul li a:hover,
			#wpadminbar:not(.mobile)>#wp-toolbar a:focus span.ab-label, #wpadminbar:not(.mobile)>#wp-toolbar li:hover span.ab-label, #wpadminbar>#wp-toolbar li.hover span.ab-label,
			#wpadminbar .ab-top-menu>li.hover>.ab-item, #wpadminbar.nojq .quicklinks .ab-top-menu>li>.ab-item:focus, #wpadminbar:not(.mobile) .ab-top-menu>li:hover>.ab-item, #wpadminbar:not(.mobile) .ab-top-menu>li>.ab-item:focus,
			#collapse-button:focus,
			#collapse-button:hover,
			#adminmenu li.opensub>a.menu-top {
				color: #8AB336 !important;
			}
			</style>
			<?php 
		}

        //add login stylesheet
		public function d1g1_login_stylesheet() { 
          
			if ( is_eng_d1g1( ) ) {
				$img_url =  Globals::$FWDIGI_URL . '/assets/img/d1g1-logo-cz.png';
			} else {
				$img_url =  Globals::$FWDIGI_URL . '/assets/img/d1g1-logo-cz.png';
			}

			?>

            <style type="text/css">

                body.login h1 a {
                background: transparent url( <?php echo $img_url; ?>) no-repeat !important;
                background-size: contain!important;
                width: 270px !important;
                height: 80px !important;
                }

                body.wp-core-ui .button-primary {
                    background:  #FF817E;
                    border-color: #FF817E #FF817E #FF817E;
                    -webkit-box-shadow: 0 1px 0 #FF817E;
                    box-shadow: 0 1px 0 #FF817E;
                    color: #fff;
                    text-decoration: none;
                    text-shadow: 0 -1px 1px #FF817E, 1px 0 1px #FF817E, 0 1px 1px #FF817E, -1px 0 1px #FF817E;
                    transition: 0.3 linear all;
                    -webkit-transition: 0.3 linear all;
                    -moz-transition: 0.3 linear all;
                }

                body.wp-core-ui .button-primary:active,
                body.wp-core-ui .button-primary:focus,
                body.wp-core-ui .button-primary:hover {
                    background: #dc6d6a;
                    border-color: #dc6d6a;
                    color: #fff;
                    box-shadow: inset 0 2px 0 #dc6d6a;
                }
                #language-switcher .button,
                #language-switcher-locales {
                    color: #FF817E!important;
                    border-color: #FF817E!important;
                }
                #language-switcher .button:hover,
                #language-switcher .button:focus,
                #language-switcher .button:target {
                    color: #dc6d6a!important;
                    border-color: #dc6d6a!important;
                }

                #language-switcher-locales:focus {
                    box-shadow: 0 0 2px #FF817E !important;
                }


                input[type=text]:focus, 
                input[type=search]:focus, 
                input[type=radio]:focus, 
                input[type=tel]:focus, 
                input[type=time]:focus, 
                input[type=url]:focus, 
                input[type=week]:focus, 
                input[type=password]:focus, 
                input[type=checkbox]:focus, 
                input[type=color]:focus, 
                input[type=date]:focus, 
                input[type=datetime]:focus, 
                input[type=datetime-local]:focus, 
                input[type=email]:focus, 
                input[type=month]:focus, 
                input[type=number]:focus, 
                select:focus, textarea:focus {
                    border-color: #FF817E !important;
                    -webkit-box-shadow: 0 0 2px #77D77B !important;
                    box-shadow: 0 0 2px #77D77B !important;
                }

                body.login .message {
                    border-left: 4px solid #77D77B;
                }

                body input[type=checkbox]:checked:before {
                    content: "\f147";
                    margin: -3px 0 0 -4px;
                    color: #77D77B!important;
                }

                .login #backtoblog a:hover, 
                .login #nav a:hover, 
                .login h1 a:hover {
                    color: #dc6d6a !important;
                }

                .login form input[type=checkbox]:focus {
                    box-shadow: 0 0 2px #FF817E !important;
                }
                a:focus {
                    -webkit-box-shadow: 0 0 0 1px #dc6d6a, 0 0 2px 1px rgba(30,140,190,.8) !important;
                    box-shadow: 0 0 0 1px #dc6d6a, 0 0 2px 1px rgba(30,140,190,.8) !important;

                }

                body.wp-core-ui .button-secondary .dashicons-hidden:before,
                body.wp-core-ui .button-secondary .dashicons-visibility:before {
                    color: #FF817E !important
                }

                body.login .button.wp-hide-pw:focus {
                    border-color: #dc6d6a;
                    box-shadow: 0 0 0 1px #dc6d6a;
                }

                body a,
                body a:hover,
                body a:focus {
                    color: #77D77B;
                }

                body.wp-core-ui .admin-email__actions-primary .button.button-large:not(#correct-admin-email) {
                    color: #dc6d6a;
                    border-color: #dc6d6a;
                }

                .wp-core-ui input[type=checkbox]:checked:before {
                    margin: 7px 0 0 -4px;
                    color: #dc6d6a;
                    content: '\2714';
                }

            </style>

		<?php } 
    
    }
    new CustomScriptsStylesD1G1;

}
