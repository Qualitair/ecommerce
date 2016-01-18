<?php
/**
 * Define theme shortcodes
 *
 * @package BigBoom
 */
class BigBoom_Shortcodes {

	/**
	 * Store variables for js
	 *
	 * @var array
	 */
	public $l10n = array();

	/**
	 * Construction
	 *
	 * @return bigboom_Shortcodes
	 */
	function __construct() {
		$shortcodes = array(
			'products_carousel',
			'products_carousel_2',
			'products_carousel_3',
			'images_carousel',
			'posts_carousel',
			'hot_deal_products',
			'recent_products',
			'featured_products',
			'sale_products',
			'best_selling_products',
			'top_rated_products',
			'icon_box'
		);

		foreach ( $shortcodes as $shortcode ) {
			add_shortcode( $shortcode, array( $this, $shortcode ) );
		}
		add_action( 'wp_footer', array( $this, 'footer' ) );
	}

	/**
	 * Load custom js in footer
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function footer() {
		wp_localize_script( 'bigboom', 'bigboomShortCode', $this->l10n );
	}
	/**
	 * Shortcode year
	 * Display current year
	 *
	 * @since 1.0
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function year( $atts, $content = null ) {
		return date( 'Y' );
	}

	/**
	 * Shortcode to display a link back to the site.
	 *
	 * @since 1.0
	 * @param array $atts Shortcode attributes
	 * @return string
	 */
	function site_link( $atts ) {
		$name = get_bloginfo( 'name' );
		return '<a class="site-link" href="' . esc_url( get_home_url() ) . '" title="' . esc_attr( $name ) . '" rel="home">' . $name . '</a>';
	}

	/**
	 * Products Slider shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function products_carousel( $atts ) {
		$atts = shortcode_atts( array(
			'products'        => 'recent',
			'title'           => '',
			'per_page'        => '',
			'columns'         => '',
			'views'           => '',
			'spacing'         => '30',
			'orderby'         => '',
			'order'           => '',
			'auto_play'       => '',
			'hide_navigation' => '',
			'class_name'      => '',
		), $atts );

		$output = '';
		if( $atts['title'] ) {
			$output .= sprintf( '<h2 class="section-heading">%s</h2>', $atts['title'] );
		}
		else {
			$atts['class_name'] .= ' no-title';
		}
		if( $atts['hide_navigation'] ) {
			$atts['class_name'] .= ' no-owl-controls';
		}

		if( $atts['spacing'] == 0 ) {
			$atts['class_name'] .= ' no-spacing';
		}

		if( $atts['views'] < $atts['columns'] ) {
			$atts['columns'] = $atts['views'];
		}

		if ( $atts['products'] == 'recent' ) {
			$output .= $this->recent_products( $atts );
		}
		elseif ( $atts['products'] == 'featured' ) {
			$output .= $this->featured_products( $atts );
		}
		elseif ( $atts['products'] == 'best_selling' ) {
			$output .= $this->best_selling_products( $atts );
		}
		elseif ( $atts['products'] == 'top_rated' ) {
			$output .= $this->top_rated_products( $atts );
		}
		elseif ( $atts['products'] == 'sale' ) {
			$output .= $this->sale_products( $atts );
		}

		$spacing = intval( $atts['spacing'] );
		if( $spacing ) $spacing = $spacing / 2;
		else $spacing = 0;

		$id = uniqid( 'products-carousel-' );
		$this->l10n['productsCarousel'][$id] = array(
			'number'     => $atts['columns'],
			'spacing'    => $spacing,
			'autoplay'   => $atts['auto_play'] ? $atts['auto_play'] : 'false',
			'navigation' => $atts['hide_navigation'] ? $atts['hide_navigation'] : 'true'
		);

		return sprintf(
			'<div class="products-carousel section-products woocommerce %s" id="%s">%s</div>',
			esc_attr( $atts['class_name'] ),
			esc_attr( $id ),
			$output
		);
	}


	/**
	 * Product carousel 2 shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function products_carousel_2( $atts, $content ) {
		$atts = shortcode_atts( array(
			'title'           => '',
			'per_page'        => '12',
			'taxonomies'      => '',
			'columns'         => '',
			'orderby'        => '',
			'order'           => '',
			'number'          => '2',
			'auto_play'       => '',
			'hide_navigation' => '',
			'class_name'      => '',
		), $atts );

		$output = array();
		$number = intval( $atts['number'] );
		$number = $number ? $number : '2';
		$id = uniqid( 'products-carousel-' );
		$this->l10n['productsCarousel'][$id] = array(
			'number'     => $number,
			'autoplay'   => $atts['auto_play'] ? $atts['auto_play'] : 'false',
			'navigation' => $atts['hide_navigation'] ? $atts['hide_navigation'] : 'true'
		);

		$meta_query = WC()->query->get_meta_query();

		$args = array(
			'posts_per_page'	=> $atts['per_page'],
			'orderby' 			=> $atts['orderby'],
			'order' 			=> $atts['order'],
			'post_status' 		=> 'publish',
			'post_type' 		=> 'product',
			'meta_query' 		=> $meta_query,
		);

		if ( ! empty( $atts['taxonomies'] ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'id',
					'terms'    => $atts['taxonomies'],
				),
			);
		}

		ob_start();

		$this->bigboom_wc_get_products( $args, $atts );

		$heading = '';
		if( $atts['title'] ) {
			$heading = sprintf( '<h2 class="section-heading">%s</h2>', $atts['title'] );
		}
		else {
			$atts['class_name'] .= ' no-title';
		}
		if( $atts['hide_navigation'] ) {
			$atts['class_name'] .= ' no-owl-controls';
		}

		return sprintf(
			'<div class="products-carousel-2 section-products woocommerce %s" id="%s">%s %s</div>',
			esc_attr( $atts['class_name'] ),
			esc_attr( $id ),
			$heading,
			ob_get_clean()
		);

	}

	/**
	 * Products Slider shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function products_carousel_3( $atts ) {
		$atts = shortcode_atts( array(
			'products'        => 'recent',
			'title'           => '',
			'per_page'        => '',
			'columns'         => '1',
			'views'           => '',
			'orderby'         => '',
			'order'           => '',
			'auto_play'       => '',
			'hide_navigation' => '',
			'class_name'      => '',
		), $atts );

		$output = '';
		if( $atts['title'] ) {
			$output .= sprintf( '<h2 class="section-heading">%s</h2>', $atts['title'] );
		}
		else {
			$atts['class_name'] .= ' no-title';
		}
		if( $atts['hide_navigation'] ) {
			$atts['class_name'] .= ' no-owl-controls';
		}

		if( $atts['views'] < $atts['columns'] ) {
			$atts['columns'] = $atts['views'];
		}

		if ( $atts['products'] == 'recent' ) {
			$output .= $this->recent_products( $atts );
		}
		elseif ( $atts['products'] == 'featured' ) {
			$output .= $this->featured_products( $atts );
		}
		elseif ( $atts['products'] == 'best_selling' ) {
			$output .= $this->best_selling_products( $atts );
		}
		elseif ( $atts['products'] == 'top_rated' ) {
			$output .= $this->top_rated_products( $atts );
		}
		elseif ( $atts['products'] == 'sale' ) {
			$output .= $this->sale_products( $atts );
		}

		$id = uniqid( 'products-carousel-' );
		$this->l10n['productsCarousel'][$id] = array(
			'number'     => $atts['columns'],
			'autoplay'   => $atts['auto_play'] ? $atts['auto_play'] : 'false',
			'navigation' => $atts['hide_navigation'] ? $atts['hide_navigation'] : 'true'
		);

		return sprintf(
			'<div class="products-carousel-3 section-products woocommerce %s" id="%s">%s</div>',
			esc_attr( $atts['class_name'] ),
			esc_attr( $id ),
			$output
		);
	}

	/**
	 * Hot deal shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function hot_deal_products( $atts ) {
		$atts = shortcode_atts( array(
			'title'      =>'',
			'per_page'   => '12',
			'columns'    => '4',
			'orderby'    => 'date',
			'order'      => 'desc',
			'class_name' => '',
		), $atts );

		// Get products on sale
		$product_ids_on_sale = wc_get_product_ids_on_sale();
		$meta_query = WC()->query->get_meta_query();
		$meta_query[] = array(
			'key'     => '_sale_price_dates_to',
			'value'   => strtotime( 'now' ),
			'compare' => '>'
		);
		$args = array(
			'posts_per_page'	=> $atts['per_page'],
			'orderby' 			=> $atts['orderby'],
			'order' 			=> $atts['order'],
			'no_found_rows' 	=> 1,
			'post_status' 		=> 'publish',
			'post_type' 		=> 'product',
			'meta_query' 		=> $meta_query,
			'post__in'			=> array_merge( array( 0 ), $product_ids_on_sale )
		);

		ob_start();

		$this->bigboom_wc_get_products( $args, $atts );

		$heading = '';
		if( $atts['title'] ) {
			$heading = sprintf( '<h2 class="section-heading">%s</h2>', $atts['title'] );
		}
		else {
			$atts['class_name'] .= ' no-title';
		}

		return sprintf(
			'<div class="hot-deal-products section-products woocommerce %s" id="hot-deal-products">%s %s</div>',
			esc_attr( $atts['class_name'] ),
			$heading,
			ob_get_clean()
		);
	}

	/**
	 * Images carousel shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function images_carousel( $atts, $content ) {
		$atts = shortcode_atts( array(
			'title'               => '',
			'images'              => '',
			'image_size'          => 'thumbnail',
			'custom_links'        => '',
			'custom_links_target' => '',
			'number'              => '6',
			'auto_play'           => '',
			'hide_navigation'     => '',
			'class_name'          => '',
		), $atts );

		$output = array();
		$number = intval( $atts['number'] );
		$number = $number ? $number : '4';
		$custom_links = $atts['custom_links'] ? explode( "\n", $atts['custom_links'] ) : '';

		$images = $atts['images'] ? explode( ',', $atts['images'] ) : '';

		$id = uniqid( 'images-carousel-' );
		$this->l10n['imagesCarousel'][$id] = array(
			'number'     => $number,
			'autoplay'   => $atts['auto_play'] ? $atts['auto_play'] : 'false',
			'navigation' => $atts['hide_navigation'] ? $atts['hide_navigation'] : 'true'
		);
		if( $images ) {
			$i= 0;
			foreach ( $images as $attachment_id ) {
				$image = wp_get_attachment_image_src( $attachment_id, $atts['image_size'] );
				if( $image ) {
					$link = '';
					if( $custom_links && isset( $custom_links[$i] ) ) {
						$link = 'href="' . esc_url( $custom_links[$i] ) . '"';
					}
					$output[] =	sprintf( '<div class="item"><a %s target="%s"><img alt="%s" src="%s"></a></div>',
						$link,
						esc_attr( $atts['custom_links_target'] ),
						esc_attr( $attachment_id ),
						esc_url( $image[0] )
					);
				}
				$i++;
			}
		}

		$heading = '';
		if( $atts['title'] ) {
			$heading = sprintf( '<h2 class="section-heading">%s</h2>', $atts['title'] );
		}
		else {
			$atts['class_name'] .= ' no-title';
		}
		if( $atts['hide_navigation'] ) {
			$atts['class_name'] .= ' no-owl-controls';
		}

		return sprintf( '<div id="%s" class="bb-images-owl-carousel %s">%s<div class="bb-owl-list">%s</div></div>',
			esc_attr( $id ),
			esc_attr( $atts['class_name'] ),
			$heading,
			implode( '', $output )
		);
	}

	/**
	 * Posts carousel shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function posts_carousel( $atts, $content ) {
		$atts = shortcode_atts( array(
			'title'           => '',
			'total'           => '12',
			'categories'      => '',
			'order_by'        => '',
			'order'           => '',
			'excerpt_length'  => '',
			'hide_read_more'  => '',
			'read_more_text'  => __( 'Read More', 'bigboom' ),
			'number'          => '3',
			'auto_play'       => '',
			'hide_navigation' => '',
			'class_name'      => '',
		), $atts );

		$output = array();
		$number = intval( $atts['number'] );
		$number = $number ? $number : '4';

		$id = uniqid( 'posts-carousel-' );
		$this->l10n['postsCarousel'][$id] = array(
			'number'     => $number,
			'autoplay'   => $atts['auto_play'] ? $atts['auto_play'] : 'false',
			'navigation' => $atts['hide_navigation'] ? $atts['hide_navigation'] : 'true'
		);

		$query_args = array(
			'orderby'             => $atts['order_by'],
			'order'               => $atts['order'],
			'posts_per_page'      => $atts['total'],
			'post_type'           => 'post',
			'ignore_sticky_posts' => true,
		);
		if ( ! empty( $atts['categories'] ) ) {
			$query_args['category__in'] = $atts['categories'];
		}
		$query = new WP_Query( $query_args );

		while ( $query->have_posts() ) : $query->the_post();

			$src = bigboom_get_image( array(
				'size'   => 'blog-thumb-tiny',
				'format' => 'src',
				'echo'   => false,
			) );

			$class_thumb = 'no-thumb';
			$article = array();
			if( $src ) {

				$class_thumb = '';

				$article[] = sprintf(
					'<a class="post-thumb" href="%s" title="%s"><img src="%s" alt="%s"></a>',
					esc_url( get_permalink() ),
					the_title_attribute( 'echo=0' ),
					esc_url( $src ),
					the_title_attribute( 'echo=0' )
				);
			}

			$article[] = sprintf( '<div class="post-text"><a class="post-title" href="%s" title="%s" rel="bookmark">%s</a>',
				esc_url( get_permalink() ),
				the_title_attribute( 'echo=0' ),
				get_the_title()
			);

			$article[] = bigboom_content_limit( get_the_excerpt(), intval( $atts['excerpt_length'] ), '', false );
			if ( ! $atts['hide_read_more'] ) {
				$article[] = sprintf( '<a class="read-more" href="%s" title="%s" rel="bookmark">%s<i class="fa fa-arrow-circle-right"></i></a>',
					esc_url( get_permalink() ),
					the_title_attribute( 'echo=0' ),
					$atts['read_more_text']
				);
			}

			$output[] = sprintf( '<div class="bb-post %s">%s</div></div>',
				esc_attr( $class_thumb ),
				implode( '', $article )
			);
		endwhile;
		wp_reset_postdata();

		$heading = '';
		if( $atts['title'] ) {
			$heading = sprintf( '<h2 class="section-heading">%s</h2>', $atts['title'] );
		}
		else {
			$atts['class_name'] .= ' no-title';
		}
		if( $atts['hide_navigation'] ) {
			$atts['class_name'] .= ' no-owl-controls';
		}

		return sprintf( '<div id="%s" class="bb-posts-owl-carousel %s">%s<div class="bb-owl-list">%s</div></div>',
			esc_attr( $id ),
			esc_attr( $atts['class_name'] ),
			$heading,
			implode( '', $output )
		);
	}

	/**
	 * Recent Products shortcode
	 *
	 * @param array $atts
	 * @return string
	 */
	function recent_products( $atts ) {
		global $woocommerce_loop;

		$atts = shortcode_atts( array(
			'per_page' 	=> '12',
			'columns' 	=> '4',
			'orderby' 	=> 'date',
			'order' 	=> 'desc',
			'views'		=> '4'
		), $atts );

		$meta_query = WC()->query->get_meta_query();

		$args = array(
			'post_type'				=> 'product',
			'post_status'			=> 'publish',
			'ignore_sticky_posts'	=> 1,
			'posts_per_page' 		=> $atts['per_page'],
			'orderby' 				=> $atts['orderby'],
			'order' 				=> $atts['order'],
			'meta_query' 			=> $meta_query
		);

		ob_start();

		$this->bigboom_wc_get_products( $args, $atts );

		return ob_get_clean();
	}

	/**
	 * Output featured products
	 *
	 * @param array $atts
	 * @return string
	 */
	function featured_products( $atts ) {
		global $woocommerce_loop;

		$atts = shortcode_atts( array(
			'per_page' => '12',
			'columns'  => '4',
			'orderby'  => 'date',
			'order'    => 'desc',
			'views'		=> '4'
		), $atts );

		$meta_query   = WC()->query->get_meta_query();
		$meta_query[] = array(
			'key'   => '_featured',
			'value' => 'yes'
		);

		$args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'posts_per_page'      => $atts['per_page'],
			'orderby'             => $atts['orderby'],
			'order'               => $atts['order'],
			'meta_query'          => $meta_query
		);

		ob_start();

		$this->bigboom_wc_get_products( $args, $atts );

		return ob_get_clean();
	}

	/**
	 * List best selling products on sale
	 *
	 * @param array $atts
	 * @return string
	 */
	function best_selling_products( $atts ) {
		global $woocommerce_loop;

		$atts = shortcode_atts( array(
			'per_page' => '12',
			'columns'  => '4',
			'views'		=> '4'
		), $atts );

		$meta_query = WC()->query->get_meta_query();

		$args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'posts_per_page'      => $atts['per_page'],
			'meta_key'            => 'total_sales',
			'orderby'             => 'meta_value_num',
			'meta_query'          => $meta_query
		);

		ob_start();

		$this->bigboom_wc_get_products( $args, $atts );

		return ob_get_clean();
	}

	/**
	 * List top rated products on sale
	 *
	 * @param array $atts
	 * @return string
	 */
	function top_rated_products( $atts ) {
		global $woocommerce_loop;

		$atts = shortcode_atts( array(
			'per_page' => '12',
			'columns'  => '4',
			'orderby'  => 'title',
			'order'    => 'asc',
			'views'		=> '4'
		), $atts );

		$meta_query = WC()->query->get_meta_query();

		$args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'orderby'             => $atts['orderby'],
			'order'               => $atts['order'],
			'posts_per_page'      => $atts['per_page'],
			'meta_query'          => $meta_query
		);

		ob_start();

		add_filter( 'posts_clauses', array( 'WC_Shortcodes', 'order_by_rating_post_clauses' ) );

		$this->bigboom_wc_get_products( $args, $atts );

		remove_filter( 'posts_clauses', array( 'WC_Shortcodes', 'order_by_rating_post_clauses' ) );

		return ob_get_clean();
	}

	/**
	 * List all products on sale
	 *
	 * @param array $atts
	 * @return string
	 */
	function sale_products( $atts ) {
		global $woocommerce_loop;

		$atts = shortcode_atts( array(
			'per_page' => '12',
			'columns'  => '4',
			'orderby'  => 'title',
			'order'    => 'asc',
			'views'		=> '4'
		), $atts );

		// Get products on sale
		$product_ids_on_sale = wc_get_product_ids_on_sale();

		$meta_query = WC()->query->get_meta_query();

		$args = array(
			'posts_per_page'	=> $atts['per_page'],
			'orderby' 			=> $atts['orderby'],
			'order' 			=> $atts['order'],
			'no_found_rows' 	=> 1,
			'post_status' 		=> 'publish',
			'post_type' 		=> 'product',
			'meta_query' 		=> $meta_query,
			'post__in'			=> array_merge( array( 0 ), $product_ids_on_sale )
		);

		ob_start();

		$this->bigboom_wc_get_products( $args, $atts );

		return ob_get_clean();
	}

	/**
	 * Display icon box shortcode
	 *
	 * @param  array $atts
	 * @param  string $content
	 * @return string
	 */
	function icon_box( $atts, $content ) {
		$title = $subtitle = $icon = $icon_position = $icon_class = $read_more_text = $link = '';
		extract( shortcode_atts( array(
			'title'          => '',
			'subtitle'       => '',
			'icon'           => '',
			'icon_position'  => '',
			'read_more_text' => '',
			'link'           => '',
			'icon_class'     => ''
		), $atts ) );

		$title = '<h4 class="icon-title">' . $title . '</h4>';
		if( $subtitle ) {
			$title .= '<span class="icon-subtitle">' . $subtitle . '</span>';
		}

		if( $content ) {
			$content = '<div class="box-content">' . do_shortcode( $content ) . '</div>';
		}

		if( $read_more_text ) {
			$content .= '<a class="icon-read-more" href="' . $link . '">' . $read_more_text . '<i class="fa fa-arrow-circle-right"></i></a>';
		}

		$icon_class .= ' icon-' . $icon_position;

		return sprintf(
			'<div class="icon-box %s">
				<div class="box-icon"><i class="%s b-icon"></i>%s</div>%s
			</div>',
			esc_attr( $icon_class ),
			esc_attr( $icon ),
			$title,
			$content
		);
	}

	/**
	 * Get products
	 *
	 * @since  1.0
	 *
	 * @param array $args
	 * @param array $atts
	 */
	function bigboom_wc_get_products( $args, $atts ) {

		$products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $args, $atts ) );

		$woocommerce_loop['columns'] = $atts['columns'];

		if ( $products->have_posts() ) : ?>

			<?php
			$lines = 0;
			if( ! empty( $atts['views'] ) && ! empty( $atts['columns'] ) ) {
			 	$lines = ceil( $atts['views'] / $atts['columns'] );
			}
			$index = 0;
			?>
			<?php woocommerce_product_loop_start(); ?>

				<?php while ( $products->have_posts() ) : $products->the_post(); ?>
					<?php if( $lines <= 1 || $index % $lines == 0 ) : ?>
						<li class="<?php $this->bigboom_wc_content_columns( $atts['columns'] ); ?>">
							<ul class="bb-products">
					<?php endif; ?>
						<?php wc_get_template_part( 'content', 'product' ); ?>
					<?php if( $lines <= 1 || $index % $lines == $lines - 1 ) : ?>
							</ul>
						</li>
					<?php endif; ?>
					<?php $index++; ?>
				<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>

		<?php endif;

		wp_reset_postdata();
	}

	/**
	 * Get Bootstrap column classes for products
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function bigboom_wc_content_columns( $columns ) {

		$col = array( 'col-xs-12' );
		if( !empty( $columns ) ) {
			if ( 5 == $columns ) {
				$col[] = 'col-md-5ths col-sm-4';
			} elseif ( 2 == $columns || 3 == $columns || 4 == $columns || 6 == $columns ) {
				$col[] = 'col-sm-' . floor( 12 / $columns );
			}
		}
		$col[] = 'col-product';

		echo implode( ' ', $col );
	}
}
