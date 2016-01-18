<?php
/**
 * Checkout shipping information form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div id="shipping-address" class="woocommerce-shipping-fields">
	<?php if ( WC()->cart->needs_shipping_address() === true ) : ?>
		<h2 class="border h1"><?php _e( 'Shipping Address', 'media_center' ); ?></h2>
		<?php
			if ( empty( $_POST ) ) {

				$ship_to_different_address = get_option( 'woocommerce_ship_to_billing' ) === 'no' ? 1 : 0;
				$ship_to_different_address = apply_filters( 'woocommerce_ship_to_different_address_checked', $ship_to_different_address );

			} else {

				$ship_to_different_address = $checkout->get_value( 'ship_to_different_address' );

			}
		?>

		<div class="row field-row">
			<div id="ship-to-different-address" class="col-xs-12">
				<div class="checkbox">
					<label for="ship-to-different-address-checkbox">
						<input id="ship-to-different-address-checkbox" class="le-checbox auto-width inline input-checkbox" <?php checked( $ship_to_different_address, 1 ); ?> type="checkbox" name="ship_to_different_address" value="1" /> 
						<?php _e( 'Ship to a different address?', 'media_center' ); ?>
					</label>
				</div>				
			</div>
		</div>

		<div class="shipping-address shipping_address">

			<?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>

			<div class="row">

			<?php foreach ( $checkout->checkout_fields['shipping'] as $key => $field ) : ?>

				<?php
					$field['input_class'] = array( 'le-input' );
					woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); 
				?>

			<?php endforeach; ?>
			
			</div>

			<?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>

		</div>

	<?php endif; ?>

	<?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

	<?php if ( apply_filters( 'woocommerce_enable_order_notes_field', get_option( 'woocommerce_enable_order_comments', 'yes' ) === 'yes' ) ) : ?>

		<?php if ( ! WC()->cart->needs_shipping() || WC()->cart->ship_to_billing_address_only() ) : ?>

			<h2 class="border h1"><?php _e( 'Additional Information', 'media_center' ); ?></h2>

		<?php endif; ?>

		<div class="row">

		<?php foreach ( $checkout->checkout_fields['order'] as $key => $field ) : ?>

			<?php
				$field['input_class'] = array( 'le-input' );
				woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
			?>
		<?php endforeach; ?>
		</div>

	<?php endif; ?>

	<?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
</div>
