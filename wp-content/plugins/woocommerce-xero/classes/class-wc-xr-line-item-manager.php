<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class WC_XR_Line_Item_Manager {

	/**
	 * Build product line items
	 *
	 * @param WC_Order $order
	 *
	 * @return array<WC_XR_Line_Item>
	 */
	public function build_products( $order ) {
		$items = $order->get_items();

		// The line items
		$line_items = array();

		// Check if there are any order items
		if ( count( $items ) > 0 ) {

			// Settings object
			$settings = new WC_XR_Settings();

			// Get the sales account
			$sales_account = $settings->get_option( 'sales_account' );

			// Check we need to send sku's
			$send_inventory = ( ( 'on' === $settings->get_option( 'send_inventory' ) ) ? true : false );

			// Add order items as line items
			foreach ( $items as $item ) {

				// Get the product
				$product = $order->get_product_from_item( $item );

				// Create Line Item object
				$line_item = new WC_XR_Line_Item();

				// Set description
				$line_item->set_description( str_replace( array( '&#8220;', '&#8221;' ), '""', $item['name'] ) );

				// Set account code
				$line_item->set_account_code( $sales_account );

				// Send SKU?
				if ( $send_inventory ) {
					$line_item->set_item_code( $product->sku );
				}


//				if ( true === $send_inventory && $product->is_on_sale() ) {} // Set the unit price if we send inventory and the product is on sale
				// Set the Unit Amount with 4DP
				$line_item->set_unit_amount( ( floatval( $item['line_subtotal'] ) / intval( $item['qty'] ) ) );

				// Quantity
				$line_item->set_quantity( $item['qty'] );

				// Line Amount
				$line_item->set_line_amount( $item['line_subtotal'] );

				// Tax Amount
				$line_item->set_tax_amount( $item['line_tax'] );

				// Add Line Item to array
				$line_items[] = $line_item;

			}

		}

		return $line_items;
	}

	/**
	 * Build shipping line item
	 *
	 * @param WC_Order $order
	 *
	 * @return WC_XR_Line_Item
	 */
	public function build_shipping( $order ) {
		if ( $order->order_shipping > 0 ) {

			// Settings object
			$settings = new WC_XR_Settings();

			// Create Line Item object
			$line_item = new WC_XR_Line_Item();

			// Shipping Description
			$line_item->set_description( 'Shipping Charge' );

			// Shipping Quantity
			$line_item->set_quantity( 1 );

			// Shipping account code
			$line_item->set_account_code( $settings->get_option( 'shipping_account' ) );

			// Shipping cost
			$line_item->set_unit_amount( $order->order_shipping );

			// Shipping tax
			$line_item->set_tax_amount( $order->order_shipping_tax );

			return $line_item;
		}
	}

	/**
	 * Build discount line item
	 *
	 * @param WC_Order $order
	 *
	 * @return WC_XR_Line_Item
	 */
	public function build_discount( $order ) {

		if ( $order->get_total_discount() > 0 ) {

			// Settings object
			$settings = new WC_XR_Settings();

			// Create Line Item object
			$line_item = new WC_XR_Line_Item();

			// Shipping Description
			$line_item->set_description( 'Order Discount' );

			// Shipping Quantity
			$line_item->set_quantity( 1 );

			// Shipping account code
			$line_item->set_account_code( $settings->get_option( 'discount_account' ) );

			// Shipping cost
			$line_item->set_unit_amount( - $order->get_total_discount() );

			return $line_item;
		}

	}

	/**
	 * Build a correction line if needed
	 *
	 * @param WC_Order $order
	 * @param array <WC_XR_Line_Item>
	 *
	 * @return WC_XR_Line_Item
	 */
	public function build_correction( $order, $line_items ) {

		// Line Item
		$correction_line = null;

		// The line item total in cents
		$line_item_total_cents = 0;

		// Get a sum of the amount and tax of all line items
		if ( count( $line_items ) > 0 ) {
			foreach ( $line_items as $line_item ) {

				// Check if line amount is set
				if ( null !== $line_item->get_line_amount() ) {

					// Use line amount if set
					$line_item_total_cents += ( $line_item->get_line_amount() * 100 );
				} else {

					// Use unit amount if line amount is not set
					$line_item_total_cents += ( $line_item->get_unit_amount() * 100 );
				}

				$line_item_total_cents += ( $line_item->get_tax_amount() * 100 );
			}
		}

		// Order total in cents
		$order_total_cents = intval( floatval( $order->order_total ) * 100 );

		// We want an integer
		$line_item_total_cents = intval( $line_item_total_cents );

		// Check if there's a difference
		if ( $line_item_total_cents !== $order_total_cents ) {

			// Calculate difference
			$diff = round( $order_total_cents - $line_item_total_cents ) / 100;

			// Settings object
			$settings = new WC_XR_Settings();

			// Get rounding account code
			$account_code = $settings->get_option( 'rounding_account' );

			// Check rounding account code
			if ( '' !== $account_code ) {

				// Create correction line item
				$correction_line = new WC_XR_Line_Item();

				// Correction description
				$correction_line->set_description( 'Rounding adjustment' );

				// Correction quantity
				$correction_line->set_quantity( 1 );

				// Correction amount
				$correction_line->set_unit_amount( $diff );

				$correction_line->set_account_code( $account_code );

			} else {

				// There's a rounding difference but no rounding account
				$logger = new WC_XR_Logger();
				$logger->write( "There's a rounding difference but no rounding account set in XERO settings." );

			}

		}

		return $correction_line;
	}

	/**
	 * Build line items
	 *
	 * @param WC_Order $order
	 *
	 * @return array<WC_XR_Line_Item>
	 */
	public function build_line_items( $order ) {

		// Fill line items array with products
		$line_items = $this->build_products( $order );

		// Add shipping line item if there's shipping
		if ( $order->order_shipping > 0 ) {
			$line_items[] = $this->build_shipping( $order );
		}

		// Add discount line item if there's discount
		if ( $order->get_total_discount() > 0 ) {
			$line_items[] = $this->build_discount( $order );
		}

		// Build correction
		$correction = $this->build_correction( $order, $line_items );
		if ( null !== $correction ) {
			$line_items[] = $correction;
		}

		// Return line items
		return $line_items;
	}

}