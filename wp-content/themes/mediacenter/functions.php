<?php

define( 'MC_TEMPLATE_PATH', get_template_directory() );

#-----------------------------------------------------------------
# Theme Options - Redux Framework
#-----------------------------------------------------------------
require_once( get_template_directory() . '/settings/mediacenter.config.php' );


// Font Awesome for Redux Framework

function redux_icon_font() {

    wp_register_style(
        'redux-font-awesome',
        get_template_directory_uri() . '/assets/css/font-awesome.min.css',
        array(),
        time(),
        'all'
    );  
    wp_enqueue_style( 'redux-font-awesome' );
}

add_action( 'redux/page/media_center_theme_options/enqueue', 'redux_icon_font' );


#-----------------------------------------------------------------
# Includes
#-----------------------------------------------------------------

include_once get_template_directory() . '/framework/inc/custom-fonts.php'; // Load Custom Fonts
include_once get_template_directory() . '/framework/inc/custom-styles.php'; // Load Custom Styles

// Pagination function
include_once get_template_directory() . '/framework/inc/pagination.php';

//WP Alchemy
include_once get_template_directory() . '/framework/inc/wpalchemy/MetaBox-mod.php';
include_once get_template_directory() . '/framework/inc/wpalchemy/MediaAccess-mod.php';

add_action( 'init', 'media_center_metabox_styles' ); 
function media_center_metabox_styles(){
    if ( is_admin() ){ 
        wp_enqueue_style( 'wpalchemy-metabox', get_template_directory_uri() . '/framework/css/meta-boxes.css' );
    }
}

$wpalchemy_media_access = new WPAlchemy_MediaAccess();

//Include metaboxes
include_once get_template_directory() . '/framework/metaboxes/mc-page-spec.php';

//Include Post Formats
include_once get_template_directory() . '/framework/inc/post-formats/load.php';

function register_mc_widgets() { 
	if ( class_exists( 'WC_Widget' ) ) {
		get_template_part( 'framework/widgets/class-mc-widget-home-tabs' );
		get_template_part( 'framework/widgets/class-mc-widget-brands-filter');
		get_template_part( 'framework/widgets/class-mc-widget-vertical-menu');
	}
	get_template_part( 'framework/widgets/class-mc-widget', 'recent-posts' );
}
add_action( 'widgets_init', 'register_mc_widgets', 15 );

#-----------------------------------------------------------------
# Plugin Recommendations
#-----------------------------------------------------------------

require_once get_template_directory() . '/framework/inc/class-tgm-plugin-activation.php';
add_action( 'tgmpa_register', 'media_center_theme_register_required_plugins' );

function media_center_theme_register_required_plugins() {
	
	$plugins = array(

		//delivered with the theme
		
		array(
			'name'     				=> 'WooCommerce', // The plugin name
			'slug'     				=> 'woocommerce', // The plugin slug (typically the folder name)
			'source'   				=> 'https://downloads.wordpress.org/plugin/woocommerce.2.3.1.zip', // The plugin source
			'required' 				=> true, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '2.2.6', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		),

		array(
			'name'					=> 'ReduxFramework',
			'slug'					=> 'redux-framework',
			'source'				=> 'https://downloads.wordpress.org/plugin/redux-framework.3.5.4.zip',
			'required'				=> true,
			'version'				=> '3.5.4',
			'force_activation'		=> false,
			'force_deactivation'	=> false,
			'external_url'			=> '',
		),

		array(
			'name'     				=> 'YITH Woocommerce Compare', // The plugin name
			'slug'     				=> 'yith-woocommerce-compare', // The plugin slug (typically the folder name)
			'source'   				=> 'http://downloads.wordpress.org/plugin/yith-woocommerce-compare.1.2.2.zip', // The plugin source
			'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '1.2.1', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		),

		array(
			'name'     				=> 'YITH WooCommerce Wishlist', // The plugin name
			'slug'     				=> 'yith-woocommerce-wishlist', // The plugin slug (typically the folder name)
			'source'   				=> 'http://downloads.wordpress.org/plugin/yith-woocommerce-wishlist.1.1.7.zip', // The plugin source
			'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '1.1.6', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		),
		
		array(
            'name'					=> 'Visual Composer', // The plugin name
            'slug'					=> 'js_composer', // The plugin slug (typically the folder name)
            'source'				=> get_template_directory() . '/inc/plugins/js_composer.zip', // The plugin source
            'required'				=> false, // If false, the plugin is only 'recommended' instead of required
            'version'				=> '4.3.4', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'			=> '', // If set, overrides default API URL and points to an external URL
        ),

        array(
            'name'					=> 'Media Center Extensions', // The plugin name
            'slug'					=> 'media-center-extensions', // The plugin slug (typically the folder name)
            'source'				=> get_template_directory() . '/inc/plugins/media-center-extensions.zip', // The plugin source
            'required'				=> true, // If false, the plugin is only 'recommended' instead of required
            'version'				=> '1.0.6', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'			=> '', // If set, overrides default API URL and points to an external URL
        ),

        array(
            'name'					=> 'Slider Revolution', // The plugin name
            'slug'					=> 'revslider', // The plugin slug (typically the folder name)
            'source'				=> get_template_directory() . '/inc/plugins/revslider.zip', // The plugin source
            'required'				=> false, // If false, the plugin is only 'recommended' instead of required
            'version'				=> '4.6.0', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'			=> '', // If set, overrides default API URL and points to an external URL
        ),

        array(
			'name'     				=> 'Regenerate Thumbnails', // The plugin name
			'slug'     				=> 'regenerate-thumbnails', // The plugin slug (typically the folder name)
			'source'   				=> 'https://downloads.wordpress.org/plugin/regenerate-thumbnails.zip', // The plugin source
			'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '2.2.4', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		),

		array(
			'name'     				=> 'Contact Form 7', // The plugin name
			'slug'     				=> 'contact-form-7', // The plugin slug (typically the folder name)
			'source'   				=> 'https://downloads.wordpress.org/plugin/contact-form-7.4.1.zip', // The plugin source
			'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '3.9.3', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		),

	);

	$config = array(
		'domain'       		=> 'media_center',         	// Text domain
		'default_path' 		=> '',                         	// Default absolute path to pre-packaged plugins
		'parent_menu_slug' 	=> 'themes.php', 				// Default parent menu slug
		'parent_url_slug' 	=> 'themes.php', 				// Default parent URL slug
		'menu'         		=> 'install-required-plugins', 	// Menu slug
		'has_notices'      	=> true,                       	// Show admin notices or not
		'is_automatic'    	=> false,					   	// Automatically activate plugins after installation or not
		'message' 			=> '',							// Message to output right before the plugins table
		'strings'      		=> array(
			'page_title'                       			=> __( 'Install Required Plugins', 'media_center' ),
			'menu_title'                       			=> __( 'Install Plugins', 'media_center' ),
			'installing'                       			=> __( 'Installing Plugin: %s', 'media_center' ), // %1$s = plugin name
			'oops'                             			=> __( 'Something went wrong with the plugin API.', 'media_center' ),
			'notice_can_install_required'     			=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'media_center' ), // %1$s = plugin name(s)
			'notice_can_install_recommended'			=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'media_center' ), // %1$s = plugin name(s)
			'notice_cannot_install'  					=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'media_center' ), // %1$s = plugin name(s)
			'notice_can_activate_required'    			=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'media_center' ), // %1$s = plugin name(s)
			'notice_can_activate_recommended'			=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'media_center' ), // %1$s = plugin name(s)
			'notice_cannot_activate' 					=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'media_center' ), // %1$s = plugin name(s)
			'notice_ask_to_update' 						=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'media_center' ), // %1$s = plugin name(s)
			'notice_cannot_update' 						=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'media_center' ), // %1$s = plugin name(s)
			'install_link' 					  			=> _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'media_center'  ),
			'activate_link' 				  			=> _n_noop( 'Activate installed plugin', 'Activate installed plugins', 'media_center'  ),
			'return'                           			=> __( 'Return to Required Plugins Installer', 'media_center' ),
			'plugin_activated'                 			=> __( 'Plugin activated successfully.', 'media_center' ),
			'complete' 									=> __( 'All plugins installed and activated successfully. %s', 'media_center' ), // %1$s = dashboard link
			'nag_type'									=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
		)
	);

	tgmpa( $plugins, $config );
}

#-----------------------------------------------------------------
# Nav Walker
#-----------------------------------------------------------------

require_once get_template_directory() . '/framework/inc/wp_bootstrap_navwalker.php';

#-----------------------------------------------------------------
# Category Walker
#-----------------------------------------------------------------

require_once get_template_directory() . '/framework/inc/wp_bootstrap_categorieswalker.php';

#-----------------------------------------------------------------
# Rev Slider Setup
#-----------------------------------------------------------------

if( function_exists( 'set_revslider_as_theme' ) ){
	set_revslider_as_theme();
}

#-----------------------------------------------------------------
# Visual Composer Setup
#-----------------------------------------------------------------

if ( class_exists( 'WPBakeryVisualComposerAbstract' ) ):
	
	if( function_exists( 'vc_set_as_theme' ) ){
		vc_set_as_theme(false);
	}

	vc_disable_frontend();

	// Modify and remove existing shortcodes from VC
	include_once get_template_directory() . '/framework/shortcodes/visual-composer/custom_vc.php';

	// VC Templates
	$vc_templates_dir = get_template_directory() . '/framework/shortcodes/visual-composer/vc_templates/';
	vc_set_template_dir($vc_templates_dir);

	// Remove vc_teaser
	if (is_admin()) :
		function remove_vc_teaser() {
			remove_meta_box('vc_teaser', '' , 'side');
		}
		add_action( 'admin_head', 'remove_vc_teaser' );
	endif;

endif;

#-----------------------------------------------------------------
# AJAX URL
#-----------------------------------------------------------------

add_action('wp_head','media_center_ajaxurl');
function media_center_ajaxurl() {
?>
    <script type="text/javascript">
        var media_center_ajaxurl = '<?php echo admin_url('admin-ajax.php', 'relative'); ?>';
    </script>
<?php
}

#-----------------------------------------------------------------
# AJAX Calls
#-----------------------------------------------------------------

//ajax on shopping bag items number
if (class_exists('WooCommerce')) {
	function refresh_cart_info() {
		global $woocommerce;
		$output = array(
			'cart_items_count'   => $woocommerce->cart->cart_contents_count,
			'cart_total'         => $woocommerce->cart->cart_contents_total,
			'currency_symbol'    => get_woocommerce_currency_symbol()
		);
		header('Content-Type: application/json');
		echo json_encode( $output ); 
		die();
	}
	add_action( 'wp_ajax_refresh_cart_info', 'refresh_cart_info' );
	add_action( 'wp_ajax_nopriv_cart_info', 'refresh_cart_info' );
}

//ajax on wishlist items number
if (class_exists('YITH_WCWL')) {
	function refresh_wishlist_items_number() {
		global $yith_wcwl;
		echo yith_wcwl_count_products();
		die();
	}
	add_action( 'wp_ajax_refresh_wishlist_items_number', 'refresh_wishlist_items_number' );
	add_action( 'wp_ajax_nopriv_refresh_wishlist_items_number', 'refresh_wishlist_items_number' );
}

#-----------------------------------------------------------------
# Media Center Theme Setup
#-----------------------------------------------------------------

/**
 * Set up the content width value based on the theme's design.
 *
 * @see media_center_content_width()
 *
 */
if ( ! isset( $content_width ) ) {
	$content_width = 810;
}

function iframe_the_content_filter( $content ){
	$content = str_replace( '<iframe', '<div class="embed-responsive embed-responsive-16by9"><iframe', $content );
	$content = str_replace( '</iframe>', '</iframe></div>', $content );
	return $content;
}

add_filter( 'the_content', 'iframe_the_content_filter' );

/**
 * Media Center Theme Setup
 */
if ( ! function_exists( 'media_center_theme_setup' ) ) :
function media_center_theme_setup(){

	global $media_center_theme_options;

	// Theme Support
	add_theme_support( 'woocommerce' );

	$hard_crop = isset( $media_center_theme_options['hard_crop_images'] ) ? $media_center_theme_options['hard_crop_images'] : true;

	// Add Image Sizes
	add_image_size( 'product-large', 433, 325, $hard_crop );
	add_image_size( 'product-medium', 246, 186, $hard_crop );
	add_image_size( 'product-small', 194, 143, $hard_crop );
	add_image_size( 'product-thumbnail', 73, 73, $hard_crop );
	
	update_option( 'shop_single_image_size', array( 'width' => '433', 'height' => '325', 'crop' => $hard_crop ) ); 
	update_option( 'shop_catalog_image_size', array( 'width' => '246', 'height' => '186', 'crop' => $hard_crop ) ); 
	update_option( 'shop_thumbnail_image_size', array( 'width' => '73', 'height' => '73', 'crop' => $hard_crop ) );

	// Theme Support
	add_theme_support( 'post-formats', array( 'image', 'gallery', 'video', 'audio', 'quote', 'link', 'aside', 'status' ) ); // Post formats.
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );

	// Register Nav Menus
	$nav_menus = array(
		'top-left'    =>  __( 'Top Bar Left Navigation', 'media_center' ),
		'top-right'   =>  __( 'Top Bar Right Navigation', 'media_center' ),
		'primary'     =>  __( 'Main Navigation', 'media_center' ),
		'departments' =>  __( 'Shop by Departments' , 'media_center' )
	);
	register_nav_menus( $nav_menus );

	// Theme Text Domain
	load_theme_textdomain( 'media_center', get_template_directory() . '/languages' );
}
endif;
add_action( 'after_setup_theme', 'media_center_theme_setup' );

/**
 * Adjust content_width value for image attachment template.
 *
 */
if( ! function_exists( 'media_center_content_width' ) ):
function media_center_content_width() {
	if ( is_attachment() && wp_attachment_is_image() ) {
		$GLOBALS['content_width'] = 810;
	}
}
endif;
add_action( 'template_redirect', 'media_center_content_width' );

/**
 * Enqueue scripts and styles for the front end.
 */
function media_center_scripts(){

	global $media_center_theme_options, $yith_woocompare;

	/*
	* CSS
	*/
	
	// Bootstrap Core CSS
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css' );

	// Default
	wp_enqueue_style( 'media_center-main-style', get_template_directory_uri() . '/style.css' );

	if( isset( $media_center_theme_options['use_predefined_color'] ) && $media_center_theme_options['use_predefined_color'] ){
		$preset_color = isset( $media_center_theme_options['main_color'] ) ? $media_center_theme_options['main_color'] : 'green';
	} else {
		$preset_color = 'custom-color';
	}

	// Customizable CSS
	wp_enqueue_style( 'media_center-preset-color',  get_template_directory_uri() . '/assets/css/' . $preset_color . '.css' );
	
	// Javascript & CSS plugin styles
	wp_enqueue_style( 'media_center-owl-carousel',  get_template_directory_uri() . '/assets/css/owl.carousel.min.css' );
	wp_enqueue_style( 'media_center-animate',  get_template_directory_uri() . '/assets/css/animate.min.css' );

	
	$use_default_font = isset( $media_center_theme_options['use_default_font'] ) ? $media_center_theme_options['use_default_font'] : true ;

	if( $use_default_font ){
		// Google Fonts
    	wp_enqueue_style( 'media_center-open-sans', media_center_font_url(), array(), null );
	}

	// Icons/Glyphs
	wp_enqueue_style( 'media_center-font-awesome',  get_template_directory_uri() . '/assets/css/font-awesome.min.css' );

	if ( is_rtl() ) {
		wp_enqueue_style( 'bootstrap-rtl', get_template_directory_uri() . '/assets/css/bootstrap-rtl.min.css' );
	}

	/*
	* Javascript
	*/

	wp_enqueue_script( 'media_center-bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array( 'jquery' ), '1.10.2', true );
	wp_enqueue_script( 'media_center-bootstrap-hover-dropdown', get_template_directory_uri() . '/assets/js/bootstrap-hover-dropdown.min.js', array( 'jquery' ), '1.10.2', true );
	wp_enqueue_script( 'media_center-owl-carousel', get_template_directory_uri() . '/assets/js/owl.carousel.min.js', array( 'jquery' ), '1.10.2', true );
	wp_enqueue_script( 'media_center-echo', get_template_directory_uri() . '/assets/js/echo.min.js', array( 'jquery' ), '1.10.2', true );
	wp_enqueue_script( 'media_center-css-browser-selector', get_template_directory_uri() . '/assets/js/css_browser_selector.min.js', array( 'jquery' ), '1.10.2', true );
	wp_enqueue_script( 'media_center-jquery-easing', get_template_directory_uri() . '/assets/js/jquery.easing-1.3.min.js', array( 'jquery' ), '1.10.2', true );
	wp_enqueue_script( 'media_center-bootstrap-slider', get_template_directory_uri() . '/assets/js/bootstrap-slider.min.js', array( 'jquery' ), '1.10.2', true );
    wp_enqueue_script( 'media_center-jquery-raty', get_template_directory_uri() . '/assets/js/jquery.raty.min.js', array( 'jquery' ), '1.10.2', true );
    wp_enqueue_script( 'media_center-jquery-prettyphoto', get_template_directory_uri() . '/assets/js/jquery.prettyPhoto.min.js', array( 'jquery' ), '1.10.2', true );
    wp_enqueue_script( 'media_center-jquery-customselect', get_template_directory_uri() . '/assets/js/jquery.customSelect.min.js', array( 'jquery' ), '1.10.2', true );

    $sticky_header = isset ( $media_center_theme_options['sticky_header'] ) ? $media_center_theme_options['sticky_header'] : true;
	
	if( $sticky_header ) {
		wp_enqueue_script( 'media_center-waypoints', get_template_directory_uri() . '/assets/js/waypoints.min.js', array( 'jquery' ), '1.10.2', true );
    	wp_enqueue_script( 'media_center-waypoints-sticky', get_template_directory_uri() . '/assets/js/waypoints-sticky.min.js', array( 'jquery' ), '1.10.2', true );
	}

	$enable_live_search = isset( $media_center_theme_options['live_search'] ) ? $media_center_theme_options['live_search'] : true;

	if( $enable_live_search ) {
		wp_enqueue_script( 'wp_typeahead_js', get_template_directory_uri() . '/assets/js/typeahead.bundle.min.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'handlebars', get_template_directory_uri() . '/assets/js/handlebars.min.js', array( 'wp_typeahead_js' ), '', true );
	}

    wp_enqueue_script( 'media_center-wow', get_template_directory_uri() . '/assets/js/wow.min.js', array( 'jquery' ), '1.10.2', true );
    wp_enqueue_script( 'media_center-jplayer', get_template_directory_uri() . '/assets/js/jquery.jplayer.min.js', array( 'jquery' ), '1.10.2', true );
    
    // Theme Scripts
	wp_enqueue_script( 'media_center-theme-scripts', get_template_directory_uri() . '/assets/js/scripts.js', array( 'jquery' ), '1.10.2', true );

	$scroll_to_top = isset ( $media_center_theme_options['scroll_to_top'] ) ? $media_center_theme_options['scroll_to_top'] : true;
	$is_rtl_js = is_rtl() ? true : false ;

	$live_search_template = !empty( $media_center_theme_options['live_search_template'] ) ? $media_center_theme_options['live_search_template'] : '<p>{{value}}</p>' ;

	$mc_options = array(
		'rtl' 					=> $is_rtl_js,
		'ajaxurl'				=> admin_url( 'admin-ajax.php' ),
		'should_stick'			=> $sticky_header,
		'should_scroll'			=> $scroll_to_top,
		'live_search_template'	=> $live_search_template,
		'enable_live_search'	=> $enable_live_search,
		'ajax_loader_url'		=> get_template_directory_uri() . '/assets/images/ajax-loader.gif',
	);
	
	wp_localize_script( 'media_center-theme-scripts', 'mc_options', $mc_options );
	// Dequeue scripts from plugins

	// Woocompare
	if( class_exists( 'YITH_Woocompare' ) ) {
		wp_dequeue_script( 'yith-woocompare-main' );
		wp_dequeue_script( 'jquery-colorbox' );
		wp_dequeue_style( 'jquery-colorbox' );
		wp_localize_script( 'media_center-theme-scripts', 'yith_woocompare', array(
	        'nonceadd' 		=> wp_create_nonce( $yith_woocompare->obj->action_add ),
	        'nonceremove' 	=> wp_create_nonce( $yith_woocompare->obj->action_remove ),
	        'nonceview' 	=> wp_create_nonce( $yith_woocompare->obj->action_view ),
	        'ajaxurl' 		=> admin_url( 'admin-ajax.php' ),
	        'actionadd' 	=> $yith_woocompare->obj->action_add,
	        'actionremove' 	=> $yith_woocompare->obj->action_remove,
	        'actionview' 	=> $yith_woocompare->obj->action_view,
	        'added_label' 	=> __( 'Added', 'media_center' ),
	        'table_title' 	=> __( 'Product Comparison', 'media_center' ),
	        'auto_open' 	=> get_option( 'yith_woocompare_auto_open', 'yes' )
	    ));
	    wp_dequeue_style( 'yith-woocompare-widget' );
	}

    //Yith Wishlist
    if( class_exists( 'YITH_WCWL' )) {
    	wp_dequeue_script( 'jquery-yith-wcwl' );
	    wp_dequeue_style( 'yith-wcwl-main' );
	    wp_dequeue_style( 'yith-wcwl-user-main' );
	    wp_dequeue_style( 'yith-wcwl-font-awesome' );
		wp_dequeue_style( 'yith-wcwl-font-awesome-ie7' );
		$yith_wcwl_l10n = array(
            'ajax_url' 									=> admin_url( 'admin-ajax.php', is_ssl() ? 'https' : 'http' ),
            'redirect_to_cart' 							=> get_option( 'yith_wcwl_redirect_cart' ),
            'multi_wishlist' 							=> get_option( 'yith_wcwl_multi_wishlist_enable' ) == 'yes' ? true : false,
            'hide_add_button' 							=> apply_filters( 'yith_wcwl_hide_add_button', true ),
            'is_user_logged_in' 						=> is_user_logged_in(),
           	'ajax_loader_url'							=> get_template_directory_uri() . '/assets/images/ajax-loader.gif',
            'remove_from_wishlist_after_add_to_cart' 	=> get_option( 'yith_wcwl_remove_after_add_to_cart' ),
            'labels' 									=> array( 'cookie_disabled' => __( 'We are sorry,but this feature is available only if cookies on your browser are enabled.', 'media_center' )),
            'actions' 									=> array(
                'add_to_wishlist_action' 		=> 'add_to_wishlist',
                'remove_from_wishlist_action' 	=> 'remove_from_wishlist'
            )
        );
        wp_localize_script( 'media_center-theme-scripts', 'yith_wcwl_l10n', $yith_wcwl_l10n );
    }

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'media_center_scripts' );

/* Admin Styles */
function media_center_admin_styles() {
    if ( is_admin() ) {
		if (class_exists('WPBakeryVisualComposerAbstract')) { 
			wp_enqueue_style('media_center_visual_composer', get_template_directory_uri() .'/framework/css/visual-composer.css', false, "1.0", 'all');
		}
    }
}
add_action( 'admin_enqueue_scripts', 'media_center_admin_styles' );

/** 
 * Dequeue Woocmmerce styles
 */
add_filter( 'woocommerce_enqueue_styles', '__return_false' );

/**
 * Register Open Sans Google font for Media Center.
 *
 * @return string
 */
function media_center_font_url() {
	$font_url = '';
	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Open Sans, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Open Sans font: on or off', 'media_center' ) ) {
		$font_url = esc_url( add_query_arg( 'family', urlencode( 'Open Sans:400,600,700,800' ), "//fonts.googleapis.com/css" ) );
	}

	return $font_url;
}

/**
 * Creates a nicely formatted and more specific title element text
 * for output in head of document, based on current view.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string Filtered title.
 */
function media_center_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if( is_int($paged) && is_int($page) ){
		if ( $paged >= 2 || $page >= 2 ){
			$title = "$title $sep " . sprintf( __( 'Page %s', 'media_center' ), max( $paged, $page ) );
		}
	}

	return $title;
}
add_filter( 'wp_title', 'media_center_wp_title', 10, 2 );

#-----------------------------------------------------------------
# Register widgetized area and update sidebar with default widgets
#-----------------------------------------------------------------
if( ! function_exists( 'media_center_widgets_init' ) ):
function media_center_widgets_init() {
	
	$sidebars_widgets = wp_get_sidebars_widgets();	
	$footer_area_widgets_counter = "0";	
	if (isset($sidebars_widgets['footer-widget-area'])) $footer_area_widgets_counter  = count($sidebars_widgets['footer-widget-area']);
	
	switch ($footer_area_widgets_counter) {
		case 0:
			$footer_area_widgets_columns ='col-lg-12';
			break;
		case 1:
			$footer_area_widgets_columns ='col-lg-12 col-md-12 col-sm-12';
			break;
		case 2:
			$footer_area_widgets_columns ='col-lg-6 col-md-6 col-sm-12';
			break;
		case 3:
			$footer_area_widgets_columns ='col-lg-4 col-md-6 col-sm-12';
			break;
		case 4:
			$footer_area_widgets_columns ='col-lg-3 col-md-6 col-sm-12';
			break;
		case 5:
			$footer_area_widgets_columns ='col-md-15 col-lg-2 col-sm-12';
			break;
		case 6:
			$footer_area_widgets_columns ='col-lg-2 col-md-6 col-sm-12';
			break;
		default:
			$footer_area_widgets_columns ='col-lg-2 col-md-6 col-sm-12';
	}
	
	//default sidebar
	register_sidebar(array(
		'name'          => __( 'Sidebar', 'media_center' ),
		'id'            => 'default-sidebar',
		'before_widget' => '<aside id="%1$s" class="widget %2$s clearfix">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	));
	
	//footer widget area
	register_sidebar( array(
		'name'          => __( 'Footer Widget Area', 'media_center' ),
		'id'            => 'footer-widget-area',
		'before_widget' => '<div class="' . $footer_area_widgets_columns . ' columns"><aside id="%1$s" class="widget clearfix %2$s"><div class="body">',
		'after_widget'  => '</div></aside></div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	//footer bottom widget area
	register_sidebar( array(
		'name'          => __( 'Footer Bottom Widget Area', 'media_center' ),
		'id'            => 'footer-bottom-widget-area',
		'before_widget' => '<div class="columns"><aside id="%1$s" class="widget clearfix %2$s"><div class="body">',
		'after_widget'  => '</div></aside></div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	
	//catalog widget area
	register_sidebar( array(
		'name'          => __( 'Shop Sidebar', 'media_center' ),
		'id'            => 'catalog-widget-area',
		'before_widget' => '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-12"><aside id="%1$s" class="widget clearfix %2$s">',
		'after_widget'  => '</aside></div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	//product filters widget area
	register_sidebar( array(
		'name'          => __( 'Product Filters', 'media_center' ),
		'id'            => 'product-filters-widget-area',
		'before_widget' => '<aside id="%1$s" class="widget clearfix %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
}
endif;
add_action( 'widgets_init', 'media_center_widgets_init' );

#-----------------------------------------------------------------
# WooCommerce Template Settings
#-----------------------------------------------------------------

require_once get_template_directory() . '/framework/inc/woocommerce-template-functions.php';

#-----------------------------------------------------------------
# Media Center Blog Functions
#-----------------------------------------------------------------

require_once get_template_directory() . '/framework/inc/mediacenter-blog-functions.php';

#-----------------------------------------------------------------
# Header
#-----------------------------------------------------------------

// Displays part of the header

function media_center_display_header_part ( $part ){
	get_template_part( 'framework/templates/header/' . $part );
}

if( !function_exists( 'media_center_display_logo' ) ):
function media_center_display_logo( $default_logo ) {
	global $media_center_theme_options;

	$mc_logo = '';

	if ( !empty( $media_center_theme_options['use_text_logo'] ) && $media_center_theme_options['use_text_logo'] == '1' ){
		$mc_logo = '<span class="logo-text">' . $media_center_theme_options['logo_text'] . '</span>';
	} else {
		if ( !empty( $media_center_theme_options['site_logo']['url'] ) ) {
			$media_center_site_logo = $media_center_theme_options['site_logo'];
			$mc_logo = '<img alt="logo" src="' . $media_center_site_logo['url'] . '" width="' . $media_center_site_logo['width'] . '" height="' . $media_center_site_logo['height'] . '"/>';
		} else {
			$mc_logo = $default_logo;
		}
	}
    
    return $mc_logo;
}
endif;

add_filter( 'media_center_display_logo', 'media_center_display_logo' );

// Check to hide title

function media_center_should_hide_title() {
	global $mc_page_metabox;
	
	$postID = get_the_ID(); $cart_page_ID = get_option( 'woocommerce_cart_page_id' );

	return ( $mc_page_metabox->get_the_value( 'hide_title' ) === '1' ) || ( $cart_page_ID == $postID );
}

#-----------------------------------------------------------------
# The Breadcrumb
#-----------------------------------------------------------------
if( ! function_exists( 'media_center_get_category_parents' ) ) :
function media_center_get_category_parents( $id, $link = false, $separator = '/', $nicename = false, $visited = array() ) {
	$category_parents = get_category_parents( $id, $link, $separator, $nicename, $visited );
	if( is_wp_error( $category_parents ) ) {
		$category_parents = '';
	} else {
		$category_parents = str_replace( '</a>', '</a></li>', str_replace( '<a', '<li><a', $category_parents ) );
	}
	return $category_parents;
}
endif;

if( ! function_exists( 'media_center_blog_link' ) ) :
function media_center_blog_link() {
	$blog_page_link = '';
	if ( get_option( 'show_on_front' ) == 'page' ){
		$blog_page_ID = get_option( 'page_for_posts' );
		$page = get_page( $blog_page_ID );
		$blog_page_link = '<a href="' . get_permalink( $blog_page_ID ) . '">' . $page->post_title . '</a>';
	}
	return $blog_page_link;
}
endif;

if ( class_exists( 'woocommerce' ) ) {
	get_template_part( 'framework/inc/mediacenter-breadcrumb' );
}

#-----------------------------------------------------------------
# Navigation
#-----------------------------------------------------------------

// Top Bar Left Nav Menu
if( ! function_exists( 'media_center_top_left_nav_menu' ) ) :
function media_center_top_left_nav_menu(){
	global $media_center_theme_options;

	if( $media_center_theme_options['language_switcher_position'] == 'top_bar_left'){
		$enable_language_switcher = $media_center_theme_options['enable_language_switcher'];
	}else{
		$enable_language_switcher = false;
	}

	if( $media_center_theme_options['currency_switcher_position'] == 'top_bar_left'){
		$enable_currency_switcher = $media_center_theme_options['enable_currency_switcher'];
	}else{
		$enable_currency_switcher = false;
	}
	
	$top_left_menu = ''; 

	$languages_menu = $enable_language_switcher ? media_center_get_languages_menu() : '';
	$currencies_menu = $enable_currency_switcher ? media_center_get_currencies_menu() : '';

	if ( has_nav_menu ( 'top-left' ) ) {
		$top_left_menu .= wp_nav_menu( 
            array(
				'menu' 			    => 'top-left',
				'theme_location' 	=> 'top-left',
				'depth' 			=> 2,
				'container' 		=> false,
				'menu_class' 		=> 'top-left',
				'echo' 			    => 0 ,
				'walker' 			=> new wp_bootstrap_navwalker(),
				'items_wrap' 		=> '%3$s',
            )
        );
	} else {
		$pages = get_pages( array( 'parent' => 0, 'number' => '5', ) );
		foreach ( $pages as $page ){
			$top_left_menu .= '<li><a href="' . get_permalink( $page->ID ) .'">' . $page->post_title . '</a></li>';
		}
	}

	$top_left_menu = '<ul id="menu-top-left" class="top-left">' . $top_left_menu . $languages_menu . $currencies_menu . '</ul>';

	return $top_left_menu ;
}
endif;

// Top Bar Right Nav Menu
if( ! function_exists( 'media_center_top_right_nav_menu' ) ) :
function media_center_top_right_nav_menu(){
	global $media_center_theme_options;

	if( $media_center_theme_options['language_switcher_position'] == 'top_bar_right'){
		$enable_language_switcher = $media_center_theme_options['enable_language_switcher'];
	}else{
		$enable_language_switcher = false;
	}

	if( $media_center_theme_options['currency_switcher_position'] == 'top_bar_right'){
		$enable_currency_switcher = $media_center_theme_options['enable_currency_switcher'];
	}else{
		$enable_currency_switcher = false;
	}
	
	$top_right_menu = '';

	$languages_menu = $enable_language_switcher ? media_center_get_languages_menu() : '';
	$currencies_menu = $enable_currency_switcher ? media_center_get_currencies_menu() : '';

	if ( has_nav_menu ( 'top-right' ) ) {
		
		$top_right_menu .= wp_nav_menu( 
            array(
				'menu' 			    => 'top-right',
				'theme_location' 	=> 'top-right',
				'depth' 			=> 2,
				'container' 		=> false,
				'menu_class' 		=> 'right top-right',
				'echo' 			    => 0 ,
				'walker' 			=> new wp_bootstrap_navwalker(),
				'items_wrap' 		=> '%3$s',
            )
        );
	} else {
		$top_right_menu .= media_center_woocommerce_pages( true, '', '') ;
	}

	$top_right_menu = '<ul class="right top-right">' . $languages_menu . $currencies_menu . $top_right_menu . '</ul>';

	return $top_right_menu ;
}
endif;

// Languages Nav
if( ! function_exists( 'media_center_get_languages_menu' ) ) :
function media_center_get_languages_menu() {
	global $media_center_theme_options;

	$languages_menu = '';

	if ( function_exists( 'icl_get_languages' ) ) :

		if ( $media_center_theme_options['language_switcher_position'] == 'top_bar_left' ) {
			if( $media_center_theme_options['top_bar_left_menu_dropdown_trigger'] == 'hover' ) {
				$data_hover = 'data-hover="dropdown"';
			} else {
				$data_hover = '';
			}
		} elseif ( $media_center_theme_options['language_switcher_position'] == 'top_bar_right' ) {
			if( $media_center_theme_options['top_bar_right_menu_dropdown_trigger'] == 'hover' ) {
				$data_hover = 'data-hover="dropdown"';
			} else {
				$data_hover = '';
			}
		}
			
		$additional_languages = icl_get_languages('skip_missing=N&orderby=KEY&order=DIR&link_empty_to=str');
            
        if (count($additional_languages) > 1) { 
        	$languages_menu .= '<li class="dropdown"><a href="#" ' . $data_hover . ' data-toggle="dropdown" class="dropdown-toggle">' . ICL_LANGUAGE_NAME . '</a>';
			$languages_menu .= '<ul class="dropdown-menu">';
                    
            foreach( $additional_languages as $additional_language ) {
				if( !$additional_language['active'] ) {
					$langs[] = '<li><a href="'.$additional_language['url'].'">'.$additional_language['native_name'].'</a></li>';
				} 
            }
			$languages_menu .= join('', $langs);
                    
            $languages_menu .= '</ul>';
            $languages_menu .= '</li>';
        }
        
    endif;

    return $languages_menu;
}
endif;

// Currencies Menu
if( ! function_exists( 'media_center_get_currencies_menu' ) ) :
function media_center_get_currencies_menu() {
	
	$currencies_menu = '';

	if( class_exists( 'WCML_Multi_Currency_Support' ) ) {
		global $woocommerce_wpml, $sitepress, $media_center_theme_options;

		if ( $media_center_theme_options['currency_switcher_position'] == 'top_bar_left' ) {
			if( $media_center_theme_options['top_bar_left_menu_dropdown_trigger'] == 'hover' ) {
				$data_hover = 'data-hover="dropdown"';
			} else {
				$data_hover = '';
			}
		} elseif ( $media_center_theme_options['currency_switcher_position'] == 'top_bar_right' ) {
			if( $media_center_theme_options['top_bar_right_menu_dropdown_trigger'] == 'hover' ) {
				$data_hover = 'data-hover="dropdown"';
			} else {
				$data_hover = '';
			}
		}

		$settings = $woocommerce_wpml->get_settings();

		if( !isset( $settings['currencies_order'] ) ) {
            $currencies = $woocommerce_wpml->multi_currency_support->get_currency_codes();
        }else{
            $currencies = $settings['currencies_order'];
        }

		if(	!isset( $args['format'] ) ) {
            $args['format'] = isset($settings['wcml_curr_template']) && $settings['wcml_curr_template'] != '' ? $settings['wcml_curr_template'] : '%name% (%code%)' ;
        }

        $currencies_dropdown = '<ul class="mc_wcml_currency_switcher dropdown-menu">';
        $selected_currency  = '';
        $wc_currencies = get_woocommerce_currencies();

		foreach($currencies as $currency){
            if( $woocommerce_wpml->settings['currency_options'][$currency]['languages'][$sitepress->get_current_language()] == 1 ){
				
				$currency_format = preg_replace( array('#%name%#', '#%symbol%#', '#%code%#'), array( $wc_currencies[$currency], get_woocommerce_currency_symbol( $currency ), $currency ), $args['format'] );                
                
                if( $currency == $woocommerce_wpml->multi_currency_support->get_client_currency() ){
                	$selected_currency = '<a href="#" ' . $data_hover . ' class="dropdown-toggle" data-toggle="dropdown">' . $currency_format . '</a>';
                }
                
                $currencies_dropdown .= '<li><a data-currency="' . $currency . '" href="#">' . $currency_format . '</a></li>';
        	}
        }

        $currencies_dropdown .= '</ul>';

        $currencies_menu = '<li class="dropdown">' . $selected_currency . $currencies_dropdown . '</li>';
	}

	return $currencies_menu;
}
endif;

// Primary Navigation
if( ! function_exists( 'media_center_primary_nav_menu' ) ) :
function media_center_primary_nav_menu(){
	$primary_nav_menu = '';

	if ( has_nav_menu( 'primary' ) ) {

        $primary_nav_menu .= wp_nav_menu( 
            array(
                'menu' 				=> 'primary',
                'theme_location' 	=> 'primary',
                'depth' 			=> 0,
                'container' 		=> false,
                'menu_class' 		=> 'navbar-nav nav',
                'echo' 				=> 0,
                'walker' 			=> new wp_bootstrap_navwalker()
            )
        );

    } else {
        $primary_nav_menu .= '<ul class="navbar-nav nav">';
        $primary_nav_menu .=  wp_list_categories(
            array(
                'title_li'     => '', 
                'hide_empty'   => 1 , 
                'taxonomy'     => 'product_cat',
                'hierarchical' => 1 ,
                'echo'         => 0 ,
                'depth'        => 1 ,
            )
        );
        $primary_nav_menu .= '</ul>';
    }

    return $primary_nav_menu;
}
endif;

// Departments Navigation
if( ! function_exists( 'media_center_department_nav_menu' ) ) :
function media_center_department_nav_menu(){
	$department_nav_menu = '';

	if ( has_nav_menu( 'departments' ) ) {

        $department_nav_menu .= wp_nav_menu( 
            array(
                'menu' 				=> 'departments',
                'theme_location' 	=> 'departments',
                'depth' 			=> 0,
                'container' 		=> false,
                'menu_class' 		=> 'dropdown-menu',
                'echo' 				=> 0,
                'walker' 			=> new wp_bootstrap_navwalker()
            )
        );

    } else {
        $department_nav_menu .= '<ul class="dropdown-menu">';
        $department_nav_menu .=  wp_list_categories(
            array(
                'title_li'     => '', 
                'hide_empty'   => 1 , 
                'taxonomy'     => 'product_cat',
                'hierarchical' => 0 ,
                'echo'         => 0 ,
            )
        );
        $department_nav_menu .= '</ul>';
    }

    return $department_nav_menu;
}
endif;

if( ! function_exists( 'media_center_filter_products_query' ) ) :
function media_center_filter_products_query( $args, $atts ){
	global $woocommerce_loop;

	if( isset($atts['product_item_size']) ){
		$woocommerce_loop['product_item_size'] = $atts['product_item_size'];
		$woocommerce_loop['screen_width'] = 100;
	}

	return $args;
}
endif;

add_filter( 'woocommerce_shortcode_products_query', 'media_center_filter_products_query', 10, 2 );

if( ! function_exists( 'woocommerce_products_live_search' ) ) :
function woocommerce_products_live_search(){
	if ( isset( $_REQUEST['fn'] ) && 'get_ajax_search' == $_REQUEST['fn'] ) {

		$query_args = array(
			'posts_per_page' 	=> 10,
            'no_found_rows' 	=> true,
            'post_type'			=> 'product',
		);

		if( isset( $_REQUEST['terms'] ) ) {
			$query_args['s'] = $_REQUEST['terms'];
		}

        $search_query = new WP_Query( $query_args );
 
        $results = array( );
        if ( $search_query->get_posts() ) {
            foreach ( $search_query->get_posts() as $the_post ) {
                $title = get_the_title( $the_post->ID );
                if ( has_post_thumbnail( $the_post->ID ) ) {
					$post_thumbnail_ID = get_post_thumbnail_id( $the_post->ID );
					$post_thumbnail_src = wp_get_attachment_image_src( $post_thumbnail_ID, 'thumbnail' );
				}else{
					$dimensions = wc_get_image_size( 'thumbnail' );
					$post_thumbnail_src = array(
						wc_placeholder_img_src(),
						esc_attr( $dimensions['width'] ),
						esc_attr( $dimensions['height'] )
					);
				}

				$product = new WC_Product( $the_post->ID );
				$price = $product->get_price_html();
				$brand = woocommerce_show_brand( $the_post->ID, true );
				$title = html_entity_decode( $title , ENT_QUOTES, 'UTF-8' );
                
                $results[] = array(
                    'value' 	=> $title,
                    'url' 		=> get_permalink( $the_post->ID ),
                    'tokens' 	=> explode( ' ', $title ),
                    'image' 	=> $post_thumbnail_src[0],
                    'price'		=> $price,
                    'brand'		=> $brand
                );
            }
        } else {
            $results[] = __( 'Sorry. No results match your search.', 'media_center' );
        }
 
        wp_reset_postdata();
        echo json_encode( $results );
    }
    die();
}
endif;

add_action( 'wp_ajax_nopriv_products_live_search', 'woocommerce_products_live_search' );
add_action( 'wp_ajax_products_live_search', 'woocommerce_products_live_search' );