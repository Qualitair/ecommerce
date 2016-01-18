<?php
/**
 * Top Cart
 *
 * @author      Ibrahim Ibn Dawood
 * @package     Framework/Templates
 * @version     1.0.6
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $media_center_theme_options, $yith_wcwl, $yith_woocompare;
?>
<div class="top-cart-row-container">
    <div class="wishlist-compare-holder">
        
        <?php if (class_exists('YITH_WCWL')) : ?>
        <div class="wishlist">
            <a id="yith-wishlist-link" href="<?php echo esc_url( media_center_get_wishlist_url() ); ?>">
                <i class="fa fa-heart"></i> 
                <?php echo __( 'Wishlist', 'media_center' ); ?> 
                <span id="top-cart-wishlist-count" class="value">(<?php echo yith_wcwl_count_products(); ?>)</span> 
            </a>
        </div><!-- /.wishlist -->
        <?php endif; ?>

        <?php if (class_exists('YITH_Woocompare')) : ?>
        <div class="compare">
            <a id="yith-woocompare-link" href="<?php echo esc_url( media_center_get_compare_url() ); ?>" class="compare">
                <i class="fa fa-exchange"></i> 
                <?php echo __( 'Compare', 'media_center' ); ?>
                <span id="top-cart-compare-count" class="value">(<?php echo count( $yith_woocompare->obj->products_list ); ?>)</span>
            </a>
        </div><!-- /.compare -->
        <?php endif; ?>
    </div><!-- /.wishlist-compare-holder -->

    <?php 
        $catalog_mode = false ;
        if( isset( $media_center_theme_options['catalog_mode'] ) && $media_center_theme_options['catalog_mode'] == 1 ){
            $catalog_mode = true;
        }
        if ( class_exists( 'WooCommerce' ) && ! $catalog_mode ) : ?>
    <!-- ============================================================= SHOPPING CART DROPDOWN ============================================================= -->
    <div class="top-cart-holder dropdown animate-dropdown">
        <div class="basket widget_shopping_cart_content">
            <?php woocommerce_mini_cart();?>
        </div><!-- /.basket -->
    </div><!-- /.top-cart-holder -->
    <!-- ============================================================= SHOPPING CART DROPDOWN : END ============================================================= -->
    <?php endif; ?>
</div><!-- /.top-cart-row-container -->