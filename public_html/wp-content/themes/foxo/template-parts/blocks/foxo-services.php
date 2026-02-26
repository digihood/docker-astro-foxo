<?php
/**
 * Block: Služby
 */

add_action( 'acf/init', function () {
    if ( ! function_exists( 'acf_register_block_type' ) ) {
        return;
    }

    acf_register_block_type( [
        'name'            => 'foxo-services',
        'title'           => __( 'Služby', 'foxo' ),
        'description'     => __( 'Sekce služeb s ikonami a feature listem', 'foxo' ),
        'render_template' => 'template-parts/blocks/foxo-services.php',
        'category'        => 'formatting',
        'icon'            => 'admin-tools',
        'keywords'        => [ 'services', 'služby' ],
        'mode'            => 'preview',
        'supports'        => [ 'align' => false ],
    ] );

    acf_add_local_field_group( [
        'key'      => 'group_block_foxo_services',
        'title'    => 'Služby',
        'fields'   => [
            [
                'key'         => 'field_foxo_services_heading',
                'label'       => 'Nadpis',
                'name'        => 'services_heading',
                'type'        => 'text',
                'placeholder' => 'Naše',
            ],
            [
                'key'         => 'field_foxo_services_heading_highlight',
                'label'       => 'Nadpis – zvýrazněná část',
                'name'        => 'services_heading_highlight',
                'type'        => 'text',
                'placeholder' => 'služby',
            ],
            [
                'key'         => 'field_foxo_services_description',
                'label'       => 'Popis',
                'name'        => 'services_description',
                'type'        => 'textarea',
                'rows'        => 3,
                'placeholder' => 'Krátký popis sekce služeb...',
            ],
            [
                'key'        => 'field_foxo_services_items',
                'label'      => 'Služby',
                'name'       => 'services_items',
                'type'       => 'repeater',
                'layout'     => 'block',
                'min'        => 0,
                'max'        => 12,
                'sub_fields' => [
                    [
                        'key'           => 'field_foxo_svc_icon',
                        'label'         => 'Ikona',
                        'name'          => 'icon',
                        'type'          => 'image',
                        'return_format' => 'array',
                        'preview_size'  => 'thumbnail',
                    ],
                    [
                        'key'         => 'field_foxo_svc_title',
                        'label'       => 'Název služby',
                        'name'        => 'title',
                        'type'        => 'text',
                        'placeholder' => 'Název služby',
                    ],
                    [
                        'key'         => 'field_foxo_svc_description',
                        'label'       => 'Popis služby',
                        'name'        => 'description',
                        'type'        => 'textarea',
                        'rows'        => 2,
                        'placeholder' => 'Popis služby...',
                    ],
                    [
                        'key'        => 'field_foxo_svc_features',
                        'label'      => 'Features',
                        'name'       => 'features',
                        'type'       => 'repeater',
                        'layout'     => 'table',
                        'min'        => 0,
                        'max'        => 10,
                        'sub_fields' => [
                            [
                                'key'         => 'field_foxo_svc_feat_text',
                                'label'       => 'Text',
                                'name'        => 'feature_text',
                                'type'        => 'text',
                                'placeholder' => 'Feature...',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'block',
                    'operator' => '==',
                    'value'    => 'acf/foxo-services',
                ],
            ],
        ],
    ] );
} );

// --- Gutenberg preview šablona ---
if ( ! isset( $block ) ) {
    return;
}

$heading = get_field( 'services_heading' ) ?: '';
$highlight = get_field( 'services_heading_highlight' ) ?: '';
$items   = get_field( 'services_items' ) ?: [];
?>
<div style="padding:40px;background:#111;color:#fff;text-align:center;">
    <h2 style="font-size:1.5rem;margin:0;">
        <?php echo esc_html( $heading ); ?>
        <span style="color:#f97316;"><?php echo esc_html( $highlight ); ?></span>
    </h2>
    <?php if ( $items ) : ?>
        <p style="margin-top:8px;opacity:.5;"><?php echo count( $items ); ?> služeb</p>
    <?php endif; ?>
    <p style="margin-top:8px;font-size:.75rem;opacity:.4;">[Služby blok – Foxo]</p>
</div>
