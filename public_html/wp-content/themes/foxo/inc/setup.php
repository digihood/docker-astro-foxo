<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'FoxoThemeSetup' ) ) {

    class FoxoThemeSetup {

        public function __construct() {
            add_action( 'after_setup_theme', [ $this, 'site_setup' ] );
            add_action( 'init', [ $this, 'require_acf' ] );
            add_action( 'init', 'foxo_disable_emojis' );
        }

        /**
         * Základní nastavení šablony
         */
        public function site_setup() {
            load_theme_textdomain( 'foxo', FOXO_DIR . '/languages' );

            add_theme_support( 'post-thumbnails' );
            add_theme_support( 'menus' );
            add_theme_support( 'title-tag' );
            add_theme_support( 'custom-logo', [
                'height'               => 80,
                'width'                => 200,
                'flex-height'          => true,
                'flex-width'           => true,
                'unlink-homepage-logo' => false,
            ] );

            add_theme_support( 'html5', [
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'style',
                'script',
            ] );

            // Remove default image sizes
            remove_image_size( '1536x1536' );
            remove_image_size( '2048x2048' );

            // Register menus
            register_nav_menus( [
                'main_menu'   => __( 'Hlavní menu', 'foxo' ),
                'footer_menu' => __( 'Menu zápatí', 'foxo' ),
            ] );

            $GLOBALS['content_width'] = 1640;
        }

        /**
         * Ukončí šablonu, pokud není aktivní ACF
         */
        public function require_acf() {
            if ( is_admin() ) {
                return;
            }

            if ( ! function_exists( 'get_field' ) ) {
                wp_die(
                    __( 'Plugin Advanced Custom Fields Pro je požadovaný. Aktivujte ho.', 'foxo' ),
                    __( 'Chybějící plugin', 'foxo' ),
                    [ 'back_link' => true ]
                );
            }
        }
    }

    new FoxoThemeSetup();
}

/**
 * Odebere WordPress emojis
 */
function foxo_disable_emojis() {
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

    add_filter( 'tiny_mce_plugins', function ( $plugins ) {
        return is_array( $plugins ) ? array_diff( $plugins, [ 'wpemoji' ] ) : [];
    } );
}
