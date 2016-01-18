<?php
/**
 * Load and register widgets
 *
 * @package BigBoom
 */

require_once THEME_DIR . '/inc/widgets/recent-posts.php';
require_once THEME_DIR . '/inc/widgets/tabs.php';
require_once THEME_DIR . '/inc/widgets/recent-comments.php';
require_once THEME_DIR . '/inc/widgets/product-search.php';
require_once THEME_DIR . '/inc/widgets/mini-cart.php';

/**
 * Register widgets
 *
 * @since  1.0
 *
 * @return void
 */
function bigboom_register_widgets() {
	register_widget( 'TA_Recent_Posts_Widget' );
	register_widget( 'TA_Tabs_Widget' );
	register_widget( 'TA_Recent_Comments_Widget' );
	register_widget( 'TA_Product_Search_Widget' );
	register_widget( 'TA_Mini_Cart_Widget' );
}
add_action( 'widgets_init', 'bigboom_register_widgets' );
