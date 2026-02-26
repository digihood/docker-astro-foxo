<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Theme version
define( 'FOXO_VERSION', '1.0.0' );

// Theme directory shortcuts
define( 'FOXO_DIR', get_template_directory() );
define( 'FOXO_URI', get_template_directory_uri() );

// Frontend URL (Astro) — can be overridden via wp-config.php define() or server env
if ( ! defined( 'FOXO_FRONTEND_URL' ) ) {
    define( 'FOXO_FRONTEND_URL', getenv( 'FOXO_FRONTEND_URL' ) ?: 'http://localhost:4321' );
}
if ( ! defined( 'FOXO_REVALIDATE_SECRET' ) ) {
    define( 'FOXO_REVALIDATE_SECRET', getenv( 'FOXO_REVALIDATE_SECRET' ) ?: 'dev-revalidate-secret' );
}
if ( ! defined( 'FOXO_PREVIEW_SECRET' ) ) {
    define( 'FOXO_PREVIEW_SECRET', getenv( 'FOXO_PREVIEW_SECRET' ) ?: 'dev-preview-secret' );
}

// Include theme functions
include_once FOXO_DIR . '/inc/helpers.php';
include_once FOXO_DIR . '/inc/setup.php';
include_once FOXO_DIR . '/inc/options.php';
include_once FOXO_DIR . '/inc/blocks.php';
include_once FOXO_DIR . '/inc/rest-api.php';
include_once FOXO_DIR . '/inc/headless.php';
include_once FOXO_DIR . '/inc/revalidation.php';
