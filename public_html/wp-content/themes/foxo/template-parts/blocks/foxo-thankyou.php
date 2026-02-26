<?php
/**
 * Block: Thank You
 */

add_action( 'acf/init', function () {
    if ( ! function_exists( 'acf_register_block_type' ) ) {
        return;
    }

    acf_register_block_type( [
        'name'            => 'foxo-thankyou',
        'title'           => __( 'Thank You', 'foxo' ),
        'description'     => __( 'Poděkování po odeslání formuláře s ceníkem a kontaktními údaji', 'foxo' ),
        'render_template' => 'template-parts/blocks/foxo-thankyou.php',
        'category'        => 'formatting',
        'icon'            => 'yes-alt',
        'keywords'        => [ 'thank', 'děkujeme', 'poděkování' ],
        'mode'            => 'preview',
        'supports'        => [ 'align' => false ],
    ] );

    acf_add_local_field_group( [
        'key'      => 'group_block_foxo_thankyou',
        'title'    => 'Thank You',
        'fields'   => [
            [
                'key'         => 'field_foxo_ty_heading',
                'label'       => 'Nadpis',
                'name'        => 'ty_heading',
                'type'        => 'text',
                'placeholder' => 'Děkujeme!',
            ],
            [
                'key'         => 'field_foxo_ty_description',
                'label'       => 'Popis',
                'name'        => 'ty_description',
                'type'        => 'textarea',
                'rows'        => 2,
                'placeholder' => 'Díky za váš zájem! Ozveme se vám nejpozději do 24 hodin.',
            ],
            [
                'key'         => 'field_foxo_ty_text',
                'label'       => 'Doplňkový text',
                'name'        => 'ty_text',
                'type'        => 'textarea',
                'rows'        => 3,
                'placeholder' => 'Volitelný doplňující text pod popisem...',
            ],
            // --- Ceník ---
            [
                'key'          => 'field_foxo_ty_pricing_col_role',
                'label'        => 'Nadpis sloupce "Role"',
                'name'         => 'ty_pricing_col_role',
                'type'         => 'text',
                'placeholder'  => 'Role v projektu',
            ],
            [
                'key'          => 'field_foxo_ty_pricing_col_price',
                'label'        => 'Nadpis sloupce "Sazba"',
                'name'         => 'ty_pricing_col_price',
                'type'         => 'text',
                'placeholder'  => 'Hodinová sazba (bez DPH)',
            ],
            [
                'key'        => 'field_foxo_ty_pricing',
                'label'      => 'Ceník',
                'name'       => 'ty_pricing',
                'type'       => 'repeater',
                'layout'     => 'table',
                'min'        => 0,
                'max'        => 10,
                'sub_fields' => [
                    [
                        'key'         => 'field_foxo_ty_pricing_role',
                        'label'       => 'Role',
                        'name'        => 'role',
                        'type'        => 'text',
                        'placeholder' => 'Kreativa & Design',
                    ],
                    [
                        'key'         => 'field_foxo_ty_pricing_price',
                        'label'       => 'Sazba',
                        'name'        => 'price',
                        'type'        => 'text',
                        'placeholder' => '1 200 Kč',
                    ],
                ],
            ],
            // --- Kontaktní karty ---
            [
                'key'        => 'field_foxo_ty_cards',
                'label'      => 'Kontaktní karty',
                'name'       => 'ty_cards',
                'type'       => 'repeater',
                'layout'     => 'block',
                'min'        => 0,
                'max'        => 6,
                'sub_fields' => [
                    [
                        'key'         => 'field_foxo_ty_card_label',
                        'label'       => 'Popisek',
                        'name'        => 'label',
                        'type'        => 'text',
                        'placeholder' => 'Telefon',
                    ],
                    [
                        'key'         => 'field_foxo_ty_card_value',
                        'label'       => 'Hodnota',
                        'name'        => 'value',
                        'type'        => 'text',
                        'placeholder' => '+420 123 456 789',
                    ],
                ],
            ],
            // --- CTA tlačítko ---
            [
                'key'           => 'field_foxo_ty_cta',
                'label'         => 'CTA tlačítko',
                'name'          => 'ty_cta',
                'type'          => 'link',
                'return_format' => 'array',
                'instructions'  => 'Tlačítko pod kartami (např. "Zpět na kontakt")',
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'block',
                    'operator' => '==',
                    'value'    => 'acf/foxo-thankyou',
                ],
            ],
        ],
    ] );
} );

// --- Gutenberg preview šablona ---
if ( ! isset( $block ) ) {
    return;
}

$heading = get_field( 'ty_heading' ) ?: 'Děkujeme!';
$desc    = get_field( 'ty_description' ) ?: '';
?>
<div style="padding:40px;background:#111;color:#fff;text-align:center;">
    <div style="width:64px;height:64px;background:rgba(247,148,30,.2);border-radius:50%;margin:0 auto 16px;display:flex;align-items:center;justify-content:center;">
        <span style="font-size:32px;">&#10003;</span>
    </div>
    <h2 style="font-size:2rem;margin:0;"><?php echo esc_html( $heading ); ?></h2>
    <?php if ( $desc ) : ?>
        <p style="margin-top:8px;opacity:.7;"><?php echo esc_html( $desc ); ?></p>
    <?php endif; ?>
    <p style="margin-top:8px;font-size:.75rem;opacity:.4;">[Thank You blok – Foxo]</p>
</div>
