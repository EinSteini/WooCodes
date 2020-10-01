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

        $option = get_option('woocodes');

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
                $mail = wc_mail($order->get_billing_email(), 'Your password for the video page', 'As ordered, here is your password for the video page: '.$option['password']);
                if(!$mail){
                    echo "<p>Es ist ein Fehler aufgetreten</p>";
                }
        }
        

    }
    add_action('woocommerce_thankyou', 'wcs_buyevent');

    function wcs_activation(){
        wp_insert_term(        //Diese ganze Methode kopieren und 'Gift Card Code' mit dem neuen Namen der Kategorie ersetzen
            'Gift Card Code',
            'product_cat'
        ) ;                     //Bis hier kopieren

        add_option('woocodes', array(
            'password' => 'pass'
        ));
    }
    register_activation_hook( __FILE__, 'wcs_activation' );
    require_once('admin_menu.php');
}

/*
Useful Links:
https://stackoverflow.com/questions/39401393/how-to-get-woocommerce-order-details
*/
?>
