<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'acf/init', function () {
    if ( ! function_exists( 'acf_add_options_page' ) ) {
        return;
    }

    acf_add_options_page( [
        'page_title' => 'Foxo nastavení',
        'menu_title' => 'Foxo nastavení',
        'menu_slug'  => 'foxo-options',
        'capability' => 'edit_posts',
        'redirect'   => false,
        'icon_url'   => 'dashicons-admin-generic',
        'position'   => 2,
    ] );

    acf_add_local_field_group( [
        'key'      => 'group_foxo_options',
        'title'    => 'Nastavení webu',
        'fields'   => [
            // === Logo ===
            [
                'key'           => 'field_opt_logo',
                'label'         => 'Logo',
                'name'          => 'site_logo',
                'type'          => 'image',
                'return_format' => 'array',
                'preview_size'  => 'thumbnail',
                'instructions'  => 'Logo webu zobrazené v hlavičce a zápatí.',
            ],
            // === Footer ===
            [
                'key'         => 'field_opt_footer_desc',
                'label'       => 'Popis v zápatí',
                'name'        => 'footer_description',
                'type'        => 'textarea',
                'rows'        => 3,
                'placeholder' => 'Krátký popis firmy zobrazený v zápatí...',
            ],
            // === Kontakt v zápatí ===
            [
                'key'           => 'field_opt_footer_contact',
                'label'         => 'Kontakt v zápatí',
                'name'          => 'footer_contact',
                'type'          => 'wysiwyg',
                'tabs'          => 'all',
                'toolbar'       => 'basic',
                'media_upload'  => 0,
                'instructions'  => 'Obsah kontaktního sloupce v zápatí (telefon, email, adresa apod.)',
            ],
            // === Sociální sítě ===
            [
                'key'         => 'field_opt_instagram',
                'label'       => 'Instagram URL',
                'name'        => 'social_instagram',
                'type'        => 'url',
                'placeholder' => 'https://instagram.com/foxo',
            ],
            [
                'key'         => 'field_opt_linkedin',
                'label'       => 'LinkedIn URL',
                'name'        => 'social_linkedin',
                'type'        => 'url',
                'placeholder' => 'https://linkedin.com/company/foxo',
            ],
            [
                'key'         => 'field_opt_youtube',
                'label'       => 'YouTube URL',
                'name'        => 'social_youtube',
                'type'        => 'url',
                'placeholder' => 'https://youtube.com/@foxo',
            ],
            [
                'key'         => 'field_opt_facebook',
                'label'       => 'Facebook URL',
                'name'        => 'social_facebook',
                'type'        => 'url',
                'placeholder' => 'https://facebook.com/foxo',
            ],
            // === Odkazy v zápatí ===
            [
                'key'           => 'field_opt_privacy_link',
                'label'         => 'Ochrana soukromí',
                'name'          => 'privacy_link',
                'type'          => 'link',
                'return_format' => 'array',
                'instructions'  => 'Odkaz na stránku ochrany soukromí.',
            ],
            [
                'key'           => 'field_opt_terms_link',
                'label'         => 'Obchodní podmínky',
                'name'          => 'terms_link',
                'type'          => 'link',
                'return_format' => 'array',
                'instructions'  => 'Odkaz na stránku obchodních podmínek.',
            ],
            // === Copyright ===
            [
                'key'         => 'field_opt_copyright',
                'label'       => 'Copyright text',
                'name'        => 'copyright_text',
                'type'        => 'text',
                'placeholder' => 'Foxo. Všechna práva vyhrazena.',
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'options_page',
                    'operator' => '==',
                    'value'    => 'foxo-options',
                ],
            ],
        ],
    ] );
} );
