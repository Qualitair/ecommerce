<?php
/**
 * Checkout Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;
?>

<div id="checkout-page">
	<div class="row">
		<div class="col-lg-8 center-block">
		<?php

			wc_print_notices();

			do_action( 'woocommerce_before_checkout_form', $checkout );

			// If checkout registration is disabled and not logged in, the user cannot checkout
			if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
				echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'media_center' ) );
				return;
			}

			// filter hook for include new pages inside the payment method
			$get_checkout_url = apply_filters( 'woocommerce_get_checkout_url', WC()->cart->get_checkout_url() ); ?>

			<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( $get_checkout_url ); ?>">

				<?php if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>

					<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

					<?php do_action( 'woocommerce_checkout_billing' ); ?>
				
					<?php do_action( 'woocommerce_checkout_shipping' ); ?>

					<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

				<?php endif; ?>

				<section id="your-order">
					
					<h2 id="order_review_heading" class="border h1"><?php _e( 'Your order', 'media_center' ); ?></h2>

					<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

					<div id="order_review" class="woocommerce-checkout-review-order">
						<?php do_action( 'woocommerce_checkout_order_review' ); ?>
					</div>

					<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
				
				</section>

			</form>

			<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
			
		</div><!-- /.center-block -->
	</div><!-- /.row -->
</div><!-- /#checkout-page -->