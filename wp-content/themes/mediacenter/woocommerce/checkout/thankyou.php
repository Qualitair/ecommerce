<?php
/**
 * Thankyou page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( $order ) : ?>

	<?php if ( $order->has_status( 'failed' ) ) : ?>

		<p class="alert alert-danger">
			<?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction.', 'media_center' ); ?><br/>
			<?php
			if ( is_user_logged_in() )
				_e( 'Please attempt your purchase again or go to your account page.', 'media_center' );
			else
				_e( 'Please attempt your purchase again.', 'media_center' );
			?><br/>
			<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'media_center' ) ?></a>
			<?php if ( is_user_logged_in() ) : ?>
			<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'myaccount' ) ) ); ?>" class="button pay"><?php _e( 'My Account', 'media_center' ); ?></a>
			<?php endif; ?>
		</p>

	<?php else : ?>

		<p class="alert alert-success"><?php _e( 'Thank you. Your order has been received.', 'media_center' ); ?></p>

		<table class="order_details table table-bordered">
			<tr class="order">
				<th><?php _e( 'Order:', 'media_center' ); ?></th>
				<td><strong><?php echo $order->get_order_number(); ?></strong></td>
			</tr>
			<tr class="date">
				<th><?php _e( 'Date:', 'media_center' ); ?></th>
				<td><strong><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></strong></td>
			</tr>
			<tr class="total">
				<th><?php _e( 'Total:', 'media_center' ); ?></th>
				<td><strong><?php echo $order->get_formatted_order_total(); ?></strong></td>
			</tr>
			<?php if ( $order->payment_method_title ) : ?>
			<tr class="method">
				<th><?php _e( 'Payment method:', 'media_center' ); ?></th>
				<td><strong><?php echo $order->payment_method_title; ?></strong></td>
			</tr>
			<?php endif; ?>
		</table>
		<div class="clearfix"></div>

	<?php endif; ?>

	<?php do_action( 'woocommerce_thankyou_' . $order->payment_method, $order->id ); ?>
	<?php do_action( 'woocommerce_thankyou', $order->id ); ?>

<?php else : ?>

	<p class="alert alert-success"><?php _e( 'Thank you. Your order has been received.', 'media_center' ); ?></p>

<?php endif; ?>