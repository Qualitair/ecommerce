<?php
/**
 * Hooks for frontend display
 *
 * @package BigBoom
 */


/**
 * Adds custom classes to the array of body classes.
 *
 * @since 1.0
 * @param array $classes Classes for the body element.
 * @return array
 */
function bigboom_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Add a class of layout
	$classes[] = bigboom_get_layout();

	// Add a class of shop
	if ( isset( $_COOKIE['shop_view'] ) && function_exists( 'is_shop' ) && ( is_shop() || is_product_category() || is_product_tag() ) ) {
		$classes[] = 'shop-view-' . $_COOKIE['shop_view'];
	}

	// Add a class for color scheme
	if ( intval( bigboom_theme_option( 'custom_color_scheme' ) ) && bigboom_theme_option( 'custom_color_1' ) ) {
		$classes[] = 'custom-color-scheme';
	} else {
		$classes[] = bigboom_theme_option( 'color_scheme' );
	}

	return $classes;
}
add_filter( 'body_class', 'bigboom_body_classes' );
