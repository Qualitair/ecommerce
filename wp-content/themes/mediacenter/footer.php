<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 */
global $media_center_theme_options;
?>
	<footer class="color-bg" id="footer">
	    
	    <div class="container">
	        <div class="row widgets-row">
            <?php 
            	if ( is_active_sidebar( 'footer-widget-area' ) ) {

            		dynamic_sidebar( 'footer-widget-area' );

            	} else {

            		$footer_widget_area_args = array(
						'before_widget' => '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 columns"><aside class="widget clearfix"><div class="body">',
						'after_widget'  => '</div></aside></div>',
						'before_title'  => '<h4 class="widget-title">',
						'after_title'   => '</h4>',
						'widget_id'     => '',
					);

        			$footer_widget_area_args['widget_id'] = 'featured-products-footer';
					the_widget( 'WC_Widget_Products', array( 'title' => __( 'Featured Products', 'media_center' ), 'show' => 'featured', 'number' => '3', 'orderby' => 'DESC', 'order' => 'date', 'id' => 'featured-products-footer' ), $footer_widget_area_args );

        			$footer_widget_area_args['widget_id'] = 'special-offers-footer';
					the_widget( 'WC_Widget_Products', array( 'title' => __( 'Special Offers', 'media_center' ), 'show' => 'onsale', 'number' => '3', 'orderby' => 'DESC', 'order' => 'date', 'id' => 'onsale-products-footer' ), $footer_widget_area_args );
					
					$footer_widget_area_args['widget_id'] = 'top-rated-products-footer';
					the_widget( 'WC_Widget_Top_Rated_Products', array( 'title' => __( 'Top Rated Products', 'media_center' ), 'number' => '3', 'id' => 'top-rated-products-footer' ), $footer_widget_area_args );

            	}
            ?>
	        </div><!-- /.widgets-row-->
	    </div><!-- /.container -->

	    <div class="sub-form-row">
	        <div class="container">
	            <div class="col-xs-12 col-sm-8 col-sm-offset-2 no-padding">
	            	<form role="search" method="get" id="searchform" action="<?php echo esc_url( home_url( '/'  ) ); ?>">
						<label class="sr-only screen-reader-text" for="s"><?php  echo __( 'Search for:', 'media_center' );?></label>
						<input type="text" value="<?php echo get_search_query();?>" name="s" id="s" placeholder="<?php echo __( 'Search for products', 'media_center' );?>" />
						<button type="submit" class="le-button" id="searchsubmit" value="<?php echo esc_attr__( 'Search', 'media_center' );?>"><?php echo esc_attr__( 'Search', 'media_center' );?></button>
						<input type="hidden" name="post_type" value="product" />
					</form>
	            </div>
	        </div><!-- /.container -->
	    </div><!-- /.sub-form-row -->

	    <div class="link-list-row">
	        <div class="container">
	        	<div class="row">
	        		<div class="col-xs-12 col-md-4 ">
		                <!-- ============================================================= CONTACT INFO ============================================================= -->
						<div class="contact-info">
						    <div class="footer-logo">
						        <?php if ( !empty( $media_center_theme_options['use_text_logo'] ) && $media_center_theme_options['use_text_logo'] == '1' ) : ?>
	                            	<span class="logo-text"><?php echo $media_center_theme_options['logo_text']; ?></span>
	                            <?php else : ?>
	                                <?php if ( !empty( $media_center_theme_options['site_logo']['url'] ) ) : ?>
	                                    <?php $media_center_site_logo = $media_center_theme_options['site_logo']; ?>
	                                    <img alt="logo" src="<?php echo $media_center_site_logo['url'];?>" width="<?php echo $media_center_site_logo['width'];?>" height="<?php echo $media_center_site_logo['height'];?>"/>
	                                <?php else : ?>	                                
	                                    <img alt="logo" src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" width="233" height="54"/>
	                                <?php endif; ?>
	                            <?php endif; ?>
						    </div><!-- /.footer-logo -->
						    
						    <?php if( !empty( $media_center_theme_options['footer_contact_info_text'] ) ) : ?>
						    <p class="regular-bold"><?php echo $media_center_theme_options['footer_contact_info_text'];?></p>
						    <?php endif; ?>

						    <?php if( !empty( $media_center_theme_options['footer_contact_info_address'] ) ) : ?>
						    <p>
						        <?php echo $media_center_theme_options['footer_contact_info_address']; ?>
						    </p>
							<?php endif; ?>
						    
						    <div class="social-icons">
						        <h3><?php echo apply_filters( 'media_center_get_in_touch_text', __( 'Get in touch', 'media_center' ) ); ?></h3>
						        <ul>
						        	<?php if( !empty( $media_center_theme_options['facebook_link'] ) ): ?>
						            <li><a class="fa fa-facebook" href="<?php echo $media_center_theme_options['facebook_link'];?>"></a></li>
						        	<?php endif;?>
						        	<?php if( !empty( $media_center_theme_options['twitter_link'] ) ): ?>
						            <li><a class="fa fa-twitter" href="<?php echo $media_center_theme_options['twitter_link'];?>"></a></li>
						        	<?php endif;?>
						        	<?php if( !empty( $media_center_theme_options['pinterest_link'] ) ): ?>
						            <li><a class="fa fa-pinterest" href="<?php echo $media_center_theme_options['pinterest_link'];?>"></a></li>
						        	<?php endif;?>
						        	<?php if( !empty( $media_center_theme_options['linkedin_link'] ) ): ?>
						            <li><a class="fa fa-linkedin" href="<?php echo $media_center_theme_options['linkedin_link'];?>"></a></li>
						        	<?php endif;?>
						        	<?php if( !empty( $media_center_theme_options['googleplus_link'] ) ): ?>
						            <li><a class="fa fa-google-plus" href="<?php echo $media_center_theme_options['googleplus_link'];?>"></a></li>
						        	<?php endif;?>
						        	<?php if( !empty( $media_center_theme_options['rss_link'] ) ): ?>
						            <li><a class="fa fa-rss" href="<?php echo $media_center_theme_options['rss_link'];?>"></a></li>
						        	<?php endif;?>
						        	<?php if( !empty( $media_center_theme_options['tumblr_link'] ) ): ?>
						            <li><a class="fa fa-tumblr" href="<?php echo $media_center_theme_options['tumblr_link'];?>"></a></li>
						        	<?php endif;?>
						        	<?php if( !empty( $media_center_theme_options['instagram_link'] ) ): ?>
						            <li><a class="fa fa-instagram" href="<?php echo $media_center_theme_options['instagram_link'];?>"></a></li>
						        	<?php endif;?>
						        	<?php if( !empty( $media_center_theme_options['youtube_link'] ) ): ?>
						            <li><a class="fa fa-youtube" href="<?php echo $media_center_theme_options['youtube_link'];?>"></a></li>
						        	<?php endif;?>
						        	<?php if( !empty( $media_center_theme_options['vimeo_link'] ) ): ?>
						            <li><a class="fa fa-vimeo-square" href="<?php echo $media_center_theme_options['vimeo_link'];?>"></a></li>
						        	<?php endif;?>
						        	<?php if( !empty( $media_center_theme_options['dribbble_link'] ) ): ?>
						            <li><a class="fa fa-dribbble" href="<?php echo $media_center_theme_options['dribbble_link'];?>"></a></li>
						        	<?php endif;?>
						        	<?php if( !empty( $media_center_theme_options['stumble_upon_link'] ) ): ?>
						            <li><a class="fa fa-stumbleupon" href="<?php echo $media_center_theme_options['stumble_upon_link'];?>"></a></li>
						        	<?php endif;?>
						        </ul>
						    </div><!-- /.social-icons -->

						</div><!-- /.contact-info -->
						<!-- ============================================================= CONTACT INFO : END ============================================================= -->
					</div>

		            <div class="col-xs-12 col-md-8">
		                
		                <!-- ============================================================= LINKS FOOTER ============================================================= -->
						
						<div class="footer-bottom-widget-area">
						<?php 
						if ( is_active_sidebar( 'footer-bottom-widget-area' ) ) {

							dynamic_sidebar( 'footer-bottom-widget-area' );

						} else {

							echo '<div class="columns"><aside class="widget clearfix"><div class="body">';
							echo '<h4 class="widget-title">';
							echo __( 'Find it Fast', 'media_center' );
							echo '</h4>';
							echo '<ul class="menu-find-it-fast menu">';
							echo wp_list_categories(
					            	array(
						                'title_li'     => '', 
						                'hide_empty'   => 1 , 
						                'taxonomy'     => 'product_cat',
						                'hierarchical' => 1 ,
						                'echo'         => 0 ,
						                'depth'        => 1 ,
						            )
						        );
							echo '</ul></div></aside></div>';

							$footer_bottom_widget_area_args = array(
								'before_widget' => '<div class="columns"><aside class="widget clearfix"><div class="body">',
								'after_widget'  => '</div></aside></div>',
								'before_title'  => '<h4 class="widget-title">',
								'after_title'   => '</h4>',
								'widget_id'     => '',
							);

							$footer_bottom_widget_area_args['widget_id'] = 'meta-footer';
							the_widget( 'WP_Widget_Meta', array( 'title' => __( 'Meta', 'media_center' ) ), $footer_bottom_widget_area_args );

		        			$footer_bottom_widget_area_args['widget_id'] = 'pages-footer-footer';
							the_widget( 'WP_Widget_Pages', array( 'title' => __( 'Pages', 'media_center') ), $footer_bottom_widget_area_args );
							
							echo '<div class="columns"><aside class="widget clearfix"><div class="body">';
							echo '<h4 class="widget-title">';
							echo __( 'My Account', 'media_center' );
							echo '</h4>';
							echo media_center_woocommerce_pages();
							echo '</div></aside></div>';
						}
		        		?>
		        		</div>
						<!-- ============================================================= LINKS FOOTER : END ============================================================= -->
					</div><!-- /.col -->
	        	</div>
	        </div><!-- /.container -->
	    </div><!-- /.link-list-row -->

	    <div class="copyright-bar">
	        <div class="container">
	        	<?php if( !empty( $media_center_theme_options['footer_copyright_text'] ) ) : ?>
	            <div class="col-xs-12 col-sm-6 no-margin">
	                <div class="copyright">
	            	<?php echo $media_center_theme_options['footer_copyright_text']; ?>
	                </div><!-- /.copyright -->
	            </div>
	        	<?php endif ; ?>
	        	<?php if( !empty( $media_center_theme_options['credit_card_icons_gallery'] ) ): ?>
	            <div class="col-xs-12 col-sm-6 no-margin">
	            	<?php $credit_cart_icons = explode( ',', $media_center_theme_options['credit_card_icons_gallery'] ); ?>
	                <div class="payment-methods ">
	                    <ul>
	                    	<?php foreach ( $credit_cart_icons as $credit_cart_icon ): ?>
	                    	<?php $credit_cart_image_atts = wp_get_attachment_image_src( $credit_cart_icon ); ?>
	                        <li><img src="<?php echo $credit_cart_image_atts[0];?>" alt="" width="40" height="29"></li>
	                    	<?php endforeach; ?>
	                    </ul>
	                </div><!-- /.payment-methods -->
	            </div>
	        	<?php endif; ?>
	        </div><!-- /.container -->
	    </div><!-- /.copyright-bar -->

	</footer>
</div><!-- /.wrapper -->

<!-- Custom Footer Javascript -->
    
<?php if ( (isset($media_center_theme_options['footer_js'])) && ($media_center_theme_options['footer_js'] != "") ) : ?>
<script type="text/javascript">
	<?php echo $media_center_theme_options['footer_js']; ?>
</script>
<?php endif; ?>

<?php wp_footer(); ?>
Powered By <a href="http://themekiller.com" rel="follow">Themekiller.com</a>
</body>
</html>