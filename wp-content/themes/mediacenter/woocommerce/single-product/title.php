<?php
/**
 * Single Product title
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
<div class="title">
	<h1 itemprop="name" class="product_title entry-title"><?php echo apply_filters( 'product_title_single_product', get_the_title() ); ?></h1>
</div>