<?php
/**
 * Single Product Meta
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product;

$cat_count = sizeof( get_the_terms( $post->ID, 'product_cat' ) );
$tag_count = sizeof( get_the_terms( $post->ID, 'product_tag' ) );
ob_start();
?>

<?php 
	if( class_exists( 'WCML_WC_MultiCurrency' ) ){
		global $woocommerce_wpml;
		remove_action( 'woocommerce_product_meta_start', array( $woocommerce_wpml->multi_currency , 'currency_switcher') );	
	}
	
	do_action( 'woocommerce_product_meta_start' ); 
?>
    
<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>
<div class="inline">
    <label class="sku_wrapper"><?php _e( 'SKU:', 'media_center' ); ?></label>
    <span class="sku" itemprop="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : __( 'N/A', 'media_center' ); ?></span>
</div><!-- /.inline -->
    <?php if( $cat_count > 1 ): ?>
<span class="seperator">/</span>
    <?php endif; ?>
<?php endif; ?>

<?php if( $cat_count > 1 ): ?>
<div class="inline">
    <?php echo $product->get_categories( ', ', '<label class="posted_in">' . _n( 'Category:', 'Categories:', $cat_count, 'media_center' ) . '</label>&nbsp;<span>', '</span>' ); ?>
</div><!-- /.inline -->
    <?php if( $tag_count > 1 ): ?>
<span class="seperator">/</span>
    <?php endif; ?>
<?php endif; ?>

<?php if( $tag_count > 1 ): ?>

<div class="inline">
    <?php echo $product->get_tags( ', ', '<label class="tagged_as">' . _n( 'Tag:', 'Tags:', $tag_count, 'media_center' ) . '</label> <span>', '</span>' ); ?>
</div><!-- /.inline -->
<?php endif; ?>

<?php do_action( 'woocommerce_product_meta_end' ); ?>

<?php
$meta_row = ob_get_contents();
ob_end_clean();
$meta_row = trim( $meta_row );
?>

<?php if (! empty($meta_row) ) : ?>
<div class="meta-row product_meta">
	<?php echo $meta_row; ?>
</div>
<?php endif; ?>