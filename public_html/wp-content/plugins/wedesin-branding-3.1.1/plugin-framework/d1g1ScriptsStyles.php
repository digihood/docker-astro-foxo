<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}
use digibrand\framework\Globals;
if ( !class_exists('d1g1ScriptsStyles') ) {

  /**
   * načítání scriptů a stylů pro framework.
   */
  class d1g1ScriptsStyles {

    /**
     * Constructor
     */
    public function __construct()
    {
      add_action( 'admin_enqueue_scripts', [$this, 'add_admin_scripts'] );
    }
  
    public function add_admin_scripts( ) {
   
      //Register style
      $admin_style = filemtime(  Globals::$FWDIGI_PATHSLUG . 'plugin-framework/FrameworkAssets/styles/d1g1-admin.css' );

      wp_enqueue_style( 'star-digibrand', Globals::$FWDIGI_URL . 'plugin-framework/FrameworkAssets/styles/d1g1-admin.css', array( ), $admin_style, 'all' );
    
    
      wp_enqueue_style( 'font-Lato', 'https://fonts.googleapis.com/css2?family=Lato:ital@0;1&display=swap' );
  
      wp_enqueue_style( 'wp-color-picker' );

      //register javascript
      $admin_script = filemtime(  Globals::$FWDIGI_PATHSLUG . 'plugin-framework/FrameworkAssets/scripts/d1g1-admin.js' );
      wp_enqueue_script( 'star-digibrand', Globals::$FWDIGI_URL . 'plugin-framework/FrameworkAssets/scripts/d1g1-admin.js', array( 'jquery' ), $admin_script, true );
      
      //WP media uploader
      wp_enqueue_media();
  
      //color picker scripts
      $colorpicker_script = filemtime(  Globals::$FWDIGI_PATHSLUG . 'plugin-framework/FrameworkAssets/scripts/wp-color-picker-alpha.min.js' );
      wp_enqueue_script( 'wp-color-picker-alpha', Globals::$FWDIGI_URL . 'plugin-framework/FrameworkAssets/scripts/wp-color-picker-alpha.min.js', array( 'wp-color-picker' ), $colorpicker_script, true );
  
      wp_add_inline_script(
        'wp-color-picker-alpha',
        'jQuery( function() { jQuery( ".color-picker" ).wpColorPicker(); } );'
      );
  
      //Selectize.js
      $selectize_script = filemtime(   Globals::$FWDIGI_PATHSLUG . 'plugin-framework/FrameworkAssets/scripts/selectize.js' );
      wp_enqueue_script( 'selectize', Globals::$FWDIGI_URL . 'plugin-framework/FrameworkAssets/scripts/selectize.js', array( 'jquery' ), $selectize_script, true );
  
      $selectize_style = filemtime(   Globals::$FWDIGI_PATHSLUG . 'plugin-framework/FrameworkAssets/styles/selectize.css' );
      wp_enqueue_style( 'selectize', Globals::$FWDIGI_URL . 'plugin-framework/FrameworkAssets/styles/selectize.css', array( ), $selectize_style, 'all' );
      
    }
  
  }
  new d1g1ScriptsStyles;

}
