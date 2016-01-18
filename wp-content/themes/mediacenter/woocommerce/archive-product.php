<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header( 'shop' ); ?>

<?php
global $media_center_theme_options;

$shop_layout = isset( $media_center_theme_options['shop_page_layout'] ) ? $media_center_theme_options['shop_page_layout'] : 'sidebar-left';

if( $shop_layout == 'full-width' ){
	get_template_part( 'templates/shop-fullwidth' );
} else if ( $shop_layout == 'sidebar-right' ) {
	get_template_part( 'templates/shop-sidebar-right' );
} else {
	get_template_part( 'templates/shop-sidebar-left' );
}

?>

<?php get_footer( 'shop' );