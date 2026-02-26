<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Přesměruje WP frontend na Astro (kromě admin, REST, AJAX, cron)
 */
function foxo_headless_redirect() {
    // Nepřesměrovávat v admin, REST API, AJAX, WP Cron, CLI
    if (
        is_admin()
        || wp_doing_ajax()
        || wp_doing_cron()
        || ( defined( 'REST_REQUEST' ) && REST_REQUEST )
        || ( defined( 'WP_CLI' ) && WP_CLI )
        || ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST )
    ) {
        return;
    }

    // Nepřesměrovávat sitemapy, robots.txt, XSL — servíruje je WP (Rank Math)
    $request_uri = $_SERVER['REQUEST_URI'] ?? '';
    if (
        preg_match( '/sitemap.*\.xml/', $request_uri )
        || preg_match( '/\.xsl$/', $request_uri )
        || $request_uri === '/robots.txt'
    ) {
        return;
    }

    // Nepřesměrovávat preview (řeší se zvlášť)
    if ( is_preview() ) {
        return;
    }

    // Nepřesměrovávat pokud je přihlášený admin v customizeru
    if ( is_customize_preview() ) {
        return;
    }

    $frontend_url = rtrim( FOXO_FRONTEND_URL, '/' );

    // Sestavení cílové URL
    $path = $_SERVER['REQUEST_URI'] ?? '/';

    // Homepage
    if ( is_front_page() || $path === '/' ) {
        $redirect_url = $frontend_url . '/';
    } else {
        $redirect_url = $frontend_url . $path;
    }

    // Pro přihlášené uživatele přidej flag pro admin bar na frontendu
    if ( is_user_logged_in() ) {
        $redirect_url = add_query_arg( 'wp-admin-bar', '1', $redirect_url );
    }

    wp_redirect( $redirect_url, 301 );
    exit;
}
add_action( 'template_redirect', 'foxo_headless_redirect' );

/**
 * Preview URL: přesměruje WP preview na Astro preview stránku
 */
function foxo_preview_link( $preview_link, $post ) {
    $frontend_url = rtrim( FOXO_FRONTEND_URL, '/' );

    return add_query_arg( [
        'id'    => $post->ID,
        'token' => FOXO_PREVIEW_SECRET,
    ], $frontend_url . '/preview' );
}
add_filter( 'preview_post_link', 'foxo_preview_link', 10, 2 );
