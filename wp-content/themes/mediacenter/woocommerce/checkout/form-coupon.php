<?php
/**
 * Checkout coupon form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! WC()->cart->coupons_enabled() ) {
	return;
}

$info_message = apply_filters( 'woocommerce_checkout_coupon_message', __( 'Have a coupon?', 'media_center' ) . ' <a href="#" class="showcoupon">' . __( 'Click here to enter your code', 'media_center' ) . '</a>' );
wc_print_notice( $info_message, 'notice' );
?>

<form class="checkout_coupon inner-bottom-xs" method="post" style="display:none">
	
	<div class="row field-row">
		<div class="col-lg-12">
			<p class="form-row form-row-first">
				<input type="text" name="coupon_code" class="le-input input-text" placeholder="<?php _e( 'Coupon code', 'media_center' ); ?>" id="coupon_code" value="" />
			</p>		
		</div>
	</div>
	

	<p class="form-row form-row-last">
		<input type="submit" class="le-button button" name="apply_coupon" value="<?php _e( 'Apply Coupon', 'media_center' ); ?>" />
	</p>

	<div class="clear"></div>
</form>