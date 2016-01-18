<?php
global $grid_products; ?>
<div class="col-xs-12 col-sm-4 no-margin product">
    <div class="product-item-wrap">
        <div class="product-item">
            <div class="product-item-inner">
                <?php woocommerce_template_loop_product_thumbnail(); ?>
                <div class="product-body">
                    <h4 class="product-title"><a href="<?php the_permalink(); ?>"><?php echo apply_filters( 'product_title_one_product_6_1', get_the_title() ); ?></a></h4>
                    <?php woocommerce_show_brand(); ?>
                </div>
                <?php wc_get_template( 'loop/price.php' ); ?>
                <?php wc_get_template( 'loop/hover-area.php' ); ?>
            </div><!-- /.product-item-inner -->
        </div><!-- /.product-item -->
    </div><!-- /.product-item-wrap -->
</div><!-- /.product-item-holder -->