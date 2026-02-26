<?php
/**
 * Block: Reference (klienti)
 */

add_action( 'acf/init', function () {
    if ( ! function_exists( 'acf_register_block_type' ) ) {
        return;
    }

    acf_register_block_type( [
        'name'            => 'foxo-references',
        'title'           => __( 'Reference – Klienti', 'foxo' ),
        'description'     => __( 'Sekce referencí s klienty', 'foxo' ),
        'render_template' => 'template-parts/blocks/foxo-references.php',
        'category'        => 'formatting',
        'icon'            => 'star-filled',
        'keywords'        => [ 'reference', 'klienti', 'portfolio' ],
        'mode'            => 'preview',
        'supports'        => [ 'align' => false ],
    ] );

    acf_add_local_field_group( [
        'key'      => 'group_block_foxo_references',
        'title'    => 'Reference – Klienti',
        'fields'   => [
            [
                'key'         => 'field_foxo_ref_heading',
                'label'       => 'Nadpis',
                'name'        => 'ref_heading',
                'type'        => 'text',
                'placeholder' => 'Naše',
            ],
            [
                'key'         => 'field_foxo_ref_heading_highlight',
                'label'       => 'Nadpis – zvýrazněná část',
                'name'        => 'ref_heading_highlight',
                'type'        => 'text',
                'placeholder' => 'reference',
            ],
            [
                'key'         => 'field_foxo_ref_description',
                'label'       => 'Popis',
                'name'        => 'ref_description',
                'type'        => 'textarea',
                'rows'        => 3,
                'placeholder' => 'Krátký popis sekce referencí...',
            ],
            [
                'key'        => 'field_foxo_ref_clients',
                'label'      => 'Klienti',
                'name'       => 'ref_clients',
                'type'       => 'repeater',
                'layout'     => 'block',
                'min'        => 0,
                'max'        => 20,
                'sub_fields' => [
                    [
                        'key'         => 'field_foxo_ref_client_name',
                        'label'       => 'Název',
                        'name'        => 'name',
                        'type'        => 'text',
                        'placeholder' => 'Název klienta',
                    ],
                    [
                        'key'         => 'field_foxo_ref_client_desc',
                        'label'       => 'Popis',
                        'name'        => 'description',
                        'type'        => 'textarea',
                        'rows'        => 2,
                        'placeholder' => 'Krátký popis...',
                    ],
                    [
                        'key'           => 'field_foxo_ref_client_icon',
                        'label'         => 'Ikona/Logo',
                        'name'          => 'icon',
                        'type'          => 'image',
                        'return_format' => 'array',
                        'preview_size'  => 'thumbnail',
                    ],
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'block',
                    'operator' => '==',
                    'value'    => 'acf/foxo-references',
                ],
            ],
        ],
    ] );
} );

// --- Gutenberg preview šablona ---
if ( ! isset( $block ) ) {
    return;
}

$heading   = get_field( 'ref_heading' ) ?: '';
$highlight = get_field( 'ref_heading_highlight' ) ?: '';
$clients   = get_field( 'ref_clients' ) ?: [];
?>
<div style="padding:40px;background:#f9f9f9;text-align:center;">
    <h2 style="font-size:1.5rem;margin:0;">
        <?php echo esc_html( $heading ); ?>
        <span style="color:#f97316;"><?php echo esc_html( $highlight ); ?></span>
    </h2>
    <p style="margin-top:8px;opacity:.5;"><?php echo count( $clients ); ?> klientů</p>
    <p style="margin-top:8px;font-size:.75rem;opacity:.4;">[Reference – Klienti blok – Foxo]</p>
</div>
