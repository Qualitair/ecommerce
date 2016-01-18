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
 * FPS Co-branded Service API (CBUI) Base class, extended by different "pipelines" to setup the redirect
 * to Amazon for different payment types, e.g. single use, recurring, multi-use, etc
 *
 * Inspired by the official Amazon FPS API PHP SDK @ http://aws.amazon.com/code/Amazon-FPS/4094948623747680
 *
 * @since 2.0
 */
class WC_Amazon_FPS_API_CBUI_Pipeline extends WC_Amazon_FPS_API_Base {


	/** @var \WC_Order the order, saved as class member so it can be included in the filter for the request parameters */
	private $order;


	/**
	 * Setup Co-branded UI pipeline
	 *
	 * @link http://docs.aws.amazon.com/AmazonFPS/latest/FPSAdvancedGuide/GenericParameters.html
	 *
	 * @since 2.0
	 * @param string $type the pipeline type, either `single`, `subscription`, or `pre-order`
	 * @param WC_Order $order the WC order object
	 * @param string $access_key the AWS access key
	 * @param string $secret_key the AWS secret key
	 * @param string $environment the AWS API environment
	 * @return \WC_Amazon_FPS_API_CBUI_Pipeline
	 */
	public function __construct( $type, WC_Order $order, $access_key, $secret_key, $environment ) {

		// the CBUI API has it's own version
		$this->version = '2009-01-09';

		// set AWS access/secret key
		$this->access_key  = $access_key;
		$this->secret_key  = $secret_key;

		// environment is specific to API
		$this->environment = ( 'production' === $environment ) ? 'cbui-production' : 'cbui-sandbox';

		// set parameters common to every request
		$this->set_parameter( 'callerKey',          $this->access_key );
		$this->set_parameter( 'version',            $this->version );
		$this->set_parameter( 'signatureVersion',   self::SIGNATURE_VERSION );
		$this->set_parameter( 'signatureMethod',    self::SIGNATURE_METHOD );
		$this->set_parameter( 'websiteDescription', get_bloginfo( 'name' ) );

		// save order as class member so it can be passed with filter in get_request_url()
		$this->order = $order;

		// add order-specific parameters
		$this->set_order_parameters();

		// set pipeline-specific parameters
		switch ( $type ) {

			case 'single':
				$this->set_parameter( 'pipelineName', 'SingleUse' );
				break;

			case 'subscription':
				// use Multi-Use pipeline
				$this->set_parameter( 'pipelineName', 'MultiUse' );

				// remove transaction amount
				$this->remove_parameter( 'transactionAmount' );

				// set the total amount that can be charged throughout the lifetime of the subscription
				$this->set_parameter( 'globalAmountLimit', $this->order->amazon_lifetime_subscription_total );

				// the lifetime amount is calculated based on a maximum of 5 years
				$this->set_parameter( 'validityExpiry', strtotime( 'now +5 years' ) );
				break;

			case 'pre-order':
				// use Multi-use pipeline
				$this->set_parameter( 'pipelineName', 'MultiUse' );

				// remove transaction amount
				$this->remove_parameter( 'transactionAmount' );

				// set the order total that will be charged at pre-order release
				$this->set_parameter( 'globalAmountLimit', $this->order->amazon_order_total );

				// set the token expiration
				$this->set_parameter( 'validityExpiry', $this->order->amazon_pre_order_release_date );
				break;
		}
	}


	/**
	 * Set the order-specific parameters for the request
	 *
	 * @since 2.0
	 */
	private function set_order_parameters() {

		// set return URL
		$this->set_parameter( 'returnURL', $this->order->amazon_return_url );

		// add payment reason/description
		$this->set_parameter( 'paymentReason', $this->order->amazon_description );

		// add shipping address info if set
		if ( $this->order->get_shipping_method() ) {

			$this->set_parameter( 'addressName',  $this->order->shipping_first_name . ' ' . $this->order->shipping_last_name );
			$this->set_parameter( 'addressLine1', $this->order->shipping_address_1 );
			$this->set_parameter( 'city',         $this->order->shipping_city );
			$this->set_parameter( 'state',        empty( $this->order->shipping_state ) ? 'N/A' : $this->order->shipping_state );
			$this->set_parameter( 'country',      $this->order->shipping_country );
			$this->set_parameter( 'zip',          $this->order->shipping_postcode );
			$this->set_parameter( 'phoneNumber',  $this->order->billing_phone );
		}

		if ( ! empty( $this->order->shipping_address_2 ) ) {
			$this->set_parameter( 'addressLine2', $this->order->shipping_address_2 );
		}

		// add order ID, which uniquely identifies this request to Amazon
		// note this is not displayed to the customer, so the ID is used instead of the order number
		$this->set_parameter( 'callerReference', $this->order->id );

		// add order total
		$this->set_parameter( 'transactionAmount', $this->order->amazon_order_total );
	}


	/**
	 * Get the URL to redirect to the customer to in order to login/authorize their payment on Amazon
	 *
	 * @since 2.0
	 * @return string the URL
	 */
	public function get_request_url() {

		// allow parameters to be modified prior to generating the signature
		$this->parameters = apply_filters( 'wc_amazon_fps_cbui_pipeline_parameters', $this->parameters, $this->order );

		return parent::get_request_url();
	}


}
