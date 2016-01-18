<?php
/**
 * Loop Hover Area
 *
 * @author 		Ibrahim Ibn Dawood
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="hover-area">			
<?php
	wc_get_template( 'loop/add-to-cart.php' );
	wc_get_template( 'loop/wish-compare.php' ); 
?>
</div>