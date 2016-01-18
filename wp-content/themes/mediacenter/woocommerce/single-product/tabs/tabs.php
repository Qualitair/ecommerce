<?php
/**
 * Single Product tabs
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Filter tabs and allow third parties to add their own
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $tabs ) ) : ?>

	<div id="single-product-tab" class="woocommerce-tabs inner-bottom-xs">
		<div class="container-fluid">

			<div class="tab-holder">
				<ul class="nav nav-tabs simple" role="tablist">
					<?php $loop = 0; ?>
					<?php foreach ( $tabs as $key => $tab ) : ?>

						<li class="<?php echo $key ?>_tab <?php if( 0 == $loop++ ){ echo 'active'; }?>">
							<a href="#tab-<?php echo $key ?>" role="tab" data-toggle="tab"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', $tab['title'], $key ) ?></a>
						</li>

					<?php endforeach; ?>
				</ul>
				<div class="tab-content">

					<?php $loop = 0; ?>
					<?php foreach ( $tabs as $key => $tab ) : ?>

					<div class="tab-pane entry-content <?php if( 0 == $loop++ ){ echo 'active'; }?>" id="tab-<?php echo $key ?>">
						<?php call_user_func( $tab['callback'], $key, $tab ) ?>
					</div>

					<?php endforeach; ?>
				</div><!-- /.tab-content -->
			</div><!-- /.tab-holder -->
			
		</div><!-- /.container-fluid -->
	</div><!-- /.woocommerce-tabs -->

<?php endif; ?>