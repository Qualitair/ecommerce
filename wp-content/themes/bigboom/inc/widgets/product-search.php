<?php
/**
 * TA_Product_Search_Widget widget class
 *
 * @since 1.0
 */
class TA_Product_Search_Widget extends WP_Widget {
	/**
	 * Holds widget default settings, populated in constructor.
	 *
	 * @var array
	 */
	protected $defaults;

	/**
	 * Class constructor
	 * Set up the widget
	 *
	 * @return TA_Product_Search_Widget
	 */
	function __construct() {
		$this->defaults = array(
			'title'    => '',
			'instance' => 0,
		);

		$this->WP_Widget(
			'ta-product-search-widget',
			__( 'TA - Product Search', 'bigboom' ),
			array(
				'classname'   => 'ta-product-search-widget',
				'description' => __( 'Display an advanced product search form with category selectable', 'bigboom' ),
			)
		);

		// Use ajax search product
		add_action( 'wp_ajax_search_products', array( $this, 'instance_search_result' ) );
		add_action( 'wp_ajax_nopriv_search_products', array( $this, 'instance_search_result' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Outputs the HTML for this widget.
	 *
	 * @param array $args     An array of standard parameters for widgets in this theme
	 * @param array $instance An array of settings for this widget instance
	 *
	 * @return void Echoes it's output
	 */
	public function widget( $args, $instance ) {
		$instance = wp_parse_args( $instance, $this->defaults );
		extract( $args );

		if ( $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) ) {
			echo $before_title . esc_html( $title ) . $after_title;
		}

		echo $before_widget;
		?>

		<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="<?php echo $instance['instance'] ? 'instance-search' : '' ?>">
			<div class="product-cat">
				<?php
				wp_dropdown_categories( array(
					'name'            => 'product_cat',
					'taxonomy'        => 'product_cat',
					'class'           => 'cs-select cs-skin-elastic',					
					'orderby'            => 'NAME', 
					'order'              => 'ASC',
					'hierarchical'    => 1,
					'hide_empty'      => 0,
					'show_option_all' => __( 'All Categories', 'bigboom' ),
					'walker'          => new TA_Cat_Slug_Walker,
				) );
				?>
			</div>
			<input type="text" name="s" class="search-field" placeholder="Search the entire shop.....">
			<input type="hidden" name="post_type" value="product">
			<input type="submit" value="<?php esc_attr_e( 'Search', 'bigboom' ); ?>" class="search-submit">
		</form>

		<?php
		echo $after_widget;
	}

	/**
	 * Deals with the settings when they are saved by the admin.
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']  = strip_tags( $new_instance['title'] );
		$instance['instance'] = absint( $new_instance['instance'] );

		return $instance;
	}

	/**
	 * Display widget settings
	 *
	 * @param array $instance Widget settings
	 *
	 * @return void
	 */
	function form( $instance ) {
		$instance = wp_parse_args( $instance, $this->defaults );
		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title', 'bigboom' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>

		<p>
			<input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'instance' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'instance' ) ); ?>" value="1" <?php checked( 1, $instance['instance'] ) ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'instance' ) ); ?>"><?php _e( 'Instance serach', 'bigboom' ); ?></label><br>
		</p>

		<?php
	}

	/**
	 * Search products
	 *
	 * @since 1.0
	 */
	function instance_search_result() {
		check_ajax_referer( '_bigboom_nonce', 'bbnonce' );

		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => 30,
			's'              => trim( $_POST['term'] ),
		);

		if ( isset( $_POST['cat'] ) && $_POST['cat'] != '0' ) {
			$args['product_cat'] = $_POST['cat'];
		}

		$products = new WP_Query( $args );

		$response = array();

		if ( $products->have_posts() ) {
			while ( $products->have_posts() ) {
				$products->the_post();
				$product = new WC_Product( $products->post );

				$response[] = array(
					'label' => get_the_title(),
					'value' => get_permalink(),
					'price' => $product->get_price_html(),
					'rate'  => $product->get_rating_html(),
					'thumb' => get_the_post_thumbnail( get_the_ID(), 'shop_thumbnail' ),
				);
			}
		}

		if ( empty( $response ) ) {
			$response[] = array(
				'label' => __( 'Nothing found', 'bigboom' ),
				'value' => '#',
				'price' => '',
				'rate'  => '',
				'thumb' => '',
			);
		}

		wp_send_json_success( $response );
		die();
	}

	/**
	 * Load jQuery UI autocomplate only for this widget
	 *
	 * @since 1.0.1
	 */
	function enqueue_scripts() {
		wp_enqueue_script( 'jquery-ui-autocomplete' );
	}
}

/**
 * TA_Cat_Slug_Walker class
 */
class TA_Cat_Slug_Walker extends Walker_CategoryDropdown {
	/**
	 * Start the element output.
	 *
	 * @see Walker::start_el()
	 *
	 * @param string $output   Passed by reference. Used to append additional content.
	 * @param object $category Category data object.
	 * @param int    $depth    Depth of category. Used for padding.
	 * @param array  $args     Uses 'selected' and 'show_count' keys, if they exist. @see wp_dropdown_categories()
	 */
	public function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
		$pad = str_repeat( '&nbsp;', $depth * 3 );

		if($pad !== '') return;
		/** This filter is documented in wp-includes/category-template.php */
		$cat_name = apply_filters( 'list_cats', $category->name, $category );

		$output .= "\t<option class=\"level-$depth\" value=\"" . $category->name . "\"";
		if ( $category->term_id == $args['selected'] ) {
			$output .= ' selected="selected"';
		}
		$output .= '>';
		$output .= $pad . $cat_name;
		if ( $args['show_count'] ) {
			$output .= '&nbsp;&nbsp;('. number_format_i18n( $category->count ) .')';
		}
		$output .= "</option>\n";
	}
}
