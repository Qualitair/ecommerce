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
 * Amazon FPS API Wrapper
 *
 * Inspired by the official Amazon FPS API PHP SDK @ http://aws.amazon.com/code/Amazon-FPS/4094948623747680
 *
 * @since 2.0
 */
class WC_Amazon_FPS_API {


	/** @var string AWS access key */
	private $access_key;

	/** @var string AWS secret key */
	private $secret_key;

	/** @var string the API environment, either `production` or `sandbox` */
	private $environment;

	/** @var string API raw request URI for logging */
	private $request_url;

	/** @var string API raw response XML for logging */
	private $response_xml;


	/**
	 * Setup the class
	 *
	 * @since 2.0
	 * @param string $access_key the AWS access key
	 * @param string $secret_key the AWS secret key
	 * @param string $environment the AWS API environment
	 */
	public function __construct( $access_key, $secret_key, $environment ) {

		$this->access_key  = $access_key;
		$this->secret_key  = $secret_key;
		$this->environment = $environment;

		// load base class
		require_once( wc_amazon_fps()->get_plugin_path() . '/includes/api/abstract/abstract-wc-amazon-fps-api-base.php' );

		// load CBUI pipeline class
		require_once( wc_amazon_fps()->get_plugin_path() . '/includes/api/cbui/class-wc-amazon-fps-api-cbui-pipeline.php' );

		// load FPS classes
		require_once( wc_amazon_fps()->get_plugin_path() . '/includes/api/fps/class-wc-amazon-fps-api-request.php' );
		require_once( wc_amazon_fps()->get_plugin_path() . '/includes/api/fps/class-wc-amazon-fps-api-response.php' );
	}


	/** FPS API methods ******************************************************/


	/**
	 * Get the request URL to verify an outbound signature
	 *
	 * @since 2.0
	 * @param string $endpoint the originating endpoint for the signature, either the return URL for a CBUI request or the IPN URL for an IPN request
	 * @param string $params URL encoded string of parameters that were included in the request that contained the signature to verify
	 * @return \WC_Amazon_FPS_API_Response
	 */
	public function verify_signature( $endpoint, $params ) {

		$request = new WC_Amazon_FPS_API_Request( $this->access_key, $this->secret_key, $this->environment );

		return $this->parse_response( $this->perform_request( $request->get_verify_signature_request( $endpoint, $params ) ) );
	}


	/**
	 * Process a transaction via the FPS API
	 *
	 * @link http://docs.aws.amazon.com/AmazonFPS/latest/FPSAdvancedGuide/Pay.html
	 *
	 * @since 2.0
	 * @param WC_Order $order the WC Order object
	 * @return \WC_Amazon_FPS_API_Response
	 */
	public function pay( WC_Order $order ) {

		$request = new WC_Amazon_FPS_API_Request( $this->access_key, $this->secret_key, $this->environment );

		return $this->parse_response( $this->perform_request( $request->get_pay_request( $order ) ) );
	}


	/** Co-Branded Pipeline UI methods ******************************************************/


	/**
	 * Get the URL to redirect the customer to in order to sign in to Amazon and authorize the payment - this URL
	 * includes all the necessary params for a single purchase
	 *
	 * @since 2.0
	 * @param WC_Order $order the WooCommerce order object
	 * @return string the URL to redirect to
	 */
	public function get_single_purchase_url( WC_Order $order ) {

		$pipeline = new WC_Amazon_FPS_API_CBUI_Pipeline( 'single', $order, $this->access_key, $this->secret_key, $this->environment );

		return $pipeline->get_request_url();
	}


	/**
	 * Get the URL to redirect the customer to in order to sign in to Amazon and authorize the payment - this URL
	 * includes all the necessary params for a recurring transaction
	 *
	 * @since 2.0
	 * @param WC_Order $order the WooCommerce order object
	 * @return string the URL to redirect to
	 */
	public function get_subscriptions_purchase_url( WC_Order $order ) {

		$pipeline = new WC_Amazon_FPS_API_CBUI_Pipeline( 'subscription', $order, $this->access_key, $this->secret_key, $this->environment );

		return $pipeline->get_request_url();
	}


	/**
	 * Get the URL to redirect the customer to in order to sign in to Amazon and authorize the payment - this URL
	 * includes all the necessary params for authorizing a payment now and settling it later
	 *
	 * @since 2.0
	 * @param WC_Order $order the WooCommerce order object
	 * @return string the URL to redirect to
	 */
	public function get_pre_order_purchase_url( WC_Order $order ) {

		$pipeline = new WC_Amazon_FPS_API_CBUI_Pipeline( 'pre-order', $order, $this->access_key, $this->secret_key, $this->environment );

		return $pipeline->get_request_url();
	}


	/** Helper methods ******************************************************/


	/**
	 * Perform the REST request
	 *
	 * @since 2.0
	 * @param string $url the URL to GET
	 * @throws Exception if response body is empty
	 * @return string the XML response body
	 */
	public function perform_request( $url ) {

		$args = apply_filters( 'wc_amazon_fps_api_http_args', array(
			'timeout'     => 30,
			'redirection' => 5,
			'httpversion' => '1.0',
			'sslverify'   => false,
			'blocking'    => true,
			'user-agent'  => 'PHP ' . PHP_VERSION
		) );

		$response = wp_remote_get( $url, $args );

		// Check for Network timeout, etc.
		if ( is_wp_error( $response ) ) {
			throw new Exception( $response->get_error_message() );
		}

		// return blank XML document if response body doesn't exist
		if ( empty( $response['body'] ) ) {
			throw new Exception( __( 'Response body is empty', WC_Amazon_FPS::TEXT_DOMAIN ) );
		}

		// save raw request URL & response XML for debugging
		$this->request_url  = $url;
		$this->response_xml = $response['body'];

		// fire an action so request URL/response XML can be logged easily
		do_action( 'wc_gateway_amazon_fps_api_request_performed' );

		return $response['body'];
	}


	/**
	 * Parse the response XML
	 *
	 * @since 2.0
	 * @param string $xml the response XML to parse
	 * @throws Exception for API errors
	 * @return \WC_Amazon_FPS_API_Response
	 */
	private function parse_response( $xml ) {

		// Remove namespace as SimpleXML throws warnings with invalid namespace URIs
		$xml = preg_replace( '/[[:space:]]xmlns[^=]*="[^"]*"/i', '', $xml );

		// LIBXML_NOCDATA ensures that any XML fields wrapped in [CDATA] will be included as text nodes
		$response = new WC_Amazon_FPS_API_Response( $xml, LIBXML_NOCDATA );

		// Throw exception for API errors
		if ( $response->has_error() ) {
			throw new Exception( $response->get_errors() );
		}

		return $response;
	}


	/**
	 * Get the raw request URL for debugging
	 *
	 * @since 2.0
	 * @return string the request URL
	 */
	public function get_request_url() {

		return $this->request_url;
	}


	/**
	 * Get the raw response XML for debugging
	 *
	 * @since 2.0
	 * @return string the response XML
	 */
	public function get_response_xml() {

		return $this->response_xml;
	}


}
