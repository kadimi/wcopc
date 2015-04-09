<?php
/*
Plugin Name: WC One per Category
Plugin URI: http://www.kadimi.com/
Description: Prevent adding more than one product per category
Version: 1.0.0
Author: Nabil Kadimi
Author URI: http://kadimi.com
License: GPL2
*/

// Include Skelet (written by Nabil Kadimi)
include dirname( __FILE__ ) . '/skelet/skelet.php';

// Include options definitions
skelet_dir( dirname( __FILE__ ) . '/data' );

add_action( 'init','remove_loop_button' );
function remove_loop_button() {
	// remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
	// remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
}

add_action( 'woocommerce_add_to_cart_validation', 'wcopc_validate', 10, 9999 );
function wcopc_validate() {

	// Get information about the product being added
	$args = func_get_args();
	$product_id = $args[1];
	$quantity = $args[2];
	$product_categories = wcopc_wc_product_get_categories_IDs( $product_id );
	$cart_contents_IDs = wcopc_get_wc_cart_contents_IDs();

	// Always pass validation if product already in cart
	if ( in_array( $product_id, $cart_contents_IDs ) ) {
		return true;
	}

	// Get categories of products in the cart
	$cart_categories = array();
	foreach ( $cart_contents_IDs as $cart_contents_ID ) {
		$cart_categories = array_merge( $cart_categories, wcopc_wc_product_get_categories_IDs( $cart_contents_ID ) );
	}


	// Validate (valid if the current product categories are not among the categories present in the cart)
	if ( array_intersect( $product_categories, $cart_categories ) ) {
		$product = get_product( $product_id );
		$product_title = $product->post->post_title;

		$notice_type = paf( 'wcopc_notice_type' );
		$notice_message = paf( 'wcopc_notice_message' );
		$notice_message = str_replace( '%product_title%', $product_title, $notice_message );

		wc_add_notice( $notice_message, $notice_type );
	} else {
		return true;
	}
}

/**
 * Get IDs of products in cart
 *
 * @retrun array An array of product IDs
 */
function wcopc_get_wc_cart_contents_IDs() {

	$r = array();
	foreach ( WC()->cart->cart_contents as $k ) {
		$r[] = $k[ 'product_id' ];
	}
	return $r;
}

/**
 * Get IDs of product categories
 *
 * @param int $product_id The product ID
 * @retrun array An array of product categories IDs
 */
function wcopc_wc_product_get_categories_IDs( $product_id ) {

	$r = array();
	foreach ( get_the_terms( $product_id, 'product_cat' ) as $c ) {
		$r[] = $c->term_id;
	}
	return $r;
}