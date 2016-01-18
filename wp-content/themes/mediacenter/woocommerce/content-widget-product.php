<?php global $product, $media_center_theme_options; ?>
<li class="media">
    <a class="thumb-holder" href="<?php echo esc_url( get_permalink( $product->id ) ); ?>">
        <?php 
            if( $media_center_theme_options['lazy_loading'] ){
                echo get_widget_product_lazy_load_image( $product, 'product-thumbnail' );
            }else{
                echo $product->get_image( 'product-thumbnail' );
            }
        ?>
    </a>
    <div class="media-body">
        <h4 class="media-heading"><a href="<?php echo esc_url( get_permalink( $product->id ) ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>"><?php echo $product->get_title(); ?></a></h4>
        <?php if ( ! empty( $show_rating ) ) echo $product->get_rating_html(); ?>
        <div class="price">
            <?php echo $product->get_price_html(); ?>
        </div>
    </div>
</li>