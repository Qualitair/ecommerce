<?php
/**
 * Product Loop Start
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
<div id="<?php echo $woocommerce_loop['carousel_id'];?>" class="products-carousel-<?php echo $woocommerce_loop['columns'];?> product-grid-holder owl-carousel">
<?php else : ?>
	<div class="product-items">
		<div class="grid-view row">
<?php endif;