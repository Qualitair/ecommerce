<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class WC_XR_Contact_Manager {

	/**
	 * @param WC_Order $order
	 *
	 * @return WC_XR_Address
	 */
	public function get_address_by_order( $order ) {

		// Setup address object
		$address = new WC_XR_Address();

		// Set line 1
		$address->set_line_1( $order->billing_address_1 );

		// Set city
		$address->set_city( $order->billing_city );

		// Set region
		$address->set_region( $order->billing_state );

		// Set postal code
		$address->set_postal_code( $order->billing_postcode );

		// Set country
		$address->set_country( $order->billing_country );

		// Set line 2
		if ( strlen( $order->billing_address_2 ) > 0 ) {
			$address->set_line_2( $order->billing_address_2 );
		}

		// Return address object
		return $address;
	}

	/**
	 * @param WC_Order $order
	 *
	 * @return WC_XR_Contact
	 */
	public function get_contact_by_order( $order ) {

		// Setup Contact object
		$contact = new WC_XR_Contact();

		// Set Invoice name
		if ( strlen( $order->billing_company ) > 0 ) {
			$invoice_name = $order->billing_company;
		} else {
			$invoice_name = $order->billing_first_name . ' ' . $order->billing_last_name;
		}

		// Set name
		$contact->set_name( $invoice_name );

		// Set first name
		$contact->set_first_name( $order->billing_first_name );

		// Set last name
		$contact->set_last_name( $order->billing_last_name );

		// Set email address
		$contact->set_email_address( $order->billing_email );

		// Set address
		$contact->set_addresses( array( $this->get_address_by_order( $order ) ) );

		// Set phone
		$contact->set_phones( array( new WC_XR_Phone( $order->billing_phone ) ) );

		// Return contact
		return $contact;
	}

}