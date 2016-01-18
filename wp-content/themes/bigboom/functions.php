<?php
/**
 * Theme Alien Core functions and definitions
 *
 * @package BigBoom
 */

/**
 * Define theme's constant
 */
if ( ! defined( 'THEME_VERSION' ) ) {
	define( 'THEME_VERSION', '1.0.4' );
}
if ( ! defined( 'THEME_DIR' ) ) {
	define( 'THEME_DIR', get_template_directory() );
}
if ( ! defined( 'THEME_URL' ) ) {
	define( 'THEME_URL', get_template_directory_uri() );
}

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640;
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @since  1.0
 *
 * @return void
 */
function bigboom_setup() {

	// Make theme available for translation.
	load_theme_textdomain( 'bigboom', get_template_directory() . '/lang' );

	// Theme supports
	add_theme_support( 'woocommerce' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );
	add_theme_support( 'html5', array(
		'comment-list',
		'search-form',
		'comment-form',
		'gallery',
	) );

	// Register theme nav menu
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'bigboom' ),
		'footer'  => __( 'Footer Menu', 'bigboom' ),
		'mobile'  => __( 'Mobile Menu', 'bigboom' ),
	) );
}
add_action( 'after_setup_theme', 'bigboom_setup' );

/**
 * Initialize additional functions/classes
 *
 * @since 1.0
 */
function bigboom_init() {
	new BigBoom_VC;
	new BigBoom_WooCommerce;

	if ( is_admin() ) {
		new Bigboom_Walker_Nav_Menu_Custom_Fields;

		new TA_Demo_Import( array(
			'home_page' => 'Home Page 1',
			'blog_page' => 'Blog',
			'base_url'  => 'http://demo.themealien.com/bigboom-2/',
			'sliders'   => true,
		) );
	} else {
		new BigBoom_Shortcodes;
	}
}
add_action( 'after_setup_theme', 'bigboom_init', 20 );

/**
 * Add image sizes.
 * Must be added to init hook to remove sizes of portfolio plugin.
 *
 * @since 1.0
 */
function bigboom_add_image_sizes() {
	add_image_size( 'blog-thumb-tiny', 113, 113, true );
	add_image_size( 'blog-small-thumb', 555, 245, true );
	add_image_size( 'blog-thumb', 870, 385, true );
	add_image_size( 'blog-large-thumb', 1170, 520, true );
	add_image_size( 'widget-thumb', 125, 135, true );
}
add_action( 'after_setup_theme', 'bigboom_add_image_sizes', 10 );

/**
 * Register widgetized area and update sidebar with default widgets.
 *
 * @since 1.0
 *
 * @return void
 */
function bigboom_register_sidebar() {
	// Register topbar sidebar
	register_sidebar( array(
		'name'          => __( 'Topbar Left', 'bigboom' ),
		'id'            => 'topbar-left',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => __( 'Topbar Right', 'bigboom' ),
		'id'            => 'topbar-right',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	// Register header sidebar
	register_sidebar( array(
		'name'          => __( 'Header Right', 'bigboom' ),
		'id'            => 'header-sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	// Register shop sidebar
	register_sidebar( array(
		'name'          => __( 'Shop Sidebar', 'bigboom' ),
		'id'            => 'shop-sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	// Register blog sidebar
	register_sidebar( array(
		'name'          => __( 'Blog Sidebar', 'bigboom' ),
		'id'            => 'blog-sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	// Register page sidebar
	register_sidebar( array(
		'name'          => __( 'Page Sidebar', 'bigboom' ),
		'id'            => 'page-sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	// Register footer sidebar
	register_sidebars( 5, array(
		'name'          => __( 'Footer', 'bigboom' ) . ' %d',
		'id'            => 'footer-sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
}
add_action( 'widgets_init', 'bigboom_register_sidebar' );

/* <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< ( ADMIN MODIFIED) */
/**
 * _______________ START BLOCK ______________
 *              MENU ON TOP RIGHT
 *	Add custom top-right menu
 *
 *	@return string
 *
 */
function wps_add_login_logout_link($items, $args) {
	
	$login = __('<i class="fa fa-users"></i> Members Area');
	$logout = __('<i class="fa fa-user"></i> Account');

	//use one of the following methods of identification
	$menu_id = '284';
	$menu_name =''; //name you gave to the menu
	$menu_slug =''; //slug of the menu, generally menu_name reduced to lowercase

	if ( ! is_user_logged_in() ) 
	$link = '<a href="' . esc_url( '/login/') . '">' . $login . '</a>';
	else
	$link = '<a href="' . esc_url( '/account/' ) . '">' . $logout . '</a>';
	
	/** check menu **/
	// if ( ($menu_id) && ($args->menu->term_id == $menu_id) )
	// $items .= '<li>'. $link .'</li>';
	// else
	if ( (($menu_name) && ($args->menu->name == $menu_name))  or ($menu_id && ($args->menu->term_id == $menu_id))) {
		if( is_user_logged_in()) $items .= 	
			'<li>'. $link .
				'<ul class="sub-menu">
					<li><a href="'.  esc_url( '/account/#Profile') .'" onclick="document.getElementById(\'ui-id-1\').click()"><i class="fa fa-edit"></i> Edit Account</a></li>
					<li><a  href="'.  esc_url( '/account/#Orders') .'" onclick="document.getElementById(\'ui-id-2\').click()"><i class="fa fa-suitcase"></i> My Orders</a></li>
					<li><a href="'.  wp_logout_url( home_url() ) .'"><i class="fa fa-sign-out"></i> Logout</a></li>
				</ul>'.
			'</li>';

		else $items .= '<li>'. $link .
				'<ul class="sub-menu">
					<li><a href="'.  esc_url( '/login/') .'"><i class="fa fa-unlock-alt"></i> &nbsp;&nbsp;Login</a></li>
					<li><a href="'.  esc_url( '/register/') .'"><i class="fa fa-user-plus"></i> Register</a></li>
				</ul>'.
			'</li>';
	}	
	elseif ( ($menu_slug) && ($args->menu->slug == $menu_slug) )
	$items .= '<li>'. $link .'</li>';

	return $items;
}
add_filter('wp_nav_menu_items', 'wps_add_login_logout_link', 10, 2);
/////////////////////// -- END --/////////////////////////////

/*
 * _______________ START BLOCK _______________
 *                 REDIRECTION
 *	Add condition -> if empty cart redirect
 *
 *	@return string
 *
 */
function init_redirection() {
    global $woocommerce;

    //check if empty cart
    if ( is_page('cart') and !sizeof($woocommerce->cart->cart_contents) ) {
        wp_redirect( '/cart/cart-empty/' ); exit;
    }

    if ( is_page('cart/cart-empty') and sizeof($woocommerce->cart->cart_contents) ) {
        wp_redirect( '/cart' ); exit;
    }

    //check if logged
    if ( (is_page('login') or is_page('register')) and is_user_logged_in() ) {
        wp_redirect( '/account/' ); exit;
    }

    //check if not logged
    if ( is_page('account') and !is_user_logged_in() ) {
        wp_redirect( '/login/' ); exit;
    }


    //echo (get_post_meta(4427, 'festiUserRolePrices', true));
    //exit;
}
add_action( 'loop_start', 'init_redirection' );
/////////////////////// -- END --/////////////////////////////

/*
 * _______________ START BLOCK _______________
 *                REGISTRATION
 *	Add fields and validation to woocommerce registration
 *
 *	@return string
 *
 */
add_filter('woocommerce_registration_errors', 'registration_errors_validation', 10,3);
function registration_errors_validation($reg_errors, $sanitized_user_login, $user_email) {
	global $woocommerce;
	extract( $_POST );	
	if ( !preg_match("/^[a-zA-Z'-]+$/", $first_name) ) {
		return new WP_Error( 'registration-error', __( 'Invalid first name.', 'woocommerce' ) );
	}

	if ( !preg_match("/^[a-zA-Z'-]+$/", $last_name) ) {
		return new WP_Error( 'registration-error', __( 'Invalid last name.', 'woocommerce' ) );
	}

	if ( strcmp( $password, $password2 ) !== 0 ) {
		return new WP_Error( 'registration-error', __( 'Passwords do not match.', 'woocommerce' ) );
	}

	return $reg_errors;
}

//on user registration
add_action('user_register', 'add_user_custom_meta');
function add_user_custom_meta($user_id) {
    extract( $_POST );

    $fname = sanitize_text_field( $first_name );
    $lname = sanitize_text_field( $last_name );

	if ( isset( $_POST['first_name'] ) ) {
	    update_user_meta($user_id, 'first_name', $fname);
	    update_user_meta($user_id, 'billing_first_name', $fname);
	}

	if ( isset( $_POST['last_name'] ) ) {
	    update_user_meta($user_id, 'last_name', $lname);
	    update_user_meta($user_id, 'billing_last_name', $lname);
	}

	if ( isset( $_POST['gender'] ) ) {
	    update_user_meta($user_id, 'gender', $gender);
	}

	if ( ! empty( $_POST['role'] ) ) {
        wp_update_user( array( 'ID' => $user_id, 'role' => $_POST['role'] ) );
    }

	//wp_mail($user_info->user_email, 'User first and last name', sprintf('Hi we have added your first name :-% and last name:- % to our site.',$_POST['first_name'],$_POST['last_name']));
}
/////////////////////// -- END --/////////////////////////////

/*
 * _______________ START BLOCK _______________
 *                USER EDITTING
 *	Add fields and validation to woocommerce registration
 *
 *	@return string
 *
 */
//on user update profile
add_action( 'profile_update', 'edit_user_save_custom_meta', 10, 2 );
function edit_user_save_custom_meta($user_id) {

    extract( $_POST );
    if ( isset( $_POST['account_gender'] ) )
	    update_user_meta($user_id, 'gender', $account_gender);

	if ( ! empty( $_POST['role'] ) ) {
		//update_user_meta($user_id, 'role', $role);
        //wp_update_user( array( 'ID' => $user_id, 'role' => $_POST['role'] ) );
        // Fetch the WP_User object of our user.
		$u = new WP_User( $user_id );

		// Replace the current role with 'editor' role
		$u->set_role(  $_POST['role'] );
    }
}
/////////////////////// -- END --/////////////////////////////
/*
 * _______________ START BLOCK _______________
 *                ADD LINK STYLE/JS
 *	Add fields and validation to woocommerce registration
 *
 *	@return string
 *
 */
//on user update profile
/**
 * Proper way to enqueue scripts and styles
 */
function theme_name_scripts() {
	//style
	wp_enqueue_style( 'style-name-1', get_template_directory_uri() . '/_admin/plugins/dropdown-heapbox/themes/belize_hole/css/belize_hole.css', array(), '1.0.0', false );
	wp_enqueue_style( 'style-name-2', get_template_directory_uri() . '/_admin/plugins/dinbror_bpopup/style.min.css', array(), '1.0.0', false );
	//script
	wp_enqueue_script( 'script-name-1', get_template_directory_uri() . '/_admin/plugins/dropdown-heapbox/src/jquery.heapbox-0.9.4.min.js', array(), '1.0.0', false );
	wp_enqueue_script( 'script-name-2', get_template_directory_uri() . '/_admin/plugins/dinbror_bpopup/jquery.bpopup.min.js', array(), '1.0.0', false );
}

add_action( 'wp_enqueue_scripts', 'theme_name_scripts' );
/////////////////////// -- END --/////////////////////////////

include_once( get_theme_root() . '/bigboom/_admin-sync-product.php' );


function getCurrentUrl() {

	$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') 
                === FALSE ? 'http' : 'https';
	$host     = $_SERVER['HTTP_HOST'];
	$script   = $_SERVER["REQUEST_URI"];
	$params   = $_SERVER['QUERY_STRING'];
	 
	$currentUrl = $protocol . '://' . $host . $script;
	 
	return $currentUrl;
}



// FIX URL
// update_option('siteurl','http://www.qualitair.com.au');
// update_option('home','http://www.qualitair.com.au');
/**
 * Load theme
 */

// Theme Options
require THEME_DIR . '/inc/libs/theme-options/framework.php';
require THEME_DIR . '/inc/backend/theme-options.php';

// Widgets
require THEME_DIR . '/inc/widgets/widgets.php';

// Visual composer
require THEME_DIR . '/inc/backend/visual-composer.php';

// Woocommerce hooks
require THEME_DIR . '/inc/frontend/woocommerce.php';

if ( is_admin() ) {
	require THEME_DIR . '/inc/libs/class-tgm-plugin-activation.php';
	require THEME_DIR . '/inc/backend/plugins.php';
	require THEME_DIR . '/inc/backend/meta-boxes.php';
	require THEME_DIR . '/inc/backend/nav-menus.php';
	require THEME_DIR . '/inc/importer/importer.php';
} else {
	// Frontend functions and shortcodes
	require THEME_DIR . '/inc/functions/media.php';
	require THEME_DIR . '/inc/functions/nav.php';
	require THEME_DIR . '/inc/functions/mega-menu-walker.php';
	require THEME_DIR . '/inc/functions/layout.php';
	require THEME_DIR . '/inc/functions/entry.php';
	require THEME_DIR . '/inc/functions/shortcodes.php';

	// Frontend hooks
	require THEME_DIR . '/inc/frontend/layout.php';
	require THEME_DIR . '/inc/frontend/header.php';
	require THEME_DIR . '/inc/frontend/nav.php';
	require THEME_DIR . '/inc/frontend/entry.php';
	require THEME_DIR . '/inc/frontend/footer.php';
}
