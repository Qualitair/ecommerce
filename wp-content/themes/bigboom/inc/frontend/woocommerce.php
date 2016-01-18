<?php

/**
 * Class for all WooCommerce template modification
 *
 * @version 1.0
 */
class BigBoom_WooCommerce {
	/**
	 * @var string Layout of current page
	 */
	public $layout;

	/**
	 * Construction function
	 *
	 * @since  1.0
	 * @return bigboom_WooCommerce
	 */
	function __construct() {
		// Check if Woocomerce plugin is actived
		if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			return false;
		}

		// Define all hook
		add_action( 'template_redirect', array( $this, 'hooks' ) );

		// Need an early hook to ajaxify update mini shop cart
		add_filter( 'add_to_cart_fragments', array( $this, 'add_to_cart_fragments' ) );

		// Change product number
		add_action( 'pre_get_posts', array( $this, 'pre_get_products' ) );
	}

	/**
	 * Hooks to WooCommerce actions, filters
	 *
	 * @since  1.0
	 * @return void
	 */
	function hooks() {
		$this->layout       = bigboom_get_layout();
		$this->new_duration = bigboom_theme_option( 'product_newness' );
		$this->shop_view    = isset( $_COOKIE['shop_view'] ) ? $_COOKIE['shop_view'] : 'grid';

		// WooCommerce Styles
		add_filter( 'woocommerce_enqueue_styles', array( $this, 'wc_styles' ) );

		// Add toolbars for shop page
		add_filter( 'woocommerce_show_page_title', '__return_false' );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
		remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination' );
		add_action( 'woocommerce_before_shop_loop', array( $this, 'shop_tool_bar' ) );
		add_action( 'woocommerce_after_shop_loop', array( $this, 'shop_tool_bar' ) );

		// Change shop columns
		add_filter( 'loop_shop_columns', array( $this, 'shop_columns' ), 20 );

		// Add Bootstrap classes
		add_filter( 'post_class', array( $this, 'product_class' ), 10, 3 );

		// Wrap product loop content
		add_action( 'woocommerce_before_shop_loop_item', array( $this, 'open_product_inner' ), 1 );
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'close_product_inner' ), 100 );

		// Add badges
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash' );
		remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash' );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_badges' ) );
		add_action( 'woocommerce_before_single_product_summary', array( $this, 'product_badges' ) );

		// Add secondary image to product thumbnail
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail' );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_thumbnail' ) );

		// Display product excerpt and subcategory description for list view
		add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_single_excerpt', 5 );
		add_action( 'woocommerce_after_subcategory', array( $this, 'show_cat_desc' ) );

		// Change number of related products
		add_filter( 'woocommerce_output_related_products_args', array( $this, 'related_products_args' ) );
		add_filter( 'woocommerce_cross_sells_columns', array( $this, 'cross_sells_columns' ) );

		// Change columns number of product thumbnails in the single product page
		add_filter( 'woocommerce_product_thumbnails_columns', array( $this, 'product_thumbnails_columns' ) );

		// Add a sep line before buttons
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'add_sep_line' ), 5 );

		// Add the wishlist button and compare button
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'product_loop_wishlist' ), 15 );
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'product_loop_compare' ), 20 );

		// Show share icons
		add_action( 'woocommerce_single_product_summary', array( $this, 'share' ), 35 );

		// Change next and prev icon
		add_filter( 'woocommerce_pagination_args', array( $this, 'pagination_args' ) );

		// Change product image thumbnail html
		add_filter( 'woocommerce_single_product_image_thumbnail_html', array( $this, 'product_single_thumbnail_html' ), 10, 4 );

		// Change product image html
		add_filter( 'woocommerce_single_product_image_html', array( $this, 'product_single_html' ) );

		// Show view detail after view cart button
		add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'view_detail_button' ) );

		// Change product rating html
		add_filter( 'woocommerce_product_get_rating_html', array( $this, 'product_rating_html' ), 10, 2 );

		// Show sale price date
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'sale_price_date' ), 15 );

		// Change product stock html
		add_filter( 'woocommerce_stock_html', array( $this, 'product_stock_html' ), 10, 3 );

		// Change add to cart link
		add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'add_to_cart_link' ) );

		// Add products upsell display
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
		add_action( 'woocommerce_after_single_product_summary', array( $this, 'product_upsell_display' ), 15 );

	}

	/**
	 * Ajaxify update cart viewer
	 *
	 * @since 1.0
	 *
	 * @param array $fragments
	 *
	 * @return array
	 */
	function add_to_cart_fragments( $fragments ) {
		global $woocommerce;

		if ( empty( $woocommerce ) ) {
			return $fragments;
		}

		ob_start();
		?>

		<a href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ) ?>" class="cart-contents" title="<?php esc_attr_e( 'View your shopping cart', 'bigboom' ) ?>">
			<i class="fa fa-shopping-cart"></i>
			<span class="mini-cart-counter"><?php echo intval( $woocommerce->cart->cart_contents_count ) ?> <span><?php _e( 'item(s)', 'bigboom' ) ?></span></span>
			- <?php echo $woocommerce->cart->get_cart_total(); ?>
			<span class="arrow"><i class="fa fa-arrow-right"></i></span>
		</a>

		<?php
		$fragments['a.cart-contents'] = ob_get_clean();

		return $fragments;
	}

	/**
	 * Change number of products to be displayed
	 *
	 * @param  object $query
	 */
	function pre_get_products( $query ) {
		if ( is_admin() ) {
			return;
		}

		if ( $query->is_main_query() && ( is_shop() || is_product_category() ) ) {
			$number = isset( $_GET['showposts'] ) ? absint( $_GET['showposts'] ) : 12;
			$query->set( 'posts_per_page', $number );
		}
	}

	/**
	 * Remove default woocommerce styles
	 *
	 * @since  1.0
	 *
	 * @param  array $styles
	 *
	 * @return array
	 */
	function wc_styles( $styles ) {
		// unset( $styles['woocommerce-general'] );
		unset( $styles['woocommerce-layout'] );
		unset( $styles['woocommerce-smallscreen'] );

		return $styles;
	}

	/**
	 * Display a tool bar on top of product archive
	 *
	 * @since 1.0
	 */
	function shop_tool_bar() {
		$numbers = array( 9, 12, 15, 18, 21 );

		if ( $this->layout == 'full-content' ) {
			$numbers = array( 8, 12, 16, 20, 24 );
		}

		/**
		 * Allow child theme/plugin to modify the numbers array
		 *
		 * @since 1.1
		 */
		$number = apply_filters( 'bigboom_shop_product_numbers', $numbers, $this->layout );

		$options   = array();
		$showposts = get_query_var( 'posts_per_page' );
		foreach ( $numbers as $number ) {
			$options[] = sprintf(
				'<option value="%s" %s>%s %s</option>',
				esc_attr( $number ),
				selected( $number, $showposts, false ),
				$number,
				__( 'items', 'bigboom' )
			);
		}
		?>

		<div class="shop-toolbar">
			<div class="sorter clearfix">
				<div class="shop-view col-xs-12 col-sm-4 hidden-xs hidden-sm">
					<span><?php _e( 'View as', 'bigboom' ) ?>:</span>
					<a href="#" class="grid-view <?php echo $this->shop_view == 'grid' ? 'current' : '' ?>" data-view="grid"><?php _e( 'Grid', 'bigboom' ) ?></a> /
					<a href="#" class="list-view <?php echo $this->shop_view == 'list' ? 'current' : '' ?>" data-view="list"><?php _e( 'List', 'bigboom' ) ?></a>
				</div>

				<div class="sort-by col-xs-12 col-sm-4 hidden-xs hidden-sm">
					<span><?php _e( 'Sort by', 'bigboom' ) ?>:</span>
					<?php woocommerce_catalog_ordering() ?>
				</div>

				<div class="limiter col-xs-12 col-sm-4 hidden-xs hidden-sm">
					<form class="shop-products-number" method="get">
						<span><?php _e( 'Show', 'bigboom' ) ?>:</span>
						<select name="showposts">
							<?php echo implode( '', $options ); ?>
						</select>
						<?php
						foreach( $_GET as $name => $value ) {
							if ( 'showposts' != $name ) {
								printf( '<input type="hidden" name="%s" value="%s">', esc_attr( $name ), esc_attr( $value ) );
							}
						}
						?>
					</form>
				</div>
			</div>

			<div class="pager clearfix">
				<?php woocommerce_result_count() ?>

				<?php woocommerce_pagination() ?>
			</div>
		</div>

		<?php
	}

	/**
	 * Change the shop columns
	 *
	 * @since  1.0.0
	 * @param  int $columns The default columns
	 * @return int
	 */
	function shop_columns( $columns ) {
		return 'full-content' == $this->layout ? 4 : 3;
	}

	/**
	 * Add Bootstrap's column classes for product
	 *
	 * @since 1.0
	 *
	 * @param array  $classes
	 * @param string $class
	 * @param string $post_id
	 *
	 * @return array
	 */
	function product_class( $classes, $class = '', $post_id = '' ) {
		if ( ! $post_id || get_post_type( $post_id ) !== 'product' || is_single( $post_id ) ) {
			return $classes;
		}

		if ( ( is_woocommerce() || is_cart() ) && 'full-content' == $this->layout ) {
			$classes[] = 'col-md-3 col-sm-6 col-xs-6 col-sms-12';
		} elseif ( ( is_woocommerce() || is_cart() ) && 'full-content' != $this->layout ) {
			$classes[] = 'col-md-4 col-sm-6 col-xs-6 col-sms-12';
		}

		return $classes;
	}

	/**
	 * Wrap product content
	 * Open a div
	 *
	 * @since 1.0
	 */
	function open_product_inner() {
		echo '<div class="product-inner clearfix">';
	}

	/**
	 * Wrap product content
	 * Close a div
	 *
	 * @since 1.0
	 */
	function close_product_inner() {
		echo '</div>';
	}

	/**
	 * Display badge for new product or featured product
	 *
	 * @since 1.0
	 */
	function product_badges() {
		global $post, $product;

		$ribbons = '';

		// Ribbon for sale product
		if ( $product->is_on_sale() ) {
			$sale_percent = 0;

			if( $product->get_regular_price() != 0 && $product->get_sale_price() != 0 ) {
				$sale_percent = round( $product->get_sale_price() / $product->get_regular_price() * 100 );
			}

			if( $sale_percent != 0 ) {
				$sale_percent = $sale_percent - 100;
				$ribbons .= apply_filters( 'woocommerce_sale_flash', '<span class="onsale ribbon"><span>' . $sale_percent . '%' . '</span></span>', $post, $product );
			}

		}

		// Ribbon for featured product
		if ( $product->is_featured() ) {
			$ribbons .= '<span class="featured ribbon"><span>' . __( 'Hot', 'bigboom' ) . '</span></span>';
		}

		// If the product was published within the newness time frame display the new badge
		if ( ( time() - ( 60 * 60 * 24 * $this->new_duration ) ) < strtotime( get_the_time( 'Y-m-d' ) ) ) {
			$ribbons .= '<span class="newness ribbon"><span>' . __( 'New', 'bigboom' ) . '</span></span>';
		}

		if ( $ribbons ) {
			printf( '<div class="ribbons">%s</div>', $ribbons );
		}
	}

	/**
	 * WooCommerce Loop Product Thumbs
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function product_thumbnail() {
		global $product;
		$attachment_ids = $product->get_gallery_attachment_ids();

		if( count( $attachment_ids ) == 0 ) {
			echo '<span class="bb-product-thumbnails bb-thumbnail-single">';
		} else {
			echo '<span class="bb-product-thumbnails">';
		}

		echo woocommerce_get_product_thumbnail();

		if( count( $attachment_ids ) > 0 ) {
			echo wp_get_attachment_image( $attachment_ids[0], 'shop_catalog' );
		}

		echo '<span data-href="' . $product->get_permalink() . '" data-original-title="' . esc_attr__( 'Quick View', 'bigboom' ) . '" rel="tooltip" class="bb-quick-view"><i class="fa fa-search"></i></span>';

		echo '</span>';
	}

	/**
	 * WooCommerce Single Product Thumbs
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function product_single_thumbnail_html( $html, $attachment_id, $post_id, $image_class ) {
		$image        = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) );
		$image_title  = esc_attr( get_the_title( $attachment_id ) );
		$image_single = wp_get_attachment_image_src( $attachment_id, 'shop_single' );
		$image_full   = wp_get_attachment_image_src( $attachment_id, 'full' );

		if( $image_single && $image_full ) {
			return sprintf( '<a href="%s" rel="bb-prettyPhoto[product-gallery]" data-src="%s" title="%s">%s</a>',
				esc_url( $image_full[0] ),
				esc_url( $image_single[0] ),
				esc_attr( $image_title ),
				$image
			);
		}
	}

	/**
	 * WooCommerce Single Product
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function product_single_html() {
		global $product, $post;

		if ( has_post_thumbnail() ) {
			$image_title 	= esc_attr( get_the_title( get_post_thumbnail_id() ) );
			$image_link  	= wp_get_attachment_url( get_post_thumbnail_id() );
			$image       	= get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
				'title'	=> $image_title,
				'alt'	=> $image_title
				) );
			$attachment_count = count( $product->get_gallery_attachment_ids() );

			return sprintf( '<a href="%s" class="woocommerce-main-image">%s</a>', esc_url( $image_link ), $image );
		}
		else {
			return sprintf( '<img src="%s" alt="%s">', esc_url( wc_placeholder_img_src() ), esc_attr__( 'Placeholder', 'woocommerce' ) );
		}
	}

	/**
	 * Display description of sub-category in list view
	 *
	 * @param  object $category
	 */
	function show_cat_desc( $category ) {
		printf( '<div class="sub-category-desc" itemprop="description">%s</div>', $category->description );
	}

	/**
	 * Change related products args to display in correct grid
	 *
	 * @param  array $args
	 *
	 * @return array
	 */
	function related_products_args( $args ) {
		$number = 'full-content' == $this->layout ? 4 : 3;

		$args['posts_per_page'] = $number;
		$args['columns']        = $number;

		return $args;
	}

	/**
	 * Change number of columns when display cross sells products
	 *
	 * @param  int $cl
	 * @return int
	 */
	function cross_sells_columns( $columns ) {
		return 'full-content' == $this->layout ? 4 : 3;
	}

	/**
	 * Change product thumbnails columns
	 *
	 * @return int
	 */
	function product_thumbnails_columns() {
		return 4;
	}

	/**
	 * Display a separator before buttons, on the shop page
	 */
	function add_sep_line() {
		echo '<hr class="sep">';
	}

	/**
	 * WooCommerce Product Loop Compare
	 *
	 * @since  1.0
	 */
	function product_loop_compare() {
		if( function_exists( 'yith_woocompare_constructor' ) ) {
			echo do_shortcode( '[yith_compare_button]' );
		}
	}

	/**
	 * WooCommerce Product Loop Wishlist
	 *
	 * @since  1.0
	 */
	function product_loop_wishlist() {
		if( function_exists( 'yith_wishlist_constructor' ) ) {
			echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
		}
	}

	/**
	 * Change next and previous icon of pagination nav
	 *
	 * @since  1.0
	 */
	function pagination_args( $args ) {
		$args['prev_text'] = '<i class="fa fa-angle-left"></i>';
		$args['next_text'] = '<i class="fa fa-angle-right"></i>';

		return $args;
	}

	/**
	 * Display Addthis sharing
	 *
	 * @since 1.0
	 */
	function share() {
		if ( $addthis_id = bigboom_theme_option( 'addthis_profile_id' ) ) {
			printf(
				'<div class="addthis_native_toolbox addthis_toolbox addthis_default_style addthis_32x32_style">
					<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
					<a class="addthis_button_tweet"></a>
					<a class="addthis_button_google_plusone"></a>
					<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=%s"></script>
				</div>',
				esc_attr( $addthis_id )
			);
		}
	}

	/**
	 * Display view detail button
	 *
	 * @since 1.0
	 */
	function view_detail_button() {
		global $product;
		printf( '<a href="%s" class="view-detail-button">%s</a>',
			esc_url( $product->get_permalink() ),
			__( 'View Detail', 'bigboom' )
		);
	}

	/**
	 * Display product rating
	 *
	 * @since 1.0
	 */
	function product_rating_html( $rating_html, $rating ) {
		$rating_html  = '<div class="star-rating" title="' . sprintf( esc_attr__( 'Rated %s out of 5', 'bigboom' ), $rating ) . '">';
		$rating_html .= '<span style="width:' . ( ( $rating / 5 ) * 100 ) . '%"><strong class="rating">' . $rating . '</strong> ' . __( 'out of 5', 'bigboom' ) . '</span>';
		$rating_html .= '</div>';

		return $rating_html;
	}

	/**
	 * Display sale price date
	 *
	 * @since 1.0
	 */
	function sale_price_date() {
		global $product;

		if ( $product->is_on_sale() ) :
			$sale_price_dates_to 	= ( $date = get_post_meta( $product->id, '_sale_price_dates_to', true ) ) ? date_i18n( 'Y/m/d', $date ) : '';
			if( $sale_price_dates_to ) :
				$output = sprintf( '<div class="sale-price-date" data-date="%s">', esc_attr( $sale_price_dates_to ) );
				$output .= sprintf( '<div class="timer-day box"><span class="day"></span><span class="title">%s</span></div>', __( 'Days', 'bigboom' ) );
				$output .= sprintf( '<div class="timer-hour box"><span class="hour"></span><span class="title">%s</span></div>', __( 'Hours', 'bigboom' ) );
				$output .= sprintf( '<div class="timer-minu box"><span class="minu"></span><span class="title">%s</span></div>', __( 'Minutes', 'bigboom' ) );
				$output .= '</div>';

				echo $output;
			endif;
		endif;
	}

	/**
	 * Display product stock
	 *
	 * @since 1.0
	 */
	function product_stock_html( $availability_html, $availability, $product ) {
		$availability      = $product->get_availability();
		$availability_html = empty( $availability['availability'] ) ? '' : '<p class="stock ' . esc_attr( $availability['class'] ) . '">' . __( 'Availability: ', 'bigboom' )  . '<span>' . esc_html( $availability['availability'] ) . '</span>' . '</p>';

		return $availability_html;
	}

	/**
	 * Display add to cart link
	 *
	 * @since 1.0
	 */
	function add_to_cart_link( $product ) {
		global $product;

		return sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="button %s product_type_%s"><i class="fa fa-shopping-cart"></i>%s</a>',
			esc_url( $product->add_to_cart_url() ),
			esc_attr( $product->id ),
			esc_attr( $product->get_sku() ),
			esc_attr( isset( $quantity ) ? $quantity : 1 ),
			$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
			esc_attr( $product->product_type ),
			esc_html( $product->add_to_cart_text() )
		);
	}

	/**
	 * Display products upsell
	 *
	 * @since 1.0
	 */
	function product_upsell_display( ) {
		woocommerce_upsell_display( 3, 3 );
	}
}
