<?php
/**
 * WooCommerce Gateway Amazon FPS
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce Gateway Amazon FPS to newer
 * versions in the future. If you wish to customize WooCommerce Gateway Amazon FPS for your
 * needs please refer to http://docs.woothemes.com/document/amazon-fps/ for more information.
 *
 * @package     WC-Gateway-Amazon-FPS/Classes
 * @author      SkyVerge
 * @copyright   Copyright (c) 2012-2015, SkyVerge, Inc.
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Amazon FPS Gateway class
 *
 * Handles all single purchases and payments-related actions
 *
 * Extended by the Addons class to provide subscriptions/pre-orders support
 *
 * @since 2.0
 * @extends \WC_Payment_Gateway
 */
class WC_Gateway_Amazon_FPS extends WC_Payment_Gateway {


	/** @var string AWS access key */
	protected $access_key;

	/** @var string AWS secret key */
	protected $secret_key;

	/** @var string the API environment, either `production` or `sandbox` */
	protected $environment;

	/** @var string 4 options for debug mode - off, checkout, log, both */
	public $debug_mode;


	/**
	 * Load payment gateway and related settings
	 *
	 * @since 2.0
	 * @return \WC_Gateway_Amazon_FPS
	 */
	public function __construct() {

		$this->id                 = 'amazon_fps';
		$this->method_title       = __( 'Amazon FPS', WC_Amazon_FPS::TEXT_DOMAIN );
		$this->method_description = __( 'Let customers checkout using any payment method stored in their Amazon.com account for single purchases, pre-orders, and subscriptions.', WC_Amazon_FPS::TEXT_DOMAIN );

		$this->supports = array( 'products' );

		// FPS doesn't have fields but enabling fields allows for a description to be added to the payment method selection
		$this->has_fields = true;

		$this->icon = apply_filters( 'woocommerce_amazon_fps_logo', $GLOBALS['wc_amazon_fps']->get_plugin_url() . '/assets/images/amazon-fps-logo.png' );

		// Load the form fields
		$this->init_form_fields();

		// Load the settings
		$this->init_settings();

		// Define user set variables
		foreach ( $this->settings as $setting_key => $setting ) {
			$this->$setting_key = $setting;
		}

		// pay page fallback
		add_action( 'woocommerce_receipt_' . $this->id, create_function( '$order', 'echo "<p>" . __( "Thank you for your order.", WC_Amazon_FPS::TEXT_DOMAIN ) . "</p>";' ) );

		// IPN listner / API logging hooks
		// note these are added only when the Addons class is not loaded, as otherwise the actions hooked in are duplicated, once for the Addons class and once for the base class
		if ( $GLOBALS['wc_amazon_fps']->gateway_class_name === get_class() ) {
			add_action( 'woocommerce_api_' . strtolower( get_class( $this ) ), array( $this, 'process_ipn' ) );
			add_action( 'wc_gateway_amazon_fps_api_request_performed', array( $this, 'log_api' ) );
		}

		// save settings
		if ( is_admin() ) {
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		}
	}


	/**
	 * Initialize payment gateway settings fields
	 *
	 * @since 2.0
	 */
	public function init_form_fields() {

		$this->form_fields = array(

			'enabled' => array(
				'title'       => __( 'Enable / Disable', WC_Amazon_FPS::TEXT_DOMAIN ),
				'label'       => __( 'Check this to enable the gateway.', WC_Amazon_FPS::TEXT_DOMAIN ),
				'type'        => 'checkbox',
				'default'     => 'no',
			),

			'title' => array(
				'title'       => __( 'Title', WC_Amazon_FPS::TEXT_DOMAIN ),
				'type'        => 'text',
				'desc_tip'    => __( 'Payment method title that the customer will see during checkout.', WC_Amazon_FPS::TEXT_DOMAIN ),
				'default'     => __( 'Amazon', WC_Amazon_FPS::TEXT_DOMAIN ),
			),

			'description' => array(
				'title'       => __( 'Description', WC_Amazon_FPS::TEXT_DOMAIN ),
				'type'        => 'textarea',
				'desc_tip'    => __( 'Payment method description that the customer will see during checkout. Limited HTML allowed.', WC_Amazon_FPS::TEXT_DOMAIN ),
				'default'     => __( 'Pay securely using your Amazon.com account', WC_Amazon_FPS::TEXT_DOMAIN ),
			),

			'environment' => array(
				'title'       => __( 'Environment', WC_Amazon_FPS::TEXT_DOMAIN ),
				'type'        => 'select',
				'desc_tip'    => __( 'What environment do you want your transactions posted to?', WC_Amazon_FPS::TEXT_DOMAIN ),
				'default'     => 'production',
				'options'     => array(
					'production'  => __( 'Production', WC_Amazon_FPS::TEXT_DOMAIN ),
					'sandbox'     => __( 'Sandbox', WC_Amazon_FPS::TEXT_DOMAIN ),
				),
			),

			'access_key' => array(
				'title'       => __( 'AWS Access Key', WC_Amazon_FPS::TEXT_DOMAIN ),
				'type'        => 'text',
				'description' => sprintf( __( 'The Access Key for your Amazon AWS account, please see the %sdocumentation%s for instructions on where to find this.', WC_Amazon_FPS::TEXT_DOMAIN ), '<a href="http://docs.woothemes.com/document/amazon-fps/">', '</a>' ),
				'default'     => '',
			),

			'secret_key' => array(
				'title'       => __( 'AWS Secret Key', WC_Amazon_FPS::TEXT_DOMAIN ),
				'type'        => 'text',
				'description' => sprintf( __( 'The Secret Key for your Amazon AWS account, please see the %sdocumentation%s for instructions on where to find this.', WC_Amazon_FPS::TEXT_DOMAIN ), '<a href="http://docs.woothemes.com/document/amazon-fps/">', '</a>' ),
				'default'     => '',
			),

			'debug_mode' => array(
				'title'       => __( 'Debug Mode', WC_Amazon_FPS::TEXT_DOMAIN ),
				'type'        => 'select',
				'desc_tip'    => __( 'Show Detailed Error Messages and API requests / responses on the checkout page and/or save them to the log for debugging purposes.', WC_Amazon_FPS::TEXT_DOMAIN ),
				'default'     => 'off',
				'options' => array(
					'off'      => __( 'Off', WC_Amazon_FPS::TEXT_DOMAIN ),
					'checkout' => __( 'Show on Checkout Page', WC_Amazon_FPS::TEXT_DOMAIN ),
					'log'      => __( 'Save to Log', WC_Amazon_FPS::TEXT_DOMAIN ),
					'both'     => __( 'Both', WC_Amazon_FPS::TEXT_DOMAIN )
				),
			),
		);
	}


	/**
	 * Checks for proper gateway configuration (required fields populated, etc)
	 * and that there are no missing dependencies
	 *
	 * @since 2.0
	 */
	public function is_available() {

		// is enabled check
		$is_available = parent::is_available();

		// proper configuration
		if ( ! $this->get_access_key() || ! $this->get_secret_key() ) {
			$is_available = false;
		}

		// all dependencies met
		if ( count( $GLOBALS['wc_amazon_fps']->get_missing_dependencies() ) > 0 ) {
			$is_available = false;
		}

		return apply_filters( 'wc_gateway_amazon_fps_is_available', $is_available );
	}


	/**
	 * Display the description if provided on the checkout page
	 *
	 * @since 2.0
	 */
	public function payment_fields() {

		if ( ! $this->is_production() ) {
			echo '<p>' . __( 'Sandbox Mode Enabled', WC_Amazon_FPS::TEXT_DOMAIN ) . '</p>';
		}

		if ( $this->description ) {
			echo '<p>' . wp_kses_post( $this->description ) . '</p>';
		}
	}


	/**
	 * Generate the redirect URL to Amazon to authorize the payment
	 *
	 * @since 2.0
	 * @param int $order_id the ID of the order
	 * @return array|void
	 */
	public function process_payment( $order_id ) {

		$order = $this->get_order( $order_id );

		try {

			// get the URL to redirect to
			$url = $this->get_api()->get_single_purchase_url( $order );

			// add to log
			$this->log( $url, 'request' );

			return array(
				'result'   => 'success',
				'redirect' => $url,
			);

		} catch ( Exception $e ) {

			$this->mark_order_as_failed( $order, $e->getMessage() );
		}
	}


	/**
	 * Add Amazon-specific data to the order object
	 *
	 * @since 2.0
	 * @param int $order_id order ID being processed
	 * @return \WC_Order instance
	 */
	protected function get_order( $order_id ) {

		$order = SV_WC_Plugin_Compatibility::wc_get_order( $order_id );

		// set the order total here so it can be modified later by Subscriptions if needed
		$order->amazon_order_total = number_format( $order->get_total(), 2, '.', '' );

		// set the order ID here so it can be modified for subscription renewal payments, which require a unique ID per transaction
		$order->amazon_caller_reference = $order->id;

		// get the URL that Amazon will redirect customer to after they've authorized the payment
		$order->amazon_return_url = $this->get_return_url( $order );

		// set a description, note this can be filtered using the `wc_amazon_fps_cbui_pipeline_parameters` inside the base CBUI class
		$order->amazon_description = sprintf( __( '%s - Order %s', WC_Amazon_FPS::TEXT_DOMAIN ), esc_html( get_bloginfo( 'name' ) ), $order->get_order_number() );

		// set the IPN URL for each order so customer doesn't need to configure it within their amazon account
		$order->amazon_ipn_url = $this->get_ipn_url();

		return $order;
	}


	/**
	 * Process the redirect from Amazon to the thank you page after the customer has authorized their payment method
	 *
	 * @since 2.0
	 * @throws Exception if verification of parameters fails
	 */
	public function process_redirect() {

		// clean data
		$data = stripslashes_deep( $_GET );

		// log query vars
		$this->log( print_r( $data, true ), 'request' );

		// get order ID & caller reference
		$order_id         = isset( $GLOBALS['wp']->query_vars['order-received'] ) ? absint( $GLOBALS['wp']->query_vars['order-received'] ) : 0;
		$caller_reference = ( isset( $data['callerReference'] ) ) ? absint( $data['callerReference'] ) : null;

		// require order ID
		if ( ! $order_id ) {
			wp_die( __( 'Order Failed, please contact us.', WC_Amazon_FPS::TEXT_DOMAIN ) );
		}

		// setup order
		$order = $this->get_order( $order_id );

		// protect against duplicate attempts if the customer refreshes the thank you page
		if ( metadata_exists( 'post', $order->id, '_wc_amazon_fps_token_id' ) ) {
			return;
		}

		try {

			// handle aborted transactions
			if ( isset( $data['status'] ) && 'A' == $data['status'] ) {
				throw new Exception( __( 'Customer aborted transaction', WC_Amazon_FPS::TEXT_DOMAIN ) );
			}

			// verify caller reference matches order ID
			if ( $order_id !== $caller_reference ) {
				throw new Exception( sprintf( __( 'Caller Reference %s does not match Order ID %s', WC_Amazon_FPS::TEXT_DOMAIN ), $caller_reference, $order_id ) );
			}

			// verify signature
			if ( ! $this->get_api()->verify_signature( $this->get_return_url( $order ), $data )->is_signature_valid() ) {
				throw new Exception( __( 'Signature Verification Failed', WC_Amazon_FPS::TEXT_DOMAIN ) );
			}

			// check for valid status
			// @link http://docs.aws.amazon.com/AmazonFPS/latest/FPSMarketplaceGuide/SingleUsePipeline.html#SingleuseStatusCode
			if ( ! in_array( $data['status'], array( 'SA', 'SB', 'SC' ) ) ) {
				throw new Exception( sprintf( __( 'Status: %s - %s', WC_Amazon_FPS::TEXT_DOMAIN ), $data['status'], ( isset( $data['errorMessage'] ) ) ? $data['errorMessage'] : __( 'N/A', WC_Amazon_FPS::TEXT_DOMAIN ) ) );
			}

			// add any warning messages as an order note
			if ( isset( $data['warningCode'] ) && isset( $data['warningMessage'] ) ) {
				$order->add_order_note( sprintf( __( 'Amazon Transaction Warning: Code %s - %s', WC_Amazon_FPS::TEXT_DOMAIN ), $data['warningCode'], $data['warningMessage'] ) );
			}

			// save tokenID + expiry + API environment
			update_post_meta( $order->id, '_wc_amazon_fps_token_id', $data['tokenID'] );
			update_post_meta( $order->id, '_wc_amazon_fps_api_environment', $this->get_environment() );

			if ( isset( $data['expiry'] ) ) {
				update_post_meta( $order->id, '_wc_amazon_fps_token_expiry', $data['expiry'] );
			}

			// finally, process the transaction if it hasn't already been processed
			$this->process_transaction( $order );

		} catch( Exception $e ) {

			$this->mark_order_as_failed( $order, __( 'Redirect: ', WC_Amazon_FPS::TEXT_DOMAIN ) . $e->getMessage() );
		}
	}


	/**
	 * Process the transaction using the token ID returned by the redirect to the thank you page
	 *
	 * @since 2.0
	 * @param WC_Order $order the WC order object
	 */
	protected function process_transaction( WC_Order $order ) {

		// process transaction via the FPS API
		$response = $this->get_api()->pay( $order );

		// get transaction ID
		$transaction_id = $response->get_transaction_id();

		// add transaction ID as order meta
		update_post_meta( $order->id, '_wc_amazon_fps_transaction_id', $transaction_id );

		$order->add_order_note( sprintf( __( 'Amazon Payment Pending, awaiting IPN (ID: %s)', WC_Amazon_FPS::TEXT_DOMAIN ), $transaction_id ) );
	}


	/** IPN methods ******************************************************/


	/**
	 * Process IPN notifications
	 *
	 * @since 2.0
	 */
	public function process_ipn() {

		// get the IPN data along with a valid WC_Order instance
		$ipn_data = $this->get_ipn_data();

		$order = $ipn_data['order'];
		$data  = $ipn_data['data'];

		try {

			// verify all required IPN data is present, signature is valid, etc.
			$this->verify_ipn_data( $order, $data );

			// update the order status based on the IPN status
			$this->process_ipn_status( $order, $data );

		} catch( Exception $e ) {

			$this->mark_order_as_failed( $order, __( 'IPN: ', WC_Amazon_FPS::TEXT_DOMAIN ) . $e->getMessage() );
		}

		// send success
		header( 'HTTP/1.1 200 OK' );
	}


	/**
	 * Gets the posted IPN data and sets up the WC_Order object from the passed order ID
	 *
	 * @since 2.0
	 */
	protected function get_ipn_data() {

		// parse raw POST data
		$data = stripslashes_deep( $_POST );

		// log query vars
		$this->log( print_r( $data, true ) );

		// set order ID
		$order_id = ( isset( $data['callerReference'] ) ) ? absint( $data['callerReference'] ) : null;

		// strip off any additional params added to the order ID
		if ( false !== strpos( $order_id, '-' ) )
			$order_id = substr( $order_id, 0, strpos( $order_id, '-' ) );

		// order ID is required
		if ( ! $order_id ) {
			$this->log( __( 'IPN Failure: Invalid or missing order ID', WC_Amazon_FPS::TEXT_DOMAIN ) );
			die;
		}

		// setup order
		$order = $this->get_order( $order_id );

		return array( 'order' => $order, 'data' => $data );
	}


	/**
	 * Verify the IPN data is valid by checking:
	 *
	 * 1) Required fields are present
	 * 2) The provided transaction ID matches the one stored on the order
	 * 3) The signature is valid
	 *
	 * @since 2.0
	 * @param WC_Order $order the WC order object
	 * @param array $data the IPN data
	 * @throws Exception if any of the above checks fail
	 */
	protected function verify_ipn_data( WC_Order $order, array $data ) {

		// transaction ID is required
		if ( ! isset( $data['transactionId'] ) ) {
			throw new Exception( __( 'Transaction ID is missing', WC_Amazon_FPS::TEXT_DOMAIN ) );
		}

		// transaction status is required
		if ( ! isset( $data['transactionStatus'] ) ) {
			throw new Exception( __( 'Transaction status is missing', WC_Amazon_FPS::TEXT_DOMAIN ) );
		}

		// verify transaction ID matches stored transaction ID
		if ( $data['transactionId'] !== $order->wc_amazon_fps_transaction_id ) {
			throw new Exception( sprintf( __( 'Provided transaction ID %s does not match stored transaction ID %s', WC_Amazon_FPS::TEXT_DOMAIN ), $data['transactionId'], $order->wc_amazon_fps_transaction_id ) );
		}

		// verify signature
		if ( ! $this->get_api()->verify_signature( $this->get_ipn_url(), $data )->is_signature_valid() ) {
			throw new Exception( __( 'Signature Verification Failed', WC_Amazon_FPS::TEXT_DOMAIN ) );
		}
	}


	/**
	 * Process the IPN status returned by marking the order appropriately
	 *
	 * @since 2.0
	 * @param WC_Order $order the WC order object
	 * @param array $data the IPN data
	 */
	protected function process_ipn_status( WC_Order $order, array $data ) {

		// update the order status based on the transaction status returned
		switch ( $data['transactionStatus'] ) {

			case 'SUCCESS':
				$this->mark_order_as_processing( $order, $data['transactionId'] );
				break;

			case 'FAILURE':
				$this->mark_order_as_failed( $order, sprintf( __( 'ID: %s - Code: %s - %s', WC_Amazon_FPS::TEXT_DOMAIN ), $data['transactionId'], $data['statusCode'], $data['statusMessage'] ) );
				break;

			case 'CANCELLED':
				$this->mark_order_as_cancelled( $order, $data['transactionId'] );
				break;
		}
	}


	/** Helpers ******************************************************/


	/**
	 * Mark an order as processing and payment completed
	 *
	 * @since 2.0
	 * @param WC_Order $order the WC order object
	 * @param string $transaction_id the Amazon transaction ID
	 */
	protected function mark_order_as_processing( WC_Order $order, $transaction_id = '' ) {

		// bail if order is already is processing/completed
		if ( SV_WC_Plugin_Compatibility::order_has_status( $order, array( 'processing', 'completed' ) ) ) {
			return;
		}

		// add order note
		if ( $transaction_id ) {
			$order->add_order_note( sprintf( __( 'Amazon Transaction Approved (ID: %s)', WC_Amazon_FPS::TEXT_DOMAIN ), $transaction_id ) );
		} else {
			$order->add_order_note( __( 'Amazon Token Approved', WC_Amazon_FPS::TEXT_DOMAIN ) ); // free-trial subscription or pre-orders don't have a transaction ID for the initial transaction
		}

		// mark as payment received
		$order->payment_complete();
	}


	/**
	 * Mark an order as cancelled
	 *
	 * @since 2.0
	 * @param WC_Order $order the WC order object
	 * @param string $transaction_id the Amazon transaction ID
	 */
	protected function mark_order_as_cancelled( WC_Order $order, $transaction_id ) {

		if ( ! SV_WC_Plugin_Compatibility::order_has_status( $order, 'cancelled' ) ) {
			$order->update_status( 'cancelled', sprintf( __( 'Amazon Transaction Cancelled (ID: %s)', WC_Amazon_FPS::TEXT_DOMAIN ), $transaction_id ) );
		}
	}


	/**
	 * Mark the given order as failed and set the order note
	 *
	 * @since 2.0
	 * @param WC_Order $order the WC order object
	 * @param string $message a message to display inside the 'Amazon Transaction Failed' order note
	 */
	protected function mark_order_as_failed( WC_Order $order, $message = '' ) {

		$order_note = sprintf( __( 'Amazon Transaction Failed (%s)', WC_Amazon_FPS::TEXT_DOMAIN ), $message );

		// Mark order as failed if not already set, otherwise, make sure we add the order note so we can detect when someone fails to check out multiple times
		if ( ! SV_WC_Plugin_Compatibility::order_has_status( $order, 'failed' ) ) {
			$order->update_status( 'failed', $order_note );
		} else {
			$order->add_order_note( $order_note );
		}

		// add customer-facing error message
		SV_WC_Helper::wc_add_notice( __( 'An error occurred, please try again or try an alternate form of payment.', WC_Amazon_FPS::TEXT_DOMAIN ), 'error' );

		// log the error message
		$this->log( $message );
	}


	/**
	 * Adds debug messages to the page as a WC message/error, and / or to the WC Error log
	 *
	 * @since 2.0
	 * @param string $message to add
	 * @param string $display how to display message, defaults to `message`, options are:
	 *   + `message` - display with WC message style, no formatting
	 *   + `request` - display with WC message style and <pre> formatting
	 *   + `response` - display with WC message style and XML/<pre> formatting
	 *   + `error` - display with WC error style, no formatting
	 */
	protected function log( $message, $display = 'message' ) {

		// do nothing when debug mode is off or blank message
		if ( 'off' == $this->debug_mode || ! $message ) {
			return;
		}

		// add message to checkout page
		if ( 'checkout' === $this->debug_mode || 'both' === $this->debug_mode ) {

			switch ( $display ) {

				case 'message':
					SV_WC_Helper::wc_add_notice( $message );
					break;

				case 'request':
					SV_WC_Helper::wc_add_notice( __( 'API Request: ', WC_Amazon_FPS::TEXT_DOMAIN ) . '<br/><pre>' . $message . '</pre>' );
					break;

				case 'response':
					$dom = new DOMDocument();
					$dom->loadXML( $message );
					$dom->formatOutput = true;
					SV_WC_Helper::wc_add_notice( __( 'API Response: ', WC_Amazon_FPS::TEXT_DOMAIN ) . '<br/><pre>' . htmlspecialchars( $dom->saveXML() ) . '</pre>' );
					break;

				case 'error':
					SV_WC_Helper::wc_add_notice( $message, 'error' );
					break;

			}
		}

		// add message to WC log
		if ( 'log' === $this->debug_mode || 'both' === $this->debug_mode ) {
			$GLOBALS['wc_amazon_fps']->log( $message );
		}
	}


	/**
	 * Lazy load the Amazon FPS API. Note the API instance is set on the main plugin class, and not the gateway class
	 * to avoid conflicts when the Addons class is in use
	 *
	 * @since 2.0
	 */
	protected function get_api() {

		if ( ! $this->is_available() ) {
			throw new Exception( __( 'The gateway must be configured before use.', WC_Amazon_FPS::TEXT_DOMAIN ) );
		}

		if ( wc_amazon_fps()->api instanceof WC_Amazon_FPS_API ) {
			return wc_amazon_fps()->api;
		}

		// load Amazon FPS API wrapper
		require( wc_amazon_fps()->get_plugin_path() . '/includes/api/class-wc-amazon-fps-api.php' );

		// setup
		return wc_amazon_fps()->api = new WC_Amazon_FPS_API( $this->get_access_key(), $this->get_secret_key(), $this->get_environment() );
	}


	/**
	 * Log FPS API request/responses, hooked into `wc_amazon_fps_api_request_performed`
	 *
	 * @since 2.0
	 */
	public function log_api() {

		// log request URI
		$this->log( $this->get_api()->get_request_url(), 'request' );

		// log response XML
		$this->log( $this->get_api()->get_response_xml(), 'response' );
	}


	/** Getters ******************************************************/


	/**
	 * Return whether transactions should be processed in production environment or not
	 *
	 * @since 2.0
	 * @return bool, true if transactions should be processed in production environment, false otherwise
	 */
	public function is_production() {

		return ( 'production' === $this->get_environment() );
	}


	/**
	 * Get the AWS Access Key
	 *
	 * @since 2.0
	 * @return string access key
	 */
	protected function get_access_key() {

		return $this->access_key;
	}


	/**
	 * Get the AWS Secret Key
	 *
	 * @since 2.0
	 * @return string access key
	 */
	protected function get_secret_key() {

		return $this->secret_key;
	}


	/**
	 * Get the current environment
	 *
	 * @since 2.0
	 * @return string environment
	 */
	protected function get_environment() {

		return $this->environment;
	}


	/**
	 * Get the IPN URL to provide to Amazon for each API request
	 *
	 * @since 2.0
	 * @return string environment
	 */
	protected function get_ipn_url() {

		return add_query_arg( array( 'wc-api' => $GLOBALS['wc_amazon_fps']->gateway_class_name ), home_url( '/' ) );
	}


}
