<?php

namespace digibrand\framework\pluginAdmin\Menu;

use LDAP\Result;
//use digibrand\framework\Forms\ResponseForms;
use digibrand\framework\Forms\formObject;
use digibrand\framework\Globals;
use digibrand\framework\pluginAdmin\InfoBox\viewAdminBox;

if (!defined('ABSPATH')) {

    exit;
}

if (!class_exists('d1d1SubMenuContent')) {
    class d1d1SubMenuContent
    {   

        /**
         * Aktivní tab
         *
         * @var string
         */
        private static $tab;

        public function __construct()
        {
        
        }
        /**
         * Zobrazení ukázkového formuláře
         *
         * @param none
         * 
         * @author digihood
         * @return true/false
         */
        static function my_admin_page_contents(){   
            //Získat aktivní tab z parametru $_GET 
            $default_tab = null;
            self::$tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab; ?>
            <div class="wrap d1g1-admin">
                <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
                <div class="row">
                    <div class="column content">
                        <nav class="nav-tab-wrapper">
                            <?php 
                                do_action( 'd1g1_navigations-'.Globals::$FWDIGI_PLUGINID, self::$tab);
                                echo '<a href="?page='.Globals::$FWDIGI_PLUGINID.'&tab=feedback" class="nav-tab '.(self::$tab === 'feedback' ? 'nav-tab-active' : '' ).'">'. d1g1_get_svg(Globals::$FWDIGI_PATHTOFWASSET . "icons/link-line.svg").'FeedBack</a>';
                            ?>
                            
                        </nav>
                        <?= self::show_content() ?>
                    </div>
                    <div class="column sidebar">
                    
                    <?php 
                    $zobrazeni = new viewAdminBox;
                    $zobrazeni->d1g1_dashboard_widget_display('pluginsnews');
                    $zobrazeni->d1g1_dashboard_widget_display('plugins');
                    ?>
                    </div>
                </div>
            </div>       
        <?php
        }

        /**
         * Zobrazení obsahu tabu
         *
         * @param none
         * @return none
         * 
         */
        private static function show_content(){
            echo '<div class="content-box">';
            $contents = [
                'feedback' => 'feedback'
            ];
            $contents = apply_filters( 'd1g1_navigation_content-'.Globals::$FWDIGI_PLUGINID, $contents );
            foreach($contents as $tab_name => $content){
            
                if(is_array($content)){
                    if(isset($content[0]) && $content[0] && isset($content[1]) && $content[1]){
                        if(class_exists($content[0]) && method_exists($content[0],$content[1])){
                            call_user_func( $content[0].'::'.$content[1]);
                        }
                    }
                }else{
                    self::$tab = (self::$tab === null ? 'default' : self::$tab);
                    if(self::$tab === $tab_name ){
                       
                        $form = new formObject($content);
                        echo $form->get_form_html();
                    }
                }  
            }
           echo '</div>';
           

        }
    }

    new d1d1SubMenuContent;
}