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
 * FPS API Base class, provides standard structure for FPS/CBUI classes along with utility functions for
 * generating signatures
 *
 * Inspired by the official Amazon FPS API PHP SDK @ http://aws.amazon.com/code/Amazon-FPS/4094948623747680
 *
 * @since 2.0
 */
abstract class WC_Amazon_FPS_API_Base {


	/** Amazon FPS production endpoint */
	const PRODUCTION_ENDPOINT = 'https://fps.amazonaws.com';

	/** Amazon FPS production Co-branded service endpoint, used to generate redirect URLs during checkout */
	const CBUI_PRODUCTION_ENDPOINT = 'https://authorize.payments.amazon.com/cobranded-ui/actions/start';

	/** Amazon FPS sandbox endpoint */
	const SANDBOX_ENDPOINT = 'https://fps.sandbox.amazonaws.com';

	/** Amazon FPS production Co-branded service endpoint, used to generate redirect URLs during checkout */
	const CBUI_SANDBOX_ENDPOINT = 'https://authorize.payments-sandbox.amazon.com/cobranded-ui/actions/start';

	/** string the API signature version */
	const SIGNATURE_VERSION = 2;

	/** string the API signature method */
	const SIGNATURE_METHOD = 'HmacSHA256';

	/** @var string the API version */
	protected $version;

	/** @var string AWS access key */
	protected $access_key;

	/** @var string AWS secret key */
	protected $secret_key;

	/** @var string the API environment, either 'production' or 'sandbox' */
	protected $environment;

	/** @var array the request parameters for setting up the transaction */
	protected $parameters;


	/**
	 * Set a request parameter
	 *
	 * @since 2.0
	 * @param string $key the parameter name
	 * @param string $value the parameter value
	 */
	protected function set_parameter( $key, $value ) {

		$this->parameters[ $key ] = $value;
	}


	/**
	 * Remove a request parameter
	 *
	 * @since 2.0
	 * @param string $key the parameter name
	 */
	protected function remove_parameter( $key ) {

		if ( isset( $this->parameters[ $key ] ) ) {
			unset( $this->parameters[ $key ] );
		}
	}


	protected function set_signature() {

		$this->set_parameter( 'Signature', $this->generate_signature() );
	}


	protected function get_request_url() {

		// sign the request
		$this->set_signature();

		// build
		return $this->get_endpoint_url() . '?' . http_build_query( $this->parameters, null, '&' );
	}


	protected function http_build_query( $data ) {

		$query_string = http_build_query( $data, null, '&' );

		// PHP versions prior to 5.4 didn't allow a encoding type to be specified for http_build_query, so the query string needs to be manually adjusted to respect RFC 3986
		$query_string = str_replace( '%7E', '~', $query_string );
		$query_string = str_replace( '+', '%20', $query_string );

		return $query_string;
	}

	/**
	 * Generate the signature for the request to ensure it's authenticity to Amazon
	 *
	 * @link http://docs.aws.amazon.com/AmazonFPS/latest/FPSAdvancedGuide/APPNDX_GeneratingaSignature.html
	 *
	 * @since 2.0
	 * @throws Exception if the parameters fail to sort correctly
	 * @return string the signature for inclusion in the URL
	 */
	private function generate_signature() {

		// sort query strings by their name in alphabetical order
		if ( ! uksort( $this->parameters, 'strcmp' ) ) {
			throw new Exception( sprintf( __( 'FPS - Failed to sort parameters: %s'), implode( ', ', $this->parameters ) ) );
		}

		// url encode parameter names/values and join them with an ampersand
		$query_string = $this->http_build_query( $this->parameters );

		// generate the HTTP host header / request URI (from the FPS endpoint)
		$host_header = strtolower( parse_url( $this->get_endpoint_url(), PHP_URL_HOST ) );
		$request_uri = parse_url( $this->get_endpoint_url(), PHP_URL_PATH );

		if ( empty( $request_uri ) ) {
			$request_uri = '/';
		}

		// generate the string to sign
		$string_to_sign = 'GET' . "\n" . $host_header . "\n" . $request_uri . "\n" . $query_string;

		// calculate HMAC
		$hmac = hash_hmac( 'sha256', $string_to_sign, $this->secret_key, true );

		return base64_encode( $hmac );
	}


	/**
	 * Get the endpoint URL, either the production or sandbox for FPS or CBUI API
	 *
	 * @since 2.0
	 * @return string the endpoint URL
	 */
	private function get_endpoint_url() {

		switch ( $this->environment ) {

			case 'fps-production':
				return self::PRODUCTION_ENDPOINT;

			case 'fps-sandbox':
				return self::SANDBOX_ENDPOINT;

			case 'cbui-production':
				return self::CBUI_PRODUCTION_ENDPOINT;

			case 'cbui-sandbox':
				return self::CBUI_SANDBOX_ENDPOINT;

			default:
				return self::PRODUCTION_ENDPOINT;
		}
	}


}
