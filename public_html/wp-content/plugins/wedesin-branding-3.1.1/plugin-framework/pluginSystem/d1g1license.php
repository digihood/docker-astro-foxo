<?php
namespace digibrand\framework\pluginSystem;
use digibrand\framework\PluginAdmin\d1g1PluginsAPI;
use digibrand\framework\d1g1Session;


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if( ! class_exists( 'd1g1license' ) )
{
	class d1g1license
	{   
       
       static private $license = '';
       static private $home_page;
       static private $url;
     
        public function __construct()
		{ 
            if(is_licensing()){
                if(get_option ('_d1g1_' . D1G1_BRANDING . '_license_license_key')){
                    self::$home_page = get_home_url();
                    self::$license = get_option ('_d1g1_' . D1G1_BRANDING . '_license_license_key');
                    self::$url = 'https://digihood.dev/licenses/wp-json/api/v3/token?license=' . self::$license . '&url=' . self::$home_page;
                }
            
                add_action('admin_notices', [$this,'general_admin_notice']);
                add_action('admin_init', [$this,'get_plugin_info']);
                add_action( 'admin_enqueue_scripts', [$this, 'scripts'] );
            }


		}

        public function scripts(){
               //register javascript

        $admin_script = filemtime(  D1G1_BRANDPATH . 'assets/scripts/wds-update.js' );
        wp_enqueue_script( 'plugin-update-'.D1G1_BRANDING, D1G1_BRANDURL . 'assets/scripts/wds-update.js', array( 'jquery' ), $admin_script, true );
      
        }
        public function get_plugin_info(){
            if(isset($_GET['get_plugin_info']) && $_GET['get_plugin_info'] == D1G1_BRANDING){
                $plugin = new d1g1PluginsAPI;
                $data = $plugin->d1g1_get_plugins();
                $check =  (array) $this->check_update();
                
                $this_plugin = [];
                foreach($data as $key => $plugin){
                    if($plugin['TextDomain'] == D1G1_BRANDING){
                       $data[$key]['new_version'] = $check['version'];
                       $this_plugin[] = $data[$key];
                    }
                }
              
                return wp_send_json($this_plugin);
            }
        }
     
      

        static function check_lisense(){
           
            try {
                // Přimé napojení Rest API na ziskanou URL 
                    $response = wp_remote_get(self::$url); 
               
                    
                } catch (Error $e) {
                    echo "Při aktualizace došlo k chybě " . $e->getMessage() . '.';
                }

                // Ukončí pokud se vyskytne error vrátí prazdno
                if ( !$response || is_wp_error( $response ) || !is_array( $response ) ) {
                    
                    $license = [
                        'code' => 500,
                        'message' => 'Nelze se připojit k serveru'
                    ];
                    d1g1Session::add_session(D1G1_BRANDING,$license);
                    return false;
                }
                $token = json_decode( wp_remote_retrieve_body( $response ) );
              
                if($token->code == 400 || $token->code == 401 || $token->code == 402 || $token->code == 500){
                    d1g1Session::add_session(D1G1_BRANDING,(array)$token);
                }else{
                    d1g1Session::remove_session(D1G1_BRANDING);
                }
              
               
                return $token;  

        }

        static function check_update(){
            if(self::check_lisense() == false)return;
            $token = self::check_lisense();
            if(isset($token->token) && $token->token){
                return $token;
            }elseif(isset($token->code) && $token->code){
                $error = ['code' => $token->code,'message' => $token->message ,'version' => (isset($token->version) && $token->version ? $token->version : '')];
                return $error ;
            }
        }

        function general_admin_notice(){
            
            $notice = d1g1Session::get_session(D1G1_BRANDING);
            
            if(isset($notice) && $notice){
                if($notice['code'] == 400){
                    $class = 'notice-error';
                }
                if($notice['code'] == 500){
                    $class = 'notice-error';
                }
                if($notice['code'] == 401){
                    $class = 'notice-warning';
                }
                if($notice['code'] == 402){
                    $class = 'notice-warning';
                }
                echo '<div class="notice '.$class.' d1g1-notice is-dismissible">'. 
                        '<h3>'.TM_PLUGNAMEBRAND.'</h3><p>'.$notice['message'].'</p>
                        <button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Skrýt toto upozornění.', D1G1_BRANDING).'</span></button>
                     </div>';
            }
        }
      
        static function validate_lisense_key(){
            $token = self::check_lisense();
            if($token == false)return;
            if(isset($token->token) && $token->token){
                return true;
            }elseif(isset($token->code) && $token->code){
                return false;
            }
        }

       
        
        

        
    }
   // new d1g1license;
}