<?php 
namespace digibrand\framework\Functions\Logging;

use digibrand\framework\Globals;

if ( ! defined( 'ABSPATH' ) ) {

  exit;

}

    class d1g1LogContent{
        public $LogFile;

        //konstruktor
        public function __construct()
        {
            //spuštní
        
            add_action('d1g1_plugin_log_tab_content', [$this, 'add_tab'] );
            add_action('d1g1_plugin_log_header_content', [$this, 'add_header'] );
            //add_action( 'init',  [$this, 'test__log'] );

        }

        /**
         * Přidá záložku logu pro tento plugin
         *
         * @param none
         * 
         * @author digihood
         * @return true/false
         */ 
        public function add_header( ) { 
            $plugin_data = get_plugin_data( Globals::$FWDIGI_PATHSLUG . 'plugin.php' );
            $plugin_name = $plugin_data['Name'];
            
            $default_tab = null;
            $tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;
            ?>
            <a href="?page=d1g1-logs&tab=<?= Globals::$FWDIGI_PLUGINID ?>" class="nav-tab <?= ($tab=== globals::$FWDIGI_PLUGINID ? 'nav-tab-active' : '')?>">
                <?php echo $plugin_name; ?>
            </a>
        <?php }

        /**
         * Přidá záložku logu pro tento plugin
         *
         * @param none
         * 
         * @author digihood
         * @return true/false
         */ 
        public function add_tab( ) {
            $tab = isset($_GET['tab']) ? $_GET['tab'] : '';
            $file = $this->LogFile;
            if ($tab) $file = $tab;
            $this->the_log_excerpt_admin($file);
        }

        /**
         * Výpis logu do admina 
         *
         * @param none
         * 
         * @author digihood
         * @return true/false
         */ 
        public function the_log_excerpt_admin() { 
      
                if ($_GET['tab'] == Globals::$FWDIGI_PLUGINID ) { 
            
                    include_once( __DIR__. '/html.php' );
                }
        }
    }

new d1g1LogContent;