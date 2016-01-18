<?php
/**
 * The template for displaying product list view content within loops.
 *
 *
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop, $yith_wcwl, $media_center_theme_options;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
    $woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
    $woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 3 );

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
    return;

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array( 'product-item', 'product-list-item-wrap' );
?>
<div <?php post_class( $classes ); ?>>
    <?php wc_get_template( 'loop/sale-flash.php' ); ?>

    <div class="row product-list-view">
        <div class="no-margin col-xs-12 col-sm-4 image-holder">
            <?php woocommerce_template_loop_product_thumbnail( 'list-view' ); ?>
        </div><!-- /.image-holder -->
        
        <div class="no-margin col-xs-12 col-sm-5 body-holder">
            <div class="body">
                <!--<div class="label-discount clear"></div>-->
                <div class="title">
                    <div class="pull-right">
                        <div class="star-holder clearfix">
                        <?php wc_get_template( 'loop/rating.php' ) ; ?>
                        </div>
                    </div>
                    <h4 class="product-title"><a href="<?php the_permalink(); ?>"><?php echo apply_filters( 'product_title_list_view', get_the_title() ); ?></a></h4>
                </div>
                <?php woocommerce_show_brand(); ?>
                <div class="excerpt">
                    <?php 
                        $post_excerpt = wp_strip_all_tags( get_the_excerpt() );
                        echo apply_filters( 'woocommerce_short_description_list_view', $post_excerpt );?>
                </div>
                <?php if( class_exists('YITH_Woocompare') ): ?>
                <div class="addto-compare btn-action-link">
                    <?php echo media_center_add_to_compare_button( $product->id, __( 'Add to Compare', 'media_center' ) ); ?>
                </div>
                <?php endif; ?>
            </div>
        </div><!-- /.body-holder -->

        <div class="no-margin col-xs-12 col-sm-3 price-area">
            <div class="right-clmn">
                <?php if ( $price_html = $product->get_price_html() ) : ?>
                <div class="product-price-container product-list-view-price">
                <?php echo $price_html; ?>
                </div>
                <?php endif; ?>
                <?php wc_get_template( 'loop/availability.php' ); ?>
                <?php wc_get_template( 'loop/add-to-cart.php' ); ?>
                <?php if ( class_exists('YITH_WCWL') ) : ?>
                <div class="addto-wishlist btn-action-link">
                    <?php echo do_shortcode('[mc_yith_wcwl_add_to_wishlist]');?>
                </div>
                <?php endif; ?>
            </div>
        </div><!-- /.price-area -->
    </div><!-- /.row -->
</div>