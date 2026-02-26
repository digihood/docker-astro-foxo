<?php
/**
 * Block: Kontakt
 */

add_action( 'acf/init', function () {
    if ( ! function_exists( 'acf_register_block_type' ) ) {
        return;
    }

    acf_register_block_type( [
        'name'            => 'foxo-contact',
        'title'           => __( 'Kontakt', 'foxo' ),
        'description'     => __( 'Kontaktní sekce s info kartami a CF7 formulářem', 'foxo' ),
        'render_template' => 'template-parts/blocks/foxo-contact.php',
        'category'        => 'formatting',
        'icon'            => 'email',
        'keywords'        => [ 'contact', 'kontakt', 'formulář' ],
        'mode'            => 'preview',
        'supports'        => [ 'align' => false ],
    ] );

    acf_add_local_field_group( [
        'key'      => 'group_block_foxo_contact',
        'title'    => 'Kontakt',
        'fields'   => [
            [
                'key'         => 'field_foxo_contact_heading',
                'label'       => 'Nadpis',
                'name'        => 'contact_heading',
                'type'        => 'text',
                'placeholder' => 'Kontaktujte nás',
            ],
            [
                'key'         => 'field_foxo_contact_description',
                'label'       => 'Popis',
                'name'        => 'contact_description',
                'type'        => 'textarea',
                'rows'        => 3,
                'placeholder' => 'Krátký popis kontaktní sekce...',
            ],
            // --- Info karty ---
            [
                'key'        => 'field_foxo_contact_info_cards',
                'label'      => 'Kontaktní karty',
                'name'       => 'contact_info_cards',
                'type'       => 'repeater',
                'layout'     => 'block',
                'min'        => 0,
                'max'        => 6,
                'sub_fields' => [
                    [
                        'key'           => 'field_foxo_contact_card_icon',
                        'label'         => 'Ikona',
                        'name'          => 'icon',
                        'type'          => 'image',
                        'return_format' => 'array',
                        'preview_size'  => 'thumbnail',
                    ],
                    [
                        'key'         => 'field_foxo_contact_card_label',
                        'label'       => 'Popisek',
                        'name'        => 'label',
                        'type'        => 'text',
                        'placeholder' => 'Email',
                    ],
                    [
                        'key'         => 'field_foxo_contact_card_value',
                        'label'       => 'Hodnota',
                        'name'        => 'value',
                        'type'        => 'text',
                        'placeholder' => 'hello@foxo.agency',
                    ],
                ],
            ],
            // --- CF7 formulář ---
            [
                'key'           => 'field_foxo_contact_form',
                'label'         => 'Kontaktní formulář (CF7)',
                'name'          => 'contact_form',
                'type'          => 'post_object',
                'post_type'     => [ 'wpcf7_contact_form' ],
                'return_format' => 'id',
                'allow_null'    => true,
                'instructions'  => 'Vyberte Contact Form 7 formulář',
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'block',
                    'operator' => '==',
                    'value'    => 'acf/foxo-contact',
                ],
            ],
        ],
    ] );
} );

// --- Gutenberg preview šablona ---
if ( ! isset( $block ) ) {
    return;
}

$heading     = get_field( 'contact_heading' ) ?: '';
$description = get_field( 'contact_description' ) ?: '';
$form_id     = get_field( 'contact_form' );
?>
<div style="padding:40px;background:#111;color:#fff;text-align:center;">
    <h2 style="font-size:1.5rem;margin:0;"><?php echo esc_html( $heading ); ?></h2>
    <?php if ( $description ) : ?>
        <p style="margin-top:8px;opacity:.7;"><?php echo esc_html( $description ); ?></p>
    <?php endif; ?>
    <?php if ( $form_id ) : ?>
        <p style="margin-top:8px;opacity:.5;">CF7 formulář ID: <?php echo (int) $form_id; ?></p>
    <?php endif; ?>
    <p style="margin-top:8px;font-size:.75rem;opacity:.4;">[Kontakt blok – Foxo]</p>
</div>
