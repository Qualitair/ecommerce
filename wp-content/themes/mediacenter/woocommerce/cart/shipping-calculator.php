<?php
/**
 * Shipping Calculator
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;

if ( get_option( 'woocommerce_enable_shipping_calc' ) === 'no' || ! WC()->cart->needs_shipping() )
	return;
?>


<div class="shipping-calculator-container inner-bottom-xs">
	
	<?php do_action( 'woocommerce_before_shipping_calculator' ); ?>

	<form class="shipping_calculator" action="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" method="post">

		<h2 class="shipping-calculator-title"><a href="#" class="shipping-calculator-button"><?php _e( 'Calculate Shipping', 'media_center' ); ?> <i class="fa fa-toggle-down"></i></a></h2>

		<section class="shipping-calculator-form">

			<p class="form-group form-row form-group form-row-wide">
				<select name="calc_shipping_country" id="calc_shipping_country" class="le-input form-control country_to_state" rel="calc_shipping_state">
					<option value=""><?php _e( 'Select a country&hellip;', 'media_center' ); ?></option>
					<?php
						foreach( WC()->countries->get_shipping_countries() as $key => $value )
							echo '<option value="' . esc_attr( $key ) . '"' . selected( WC()->customer->get_shipping_country(), esc_attr( $key ), false ) . '>' . esc_html( $value ) . '</option>';
					?>
				</select>
			</p>

			<p class="form-row form-group form-row-wide">
				<?php
					$current_cc = WC()->customer->get_shipping_country();
					$current_r  = WC()->customer->get_shipping_state();
					$states     = WC()->countries->get_states( $current_cc );

					// Hidden Input
					if ( is_array( $states ) && empty( $states ) ) {

						?><input type="hidden" name="calc_shipping_state" id="calc_shipping_state" placeholder="<?php _e( 'State / county', 'media_center' ); ?>" /><?php

					// Dropdown Input
					} elseif ( is_array( $states ) ) {

						?><span>
							<select class="le-input form-control" name="calc_shipping_state" id="calc_shipping_state" placeholder="<?php _e( 'State / county', 'media_center' ); ?>">
								<option value=""><?php _e( 'Select a state&hellip;', 'media_center' ); ?></option>
								<?php
									foreach ( $states as $ckey => $cvalue )
										echo '<option value="' . esc_attr( $ckey ) . '" ' . selected( $current_r, $ckey, false ) . '>' . esc_html( sprintf( __( '%s', 'media_center'), $cvalue ) ) .'</option>';
								?>
							</select>
						</span><?php

					// Standard Input
					} else {

						?><input type="text" class="input-text le-input" value="<?php echo esc_attr( $current_r ); ?>" placeholder="<?php _e( 'State / county', 'media_center' ); ?>" name="calc_shipping_state" id="calc_shipping_state" /><?php

					}
				?>
			</p>

			<?php if ( apply_filters( 'woocommerce_shipping_calculator_enable_city', false ) ) : ?>

				<div class="form-row form-group form-row-wide">
					<input type="text" class="input-text le-input" value="<?php echo esc_attr( WC()->customer->get_shipping_city() ); ?>" placeholder="<?php _e( 'City', 'media_center' ); ?>" name="calc_shipping_city" id="calc_shipping_city" />
				</div>

			<?php endif; ?>

			<?php if ( apply_filters( 'woocommerce_shipping_calculator_enable_postcode', true ) ) : ?>

				<div class="form-row form-group form-row-wide">
					<input type="text" class="input-text le-input" value="<?php echo esc_attr( WC()->customer->get_shipping_postcode() ); ?>" placeholder="<?php _e( 'Postcode / Zip', 'media_center' ); ?>" name="calc_shipping_postcode" id="calc_shipping_postcode" />
				</div>

			<?php endif; ?>

			<div><button type="submit" name="calc_shipping" value="1" class="inverse btn-block le-button button"><?php _e( 'Update Totals', 'media_center' ); ?></button></div>

			<?php wp_nonce_field( 'woocommerce-cart' ); ?>
		</section>
	</form>

	<?php do_action( 'woocommerce_after_shipping_calculator' ); ?>

</div><!-- /.shipping-calculator-container -->