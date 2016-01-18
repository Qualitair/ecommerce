<?php
/**
 * Single Product Share
 *
 * Sharing plugins can hook into here or you can add your own code directly.
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product;

?>

<div class="single-product-wish-compare">
	<div class="wishlist-compare">
		<?php if ( class_exists('YITH_WCWL') ) : ?>
		<div class="button-holder">
			<?php echo do_shortcode('[mc_yith_wcwl_add_to_wishlist]');?>
		</div>
		<?php endif; ?>

		<?php if( class_exists('YITH_Woocompare') ): ?>
		<div class="button-holder">
			<?php echo media_center_add_to_compare_button( $product->id ); ?>
	    </div>
		<?php endif; ?>
	</div>
</div>