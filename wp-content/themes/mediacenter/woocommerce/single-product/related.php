<?php
/**
 * Related Products
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop, $media_center_theme_options;

$related = $product->get_related( $posts_per_page );

if ( sizeof( $related ) == 0 ) return;

$args = apply_filters( 'woocommerce_related_products_args', array(
	'post_type'            => 'product',
	'ignore_sticky_posts'  => 1,
	'no_found_rows'        => 1,
	'posts_per_page'       => $posts_per_page,
	'orderby'              => $orderby,
	'post__in'             => $related,
	'post__not_in'         => array( $product->id )
) );

$products = new WP_Query( $args );

if( is_product() && ( $media_center_theme_options['single_product_layout'] == 'sidebar-left' || $media_center_theme_options['single_product_layout'] == 'sidebar-right' ) ){
	$woocommerce_loop['product_item_size'] = 'size-small';
	$woocommerce_loop['screen_width'] = 75;
} else {
	$woocommerce_loop['product_item_size'] = 'size-medium';
	$woocommerce_loop['screen_width'] = 100;
}

if ( $products->have_posts() ) : ?>

	<section class="section related products inner-bottom-xs">

		<div class="container-fluid">
			
			<h2><?php _e( 'Related Products', 'media_center' ); ?></h2>

			<?php woocommerce_product_loop_start(); ?>

				<?php while ( $products->have_posts() ) : $products->the_post(); ?>

					<?php wc_get_template_part( 'content', 'product' ); ?>

				<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>
		</div><!-- /.container-fluid -->

	</section><!-- /.related .products -->

<?php endif;

wp_reset_postdata();
