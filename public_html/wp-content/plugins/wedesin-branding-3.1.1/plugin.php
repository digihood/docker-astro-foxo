<?php
namespace digibrand;
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
/*
 * Plugin Name: digihood Branding
 * Plugin URI: digihood.cz   
 * Description: Nová verze digihood branding pluginu 4.1.0, Děkujeme, že děláte weby s námi.
 * Version: 4.1.0
 * Author: digihood    
 * Author URI: digihood.cz   
 */

// Global PFW constants
define( 'D1G1_BRANDING', 'digibrand' );
define( 'D1G1_PLUGSLUG_'.D1G1_BRANDING, 'd1g1_plugins');
define( 'D1G1_PLUGNAME_'.D1G1_BRANDING, 'digihood Branding');
define( 'D1G1_PATHS_'.D1G1_BRANDING, plugin_dir_path( __FILE__ ) );
define( 'D1G1_PATHTOFWASSET_'.D1G1_BRANDING, plugin_dir_path( __FILE__ ).'plugin-framework/FrameworkAssets/' );
define( 'D1G1_URL_'.D1G1_BRANDING, plugin_dir_url( __FILE__ ) );
if( ! defined('D1G1_SITE_BOX_LANG_SHOW')){
    define('D1G1_SITE_BOX_LANG_SHOW', ['cs','sk-SK']);
}


define( 'D1G1_GITHUBURL_BRAND', 'https://github.com/digihood/wedesin-branding');
define( 'D1G1_GITHUBREP_BRAND', 'wedesin-branding');


//dočastné definice
if(!defined('GithubToken')){
    define( 'GithubToken', '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');
}

// require __DIR__.'/plugin-framework/kint.phar';

include_once __DIR__ . '/includes.php';
include_once(__DIR__ . '/plugin-framework/pluginSystem/d1g1PluginRequirements.php');  
class DemandingPlugin extends \digibrand\framework\pluginSystem\d1g1PluginRequirements {
 
    const PLUGIN_NAME = "digihood Branding";
 
    public function __construct() {
        $this->add_activation_hooks( __FILE__ ); 
    }
 
    protected function check_plugin_requirements() {
        global $wp_version;
        $failed = array();
        if ( $wp_version < 4.0 )
            $failed[] = 'WordPress version must be at least 4.0! ';
        if ( version_compare( PHP_VERSION, '5.6.0', '<' ) )
            $failed[] = 'PHP version must be at least 5.6.0! ';
        return $failed;
    }
 
}
$DemandingPlugin = new DemandingPlugin();



$license = \digibrand\framework\pluginSystem\UpdatePlugin::Update_Plugin();