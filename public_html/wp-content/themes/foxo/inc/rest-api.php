<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'rest_api_init', function () {

    // === GET /wp-json/foxo/v1/page/{slug} ===
    // Regex povoluje lomítka pro hierarchické stránky (rodic/potomek)
    register_rest_route( 'foxo/v1', '/page/(?P<slug>[a-zA-Z0-9_/-]+)', [
        'methods'             => 'GET',
        'callback'            => 'foxo_api_get_page',
        'permission_callback' => '__return_true',
        'args'                => [
            'slug' => [
                'required'          => true,
                'sanitize_callback' => function ( $slug ) {
                    return implode( '/', array_map( 'sanitize_title', explode( '/', $slug ) ) );
                },
            ],
        ],
    ] );

    // === GET /wp-json/foxo/v1/menu/{location} ===
    register_rest_route( 'foxo/v1', '/menu/(?P<location>[a-zA-Z0-9_-]+)', [
        'methods'             => 'GET',
        'callback'            => 'foxo_api_get_menu',
        'permission_callback' => '__return_true',
        'args'                => [
            'location' => [
                'required'          => true,
                'sanitize_callback' => 'sanitize_key',
            ],
        ],
    ] );

    // === GET /wp-json/foxo/v1/options ===
    register_rest_route( 'foxo/v1', '/options', [
        'methods'             => 'GET',
        'callback'            => 'foxo_api_get_options',
        'permission_callback' => '__return_true',
    ] );

    // === GET /wp-json/foxo/v1/form/{id} ===
    register_rest_route( 'foxo/v1', '/form/(?P<id>\d+)', [
        'methods'             => 'GET',
        'callback'            => 'foxo_api_get_form',
        'permission_callback' => '__return_true',
        'args'                => [
            'id' => [
                'required'          => true,
                'sanitize_callback' => 'absint',
            ],
        ],
    ] );

    // SEO: používáme vestavěný Rank Math endpoint
    // GET /wp-json/rankmath/v1/getHead?url=...
    // Nutno zapnout: Rank Math → General Settings → Others → Headless CMS Support
} );

/**
 * Vrátí stránku s parsovanými ACF bloky
 */
function foxo_api_get_page( WP_REST_Request $request ) {
    $slug    = $request->get_param( 'slug' );
    $preview = $request->get_param( 'preview' ) === 'true';

    // Homepage: slug "home" → page_on_front
    if ( $slug === 'home' ) {
        $page_id = (int) get_option( 'page_on_front' );
        if ( $page_id ) {
            $page = get_post( $page_id );
        }
    }

    if ( ! isset( $page ) || ! $page ) {
        // get_page_by_path() podporuje hierarchické cesty (rodic/potomek)
        $page = get_page_by_path( $slug, OBJECT, 'page' );

        if ( $page ) {
            $allowed = $preview ? [ 'publish', 'draft', 'pending' ] : [ 'publish' ];
            if ( ! in_array( $page->post_status, $allowed, true ) ) {
                $page = null;
            }
        }
    }

    if ( ! $page ) {
        return new WP_Error( 'not_found', 'Stránka nenalezena', [ 'status' => 404 ] );
    }

    // Preview: ověření tokenu
    if ( $preview ) {
        $token = $request->get_param( 'token' );
        if ( $token !== FOXO_PREVIEW_SECRET ) {
            return new WP_Error( 'unauthorized', 'Neplatný preview token', [ 'status' => 401 ] );
        }

        // Použij nejnovější revizi, pokud existuje
        $revisions = wp_get_post_revisions( $page->ID, [ 'numberposts' => 1 ] );
        if ( ! empty( $revisions ) ) {
            $page = array_shift( $revisions );
        }
    }

    // Parsování bloků
    $raw_blocks = parse_blocks( $page->post_content );
    $blocks     = [];

    foreach ( $raw_blocks as $raw_block ) {
        if ( empty( $raw_block['blockName'] ) ) {
            continue;
        }

        $block_data = [
            'blockName' => $raw_block['blockName'],
            'attrs'     => $raw_block['attrs'] ?? [],
        ];

        // ACF bloky: extrahuj ACF data
        if ( str_starts_with( $raw_block['blockName'], 'acf/' ) ) {
            $acf_data = $raw_block['attrs']['data'] ?? [];

            if ( ! empty( $acf_data ) ) {
                acf_setup_meta( $acf_data, $raw_block['attrs']['id'] ?? uniqid(), true );
                $fields = get_fields();
                acf_reset_meta( $raw_block['attrs']['id'] ?? '' );

                // Formátovat image fieldy
                $block_data['acf'] = foxo_format_block_fields( $fields ?: [] );
            } else {
                $block_data['acf'] = [];
            }
        }

        $blocks[] = $block_data;
    }

    // Rendered HTML obsah (pro stránky bez ACF bloků — právní stránky apod.)
    $has_acf_blocks = false;
    foreach ( $blocks as $b ) {
        if ( str_starts_with( $b['blockName'], 'acf/' ) ) {
            $has_acf_blocks = true;
            break;
        }
    }

    $content = '';
    if ( ! $has_acf_blocks ) {
        $content = apply_filters( 'the_content', $page->post_content );
    }

    // Sestavit plnou hierarchickou cestu (rodic/potomek)
    $path_parts = [ $page->post_name ];
    $parent_id  = $page->post_parent;
    while ( $parent_id ) {
        $parent = get_post( $parent_id );
        if ( ! $parent ) break;
        array_unshift( $path_parts, $parent->post_name );
        $parent_id = $parent->post_parent;
    }

    return rest_ensure_response( [
        'id'      => $page->ID,
        'slug'    => $page->post_name,
        'path'    => implode( '/', $path_parts ),
        'title'   => $page->post_title,
        'status'  => $page->post_status,
        'parent'  => (int) $page->post_parent,
        'blocks'  => $blocks,
        'content' => $content,
    ] );
}

/**
 * Rekurzivně formátuje ACF block fieldy (images, links, repeatery)
 */
function foxo_format_block_fields( $fields ) {
    if ( ! is_array( $fields ) ) {
        return $fields;
    }

    $formatted = [];

    foreach ( $fields as $key => $value ) {
        if ( is_array( $value ) ) {
            // ACF image pole (má klíče url, width, height, sizes)
            if ( isset( $value['url'] ) && isset( $value['width'] ) && isset( $value['height'] ) && isset( $value['sizes'] ) ) {
                $formatted[ $key ] = foxo_format_image( $value );
            }
            // ACF link pole (má klíče url, title, target)
            elseif ( isset( $value['url'] ) && isset( $value['title'] ) && array_key_exists( 'target', $value ) && ! isset( $value['width'] ) ) {
                $formatted[ $key ] = [
                    'title'  => $value['title'] ?? '',
                    'url'    => $value['url'] ?? '',
                    'target' => $value['target'] ?? '',
                ];
            }
            // Repeater (indexed array)
            elseif ( array_is_list( $value ) ) {
                $formatted[ $key ] = array_map( 'foxo_format_block_fields', $value );
            }
            // Jiné associativní pole
            else {
                $formatted[ $key ] = foxo_format_block_fields( $value );
            }
        } else {
            $formatted[ $key ] = $value;
        }
    }

    return $formatted;
}

/**
 * Vrátí menu podle lokace
 */
function foxo_api_get_menu( WP_REST_Request $request ) {
    $location  = $request->get_param( 'location' );
    $locations = get_nav_menu_locations();

    if ( ! isset( $locations[ $location ] ) ) {
        return rest_ensure_response( [] );
    }

    $menu_items = wp_get_nav_menu_items( $locations[ $location ] );

    if ( ! $menu_items ) {
        return rest_ensure_response( [] );
    }

    $items = [];
    foreach ( $menu_items as $item ) {
        // Převeď absolutní WP URL na relativní cestu
        $url = $item->url;
        $site_url = trailingslashit( get_site_url() );

        if ( str_starts_with( $url, $site_url ) ) {
            $url = '/' . ltrim( str_replace( $site_url, '', $url ), '/' );
        }

        // Anchor linky zachovej
        if ( str_starts_with( $url, '#' ) ) {
            $url = '/' . ltrim( $url, '/' );
        }

        $items[] = [
            'id'      => $item->ID,
            'title'   => $item->title,
            'url'     => $url,
            'target'  => $item->target ?: '',
            'parent'  => (int) $item->menu_item_parent,
            'order'   => (int) $item->menu_order,
            'classes' => array_filter( $item->classes ?: [] ),
        ];
    }

    return rest_ensure_response( $items );
}

/**
 * Vrátí globální nastavení webu z ACF Options
 */
function foxo_api_get_options() {
    if ( ! function_exists( 'get_field' ) ) {
        return new WP_Error( 'acf_missing', 'ACF není aktivní', [ 'status' => 500 ] );
    }

    $logo = get_field( 'site_logo', 'option' );

    // Favicon z WP Site Icon (Vzhled → Přizpůsobit → Identita webu)
    $favicon_url = get_site_icon_url( 512 );

    return rest_ensure_response( [
        'logo'               => foxo_format_image( $logo ),
        'favicon'            => $favicon_url ?: '',
        'footer_description' => get_field( 'footer_description', 'option' ) ?: '',
        'footer_contact'     => get_field( 'footer_contact', 'option' ) ?: '',
        'social'             => [
            'instagram' => get_field( 'social_instagram', 'option' ) ?: '',
            'linkedin'  => get_field( 'social_linkedin', 'option' ) ?: '',
            'youtube'   => get_field( 'social_youtube', 'option' ) ?: '',
            'facebook'  => get_field( 'social_facebook', 'option' ) ?: '',
        ],
        'copyright_text'     => get_field( 'copyright_text', 'option' ) ?: '',
        'privacy_link'       => get_field( 'privacy_link', 'option' ) ?: null,
        'terms_link'         => get_field( 'terms_link', 'option' ) ?: null,
    ] );
}

/**
 * Vrátí CF7 formulář — rozparsované pole + nastavení
 */
function foxo_api_get_form( WP_REST_Request $request ) {
    $form_id = (int) $request->get_param( 'id' );

    if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
        return new WP_Error( 'cf7_missing', 'Contact Form 7 není aktivní', [ 'status' => 500 ] );
    }

    $form = WPCF7_ContactForm::get_instance( $form_id );
    if ( ! $form ) {
        return new WP_Error( 'form_not_found', 'Formulář nenalezen', [ 'status' => 404 ] );
    }

    $properties = $form->get_properties();
    $form_body  = isset( $properties['form'] ) ? $properties['form'] : '';
    $tags       = $form->scan_form_tags();
    $fields     = [];

    foreach ( $tags as $tag ) {
        if ( empty( $tag->name ) ) {
            // Submit button
            if ( $tag->type === 'submit' ) {
                $fields[] = [
                    'type'  => 'submit',
                    'label' => ! empty( $tag->values ) ? $tag->values[0] : 'Odeslat',
                ];
            }
            continue;
        }

        $field = [
            'tag_type'    => $tag->type,
            'name'        => $tag->name,
            'required'    => $tag->is_required(),
            'placeholder' => $tag->get_option( 'placeholder', '', true ) ?: ( ! empty( $tag->values ) ? $tag->values[0] : '' ),
            'options'     => $tag->options,
        ];

        // Extract custom CSS classes from tag options (e.g. class:half)
        $custom_classes = [];
        foreach ( $tag->options as $opt ) {
            if ( strpos( $opt, 'class:' ) === 0 ) {
                $custom_classes[] = substr( $opt, 6 );
            }
        }
        if ( ! empty( $custom_classes ) ) {
            $field['css_class'] = implode( ' ', $custom_classes );
        }

        // Map CF7 base type
        $base_type = $tag->basetype;
        switch ( $base_type ) {
            case 'text':
                $field['input_type'] = 'text';
                break;
            case 'email':
                $field['input_type'] = 'email';
                break;
            case 'tel':
                $field['input_type'] = 'tel';
                break;
            case 'url':
                $field['input_type'] = 'url';
                break;
            case 'number':
                $field['input_type'] = 'number';
                break;
            case 'textarea':
                $field['input_type'] = 'textarea';
                break;
            case 'select':
                $field['input_type'] = 'select';
                $field['values']     = $tag->values ?: [];
                break;
            case 'checkbox':
            case 'radio':
                $field['input_type'] = $base_type;
                $field['values']     = $tag->values ?: [];
                break;
            case 'acceptance':
                $field['input_type'] = 'acceptance';
                $field['content']    = $tag->content ?: '';
                $optional            = in_array( 'optional', $tag->options, true );
                $field['required']   = ! $optional;
                break;
            case 'file':
                $field['input_type'] = 'file';
                break;
            default:
                $field['input_type'] = 'text';
                break;
        }

        $fields[] = $field;
    }

    // Extract labels from the CF7 form body template
    foreach ( $fields as &$field ) {
        if ( empty( $field['name'] ) ) {
            continue;
        }

        $name_escaped = preg_quote( $field['name'], '/' );

        // Pattern 1: <label...>LABEL_TEXT [tag field-name ...]</label>
        // [^<\[]+ ensures we don't cross HTML tags or CF7 shortcodes
        if ( preg_match( '/<label[^>]*>\s*([^<\[]+)\s*\[\w+\*?\s+' . $name_escaped . '\b/', $form_body, $m ) ) {
            $label_text = trim( $m[1] );
            if ( $label_text ) {
                $field['label'] = $label_text;
            }
        }
        // Pattern 2: <label...>LABEL_TEXT</label> ... [tag field-name ...]
        elseif ( preg_match( '/<label[^>]*>\s*([^<]+?)\s*<\/label>\s*\[\w+\*?\s+' . $name_escaped . '\b/', $form_body, $m ) ) {
            $label_text = trim( $m[1] );
            if ( $label_text ) {
                $field['label'] = $label_text;
            }
        }
    }
    unset( $field );

    // Additional settings (redirect, etc.)
    $additional = isset( $properties['additional_settings'] ) ? $properties['additional_settings'] : '';

    return rest_ensure_response( [
        'id'                  => $form_id,
        'title'               => $form->title(),
        'fields'              => $fields,
        'additional_settings' => $additional,
    ] );
}

