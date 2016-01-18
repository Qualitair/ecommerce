<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $media_center_theme_options;

$single_product_layout = isset( $media_center_theme_options['single_product_layout'] ) ? $media_center_theme_options['single_product_layout'] : 'full-width' ;

switch( $single_product_layout ){
	case 'sidebar-right':
		$has_sidebar = true;
		$single_product_sidebar_class = 'col-xs-12 col-sm-3';
		$single_product_content_class = 'col-xs-12 col-sm-9';
	break;
	case 'sidebar-left':
		$has_sidebar = true;
		$single_product_sidebar_class = 'col-xs-12 col-sm-3 col-sm-pull-9';
		$single_product_content_class = 'col-xs-12 col-sm-9 col-sm-push-3';
	break;
	case 'full-width':
	default:
		$has_sidebar = false;
		$single_product_sidebar_class = 'hidden';
		$single_product_content_class = 'col-xs-12 col-sm-12';
	break;
}

get_header( 'shop' ); ?>

	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
	?>
<div class="container inner-bottom-xs">
	<div class="row">
		
		<div class="<?php echo $single_product_content_class; ?>">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php wc_get_template_part( 'content', 'single-product' ); ?>

		<?php endwhile; // end of the loop. ?>
		
		</div><!-- /.col-sm-9 -->

		<?php if( $has_sidebar ) : ?>
		<div class="<?php echo $single_product_sidebar_class; ?> sidebar">
			<?php
				/**
				 * woocommerce_sidebar hook
				 *
				 * @hooked woocommerce_get_sidebar - 10
				 */
				do_action( 'woocommerce_sidebar' );
			?>
		</div><!-- /.sidebar -->
		<?php endif; ?>
	</div><!-- /.row -->
</div><!-- /.container -->
<?php
	/**
	 * woocommerce_after_main_content hook
	 *
	 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
	 */
	do_action( 'woocommerce_after_main_content' );
?>
<?php get_footer( 'shop' );