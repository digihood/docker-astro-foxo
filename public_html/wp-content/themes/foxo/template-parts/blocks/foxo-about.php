<?php
/**
 * Block: O nás sekce
 */

add_action( 'acf/init', function () {
    if ( ! function_exists( 'acf_register_block_type' ) ) {
        return;
    }

    acf_register_block_type( [
        'name'            => 'foxo-about',
        'title'           => __( 'O nás', 'foxo' ),
        'description'     => __( 'Sekce o firmě s feature kartami', 'foxo' ),
        'render_template' => 'template-parts/blocks/foxo-about.php',
        'category'        => 'formatting',
        'icon'            => 'info-outline',
        'keywords'        => [ 'about', 'o nás', 'features' ],
        'mode'            => 'preview',
        'supports'        => [ 'align' => false ],
    ] );

    acf_add_local_field_group( [
        'key'      => 'group_block_foxo_about',
        'title'    => 'O nás',
        'fields'   => [
            [
                'key'         => 'field_foxo_about_heading',
                'label'       => 'Nadpis',
                'name'        => 'about_heading',
                'type'        => 'text',
                'placeholder' => 'Proč si vybrat',
            ],
            [
                'key'         => 'field_foxo_about_heading_highlight',
                'label'       => 'Nadpis – zvýrazněná část',
                'name'        => 'about_heading_highlight',
                'type'        => 'text',
                'placeholder' => 'nás?',
            ],
            [
                'key'         => 'field_foxo_about_description',
                'label'       => 'Popis',
                'name'        => 'about_description',
                'type'        => 'textarea',
                'rows'        => 3,
                'placeholder' => 'Krátký popis sekce...',
            ],
            [
                'key'        => 'field_foxo_about_features',
                'label'      => 'Features',
                'name'       => 'about_features',
                'type'       => 'repeater',
                'layout'     => 'block',
                'min'        => 0,
                'max'        => 8,
                'sub_fields' => [
                    [
                        'key'           => 'field_foxo_about_feat_icon',
                        'label'         => 'Ikona',
                        'name'          => 'icon',
                        'type'          => 'image',
                        'return_format' => 'array',
                        'preview_size'  => 'thumbnail',
                    ],
                    [
                        'key'         => 'field_foxo_about_feat_title',
                        'label'       => 'Název',
                        'name'        => 'title',
                        'type'        => 'text',
                        'placeholder' => 'Název feature',
                    ],
                    [
                        'key'         => 'field_foxo_about_feat_desc',
                        'label'       => 'Popis',
                        'name'        => 'description',
                        'type'        => 'textarea',
                        'rows'        => 2,
                        'placeholder' => 'Popis feature...',
                    ],
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'block',
                    'operator' => '==',
                    'value'    => 'acf/foxo-about',
                ],
            ],
        ],
    ] );
} );

// --- Gutenberg preview šablona ---
if ( ! isset( $block ) ) {
    return;
}

$heading   = get_field( 'about_heading' ) ?: '';
$highlight = get_field( 'about_heading_highlight' ) ?: '';
$features  = get_field( 'about_features' ) ?: [];
?>
<div style="padding:40px;background:#f9f9f9;text-align:center;">
    <h2 style="font-size:1.5rem;margin:0;">
        <?php echo esc_html( $heading ); ?>
        <span style="color:#f97316;"><?php echo esc_html( $highlight ); ?></span>
    </h2>
    <?php if ( $features ) : ?>
        <p style="margin-top:8px;opacity:.5;"><?php echo count( $features ); ?> feature karet</p>
    <?php endif; ?>
    <p style="margin-top:8px;font-size:.75rem;opacity:.4;">[O nás blok – Foxo]</p>
</div>
