<?php
/**
 * Hooks for template header
 *
 * @package BigBoom
 */


if ( version_compare( $GLOBALS['wp_version'], '4.1', '<' ) ) :
	/**
	 * Filters wp_title to print a neat <title> tag based on what is being viewed.
	 *
	 * @param string $title Default title text for current view.
	 * @param string $sep Optional separator.
	 * @return string The filtered title.
	 */
	function bigboom_wp_title( $title, $sep ) {
		if ( is_feed() ) {
			return $title;
		}
		global $page, $paged;
		// Add the blog name
		$title .= get_bloginfo( 'name', 'display' );
		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) ) {
			$title .= " $sep $site_description";
		}
		// Add a page number if necessary:
		if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
			$title .= " $sep " . sprintf( __( 'Page %s', 'bigboom' ), max( $paged, $page ) );
		}
		return $title;
	}
	add_filter( 'wp_title', 'bigboom_wp_title', 10, 2 );

	/**
	 * Title shim for sites older than WordPress 4.1.
	 *
	 * @link https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/
	 * @todo Remove this function when WordPress 4.3 is released.
	 */
	function bigboom_render_title() {
		?>
		<title><?php wp_title( '|', true, 'right' ); ?></title>
		<?php
	}
	add_action( 'wp_head', 'bigboom_render_title' );
endif;

/**
 * Enqueue scripts and styles.
 *
 * @since 1.0
 */
function bigboom_enqueue_scripts() {
	/* Register and enqueue styles */
	wp_register_style( 'bb-icons', THEME_URL . '/css/icons.min.css', array(), THEME_VERSION );
	wp_register_style( 'bootstrap', THEME_URL . '/css/bootstrap.min.css', array(), '3.3.2' );
	wp_register_style( 'google-fonts', '//fonts.googleapis.com/css?family=Montserrat:400,700|Raleway:300,500,600,700,400' );

	if( ! wp_style_is( 'js_composer_front', 'enqueued' ) && wp_style_is( 'js_composer_front', 'registered') ) {
		wp_enqueue_style('js_composer_front');
	}

	wp_enqueue_style( 'bigboom', get_stylesheet_uri(), array( 'google-fonts', 'bootstrap', 'bb-icons' ), THEME_VERSION );



	// Load custom color scheme file
	if ( intval( bigboom_theme_option( 'custom_color_scheme' ) ) && bigboom_theme_option( 'custom_color_1' ) ) {
		$upload_dir = wp_upload_dir();
		$dir        = path_join( $upload_dir['baseurl'], 'custom-css' );
		$file       = $dir . '/color-scheme.css';
		wp_enqueue_style( 'bigboom-color-scheme', $file, THEME_VERSION );
	}

	/** Register and enqueue scripts */
	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_register_script( 'jquery-tabs', THEME_URL . '/js/jquery.tabs.js', array( 'jquery' ), '1.0.0', true );
	wp_register_script( 'bigboom-plugins', THEME_URL . "/js/plugins$min.js", array( 'jquery' ), THEME_VERSION, true );

	wp_enqueue_script( 'bigboom', THEME_URL . "/js/scripts$min.js", array( 'bigboom-plugins' ), THEME_VERSION, true );
	wp_localize_script( 'bigboom', 'bigboom', array(
		'ajax_url'  => admin_url( 'admin-ajax.php' ),
		'nonce'     => wp_create_nonce( '_bigboom_nonce' ),
		'direction' => is_rtl() ? 'rtl' : '',
	) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

}
add_action( 'wp_enqueue_scripts', 'bigboom_enqueue_scripts' );

/**
 * Get favicon and home screen icons
 *
 * @since  1.0
 */
function bigboom_header_icons() {
	$favicon            = bigboom_theme_option( 'favicon' );
	$header_icons       =  ( $favicon ) ? '<link rel="shortcut icon" type="image/x-ico" href="' . esc_url( $favicon ) . '" />' : '';

	$icon_ipad_retina   = bigboom_theme_option( 'icon_ipad_retina' );
	$header_icons       .= ( $icon_ipad_retina ) ? '<link rel="apple-touch-icon" href="' . esc_url( $icon_ipad_retina ) . '" />' : '';

	$icon_ipad          = bigboom_theme_option( 'icon_ipad' );
	$header_icons       .= ( $icon_ipad ) ? '<link rel="apple-touch-icon" href="' . esc_url( $icon_ipad ) . '" />' : '';

	$icon_iphone_retina = bigboom_theme_option( 'icon_iphone_retina' );
	$header_icons       .= ( $icon_iphone_retina ) ? '<link rel="apple-touch-icon" href="' . esc_url( $icon_iphone_retina ). '" />' : '';

	$icon_iphone        = bigboom_theme_option( 'icon_iphone' );
	$header_icons       .= ( $icon_iphone ) ? '<link rel="apple-touch-icon" href="' . esc_url( $icon_iphone ) . '" />' : '';

	echo $header_icons;
}
add_action( 'wp_head', 'bigboom_header_icons' );

/**
 * Custom scripts and styles on header
 *
 * @since  1.0
 */
function bigboom_header_scripts() {
	$inline_css = '';

	// Promotion
	if ( intval( bigboom_theme_option( 'promotion' ) ) ) {
		$promo_bg  = bigboom_theme_option( 'promo_bg' );
		$promo_css = "background-position: {$promo_bg['position_x']} {$promo_bg['position_x']};";
		$promo_css .= "background-repeat: {$promo_bg['repeat']};";
		$promo_css .= "background-attachment: {$promo_bg['attachment']};";
		$promo_css .= ! empty( $promo_bg['image'] ) ? "background-image: url({$promo_bg['image']});" : '';
		$promo_css .= ! empty( $promo_bg['color'] ) ? "background-color: {$promo_bg['color']};" : '';
		$promo_css .= ! empty( $promo_bg['size'] ) ? "background-size: {$promo_bg['size']};" : '';

		$inline_css .= '.top-promotion {' . $promo_css . '}';
	}

	// Custom CSS
	$css_custom = bigboom_get_meta( 'custom_css' ) . bigboom_theme_option( 'custom_css' );
	if ( ! empty( $css_custom ) ) {
		$inline_css .= $css_custom;
	}

	if ( ! empty( $inline_css ) ) {
		$inline_css = '<style type="text/css">' . $inline_css . '</style>';
	}

	if( $inline_css ) {
		echo $inline_css;
	}

	// Custom javascript
	if ( $header_js = bigboom_theme_option( 'header_scripts' ) ) {
		echo $header_js;
	}

	if ( is_singular() && $custom_js = bigboom_get_meta( 'custom_js' ) ) {
		echo $custom_js;
	}
}
add_action( 'wp_head', 'bigboom_header_scripts' );

/**
 * Display promotion section at the top of site
 *
 * @since 1.0
 */
function bigboom_promotion() {
	if ( ! intval( bigboom_theme_option( 'promotion' ) ) ) {
		return;
	}

	if ( intval( bigboom_theme_option( 'promotion_home_only' ) ) && ! is_front_page() ) {
		return;
	}

	printf(
		'<div id="top-promotion" class="top-promotion promotion">
			<div class="container">
				<div class="promotion-content">
					<span class="close fa fa-times"></span>
					%s
				</div>
			</div>
		</div>',
		do_shortcode( wp_kses( bigboom_theme_option( 'promo_content' ), wp_kses_allowed_html( 'post' ) ) )
	);
}
add_action( 'bigboom_before_header', 'bigboom_promotion', 5 );

/**
 * Display topbar before site header
 *
 * @since 1.0
 */
function bigboom_topbar() {
	if ( ! intval( bigboom_theme_option( 'topbar' ) ) ) {
		return;
	}

	?>
	<div id="topbar" class="topbar">
		<div class="container">
			<div class="row">
				<div class="topbar-left topbar-sidebar col-xs-12 col-sm-12 col-md-4">
					<?php dynamic_sidebar( 'topbar-left' ); ?>
				</div>

				<div class="topbar-right topbar-sidebar col-xs-12 col-sm-12 col-md-8">
					<?php dynamic_sidebar( 'topbar-right' ); ?>
				</div>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'bigboom_before_header', 'bigboom_topbar' );
