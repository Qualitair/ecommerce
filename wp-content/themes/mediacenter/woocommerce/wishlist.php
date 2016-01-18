<?php

global $wpdb, $yith_wcwl, $woocommerce;

// Start wishlist page printing
?>
<div id="wishlist-page" class="row">
	<div class="col-lg-10 center-block style-cart-page inner-bottom-xs">
		
		<?php if( count( $wishlist_items ) > 0 ) :?>

		<?php do_action( 'yith_wcwl_before_wishlist_title' ); ?>

		<div class="section section-page-title inner-xs">
			<div class="page-header">
				<h2 class="page-title">
				<?php
					$wishlist_title = get_option( 'yith_wcwl_wishlist_title' );
					if( !empty( $wishlist_title ) ) { 
						echo apply_filters( 'yith_wcwl_wishlist_title', $wishlist_title ); 
					}else{
						echo __( 'Wishlist', 'media_center' );
					}
				?>
				</h2>
			</div>
		</div>

		<?php 
			// Start wishlist page printing
			if( function_exists( 'wc_print_notices' ) ) {
			    wc_print_notices();
			}
			else{
			    $woocommerce->show_messages();
			}
		?>

		<div id="yith-wcwl-messages"></div>
		
		<form id="yith-wcwl-form" action="<?php echo esc_url( $yith_wcwl->get_wishlist_url() ) ?>" method="post">
			
			<?php do_action( 'yith_wcwl_before_wishlist' ); ?>

			<div class="items-holder">
				<div class="container-fluid cart wishlist_table" data-pagination="<?php echo esc_attr( $pagination )?>" data-per-page="<?php echo esc_attr( $per_page )?>" data-page="<?php echo esc_attr( $current_page )?>" data-id="<?php echo esc_attr( $wishlist_meta['ID'] )?>">
				<?php
				foreach( $wishlist_items as $item ) :
					
					global $product;
					
					if( function_exists( 'wc_get_product' ) ) {
			            $product = wc_get_product( $item['prod_id'] );
		            }
		            else{
			            $product = get_product( $item['prod_id'] );
		            }

	                if( $product !== false && $product->exists() ) :
                	?>
					<div id="yith-wcwl-row-<?php echo $item['prod_id'] ?>" class="row wishlist-item cart-item cart_item" data-row-id="<?php echo $item['prod_id'] ?>">
						
						<?php if( $is_user_owner ): ?>
						<div class="col-xs-1 col-sm-1 product-remove">
                        	<a href="<?php echo esc_url( add_query_arg( 'remove_from_wishlist', $item['prod_id'] ) ) ?>" class="remove remove_from_wishlist remove-item" title="<?php _e( 'Remove this product', 'media_center' ) ?>">&times;</a>
						</div>
						<?php endif; ?>

	                	<div class="col-xs-10 col-sm-5">
	                		<div class="media wishlist-product">
		                		<a href="<?php echo esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $item['prod_id'] ) ) ) ?>" class="pull-left">
	                                <?php echo $product->get_image() ?>
	                            </a>
								<div class="media-body">
									<div class="title">
										<a href="<?php echo esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $item['prod_id'] ) ) ) ?>"><?php echo apply_filters( 'woocommerce_in_cartproduct_obj_title', $product->get_title(), $product ) ?></a>
			                    	</div><!-- /.title --> 
			                    	<?php 
			                    		if( $show_stock_status ) :
			                    			$availability = $product->get_availability();
	                						$stock_status = $availability['class'];
			                                if( $stock_status == 'out-of-stock' ) {
			                                    $stock_status = "Out";
			                                    echo '<div class="availability"><span class="label label-important wishlist-out-of-stock">' . __( 'Out of Stock', 'media_center' ) . '</span></div>';
			                                } else {
			                                    $stock_status = "In";
			                                    echo '<div class="availability"><span class="label label-success wishlist-in-stock">' . __( 'In Stock', 'media_center' ) . '</span></div>';
			                                }
			                        	endif 
			                        ?>
								</div>
							</div>
	                    </div>
						
		                <div class="col-xs-4 col-xs-offset-1 col-sm-offset-0 col-sm-3">
		                	<div class="price">
		                	<?php
			                	if( $show_price ) :
			                		if( $product->price != '0' ) {
	                                    $wc_price = function_exists('wc_price') ? 'wc_price' : 'woocommerce_price';

	                                    if( $price_excl_tax ) {
	                                        echo apply_filters( 'woocommerce_cart_item_price_html', $wc_price( $product->get_price_excluding_tax() ), $item, '' );
	                                    }
	                                    else {
	                                        echo apply_filters( 'woocommerce_cart_item_price_html', $wc_price( $product->get_price() ), $item, '' );
	                                    }
	                                }
	                                else {
	                                    echo apply_filters( 'yith_free_text', __( 'Free!', 'media_center' ) );
	                                }
                                endif;
							?>
							</div>
		                </div>
						
						<div class="col-xs-7 col-sm-3">
							<div class="text-right">
								<?php
								 if( $show_add_to_cart ) :
									echo apply_filters( 'woocommerce_loop_add_to_cart_link',
										sprintf( '<div class="add-cart-button"><a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="le-button %s product_type_%s">%s</a></div>',
											esc_url( $product->add_to_cart_url() ),
											esc_attr( $product->id ),
											esc_attr( $product->get_sku() ),
											$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
											esc_attr( $product->product_type ),
											esc_html( $product->add_to_cart_text() )
										),
									$product );
								endif;
								?>
							</div>
						</div>
	              	
	              	</div><!-- /.cart-item -->

					<?php
					endif;
				endforeach;

				if( isset( $page_links ) ) : ?>
					<div class="col-xs-12 col-sm-12"><?php echo $page_links ?></div>
				<?php endif ?>
				</div><!-- /.wishlist-table -->
			</div><!-- /.items-holder -->

			<table class="table text-center semi-bold cart"></table>

			<?php

		    	if ( $is_user_owner && $wishlist_meta['wishlist_privacy'] != 2 && $share_enabled ) {
		    		yith_wcwl_get_template( 'share.php', $share_atts );
		    	}

		    	if ( $is_user_owner && $show_ask_estimate_button && $count > 0 ) : ?>
		    		<a href="<?php echo $ask_estimate_url ?>" class="btn button ask-an-estimate-button">
                        <?php echo apply_filters( 'yith_wcwl_ask_an_estimate_icon', '<i class="fa fa-shopping-cart"></i>' )?>
                        <?php _e( 'Ask an estimate of costs', 'media_center' ) ?>
                    </a>
	    		<?php endif; ?>
			
			<?php do_action( 'yith_wcwl_after_wishlist_share' ); ?>
				
			<?php wp_nonce_field( 'yith_wcwl_edit_wishlist_action', 'yith_wcwl_edit_wishlist' ); ?>

		    <?php if( $wishlist_meta['is_default'] != 1 ): ?>
		        <input type="hidden" value="<?php echo $wishlist_meta['wishlist_token'] ?>" name="wishlist_id" id="wishlist_id">
		    <?php endif; ?>

		    <?php do_action( 'yith_wcwl_after_wishlist' ); ?>

		</form><!-- /#yith-wcwl-form -->

	<?php else: ?>

		<div class="inner-top inner-bottom-md"> 

			<h1 class="lead-title text-center cart-empty">
				<?php _e( 'No products were added <br /> to the wishlist', 'media_center' ) ?>
			</h1>
			
			<p class="return-to-shop text-center">
				<a class="wc-backward" href="<?php echo apply_filters( 'woocommerce_return_to_shop_redirect', get_permalink( wc_get_page_id( 'shop' ) ) ); ?>">
					<i class="fa fa-mail-reply"></i>
					<?php _e( 'Return To Shop', 'media_center' ) ?>
				</a>
			</p>

		</div>
					
	<?php endif; ?>
	
	</div><!-- .large-->
</div><!-- .row-->