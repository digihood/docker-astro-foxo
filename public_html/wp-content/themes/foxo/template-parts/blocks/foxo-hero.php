<?php
/**
 * Block: Hero sekce
 */

add_action( 'acf/init', function () {
    if ( ! function_exists( 'acf_register_block_type' ) ) {
        return;
    }

    acf_register_block_type( [
        'name'            => 'foxo-hero',
        'title'           => __( 'Hero sekce', 'foxo' ),
        'description'     => __( 'Úvodní hero s nadpisem, popisem, CTA a YouTube videem', 'foxo' ),
        'render_template' => 'template-parts/blocks/foxo-hero.php',
        'category'        => 'formatting',
        'icon'            => 'cover-image',
        'keywords'        => [ 'hero', 'banner', 'úvod' ],
        'mode'            => 'preview',
        'supports'        => [ 'align' => false ],
    ] );

    acf_add_local_field_group( [
        'key'      => 'group_block_foxo_hero',
        'title'    => 'Hero sekce',
        'fields'   => [
            [
                'key'         => 'field_foxo_hero_heading_line1',
                'label'       => 'Nadpis – řádek 1',
                'name'        => 'hero_heading_line1',
                'type'        => 'text',
                'placeholder' => 'Tvoříme digitální',
            ],
            [
                'key'         => 'field_foxo_hero_heading_line2',
                'label'       => 'Nadpis – řádek 2 (zvýrazněný)',
                'name'        => 'hero_heading_line2',
                'type'        => 'text',
                'placeholder' => 'zážitky',
            ],
            [
                'key'         => 'field_foxo_hero_description',
                'label'       => 'Popis',
                'name'        => 'hero_description',
                'type'        => 'textarea',
                'rows'        => 3,
                'placeholder' => 'Krátký popis pod nadpisem...',
            ],
            [
                'key'   => 'field_foxo_hero_cta_primary',
                'label' => 'Primární CTA',
                'name'  => 'hero_cta_primary',
                'type'  => 'link',
            ],
            [
                'key'   => 'field_foxo_hero_cta_secondary',
                'label' => 'Sekundární CTA',
                'name'  => 'hero_cta_secondary',
                'type'  => 'link',
            ],
            [
                'key'         => 'field_foxo_hero_youtube_url',
                'label'       => 'YouTube URL',
                'name'        => 'hero_youtube_url',
                'type'        => 'url',
                'placeholder' => 'https://www.youtube.com/watch?v=...',
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'block',
                    'operator' => '==',
                    'value'    => 'acf/foxo-hero',
                ],
            ],
        ],
    ] );
} );

// --- Gutenberg preview šablona ---
if ( ! isset( $block ) ) {
    return;
}

$line1       = get_field( 'hero_heading_line1' ) ?: '';
$line2       = get_field( 'hero_heading_line2' ) ?: '';
$description = get_field( 'hero_description' ) ?: '';
?>
<div style="padding:40px;background:#111;color:#fff;text-align:center;">
    <h2 style="font-size:2rem;margin:0;">
        <?php echo esc_html( $line1 ); ?>
        <span style="color:#f97316;"><?php echo esc_html( $line2 ); ?></span>
    </h2>
    <?php if ( $description ) : ?>
        <p style="margin-top:12px;opacity:.7;"><?php echo esc_html( $description ); ?></p>
    <?php endif; ?>
    <p style="margin-top:8px;font-size:.75rem;opacity:.4;">[Hero blok – Foxo]</p>
</div>
