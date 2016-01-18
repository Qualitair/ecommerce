<?php
/**
 * Empty cart page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

wc_print_notices();

?>


<div class="inner-top inner-bottom-xs">

	<h1 class="lead-title text-center cart-empty">
		<?php _e( 'Your cart is currently empty.', 'media_center' ) ?>
	</h1>

	<?php do_action( 'woocommerce_cart_is_empty' ); ?>

	<p class="return-to-shop text-center">
		<a class="wc-backward" href="<?php echo apply_filters( 'woocommerce_return_to_shop_redirect', get_permalink( wc_get_page_id( 'shop' ) ) ); ?>">
			<i class="fa fa-mail-reply"></i>
			<?php _e( 'Return To Shop', 'media_center' ) ?>
		</a>
	</p>

</div>