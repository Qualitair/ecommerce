<?php
/**
 * Product loop sale flash
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product;
?>

<?php if ( $product->is_on_sale() ) : ?>

	<?php echo apply_filters( 'woocommerce_sale_flash', '<div class="ribbon red"><span class="onsale">' . __( 'Sale!', 'media_center' ) . '</span></div>', $post, $product ); ?>

<?php endif; ?>

<?php echo woocommerce_get_labels( $product->id ); ?>