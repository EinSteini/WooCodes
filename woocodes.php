<?php
/*
Plugin Name: WooCodes
Description: 
Version: alpha 0.1
Author: Nils Steinkamp
WC requires at least: 2.2
WC tested up to: 2.3
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    function wcs_create_category(){
        wp_insert_term( 
            'Gift Card Code',
            'product_cat'
        ) ; 
    }

    register_activation_hook( __FILE__, 'wcs_create_category' );
}

?>
