<?php
/**
 * Cart totals
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.6
 */

if ( ! defined( 'ABSPATH' ) ){
	exit;
}
?>
<div class="widget cart-summary cart_totals <?php if ( WC()->customer->has_calculated_shipping() ) echo 'calculated_shipping'; ?>">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<h2 class="widget-title sr-only"><?php _e( 'Cart Totals', 'media_center' ); ?></h2>

	<div class="body">

		<ul class="list-unstyled tabled-data no-border inverse-bold">
			<li class="clearfix cart-subtotal">
				<label><?php _e( 'Subtotal', 'media_center' ); ?></label>
				<div class="value"><?php wc_cart_totals_subtotal_html(); ?></div>
			</li><!-- /.cart-subtotal -->
			
			<?php foreach ( WC()->cart->get_coupons( 'cart' ) as $code => $coupon ) : ?>
			<li class="clearfix cart-discount coupon-<?php echo esc_attr( sanitize_title ( $code ) ); ?>">
				<label><?php wc_cart_totals_coupon_label( $coupon ); ?></label>
				<div class="value"><?php wc_cart_totals_coupon_html( $coupon ); ?></div>
			</li>
			<?php endforeach; ?>

			<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

				<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

				<?php wc_cart_totals_shipping_html(); ?>

				<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

			<?php elseif ( WC()->cart->needs_shipping() ) : ?>

			<li class="clearfix shipping">
				<label><?php _e( 'Shipping', 'media_center' ); ?></label>
				<div class="value"><?php woocommerce_shipping_calculator(); ?></div>
			</li>

			<?php endif; ?>

			<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<li class="clearfix fee">
				<label><?php echo esc_html( $fee->name ); ?></label>
				<div class="value"><?php wc_cart_totals_fee_html( $fee ); ?></div>
			</li><!-- /.fee -->
			<?php endforeach; ?>

			<?php if ( WC()->cart->tax_display_cart == 'excl' ) : ?>
				<?php if ( get_option( 'woocommerce_tax_total_display' ) == 'itemized' ) : ?>
					<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
						<li class="clearfix tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
							<label><?php echo esc_html( $tax->label ); ?></label>
							<div class="value"><?php echo wp_kses_post( $tax->formatted_amount ); ?></div>
						</li>
					<?php endforeach; ?>
				<?php else : ?>
					<li class="clearfix tax-total">
						<label><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></label>
						<div class="value"><?php echo wc_cart_totals_taxes_total_html(); ?></div>
					</li>
				<?php endif; ?>
			<?php endif; ?>

			<?php foreach ( WC()->cart->get_coupons( 'order' ) as $code => $coupon ) : ?>
				<li class="clearfix order-discount coupon-<?php echo esc_attr( $code ); ?>">
					<label><?php wc_cart_totals_coupon_label( $coupon ); ?></label>
					<div class="value"><?php wc_cart_totals_coupon_html( $coupon ); ?></div>
				</li>
			<?php endforeach; ?>

		</ul><!-- /.tabled-data -->

		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

		<ul class="tabled-data inverse-bold no-border" id="total-price">
            <li>
                <label><?php _e( 'Total', 'media_center' ); ?></label>
                <div class="value"><?php wc_cart_totals_order_total_html(); ?></div>
            </li>
        </ul><!-- /.tabled-data #total-price -->

        <?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

        <?php if ( WC()->cart->get_cart_tax() ) : ?>
			<p><small><?php

				$estimated_text = WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping()
					? sprintf( ' ' . __( ' (taxes estimated for %s)', 'media_center' ), WC()->countries->estimated_for_prefix() . __( WC()->countries->countries[ WC()->countries->get_base_country() ], 'media_center' ) )
					: '';

				printf( __( 'Note: Shipping and taxes are estimated%s and will be updated during checkout based on your billing and shipping information.', 'media_center' ), $estimated_text );

			?></small></p>
		<?php endif; ?>

		<?php do_action( 'woocommerce_after_cart_totals' ); ?>
		
	</div><!-- /.body -->
</div>