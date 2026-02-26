<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Revaliduje ISR cache na Vercelu pomocí bypassToken.
 *
 * GET request na URL stránky s headerem x-prerender-revalidate
 * triggeruje na pozadí přegenerování ISR cache.
 *
 * @param string|array $paths  Cesta nebo pole cest ('/' = homepage, '*' = homepage jako fallback)
 */
function foxo_send_revalidation( $paths ) {
    $frontend_url = rtrim( FOXO_FRONTEND_URL, '/' );
    $token        = FOXO_REVALIDATE_SECRET;

    if ( ! is_array( $paths ) ) {
        $paths = [ $paths ];
    }

    foreach ( $paths as $path ) {
        // Global purge → revalidate homepage (other pages refresh on next visit after expiration)
        if ( $path === '*' ) {
            $path = '/';
        }

        wp_remote_get( $frontend_url . $path, [
            'timeout'  => 5,
            'headers'  => [
                'x-prerender-revalidate' => $token,
            ],
            'blocking' => false,
        ] );
    }
}

/**
 * Sestaví plnou hierarchickou cestu stránky (rodic/potomek)
 */
function foxo_get_page_path( $post ) {
    $parts     = [ $post->post_name ];
    $parent_id = $post->post_parent;

    while ( $parent_id ) {
        $parent = get_post( $parent_id );
        if ( ! $parent ) break;
        array_unshift( $parts, $parent->post_name );
        $parent_id = $parent->post_parent;
    }

    return '/' . implode( '/', $parts );
}

// === 1) Uložení stránky ===
function foxo_revalidate_on_save( $post_id, $post ) {
    if ( $post->post_type !== 'page' ) {
        return;
    }
    if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) ) {
        return;
    }
    if ( $post->post_status !== 'publish' ) {
        return;
    }

    $front_page_id = (int) get_option( 'page_on_front' );
    $path = ( $post_id === $front_page_id ) ? '/' : foxo_get_page_path( $post );

    foxo_send_revalidation( $path );
}
add_action( 'save_post', 'foxo_revalidate_on_save', 10, 2 );

// === 2) Změna ACF Options (nastavení šablony) ===
function foxo_revalidate_on_options( $post_id ) {
    if ( $post_id !== 'options' ) {
        return;
    }
    foxo_send_revalidation( '*' );
}
add_action( 'acf/save_post', 'foxo_revalidate_on_options', 20 );

// === 3) Změna menu ===
function foxo_revalidate_on_menu( $menu_id ) {
    foxo_send_revalidation( '*' );
}
add_action( 'wp_update_nav_menu', 'foxo_revalidate_on_menu' );

// === 4) Customizer (site icon, site title apod.) ===
function foxo_revalidate_on_customizer() {
    foxo_send_revalidation( '*' );
}
add_action( 'customize_save_after', 'foxo_revalidate_on_customizer' );

// === 5) Změna nastavení "Čtení" (page_on_front, posts_page) ===
function foxo_revalidate_on_reading_settings( $option, $old_value, $value ) {
    if ( in_array( $option, [ 'page_on_front', 'page_for_posts', 'show_on_front' ], true ) ) {
        foxo_send_revalidation( '*' );
    }
}
add_action( 'updated_option', 'foxo_revalidate_on_reading_settings', 10, 3 );
