<?php
/**
 * TA_Mini_Cart_Widget widget class
 *
 * @since 1.0
 */
class TA_Mini_Cart_Widget extends WP_Widget {
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
	 * @return TA_Mini_Cart_Widget
	 */
	function __construct() {
		$this->defaults = array(
			'title' => ''
		);

		$this->WP_Widget(
			'ta-mini-cart-widget',
			__( 'TA - Mini Cart', 'bigboom' ),
			array(
				'classname'   => 'ta-mini-cart-widget',
				'description' => __( 'Display minimal shoping cart', 'bigboom' ),
			)
		);
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
		if ( ! function_exists( 'is_woocommerce' ) ) {
			return;
		}

		global $woocommerce;
		$instance = wp_parse_args( $instance, $this->defaults );
		extract( $args );

		if ( $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) ) {
			echo $before_title . esc_html( $title ) . $after_title;
		}

		echo $before_widget;
		?>

		<div class="mini-cart">
			<a href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ) ?>" class="cart-contents" title="<?php esc_attr_e( 'View your shopping cart', 'bigboom' ) ?>">
				<i class="fa fa-shopping-cart"></i>
				<span class="mini-cart-counter"><?php echo intval( $woocommerce->cart->cart_contents_count ) ?> <span><?php _e( 'item(s)', 'bigboom' ) ?></span></span>
				- <?php echo $woocommerce->cart->get_cart_total(); ?>
				<span class="arrow"><i class="fa fa-arrow-right"></i></span>
			</a>

			<div id="mini-cart-content" class="mini-cart widget_shopping_cart_content"><?php woocommerce_mini_cart(); ?></div>
		</div>

		<?php
		echo $after_widget;
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
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>

		<?php
	}
}
