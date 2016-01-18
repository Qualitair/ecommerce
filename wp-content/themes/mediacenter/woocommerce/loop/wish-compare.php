<?php
/**
 * Loop Add to Wishlist and Compare
 *
 * @author 		Ibrahim Ibn Dawood
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product;
?>

<div class="wish-compare">
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