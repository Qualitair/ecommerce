<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop, $yith_wcwl, $media_center_theme_options;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 3 );

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();
$product_item_class = '';

if( isset( $woocommerce_loop['is_carousel'] ) && $woocommerce_loop['is_carousel'] ) {
	$classes[] = 'carousel-item';
	$classes[] = 'hover';
	$classes[] = 'size-small';
	$classes[] = 'small';
	$classes[] = 'hover';
} else {
	if ( !empty( $media_center_theme_options['products_animation'] ) && $media_center_theme_options['products_animation'] != 'no-animation' ){
		$product_item_class = 'wow ' . $media_center_theme_options['products_animation'];
	}
	$product_item_size = isset( $woocommerce_loop['product_item_size'] ) ? $woocommerce_loop['product_item_size'] : $media_center_theme_options['product_item_size'];
	$screen_width = isset( $woocommerce_loop['screen_width']) ? $woocommerce_loop['screen_width'] : 75;

	$col_width = media_center_product_item_col_width( $product_item_size, $screen_width );

	$woocommerce_loop['columns'] = $col_width['columns'];

	$classes = $col_width['classes'];
	if( $product_item_size == 'size-big' ) {
		$classes[] = 'size-big';
	} elseif ( $product_item_size == 'size-small' ) {
		$classes[] = 'size-small';
	} else {
		$classes[] = 'size-medium';
	}
	$classes[] = 'screen-width-' . $screen_width;
}

$product_item_wrap_class = 'product-item-wrap';
?>
<div <?php post_class( $classes ); ?>>
	<div class="<?php echo esc_attr( $product_item_wrap_class ); ?>">
		<div class="product-item <?php echo $product_item_class; if ( ( isset( $media_center_theme_options['catalog_mode'] ) ) && ( $media_center_theme_options['catalog_mode']   == 1 )  ) : ?> catalog_mode<?php endif; ?>">
			<div class="product-item-inner">
				<?php 
					/**
					 * woocommerce_before_shop_loop_item hook
					 *
					 * @hooked woocommerce_show_product_loop_sale_flash - 10
					 */
					do_action( 'woocommerce_before_shop_loop_item' ); 
				?>
				
				<div class="product-body">
					<?php
						/**
						 * woocommerce_before_shop_loop_item_title hook
						 *
						 * @hooked woocommerce_template_loop_product_thumbnail - 10
						 */
						do_action( 'woocommerce_before_shop_loop_item_title' );
					?>

					<h4 class="product-title"><a href="<?php the_permalink(); ?>"><?php echo apply_filters( 'product_title_grid_view', get_the_title() ); ?></a></h4>

					<?php
						/**
						 * woocommerce_after_shop_loop_item_title hook
						 *
						 * @hooked woocommerce_show_brand - 10
						 */
						do_action( 'woocommerce_after_shop_loop_item_title' );
					?>
				</div><!-- /.body -->

				<?php 
					/**
					 * woocommerce_after_shop_loop_item hook
					 *
					 * @hooked woocommerce_template_loop_price - 10
					 */
					do_action( 'woocommerce_after_shop_loop_item' ); 
				?>
			</div><!-- /.product-item-inner -->
		</div><!-- /.product-item -->
	</div><!-- /.product-item-wrap -->
</div><!-- /.product-item-wrap -->