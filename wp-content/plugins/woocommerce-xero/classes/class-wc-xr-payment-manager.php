<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class WC_XR_Payment_Manager {

	/**
	 * Send the payment to the XERO API
	 *
	 * @param int $order_id
	 *
	 * @return bool
	 */
	public function send_payment( $order_id ) {

		// Get the order
		$order = wc_get_order( $order_id );

		// Payment Request
		$payment_request = new WC_XR_Request_Payment( $this->get_payment_by_order( $order ) );

		// Write exception message to log
		$logger = new WC_XR_Logger();

		// Logging start
		$logger->write( 'START XERO NEW PAYMENT. order_id=' . $order->id );

		// Try to do the request
		try {
			// Do the request
			$payment_request->do_request();

			// Parse XML Response
			$xml_response = $payment_request->get_response_body_xml();

			// Check response status
			if ( 'OK' == $xml_response->Status ) {

				// Add post meta
				add_post_meta( $order->id, '_xero_payment_id', (string) $xml_response->Payments->Payment[0]->PaymentID );

				// Write logger
				$logger->write( 'XERO RESPONSE:' . "\n" . $payment_request->get_response_body() );

				// Add order note
				$order->add_order_note( __( 'Xero Payment created.  ', 'wc-xero' ) .
				                        ' Payment ID: ' . (string) $xml_response->Payments->Payment[0]->PaymentID );

			} else { // XML reponse is not OK

				// Logger write
				$logger->write( 'XERO ERROR RESPONSE:' . "\n" . $payment_request->get_response_body() );

				// Error order note
				$error_num = (string) $xml_response->ErrorNumber;
				$error_msg = (string) $xml_response->Elements->DataContractBase->ValidationErrors->ValidationError->Message;
				$order->add_order_note( __( 'ERROR creating Xero payment. ErrorNumber:' . $error_num . '| Error Message:' . $error_msg, 'wc-xero' ) );
			}

		} catch ( Exception $e ) {
			// Add Exception as order note
			$order->add_order_note( $e->getMessage() );

			$logger->write( $e->getMessage() );

			return false;
		}

		// Logging end
		$logger->write( 'END XERO NEW PAYMENT' );

		return true;
	}

	/**
	 * Get payment by order
	 *
	 * @param WC_Order $order
	 *
	 * @return WC_XR_Payment
	 */
	public function get_payment_by_order( $order ) {

		// Get the XERO invoice ID
		$invoice_id = get_post_meta( $order->id, '_xero_invoice_id', true );

		// Get the XERO currency rate
		$currency_rate = get_post_meta( $order->id, '_xero_currencyrate', true );

		// Date time object of order data
		$order_dt = new DateTime( $order->order_date );

		// Settings object
		$settings = new WC_XR_Settings();

		// The Payment object
		$payment = new WC_XR_Payment();

		// Set the invoice ID
		$payment->set_invoice_id( $invoice_id );

		// Set the Payment Account code
		$payment->set_code( $settings->get_option( 'payment_account' ) );

		// Set the payment date
		$payment->set_date( $order_dt->format( 'Y-m-d' ) );

		// Set the currency rate
		$payment->set_currency_rate( $currency_rate );

		// Set the amount
		$payment->set_amount( $order->order_total );

		return $payment;
	}

}