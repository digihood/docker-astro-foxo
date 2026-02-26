<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Formátuje ACF image pole do čistého pole pro API
 *
 * @param array|null $image  ACF image array
 * @return array|null
 */
function foxo_format_image( $image ) {
    if ( ! $image || ! is_array( $image ) ) {
        return null;
    }

    return [
        'url'    => $image['url'] ?? '',
        'alt'    => $image['alt'] ?? '',
        'width'  => $image['width'] ?? 0,
        'height' => $image['height'] ?? 0,
    ];
}

/**
 * Formátuje ACF link pole — převede absolutní WP URL na relativní cestu
 *
 * @param array|null $link  ACF link array {title, url, target}
 * @return array|null
 */
function foxo_format_link( $link ) {
    if ( ! $link || ! is_array( $link ) || empty( $link['url'] ) ) {
        return null;
    }

    $url      = $link['url'];
    $site_url = trailingslashit( get_site_url() );

    if ( str_starts_with( $url, $site_url ) ) {
        $url = '/' . ltrim( str_replace( $site_url, '', $url ), '/' );
    }

    return [
        'title'  => $link['title'] ?? '',
        'url'    => $url,
        'target' => $link['target'] ?? '',
    ];
}

/**
 * Povolí nahrávání SVG do WordPress media knihovny
 */
function foxo_allow_svg_upload( $mimes ) {
    $mimes['svg']  = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
}
add_filter( 'upload_mimes', 'foxo_allow_svg_upload' );

/**
 * Opraví MIME type check pro SVG (WP 5.0+)
 */
function foxo_fix_svg_mime_type( $data, $file, $filename, $mimes ) {
    $ext = pathinfo( $filename, PATHINFO_EXTENSION );
    if ( $ext === 'svg' ) {
        $data['type'] = 'image/svg+xml';
        $data['ext']  = 'svg';
    }
    return $data;
}
add_filter( 'wp_check_filetype_and_ext', 'foxo_fix_svg_mime_type', 10, 4 );

/**
 * Odebere automatické <p> tagy z Contact Form 7
 */
add_filter( 'wpcf7_autop_or_not', '__return_false' );

/**
 * CORS headers pro Astro frontend (potřeba pro CF7 client-side POST)
 */
function foxo_cors_headers() {
    $origin = FOXO_FRONTEND_URL;

    // Pouze pro REST API požadavky
    if ( ! defined( 'REST_REQUEST' ) || ! REST_REQUEST ) {
        return;
    }

    header( 'Access-Control-Allow-Origin: ' . $origin );
    header( 'Access-Control-Allow-Methods: GET, POST, OPTIONS' );
    header( 'Access-Control-Allow-Headers: Content-Type, Authorization' );
    header( 'Access-Control-Allow-Credentials: true' );

    // Handle preflight
    if ( $_SERVER['REQUEST_METHOD'] === 'OPTIONS' ) {
        status_header( 200 );
        exit;
    }
}
add_action( 'rest_api_init', function () {
    remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );
    add_filter( 'rest_pre_serve_request', function ( $value ) {
        foxo_cors_headers();
        return $value;
    } );
} );
