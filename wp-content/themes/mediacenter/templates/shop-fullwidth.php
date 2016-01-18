<?php 
global $mc_page_metabox;
$post_classes = '';
$page_title = $mc_page_metabox->get_the_value( 'page_title' ) ;
$page_subtitle = $mc_page_metabox->get_the_value( 'page_subtitle' ) ;

if( $mc_page_metabox->get_the_value( 'container_unwrap' ) !== '1' ){
	$post_classes = 'container';
}

if( empty( $page_title ) ){
	$page_title = get_the_title();
}

$should_hide_title = media_center_should_hide_title();

?>
<div id="post-<?php the_ID(); ?>" <?php post_class( $post_classes ); ?>>

	<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
	<div class="section section-page-title inner-xs">
		<div class="page-header">
			<h2 class="page-title"><?php woocommerce_page_title(); ?></h2>
		</div>
	</div>
	<?php endif; ?>

	<?php do_action( 'woocommerce_archive_description' ); ?>
	
	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
	?>
	<div class="container inner-xs">
		<div class="row">
			<div class="col-xs-12 col-sm-12">

				<div class="grid-list-products">
				<?php 

				global $custom_query, $wp_query, $woocommerce_loop ;

				extract( media_center_get_grid_list_tab_pane_classes() );
				
				$woocommerce_loop['screen_width'] = '100';

				// Check for a custom query, typically sent by a shortcode
				$the_query = (!$custom_query) ? $wp_query : $custom_query;

				$_wp_query = $wp_query ;
				$wp_query = $the_query ;

				if ( $the_query->have_posts() ) : 

					woocommerce_product_subcategories( array( 'before' => '<ul class="inner-bottom-xs product-subcategories-list row list-unstyled">', 'after' => '</ul>' ) );

					/**
					 * woocommerce_before_shop_loop hook
					 *
					 * @hooked media_center_control_bar - 10
					 */
					do_action( 'woocommerce_before_shop_loop' );

				?>
				<div class="tab-content">
					
					<div id="grid-view" class="tab-pane <?php echo $grid_tab_pane_class; ?>">

					<?php
					
						// Show Shop Products
						// ------------------------------------------------------------------

						woocommerce_product_loop_start();

						// Loop through the results and print each. 
						while ( $the_query->have_posts() ) : $the_query->the_post(); 
							
							wc_get_template_part( 'content', 'product' );

						endwhile;

						woocommerce_product_loop_end();
					?>

					</div><!-- /#grid-view .tab-pane -->

					<div id="list-view" class="tab-pane <?php echo $list_tab_pane_class; ?>">

						<div class="products-list">
						
						<?php

							while ( $the_query->have_posts() ) : $the_query->the_post(); 
							
								wc_get_template_part( 'content', 'product-list-view' );

							endwhile;
						?>

						</div><!-- /.products-list -->

					</div><!-- /#list-view .tab-pane -->

				</div><!-- /.tab-content -->

				<?php
				/**
				 * woocommerce_after_shop_loop hook
				 *
				 * @hooked woocommerce_pagination - 10
				 */
					do_action( 'woocommerce_after_shop_loop' );

				elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) :

					wc_get_template( 'loop/no-products-found.php' );

				endif;
				?>
				</div><!-- /.grid-list-products -->

				<?php $wp_query = $_wp_query ; ?>
			</div><!-- /.col-sm-9 -->
		</div><!-- /.row -->
	</div><!-- /.container -->
	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>
</div><!-- #post-<?php the_ID(); ?> -->