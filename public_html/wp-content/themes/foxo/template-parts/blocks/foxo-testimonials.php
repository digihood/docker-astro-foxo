<?php
/**
 * Block: Testimonials
 */

add_action( 'acf/init', function () {
    if ( ! function_exists( 'acf_register_block_type' ) ) {
        return;
    }

    acf_register_block_type( [
        'name'            => 'foxo-testimonials',
        'title'           => __( 'Testimonials', 'foxo' ),
        'description'     => __( 'Sekce s recenzemi a hodnoceními klientů', 'foxo' ),
        'render_template' => 'template-parts/blocks/foxo-testimonials.php',
        'category'        => 'formatting',
        'icon'            => 'format-quote',
        'keywords'        => [ 'testimonials', 'recenze', 'hodnocení' ],
        'mode'            => 'preview',
        'supports'        => [ 'align' => false ],
    ] );

    acf_add_local_field_group( [
        'key'      => 'group_block_foxo_testimonials',
        'title'    => 'Testimonials',
        'fields'   => [
            [
                'key'         => 'field_foxo_test_heading',
                'label'       => 'Nadpis',
                'name'        => 'test_heading',
                'type'        => 'text',
                'placeholder' => 'Co říkají naši klienti',
            ],
            [
                'key'         => 'field_foxo_test_description',
                'label'       => 'Popis',
                'name'        => 'test_description',
                'type'        => 'textarea',
                'rows'        => 3,
                'placeholder' => 'Krátký popis...',
            ],
            [
                'key'        => 'field_foxo_test_items',
                'label'      => 'Testimonials',
                'name'       => 'test_items',
                'type'       => 'repeater',
                'layout'     => 'block',
                'min'        => 0,
                'max'        => 20,
                'sub_fields' => [
                    [
                        'key'         => 'field_foxo_test_item_quote',
                        'label'       => 'Citát',
                        'name'        => 'quote',
                        'type'        => 'textarea',
                        'rows'        => 3,
                        'placeholder' => 'Co klient říká...',
                    ],
                    [
                        'key'         => 'field_foxo_test_item_author',
                        'label'       => 'Autor',
                        'name'        => 'author',
                        'type'        => 'text',
                        'placeholder' => 'Jméno autora',
                    ],
                    [
                        'key'         => 'field_foxo_test_item_position',
                        'label'       => 'Pozice',
                        'name'        => 'position',
                        'type'        => 'text',
                        'placeholder' => 'CEO, Firma s.r.o.',
                    ],
                    [
                        'key'           => 'field_foxo_test_item_rating',
                        'label'         => 'Hodnocení',
                        'name'          => 'rating',
                        'type'          => 'number',
                        'min'           => 1,
                        'max'           => 5,
                        'default_value' => 5,
                        'instructions'  => 'Počet hvězdiček (1–5)',
                    ],
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'block',
                    'operator' => '==',
                    'value'    => 'acf/foxo-testimonials',
                ],
            ],
        ],
    ] );
} );

// --- Gutenberg preview šablona ---
if ( ! isset( $block ) ) {
    return;
}

$heading = get_field( 'test_heading' ) ?: '';
$items   = get_field( 'test_items' ) ?: [];
?>
<div style="padding:40px;background:#111;color:#fff;text-align:center;">
    <h2 style="font-size:1.5rem;margin:0;"><?php echo esc_html( $heading ); ?></h2>
    <p style="margin-top:8px;opacity:.5;"><?php echo count( $items ); ?> testimonials</p>
    <p style="margin-top:8px;font-size:.75rem;opacity:.4;">[Testimonials blok – Foxo]</p>
</div>
