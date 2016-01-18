<?php
/**
 * The template for displaying brand content within loops.
 *
 *
 * @author 		Ibrahim Ibn Dawood
 * @package 	WooCommerce/Templates
 * @version     0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $brand, $woocommerce_loop;

?>
<div class="brand">
	<pre><?php echo print_r($brand, 1);?></pre>
</div><!-- /.carousel-item -->