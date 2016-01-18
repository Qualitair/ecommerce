<?php
/**
 * Order tracking form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $post;
?>
<div class="row">
	<div class="col-lg-8 center-block">
		
		<?php wc_print_notices(); ?>

		<div class="section">
			
			<p><?php _e( 'To track your order please enter your Order ID in the box below and press return. This was given to you on your receipt and in the confirmation email you should have received.', 'media_center' ); ?></p>
			
			<form action="<?php echo esc_url( get_permalink( $post->ID ) ); ?>" method="post" class="track_order">

				<div class="field-row row form-row form-row-first">
					<div class="col-xs-12">
						<label for="orderid"><?php _e( 'Order ID', 'media_center' ); ?></label> 
						<input class="le-input input-text" type="text" name="orderid" id="orderid" placeholder="<?php _e( 'Found in your order confirmation email.', 'media_center' ); ?>" />
					</div>
				</div>

				<div class="field-row row form-row form-row-last">
					<div class="col-xs-12">
						<label for="order_email"><?php _e( 'Billing Email', 'media_center' ); ?></label> 
						<input class="le-input input-text" type="text" name="order_email" id="order_email" placeholder="<?php _e( 'Email you used during checkout.', 'media_center' ); ?>" />
					</div>
				</div>

				<div class="form-row buttons-holder">
					<input type="submit" class="le-button huge button" name="track" value="<?php _e( 'Track', 'media_center' ); ?>" />
				</div>
				<?php wp_nonce_field( 'woocommerce-order_tracking' ); ?>

			</form>
		</div>	
	</div>
</div>