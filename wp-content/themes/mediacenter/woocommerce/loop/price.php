<?php
/**
 * Loop Price
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $media_center_theme_options, $woocommerce_loop;

$product_item_size = isset( $woocommerce_loop['product_item_size'] ) ? $woocommerce_loop['product_item_size'] : $media_center_theme_options['product_item_size'];
?>

<?php if( 0 && $product_item_size == 'size-big' ) : ?>
<div class="prices clearfix">
	<?php 
		if ( $price_html = $product->get_price_html() ) {
			echo $price_html ;
		}
		wc_get_template('loop/add-to-cart.php');
	?>
</div>
<?php else : ?>
<?php 
	if ( $price_html = $product->get_price_html() ) {
		echo '<div class="product-price-container prices clearfix">' . $price_html . '</div>';
	}
?>
<?php endif; ?>