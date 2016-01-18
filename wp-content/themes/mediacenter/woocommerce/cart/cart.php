<?php
/**
 * Cart Page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;

wc_print_notices();

do_action( 'woocommerce_before_cart' ); ?>

<div id="cart-page" class="style-cart-page">

	<div class="row">
		<div class="col-xs-12">
			<div class="section section-page-title inner-xs">
				<div class="page-header">
					<h2 class="page-title"><?php echo __( 'Shopping Cart Summary', 'media_center' ); ?></h2>
				</div>
			</div>
		</div>
	</div>
	
	<form action="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" method="post" class="row">

		<div class="col-xs-12 items-holder">

			<?php do_action( 'woocommerce_before_cart_table' ); ?>

			<div class="row no-margin cart-item hidden-xs table-head">
				<div class="col-xs-12 col-sm-4 col-sm-offset-1">
					<h4><?php echo __( 'Product', 'media_center' ); ?></h4>
				</div>
				<div class="col-xs-12 col-sm-2">
					<h4><?php echo __( 'Unit Price', 'media_center' ); ?></h4>
				</div>
				<div class="col-xs-12 col-sm-3">
					<h4><?php echo __( 'Quantity', 'media_center' ); ?></h4>
				</div>
				<div class="col-xs-12 col-sm-2">
					<h4><?php echo __( 'Total', 'media_center' ); ?></h4>
				</div>
			</div>

			<?php do_action( 'woocommerce_before_cart_contents' ); ?>

			<?php
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						?>

			<div class="row no-margin cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
                <div class="col-xs-12 col-sm-5">
                	<div class="media">
	                    <?php
							$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

							if ( ! $_product->is_visible() )
								echo '<a class="pull-left" href="#">' . $thumbnail . '</a>';
							else
								printf( '<a href="%s" class="pull-left thumb-holder">%s</a>', $_product->get_permalink( $cart_item ), $thumbnail );
						?>
						<div class="media-body">
							<div class="title">
		                        <?php
									if ( ! $_product->is_visible() )
										echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
									else
										echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', $_product->get_permalink( $cart_item ), $_product->get_title() ), $cart_item, $cart_item_key );

									// Meta data
									echo WC()->cart->get_item_data( $cart_item );

		               				// Backorder notification
		               				if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) )
		               					echo '<p class="backorder_notification">' . __( 'Available on backorder', 'media_center' ) . '</p>';
								?>
		                    </div><!-- /.title --> 
		                    <?php woocommerce_show_brand( $_product->id ); ?>
		                    <div class="price unit-price visible-xs">
		                		<?php
									echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
								?>
		                	</div>
						</div>
					</div>
                </div>

                <div class="hidden-xs col-sm-2">
                	<div class="price unit-price">
                		<?php
							echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
						?>
                	</div>
                </div>	

                <div class="col-xs-6 quantity-spinner col-sm-3">
                    <?php
						if ( $_product->is_sold_individually() ) {
							$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
						} else {
							$product_quantity = woocommerce_quantity_input( array(
								'input_name'  => "cart[{$cart_item_key}][qty]",
								'input_value' => $cart_item['quantity'],
								'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
								'min_value'   => '0'
							), $_product, false );
						}

						echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key );
					?>
                </div> 

                <div class="col-xs-6 col-sm-2 item-subtotal">
                    <div class="price">
                        <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
                    </div>
                    <?php echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" class="remove" title="%s">&times;</a>', esc_url( WC()->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'media_center' ) ), $cart_item_key );?>
                </div>
            </div><!-- /.cart-item -->
						<?php
					}
				}
			?>

			<?php do_action( 'woocommerce_after_cart_table' ); ?>
			
		</div><!-- /.items-holder -->

		<div class="col-xs-12 inner-top-sm cart-buttons">
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<div class="widget">
						<?php if ( WC()->cart->coupons_enabled() ) : ?>
						<div class="row">
							<div class="col-sm-8">
								<div class="coupon">
									<h2 class="sr-only"><?php _e( 'Coupon', 'media_center' ); ?></h2>
									<div class="inline-input clearfix">
										<label for="coupon_code" class="sr-only"><?php _e( 'Coupon', 'media_center' ); ?>:</label> 
										<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php _e( 'Coupon code', 'media_center' ); ?>" /> 
										<input type="submit" class="le-button button" name="apply_coupon" value="<?php _e( 'Apply', 'media_center' ); ?>" />
									</div><!-- /.inline-input -->
									<?php do_action( 'woocommerce_cart_coupon' ); ?>
								</div><!-- /.coupon -->	
							</div><!-- /.col-sm-8 -->
						</div><!-- /.row -->
						<?php endif; ?>
					</div><!-- /.widget -->
				</div><!-- /.col-sm-6 -->

				<div class="col-xs-12 col-sm-6">
					<div class="buttons-holder clearfix">
						<div class="row">
							<div class="col-xs-12 col-sm-4 col-sm-offset-2">
								<input type="submit" class="btn-block le-button big inverse button" name="update_cart" value="<?php _e( 'Update Cart', 'media_center' ); ?>" /> 		
							</div>
							<div class="col-xs-12 col-sm-6">
								<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
							</div>
						</div>
						<?php do_action( 'woocommerce_cart_actions' ); ?>
					</div><!-- /.buttons-holder -->	
				</div>
			</div>
		</div><!-- /.cart-buttons -->
		
		<?php wp_nonce_field( 'woocommerce-cart' ); ?>
	</form>

	<div class="col-xs-12 inner-top-sm inner-bottom-sm">
		<div class="row">

			<div class="col-md-5 col-md-offset-7 col-xs-12">
				<?php do_action( 'woocommerce_cart_contents' ); ?>
		
				<?php woocommerce_cart_totals(); ?>

				<?php do_action( 'woocommerce_after_cart_contents' ); ?>	
			</div><!-- /.col-md-6 -->

			<div class="clearfix"></div>

			<div class="col-xs-12">
				<div class="cart-collaterals">

					<div class="row">
						<div class="col-md-5 col-md-offset-7 col-xs-12"><?php woocommerce_shipping_calculator(); ?></div>
					</div>

					<div class="clearfix"></div>
					
					<?php do_action( 'woocommerce_cart_collaterals' ); ?>
					
				</div><!-- /.cart-collaterals -->			
			</div>

		</div><!-- /row -->
	</div><!-- /.inner-top-xs -->

	<?php do_action( 'woocommerce_after_cart' ); ?>
</div><!-- /#cart-page -->