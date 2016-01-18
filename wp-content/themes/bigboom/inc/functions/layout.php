<?php
/**
 * Custom functions for layout.
 *
 * @package bigboom
 */

/**
 * Get layout base on current page
 *
 * @return string
 */
function bigboom_get_layout() {
	$layout  = bigboom_theme_option( 'default_layout' );

	if ( is_singular() && bigboom_get_meta( 'custom_layout' ) ) {
		$layout = bigboom_get_meta( 'layout' );
	} elseif ( is_page() ) {
		$layout = bigboom_theme_option( 'page_layout' );
	} elseif ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
		$layout = bigboom_theme_option( 'shop_layout' );
	} elseif ( is_404() ) {
		$layout = 'full-content';
	}

	return $layout;
}

/**
 * Get Bootstrap column classes for content area
 *
 * @since  1.0
 *
 * @return array Array of classes
 */
function bigboom_get_content_columns( $layout = null ) {
	$layout = $layout ? $layout : bigboom_get_layout();
	if ( 'full-content' == $layout ) {
		return array( 'col-md-12' );
	}

	return array( 'col-md-9', 'col-sm-9', 'col-xs-12' );
}

/**
 * Echos Bootstrap column classes for content area
 *
 * @since 1.0
 */
function bigboom_content_columns( $layout = null ) {
	echo implode( ' ', bigboom_get_content_columns( $layout ) );
}
