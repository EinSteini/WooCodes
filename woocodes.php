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

    function wcs_buyevent( $order_id ) {

        if ( ! $order_id ){
            return;
        }
       
        $order = wc_get_order( $order_id );
    

        $cat_in_order = false;
        
        $items = $order->get_items(); 
            
        foreach ( $items as $item ) {      
            $product_id = $item->get_product_id();  
            if ( has_term( 'Gift Card Code', 'product_cat', $product_id ) ) {
                $cat_in_order = true;
                break;
            }
        }
          
        if ( $cat_in_order ) {
                $mail = wc_mail($order->get_billing_email(), 'Your password for the video page', 'pw');
                if(!$mail){
                    echo "fehler";
                }
        }
        

    }
    add_action('woocommerce_thankyou', 'wcs_buyevent');

    function wcs_create_category(){
        wp_insert_term( 
            'Gift Card Code',
            'product_cat'
        ) ; 
    }
    register_activation_hook( __FILE__, 'wcs_create_category' );
}

/*
Useful Links:
https://stackoverflow.com/questions/39401393/how-to-get-woocommerce-order-details
*/
?>
