<?php
namespace digibrand\framework;

if(!defined('ABSPATH')){
    exit;
}
class Globals{
    
    public static $FWDIGI_PLUGINID = '';
    public static $FWDIGI_PLUGSLUG = '';
    public static $FWDIGI_PLUGNAME = '';
    public static $FWDIGI_PATHSLUG = '';
    public static $FWDIGI_PATHTOFWASSET = '';
    public static $FWDIGI_URL = '';


    public function __construct(){
        self::$FWDIGI_PLUGSLUG = constant('D1G1_PLUGSLUG_'.D1G1_BRANDING);
        self::$FWDIGI_PLUGNAME = constant('D1G1_PLUGNAME_'.D1G1_BRANDING);
        self::$FWDIGI_PATHSLUG = constant('D1G1_PATHS_'.D1G1_BRANDING);
        self::$FWDIGI_PATHTOFWASSET = constant('D1G1_PATHTOFWASSET_'.D1G1_BRANDING);
        self::$FWDIGI_URL = constant('D1G1_URL_'.D1G1_BRANDING);
        self::$FWDIGI_PLUGINID = D1G1_BRANDING;
        if( ! defined('D1G1_SITE_BOX_LANG_SHOW')){
            define('D1G1_SITE_BOX_LANG_SHOW', ['cs','sk-SK']);
        }
    }
    


    public static function d1g1_get_option($form_prefix, $meta_name){
        $option = get_option (self::$FWDIGI_PLUGINID . '_' . $form_prefix .'_' . $meta_name);
        return $option;
    }
}
new Globals;

