<?php 
namespace digibrand\framework\digibrandAdmin\Menu;
use digibrand\framework\globals;
use digibrand\framework\pluginAdmin\InfoBox\viewAdminBox;
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}
if( ! class_exists( 'd1g1MainMenu' ) )
{

    /**
     * Hlavní menu pluginu
     * 
     * @author digihood
     */

    class d1g1MainMenu
	{

        /**
         * Konstruktor
         *
         * @param none
         * @return none
         * 
         */
		public function __construct()
		{
            add_action( 'admin_menu',  [$this, 'main_menu_callback'] );
           
  
        }

        /**
         * Přidává stránku nastavení digihood pluginů do adminu
         *
         * @param none
         * 
         * @author digihood
         * @return bool
         */ 
        public function main_menu_callback() {
            if (!isset( $GLOBALS['admin_page_hooks']['d1g1-plugins']) || empty( $GLOBALS['admin_page_hooks']['d1g1-plugins'] ) ) {
                add_menu_page(
                    __( 'Digi digibrands', globals::$FWDIGI_PLUGINID ),
                    __( 'Digi digibrands', globals::$FWDIGI_PLUGINID ),
                    'manage_options',
                    'd1g1-plugins',
                    array( $this, 'my_admin_page_contents' ),
                    'dashicons-admin-generic',
                    65
                );
        
            }
            
            
        }

        /**
         * obsah hlavní admin stránky v adminu wordpressu
         *
         * @param none
         * 
         * @author digihood
         */ 
        static function my_admin_page_contents() {

            $lang = get_user_locale();
            if(in_array($lang,(D1G1_SITE_BOX_LANG_SHOW))){
                $digiLink = 'https://digihood.cz/';
                $digiMail = 'hello@digihood.cz';
                $about = 'https://digihood.cz/o-digihoodu';
            }else{
                $digiLink = 'https://digihood.co.uk/';
                $digiMail = 'hello@digihood.co.uk';
                $about = 'https://digihood.co.uk/about-digihood';
            }
            $phone = '+420 777 657 474';


            ob_start();
            ?>
                <div class="wrap d1g1-admin">
                    <h1> <?= __('Digihood pluginy', 'digiSM') ?> </h1>
                    <div class="column content content-box">
                        <h3> <?php echo __('Vítejte v administrační stránce Digihood pluginů. Jsme rádi že jste si zvolili právě naše služby.', 'digiSM') ?> </h3>
                        <p> <?php echo __('Jedním ze zaměření našeho Digihood týmu je právě tvorba šablon a pluginů pro Wordpress.', 'digiSM') ?> <bold><a href="<?= $about?>"> <?php echo __('(Více o nás)') ?></a></bold>  </p>
                        <p> <?php echo __('Nemůžete najít šablonu která by vystihovala váš brand nebo scháníte specifickou funcionalitu kterou doposud žádný wordpressový plugin neumožňuje? Jsme schopni vaše nápady uskutečnnit, neváhejte nás kontaktovat!', 'digiSM') ?> </p>
                    
                        <ul class="contact-list">
                            <li> <?= $digiMail ?> </li>
                            <li> <?= $phone . __('8:00 až 16:30 ve všední dny', 'digiSM') ?>  </li>
                            <li> <?= __('skrze formulář na našich', 'digiSM') ?> <a href="https://digihood.cz/"> <?= __('webových stránkách','digiSM') ?> </a></li>
                        </ul>
                    </div>
                    <div class="column sidebar">
                        <?php
                        $zobrazeni = new viewAdminBox;
                        $zobrazeni->d1g1_dashboard_widget_display('pluginsnews');
                        $zobrazeni->d1g1_dashboard_widget_display('plugins');
                        ?>
                    </div>
                </div>

            <?php 
            $html = ob_get_clean();
            echo $html;
            
        }

    }
	new d1g1MainMenu;
}
