<?php
/**
 * Hooks for template nav menus
 *
 * @package BigBoom
 */

/**
 * Add a walder object for all nav menu
 *
 * @since  1.0.0
 *
 * @param  array $args The default args
 * @return array
 */
function bigboom_nav_menu_args( $args ) {
	$args['walker'] = new BigBoom_Walker_Nav_Menu;

	return $args;
}
add_filter( 'wp_nav_menu_args', 'bigboom_nav_menu_args' );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @since 1.0
 * @param array $args Configuration arguments.
 * @return array
 */
function bigboom_page_menu_args( $args ) {
	$args['show_home'] = true;
	unset( $args['walker'] );
	return $args;
}
add_filter( 'wp_page_menu_args', 'bigboom_page_menu_args' );
