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
 * Amazon FPS Addons class
 *
 * Extends the base Amazon FPS gateway class to provide support for subscriptions and pre-orders
 *
 * @since 2.0
 * @extends \WC_Gateway_Amazon_FPS
 */
class WC_Gateway_Amazon_FPS_Addons extends WC_Gateway_Amazon_FPS {


	/**
	 * Load parent gateway and add-on specific hooks
	 *
	 * @since 2.0
	 * @return \WC_Gateway_Amazon_FPS_Addons
	 */
	public function __construct() {

		// load parent gateway
		parent::__construct();

		/**
		 * Subscriptions has a bug that causes payment gateways to be loaded before the wc-api class loads them,
		 * resulting in duplicate hooks so we check if the hooks have been added already before adding them
		*/

		// IPN listener
		if ( ! isset( $GLOBALS['wp_filter']['woocommerce_api_wc_gateway_amazon_fps_addons'] ) ) {
			add_action( 'woocommerce_api_' . strtolower( get_class( $this ) ), array( $this, 'process_ipn' ) );
		}

		// API logging hook
		if ( ! isset( $GLOBALS['wp_filter']['wc_amazon_fps_api_request_performed'] ) ) {
			add_action( 'wc_amazon_fps_api_request_performed', array( $this, 'log_api' ) );
		}

		// add subscription support if active
		if ( wc_amazon_fps()->is_subscriptions_active() ) {

			$this->supports = array_merge( $this->supports,
				array(
					'subscriptions',
					'subscription_suspension',
					'subscription_cancellation',
					'subscription_reactivation',
					'subscription_amount_changes',
					'subscription_date_changes'
				)
			);

			// process scheduled subscription payments
			add_action( 'scheduled_subscription_payment_' . $this->id, array( $this, 'process_subscription_renewal_payment' ), 10, 2 );

			// prevent unnecessary order meta from polluting parent renewal orders
			add_filter( 'woocommerce_subscriptions_renewal_order_meta_query', array( $this, 'remove_subscription_renewal_order_meta' ), 10, 4 );
		}

		// add pre-orders support if active
		if ( wc_amazon_fps()->is_pre_orders_active() ) {

			$this->supports = array_merge( $this->supports,
				array(
					'pre-orders',
				)
			);

			// process batch pre-order payments
			add_action( 'wc_pre_orders_process_pre_order_completion_payment_' . $this->id, array( $this, 'process_pre_order_release_payment' ) );
		}
	}


	/**
	 * Process payment for an order:
	 * 1) If the order contains a subscription, process the initial subscription payment (could be $0 if a free trial exists)
	 * 2) If the order contains a pre-order, process the pre-order total (could be $0 if the pre-order is charged upon release)
	 * 3) Otherwise use the parent::process_payment() method for regular product purchases
	 *
	 * @since 2.0
	 * @param int $order_id
	 * @return array
	 */
	public function process_payment( $order_id ) {

		$order = $this->get_order( $order_id );

		try {

			/* processing subscription */
			if ( wc_amazon_fps()->is_subscriptions_active() && WC_Subscriptions_Order::order_contains_subscription( $order ) ) {

				// calculate the lifetime amount to be charged for the subscription, with a multiplier applied to account for upgrades / price increases
				$order->amazon_lifetime_subscription_total = $this->calculate_lifetime_subscription_total( $order );

				// set a subscription-specific description
				$order->amazon_description = sprintf( __( '%s - Subscription Order %s', WC_Amazon_FPS::TEXT_DOMAIN ), esc_html( get_bloginfo( 'name' ) ), $order->get_order_number() );

				$url = $this->get_api()->get_subscriptions_purchase_url( $order );

			/* processing pre-order */
			} elseif ( wc_amazon_fps()->is_pre_orders_active() && WC_Pre_Orders_Order::order_contains_pre_order( $order_id ) && WC_Pre_Orders_Order::order_requires_payment_tokenization( $order->id ) ) {

				// Amazon requires an expiration date for the token so use the pre-order release date + 6 months to account for pre-order delays
				$release_date = WC_Pre_Orders_Product::get_localized_availability_date(  WC_Pre_Orders_Order::get_pre_order_product( $order ) );

				$order->amazon_pre_order_release_date = strtotime( "$release_date +6 months" );

				$url = $this->get_api()->get_pre_order_purchase_url( $order );

			/* processing regular product (or a pre-order charged upfront) */
			} else {

				return parent::process_payment( $order_id );
			}

			// add to log
			$this->log( $url, 'request' );

			// redirect to Amazon
			return array(
				'result'   => 'success',
				'redirect' => $url,
			);

		} catch ( Exception $e ) {

			$this->mark_order_as_failed( $order, $e->getMessage() );
		}
	}


	/**
	 * Process the transaction after receiving the token from Amazon
	 *
	 * @since 2.0
	 * @param WC_Order $order the WC Order object
	 */
	protected function process_transaction( WC_Order $order ) {

		/* processing subscription */
		if ( wc_amazon_fps()->is_subscriptions_active() && WC_Subscriptions_Order::order_contains_subscription( $order ) ) {

			// set the initial payment total
			$order->amazon_order_total = WC_Subscriptions_Order::get_total_initial_payment( $order );

			// if there is a free trial, mark the order as paid, otherwise process it
			if ( 0 == $order->amazon_order_total ) {
				$this->mark_order_as_processing( $order );
			} else {
				parent::process_transaction( $order );
			}

		/* processing pre-order */
		} elseif ( wc_amazon_fps()->is_pre_orders_active() && WC_Pre_Orders_Order::order_contains_pre_order( $order ) && WC_Pre_Orders_Order::order_requires_payment_tokenization( $order ) ) {

			// mark order as pre-ordered / reduce order stock
			WC_Pre_Orders_Order::mark_order_as_pre_ordered( $order );

		/* processing regular product (or a pre-order charged upfront) */
		} else {

			parent::process_transaction( $order );
		}
	}


	/**
	 * Process the IPN response from Amazon. This is specific for processing a subscription renewal IPN response
	 * as it requires marking the order using WCS-specific methods instead of changing the order status. All
	 * non-subscription renewal IPNs are processed using the parent class process_ipn() method
	 *
	 * @since 2.0
	 */
	public function process_ipn() {

		extract( $this->get_ipn_data() );

		try {

			// verify all required IPN data is present, signature is valid, etc.
			$this->verify_ipn_data( $order, $data );

			// process the IPN status for subscription renewal payments
			if ( $GLOBALS['wc_amazon_fps']->is_subscriptions_active() && WC_Subscriptions_Order::order_contains_subscription( $order ) &&
			   ( false !== strpos( $data['callerReference'], '-' ) ) ) {

				// update subscription, note 'PENDING' status is not parsed here as it's not useful for a subscription renewal
				switch ( $data['transactionStatus'] ) {

					case 'SUCCESS':
						$order->add_order_note( sprintf( __( 'Amazon Subscription Renewal Transaction Approved (ID: %s)', WC_Amazon_FPS::TEXT_DOMAIN ), $data['transactionId'] ) );
						WC_Subscriptions_Manager::process_subscription_payments_on_order( $order );
						break;

					case 'FAILURE':
						$order->add_order_note( sprintf( __( 'Amazon Subscription Renewal Transaction Failed (ID: %s - Code: %s - %s', WC_Amazon_FPS::TEXT_DOMAIN ), $data['transactionId'], $data['statusCode'], $data['statusMessage'] ) );
						WC_Subscriptions_Manager::process_subscription_payment_failure_on_order( $order );
						break;

					case 'CANCELLED':
						$order->add_order_note( sprintf( __( 'Amazon Subscription Renewal Transaction Cancelled (ID: %s)', WC_Amazon_FPS::TEXT_DOMAIN ), $data['transactionId'] ) );
						WC_Subscriptions_Manager::process_subscription_payment_failure_on_order( $order );
						break;
				}

			} else {

				// otherwise process IPN response as normal
				parent::process_ipn_status( $order, $data );
			}

		} catch( Exception $e ) {

			$this->mark_order_as_failed( $order, __( 'IPN: ', WC_Amazon_FPS::TEXT_DOMAIN ) . $e->getMessage() );
		}

		// send success
		header( 'HTTP/1.1 200 OK' );
	}


	/**
	 * Process a pre-order payment when the pre-order is released
	 *
	 * @since 2.0
	 * @param WC_Order $order original order containing the pre-order
	 * @throws Exception invalid/missing token
	 */
	public function process_pre_order_release_payment( WC_Order $order ) {

		try {

			// set order defaults
			$order = $this->get_order( $order->id );

			// token is required
			if ( ! isset( $order->wc_amazon_fps_token_id ) ) {
				throw new Exception( __( 'invalid or missing token', WC_Amazon_FPS::TEXT_DOMAIN ) );
			}

			$this->process_transaction( $order );

		} catch ( Exception $e ) {

			$this->mark_order_as_failed( $order, $e->getMessage() );

			$order->add_order_note( __( 'Amazon Pre-Order Release Transaction Failed: ' . $e->getMessage() ) );
		}
	}


	/**
	 * Process subscription renewal
	 *
	 * @since 2.0
	 * @param float $amount_to_charge subscription amount to charge, could include multiple renewals if they've previously failed and the admin has enabled it
	 * @param WC_Order $order original order containing the subscription
	 * @throws Exception invalid or missing token
	 */
	public function process_subscription_renewal_payment( $amount_to_charge, WC_Order $order ) {

		try {

			// set order defaults
			$order = $this->get_order( $order->id );

			// set the amount to charge
			$order->amazon_order_total = $amount_to_charge;

			// add a timestamp to the order ID so Amazon doesn't consider it a duplicate of the original payment request
			$order->amazon_caller_reference .= '-' . time();

			// token is required
			if ( ! isset( $order->wc_amazon_fps_token_id ) ) {
				throw new Exception( __( 'invalid or missing token', WC_Amazon_FPS::TEXT_DOMAIN ) );
			}

			parent::process_transaction( $order );

		} catch ( Exception $e ) {

			WC_Subscriptions_Manager::process_subscription_payment_failure_on_order( $order );

			$order->add_order_note( __( 'Amazon Subscription Renewal Transaction Failed: ' . $e->getMessage() ) );
		}
	}


	/**
	 * Don't copy over Amazon FPS-specific order meta when creating a parent renewal order
	 *
	 * @since 2.0
	 * @param array $order_meta_query MySQL query for pulling the metadata
	 * @param int $original_order_id Post ID of the order being used to purchased the subscription being renewed
	 * @param int $renewal_order_id Post ID of the order created for renewing the subscription
	 * @param string $new_order_role The role the renewal order is taking, one of 'parent' or 'child'
	 * @return string
	 */
	public function remove_subscription_renewal_order_meta( $order_meta_query, $original_order_id, $renewal_order_id, $new_order_role ) {

		if ( 'parent' == $new_order_role )
			$order_meta_query .= " AND `meta_key` NOT IN ("
			  . "'_wc_amazon_fps_token_id', "
			  . "'_wc_amazon_fps_api_environment', "
			  . "'_wc_amazon_fps_token_expiry', "
			  . "'_wc_amazon_fps_transaction_id' )";

		return $order_meta_query;
	}


	/**
	 * Calculate the maximum amount that can be charged throughout the life of a subscription. Amazon displays this
	 * to the customer when they're authorizing their payment.
	 *
	 * @since 2.0
	 * @param WC_Order $order the WC order object
	 * @return float the maximum total amount that can be charged, rounded to the nearest whole number
	 */
	private function calculate_lifetime_subscription_total( WC_Order $order ) {

		// start with the total initial payment, which includes any sign up fee or one-off shipping/tax charge
		$total = WC_Subscriptions_Order::get_total_initial_payment( $order );

		// add the total from all the recurring periods
		if ( WC_Subscriptions_Order::get_subscription_length( $order ) > 0 ) {

			$total += ( WC_Subscriptions_Order::get_recurring_total( $order ) * WC_Subscriptions_Order::get_subscription_length( $order ) );

		// If a subscription never ends, use 5 years worth of recurring charges as a sensible maximum
		} else {

			// first get the subscription period in year terms
			switch ( WC_Subscriptions_Order::get_subscription_period( $order ) ) {

				case 'day':
					$period = 365;
					break;

				case 'week':
					$period = 52;
					break;

				case 'month':
					$period = 12;
					break;

				case 'year':
					$period = 1;
					break;

				default:
					$period = 1;
					break;
			}

			// divide by the interval (e.g. recurs every Xth week)
			$period = $period / WC_Subscriptions_Order::get_subscription_interval( $order );

			// multiply by recurring total per period by the number of periods in a year by 5 years to get the total
			$total += WC_Subscriptions_Order::get_recurring_total( $order ) * $period * 5;
		}

		// finally increase by 25% to account for price increases / upgrades
		$total *= 1.25;

		/**
		 * Round to whole number and allow an entirely different total to be set
		 *
		 * @since 2.0
		 * @param float $lifetime_subscription_total the total amount that may be charged, as shown to the customer during checkout @ Amazon
		 * @param WC_Order $order the WC Order object
		 */
		return apply_filters( 'wc_amazon_fps_lifetime_subscription_total', round( $total, 0 ), $order );
	}



} // end \WC_Gateway_Amazon_FPS_Addons class
