<?php
/**
 * Loop Add to Cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $media_center_theme_options, $woocommerce_loop;

$product_item_size = isset( $woocommerce_loop['product_item_size'] ) ? $woocommerce_loop['product_item_size'] : $media_center_theme_options['product_item_size'];

$btn_classes = $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button ' : '';
$btn_classes .= ( $product_item_size == 'size-big' ) ? 'big' : '';

echo apply_filters( 'woocommerce_loop_add_to_cart_link',
	sprintf( '<div class="add-cart-button"><a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="le-button %s product_type_%s">%s</a></div>',
		esc_url( $product->add_to_cart_url() ),
		esc_attr( $product->id ),
		esc_attr( $product->get_sku() ),
		$btn_classes,
		esc_attr( $product->product_type ),
		esc_html( $product->add_to_cart_text() )
	),
$product );