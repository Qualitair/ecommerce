<?php
/**
 * Order tracking
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$order_status_text = sprintf( __( 'Order %s which was made %s has the status &ldquo;%s&rdquo;', 'media_center' ), $order->get_order_number(), human_time_diff( strtotime( $order->order_date ), current_time( 'timestamp' ) ) . ' ' . __( 'ago', 'media_center' ), wc_get_order_status_name( $order->get_status() ) );

if ( $order->has_status( 'completed' ) ) $order_status_text .= ' ' . __( 'and was completed', 'media_center' ) . ' ' . human_time_diff( strtotime( $order->completed_date ), current_time( 'timestamp' ) ) . __( ' ago', 'media_center' );

$order_status_text .= '.';

?>

<div class="row">
	<div class="col-lg-8 center-block">

	<?php 
		
		echo '<div class="order-info text-center">' . wpautop( esc_attr( apply_filters( 'woocommerce_order_tracking_status', $order_status_text, $order ) ) ) . '</div>';

		$notes = $order->get_customer_order_notes();

		if ( $notes ) : ?>
		<section class="section order-updates">
			<h2><?php _e( 'Order Updates', 'media_center' ); ?></h2>
			<ol class="commentlist notes">
				<?php foreach ( $notes as $note ) : ?>
				<li class="comment note">
					<div class="comment_container">
						<div class="comment-text">
							<p class="meta"><?php echo date_i18n( __( 'l jS \o\f F Y, h:ia', 'media_center' ), strtotime( $note->comment_date ) ); ?></p>
							<div class="description">
								<?php echo wpautop( wptexturize( wp_kses_post( $note->comment_content ) ) ); ?>
							</div>
			  				<div class="clearfix"></div>
			  			</div>
						<div class="clearfix"></div>
					</div>
				</li><!-- /.note -->
				<?php endforeach; ?>
			</ol><!-- /.notes -->
		</section><!-- /.order-updates -->
	<?php endif; ?>

	<?php do_action( 'woocommerce_view_order', $order->id ); ?>	
	</div><!-- /.center-block -->
</div>