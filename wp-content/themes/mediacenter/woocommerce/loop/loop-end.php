<?php
/**
 * Product Loop End
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */
?>
<?php 
	global $woocommerce_loop;
	if( isset ( $woocommerce_loop['is_carousel'] ) && $woocommerce_loop['is_carousel'] ) :
?>
</div><!-- /.owl-carousel -->
<?php else : ?>
	</div><!-- /.grid-view -->
</div><!-- /.product-items -->
<?php endif;