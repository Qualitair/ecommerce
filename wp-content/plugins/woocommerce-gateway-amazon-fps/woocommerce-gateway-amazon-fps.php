<?php
/**
 * Plugin Name: WooCommerce Amazon FPS Gateway
 * Plugin URI: http://www.woothemes.com/products/amazon-fps/
 * Description: Adds the Amazon FPS Payment Gateway to your WooCommerce store, allowing customers to securely use their Amazon account with single purchases, pre-orders, subscriptions, and more!
 * Author: SkyVerge
 * Author URI: http://www.skyverge.com
 * Version: 2.2.0
 * Text Domain: woocommerce-gateway-amazon-fps
 * Domain Path: /i18n/languages/
 *
 * Copyright: (c) 2012-2014 SkyVerge, Inc. (info@skyverge.com)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package   WC-Gateway-Amazon-FPS
 * @author    SkyVerge
 * @category  Gateway
 * @copyright Copyright (c) 2012-2015, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Required functions
if ( ! function_exists( 'woothemes_queue_update' ) ) {
	require_once( 'woo-includes/woo-functions.php' );
}

// Plugin updates
woothemes_queue_update( plugin_basename( __FILE__ ), '20ef565e8e970977f76d2957564cde29', '18634' );

// WC active check
if ( ! is_woocommerce_active() ) {
	return;
}

// Required library class
if ( ! class_exists( 'SV_WC_Framework_Bootstrap' ) ) {
	require_once( 'lib/skyverge/woocommerce/class-sv-wc-framework-bootstrap.php' );
}

SV_WC_Framework_Bootstrap::instance()->register_plugin( '3.1.0', __( 'WooCommerce Amazon FPS Gateway', 'woocommerce-gateway-amazon-fps' ), __FILE__, 'init_woocommerce_gateway_amazon_fps', array( 'minimum_wc_version' => '2.1', 'backwards_compatible' => '3.1.0' ) );

function init_woocommerce_gateway_amazon_fps() {

/**
 * # WooCommerce Amazon FPS Main Plugin Class
 *
 * ## Plugin Overview
 *
 * This plugin adds Amazon FPS as a payment gateway. Subscriptions and Pre-Orders are supported via
 * the Add-Ons class.
 *
 * ## Database
 *
 * ### Global Settings
 *
 * + `woocommerce_amazon_fps_settings` - the serialized Amazon FPS settings array
 *
 * ### Options table
 *
 * + `wc_amazon_fps_version` - the current plugin version, set on install/upgrade
 *
 * ### Order Meta
 * + `_wc_amazon_fps_token_id` - the amazon sender token ID, used to create pay requests to complete transactions
 * + `_wc_amazon_fps_api_environment` - the environment the amazon transaction was created
 * + `_wc_amazon_fps_token_expiry` - the default expiration date for the amazon token
 * + `_wc_amazon_fps_transaction_id` - the transaction ID for the amazon transaction
 *
 */
class WC_Amazon_FPS extends SV_WC_Plugin {


	/** plugin version number */
	const VERSION = '2.2.0';

	/** @var WC_Amazon_FPS single instance of this plugin */
	protected static $instance;

	/** plugin id */
	const PLUGIN_ID = 'amazon_fps';

	/** plugin text domain */
	const TEXT_DOMAIN = 'woocommerce-gateway-amazon-fps';

	/** @var string class to load as gateway, can be base or add-ons class */
	public $gateway_class_name = 'WC_Gateway_Amazon_FPS';

	/** @var \WC_Amazon_FPS_API API instance */
	public $api;


	/**
	 * Initializes the plugin
	 *
	 * @since 2.0
	 */
	public function __construct() {

		parent::__construct(
			self::PLUGIN_ID,
			self::VERSION,
			self::TEXT_DOMAIN,
			array( 'dependencies' => array( 'dom', 'SimpleXML', 'xmlwriter' ) )
		);

		// include required files
		add_action( 'sv_wc_framework_plugins_loaded', array( $this, 'includes' ) );

		// admin
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {

			// tweak the CSS for the order notes so super-long Amazon transaction IDs fit properly
			add_action( 'admin_print_styles', array( $this, 'render_embedded_styles' ), 1 );
		}

		// process the redirect from Amazon after a customer confirms their payment
		add_action( 'wp', array( $this, 'process_redirect' ) );
	}


	/**
	 * Include required files
	 *
	 * @since 2.0
	 */
	public function includes() {

		// base gateway class
		require_once( 'includes/class-wc-gateway-amazon-fps.php' );

		// load add-ons class if subscriptions and/or pre-orders are active
		if ( $this->is_subscriptions_active() || $this->is_pre_orders_active() ) {

			require_once( 'includes/class-wc-gateway-amazon-fps-addons.php' );

			$this->gateway_class_name = 'WC_Gateway_Amazon_FPS_Addons';
		}

		// add to WC payment methods
		add_filter( 'woocommerce_payment_gateways', array( $this, 'load_gateway' ) );
	}


	/**
	 * Adds Amazon FPS to the list of available payment gateways
	 *
	 * @since 2.0
	 * @param array $gateways
	 * @return array $gateways
	 */
	public function load_gateway( $gateways ) {

		$gateways[] = $this->gateway_class_name;

		return $gateways;
	}


	/**
	 * Process the redirect from Amazon when the customer has confirmed payment
	 *
	 * @since 2.0
	 */
	public function process_redirect() {

		if ( ! empty( $_GET['signature'] ) && ! empty( $_GET['signatureVersion'] ) && ! empty( $_GET['signatureMethod'] ) && ! empty( $_GET['tokenID'] ) ) {

			$gateway = new $this->gateway_class_name;

			$gateway->process_redirect();

		}
	}


	/**
	 * Handle localization, WPML compatible
	 *
	 * @since 2.0
	 * @see SV_WC_Plugin::load_translation()
	 */
	public function load_translation() {

		load_plugin_textdomain( 'woocommerce-gateway-amazon-fps', false, dirname( plugin_basename( $this->get_file() ) ) . '/i18n/languages' );
	}


	/** Admin methods ******************************************************/


	/**
	 * Render CSS to prevent super long Amazon transaction IDs from overflowing the order note boxes
	 * @since 2.0
	 */
	public function render_embedded_styles() {
		global $pagenow, $post_type;

		if ( 'post.php' === $pagenow && 'shop_order' === $post_type ) {
			echo '<style type="text/css">ul.order_notes li .note_content { text-overflow: clip; overflow: hidden; }</style>';
		}
	}


	/** Helper methods ******************************************************/


	/**
	 * Main Amazon FPS Instance, ensures only one instance is/can be loaded
	 *
	 * @since 2.2.0
	 * @see WC_Amazon_FPS()
	 * @return WC_Amazon_FPS
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}


	/**
	 * Checks is WooCommerce Subscriptions is active
	 *
	 * @since 2.0
	 * @return bool true if WCS is active, false if not active
	 */
	public function is_subscriptions_active() {

		return $this->is_plugin_active( 'woocommerce-subscriptions.php' );
	}


	/**
	 * Checks is WooCommerce Pre-Orders is active
	 *
	 * @since 2.0
	 * @return bool true if WC Pre-Orders is active, false if not active
	 */
	public function is_pre_orders_active() {

		return $this->is_plugin_active( 'woocommerce-pre-orders.php' );
	}


	/**
	 * Returns the plugin name, localized
	 *
	 * @since 2.1
	 * @see SV_WC_Plugin::get_plugin_name()
	 * @return string the plugin name
	 */
	public function get_plugin_name() {
		return __( 'WooCommerce Amazon FPS Gateway', self::TEXT_DOMAIN );
	}


	/**
	 * Returns __FILE__
	 *
	 * @since 2.1
	 * @see SV_WC_Plugin::get_file()
	 * @return string the full path and filename of the plugin file
	 */
	protected function get_file() {
		return __FILE__;
	}


	/**
	 * Gets the plugin documentation url
	 *
	 * @since 2.1
	 * @see SV_WC_Plugin::get_documentation_url()
	 * @return string documentation URL
	 */
	public function get_documentation_url() {
		return 'http://docs.woothemes.com/document/amazon-fps/';
	}


	/**
	 * Gets the gateway configuration URL
	 *
	 * @since 2.1
	 * @see SV_WC_Plugin::get_settings_url()
	 * @param string $_ unused
	 * @return string plugin settings URL
	 */
	public function get_settings_url( $_ = null ) {

		return admin_url( 'admin.php?page=wc-settings&tab=checkout&section=' . strtolower( $this->gateway_class_name ) );
	}


	/**
	 * Returns true if on the gateway settings page
	 *
	 * @since 2.1
	 * @see SV_WC_Plugin::is_plugin_settings()
	 * @return boolean true if on the admin gateway settings page
	 */
	public function is_plugin_settings() {

		return isset( $_GET['page'] ) && 'wc-settings' == $_GET['page'] &&
		isset( $_GET['tab'] ) && 'checkout' == $_GET['tab'] &&
		isset( $_GET['section'] ) && strtolower( $this->gateway_class_name ) == $_GET['section'];
	}


	/** Lifecycle methods ******************************************************/


	/**
	 * Perform any version-related changes.
	 *
	 * @since 2.0
	 * @param int $installed_version the currently installed version of the plugin
	 */
	protected function upgrade( $installed_version ) {

		// pre-2.0 upgrade
		if ( version_compare( $installed_version, '2.0', '<' ) ) {

			// get existing settings
			$settings = get_option( 'woocommerce_amazon_fps_settings' );

			$new_settings = array();

			// migrate settings
			$new_settings['enabled']     = ( isset( $settings['enabled'] ) ) ? $settings['enabled'] : 'no';
			$new_settings['title']       = ( isset( $settings['title'] ) ) ? $settings['title'] : __( 'Amazon', self::TEXT_DOMAIN );
			$new_settings['description'] = ( isset( $settings['description'] ) ) ? $settings['description'] : __( 'Pay securely using your Amazon.com account', self::TEXT_DOMAIN );
			$new_settings['environment'] = ( isset( $settings['sandbox'] ) && 'yes' === $settings['sandbox'] ) ? 'sandbox' : 'production';
			$new_settings['access_key']  = ( isset( $settings['accesskey'] ) ) ? $settings['accesskey'] : '';
			$new_settings['secret_key']  = ( isset( $settings['secretkey'] ) ) ? $settings['secretkey'] : '';
			$new_settings['debug_mode']  = ( isset( $settings['debug_mode'] ) && 'yes' === $settings['debug_mode'] ) ? 'log' : 'off';

			// update to new settings
			update_option( 'woocommerce_amazon_fps_settings', $new_settings );
		}
	}


} // end \WC_Amazon_FPS


/**
 * Returns the One True Instance of Amazon FPS
 *
 * @since 2.2.0
 * @return WC_Amazon_FPS
 */
function wc_amazon_fps() {
	return WC_Amazon_FPS::instance();
}


/**
 * The WC_Amazon_FPS global object, exists only for backwards compat
 *
 * @deprecated 2.2.0
 * @name $wc_amazon_fps
 * @global WC_Amazon_FPS $GLOBALS['wc_amazon_fps']
 */
$GLOBALS['wc_amazon_fps'] = wc_amazon_fps();


} // init_woocommerce_gateway_amazon_fps()
