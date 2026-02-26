<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Auto-load bloků z template-parts/blocks/ adresáře
 */
foreach ( glob( FOXO_DIR . '/template-parts/blocks/*.php' ) as $block_file ) {
    require_once $block_file;
}
