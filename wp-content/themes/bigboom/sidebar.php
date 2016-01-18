<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package BigBoom
 */

if ( 'full-content' == bigboom_get_layout() ) {
	return;
}

$sidebar = 'blog-sidebar';

if ( is_page() ) {
	$sidebar = 'page-sidebar';
} elseif ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
	$sidebar = 'shop-sidebar';
}
?>
<aside id="primary-sidebar" class="widget-area primary-sidebar <?php echo esc_attr( $sidebar ) ?> col-xs-12 col-sm-3 col-md-3" role="complementary">
	<?php dynamic_sidebar( $sidebar ) ?>
</aside><!-- #secondary -->