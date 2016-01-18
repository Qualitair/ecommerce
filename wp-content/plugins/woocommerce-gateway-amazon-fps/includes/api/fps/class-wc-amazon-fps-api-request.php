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
 * Amazon FPS API Request
 *
 * Inspired by the official Amazon FPS API PHP SDK @ http://aws.amazon.com/code/Amazon-FPS/4094948623747680
 *
 * @since 2.0
 */
class WC_Amazon_FPS_API_Request extends WC_Amazon_FPS_API_Base {


	/**
	 * Setup request
	 *
	 * @link http://docs.aws.amazon.com/AmazonFPS/latest/FPSAdvancedGuide/CommonRequestParameters.html
	 *
	 * @since 2.0
	 * @param string $access_key the AWS access key
	 * @param string $secret_key the AWS secret key
	 * @param string $environment the AWS API environment
	 * @return \WC_Amazon_FPS_API_Request
	 */
	public function __construct( $access_key, $secret_key, $environment ) {

		// FPS API has it's own version
		$this->version = '2010-08-28';

		// set AWS access/secret key
		$this->access_key  = $access_key;
		$this->secret_key  = $secret_key;

		// environment is specific to API
		$this->environment = ( 'production' === $environment ) ? 'fps-production' : 'fps-sandbox';

		// set parameters common to every request
		$this->set_parameter( 'AWSAccessKeyId',   $access_key );
		$this->set_parameter( 'SignatureVersion', self::SIGNATURE_VERSION );
		$this->set_parameter( 'SignatureMethod',  self::SIGNATURE_METHOD );
		$this->set_parameter( 'Timestamp',        gmdate( 'Y-m-d\TH:i:s\Z' ) );
		$this->set_parameter( 'Version',          $this->version );
	}


	/**
	 * Get the request URL to verify an outbound signature
	 *
	 * @link http://docs.aws.amazon.com/AmazonFPS/latest/FPSAdvancedGuide/VerifySignatureAPI.html
	 *
	 * @since 2.0
	 * @param string $endpoint the originating endpoint for the signature, either the return URL for a CBUI request or the IPN URL for an IPN request
	 * @param string $params URL encoded string of parameters that were included in the request that contained the signature to verify
	 * @return string the request URL
	 */
	public function get_verify_signature_request( $endpoint, $params ) {

		// set required parameters
		$this->set_parameter( 'UrlEndPoint',    $endpoint );
		$this->set_parameter( 'HttpParameters', $this->http_build_query( $params ) );
		$this->set_parameter( 'Action',         'VerifySignature' );

		return $this->get_request_url();
	}


	/**
	 * Initiate a transaction to move funds from buyer to seller
	 *
	 * http://docs.aws.amazon.com/AmazonFPS/latest/FPSAdvancedGuide/Pay.html
	 *
	 * @since 2.0
	 * @param WC_Order $order the WC Order object
	 * @throws Exception if the sender token is missing
	 * @return string the request URL
	 */
	public function get_pay_request( WC_Order $order ) {

		$token_id = get_post_meta( $order->id, '_wc_amazon_fps_token_id', true );

		if ( ! $token_id ) {
			throw new Exception( __( 'FPS: Token ID is missing for order', WC_Amazon_FPS::TEXT_DOMAIN ) );
		}

		// set required parameters
		$this->set_parameter( 'Action',                         'Pay' );
		$this->set_parameter( 'CallerReference',                $order->amazon_caller_reference );
		$this->set_parameter( 'SenderTokenId',                  $token_id );
		$this->set_parameter( 'TransactionAmount.CurrencyCode', 'USD' ); // FPS API only supports USD
		$this->set_parameter( 'TransactionAmount.Value',        $order->amazon_order_total );

		// set optional description and IPN URL
		$this->set_parameter( 'CallerDescription', $order->amazon_description );
		$this->set_parameter( 'SenderDescription', $order->amazon_description );
		$this->set_parameter( 'OverrideIPNURL',    $order->amazon_ipn_url );

		return $this->get_request_url();
	}


	/**
	 * Get the request URL
	 *
	 * @since 2.0
	 * @return string the request URL
	 */
	protected function get_request_url() {

		// allow parameters to be modified prior to generating the signature
		$this->parameters = apply_filters( 'wc_amazon_fps_api_request_parameters', $this->parameters );

		return parent::get_request_url();
	}


}
