<?php
/*
Plugin Name: WooCodes
Description: 
Version: Alpha 1.0
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

    function wcs_buyevent( $order_id ) {
        if ( ! $order_id ){
            return;
        }

        $option = get_option('woocodes');
        $categories = get_option('woocodes_categories');
        $order = wc_get_order( $order_id );
        $items = $order->get_items(); 

        foreach ( $items as $item ) {      
            foreach ($categories as $cat_name){
                $product_id = $item->get_product_id();

                if ( has_term( 'WCS_'.$cat_name, 'product_cat', $product_id ) ) {
                    $mail = wc_mail($order->get_billing_email(), 'Your password for the zoom meeting', str_replace(
                        array('wcs_pw', 'wcs_link', 'wcs_id'),
                        array($option['password_'.mb_strtolower($cat_name)], $option['link_'.mb_strtolower($cat_name)], $option['id_'.mb_strtolower($cat_name)]),
                        $option['content_'.mb_strtolower($cat_name)]));
                    if(!$mail){
                        echo "<p>Es ist ein Fehler aufgetreten</p>";
                    }
                }
            }
        }       
    }
    add_action('woocommerce_thankyou', 'wcs_buyevent');

    function wcs_activation(){
        update_option( 'woocodes', array());
        update_option( 'woocodes_categories', array('Default'));
        wp_insert_term(  
            'WCS_Default',
            'product_cat'
        ) ;  
    }
    register_activation_hook( __FILE__, 'wcs_activation' );
    require_once('admin_menu.php');
}

/*
Useful Links:
https://stackoverflow.com/questions/39401393/how-to-get-woocommerce-order-details
*/
?>
