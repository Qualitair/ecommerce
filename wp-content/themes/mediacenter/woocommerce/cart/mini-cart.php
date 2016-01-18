<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;
$cart_subtotal = WC()->cart->subtotal;
$tp_font_class = 'ft-22';
if( $cart_subtotal >= 1000000 ){
    $tp_font_class = 'ft-12';
} elseif ( $cart_subtotal >= 100000 ){
    $tp_font_class = 'ft-14';
} elseif ( $cart_subtotal >= 10000){
    $tp_font_class = 'ft-16';
} elseif ( $cart_subtotal >= 1000){
    $tp_font_class = 'ft-18';
}
?>

<?php do_action( 'woocommerce_before_mini_cart' ); ?>

<a href="#" data-toggle="dropdown" class="dropdown-toggle">
    <div class="basket-item-count">
        <span class="cart-items-count count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
        <img alt="" src="<?php echo get_template_directory_uri();?>/assets/images/icon-cart.png">
    </div>

    <div class="total-price-basket"> 
        <span class="lbl"><?php echo __('Your Cart:', 'media_center'); ?></span>
        <span class="total-price <?php echo $tp_font_class; ?>">
        	<?php echo WC()->cart->get_cart_subtotal(); ?>
        </span>
    </div>
</a>

<ul class="dropdown-menu cart_list product_list_widget <?php echo $args['list_class']; ?>">

	<?php if ( sizeof( WC()->cart->get_cart() ) > 0 ) : ?>

		<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

					$product_name  = apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
					$thumbnail     = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
					$product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );

					?>
					<li>
						<div class="basket-item">
                            <div class="row">
                                <div class="col-xs-4 col-sm-4 no-margin text-center">
                                    <div class="thumb">
                                        <?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ); ?>
                                    </div>
                                </div>
                                <div class="col-xs-8 col-sm-8 no-margin">
                                    <div class="title"><?php echo $cart_item['quantity'];?> &times; <?php echo $product_name; ?></div>
                                    <?php echo WC()->cart->get_item_data( $cart_item ); ?>
                                    <div class="price"><?php echo $product_price; ?></div>
                                </div>
                            </div>
                            <a href="<?php echo esc_url( WC()->cart->get_remove_url( $cart_item_key ) );?>" class="close-btn"></a>
                        </div>
					</li>
					<?php
				}
			}
		?>

	<?php else : ?>

		<li class="empty">
			<div class="basket-item">
				<p class="alert alert-warning"><?php _e( 'No products in the cart.', 'media_center' ); ?></p>
			</div>
		</li>

	<?php endif; ?>

	<?php if ( sizeof( WC()->cart->get_cart() ) > 0 ) : ?>

	<li class="checkout">
		<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>
        <div class="basket-item">
            <div class="row">
                <div class="col-xs-6 col-sm-6">
                    <a class="le-button inverse" href="<?php echo WC()->cart->get_cart_url(); ?>"><?php _e( 'View Cart', 'media_center' ); ?></a>
                </div>
                <div class="col-xs-6 col-sm-6">
                    <a class="le-button" href="<?php echo WC()->cart->get_checkout_url(); ?>"><?php _e( 'Checkout', 'media_center' ); ?></a>
                </div>
            </div>
        </div>
    </li>
	<?php endif; ?>
</ul><!-- end product list -->
<?php do_action( 'woocommerce_after_mini_cart' );