<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class WC_XR_Line_Item {

	/**
	 * @var string
	 */
	private $description = '';

	/**
	 * @var string
	 */
	private $account_code = '';

	/**
	 * @var string
	 */
	private $item_code = '';

	/**
	 * @var float
	 */
	private $unit_amount = 0;

	/**
	 * @var int
	 */
	private $quantity = 0;

	/**
	 * @var float
	 */
	private $line_amount = null;

	/**
	 * @var float
	 */
	private $tax_amount = 0;

	/**
	 * @return string
	 */
	public function get_description() {
		return apply_filters( 'woocommerce_xero_line_item_description', $this->description, $this );
	}

	/**
	 * @param string $description
	 */
	public function set_description( $description ) {
		$this->description = htmlspecialchars( $description );
	}

	/**
	 * @return string
	 */
	public function get_account_code() {
		return apply_filters( 'woocommerce_xero_line_item_account_code', $this->account_code, $this );
	}

	/**
	 * @param string $account_code
	 */
	public function set_account_code( $account_code ) {
		$this->account_code = $account_code;
	}

	/**
	 * @return string
	 */
	public function get_item_code() {
		return apply_filters( 'woocommerce_xero_line_item_item_code', $this->item_code, $this );
	}

	/**
	 * @param string $item_code
	 */
	public function set_item_code( $item_code ) {
		$this->item_code = $item_code;
	}

	/**
	 * @return float
	 */
	public function get_unit_amount() {
		return apply_filters( 'woocommerce_xero_line_item_unit_amount', $this->unit_amount, $this );
	}

	/**
	 * @param float $unit_amount
	 */
	public function set_unit_amount( $unit_amount ) {
		$this->unit_amount = round( floatval( $unit_amount ), 4 );
	}

	/**
	 * @return int
	 */
	public function get_quantity() {
		return apply_filters( 'woocommerce_xero_line_item_quantity', $this->quantity, $this );
	}

	/**
	 * @param int $quantity
	 */
	public function set_quantity( $quantity ) {
		$this->quantity = intval( $quantity );
	}

	/**
	 * @return float
	 */
	public function get_line_amount() {
		return apply_filters( 'woocommerce_xero_line_item_line_amount', $this->line_amount, $this );
	}

	/**
	 * @param float $line_amount
	 */
	public function set_line_amount( $line_amount ) {
		$this->line_amount = round( floatval( $line_amount ), 2 );
	}

	/**
	 * @return float
	 */
	public function get_tax_amount() {
		return apply_filters( 'woocommerce_xero_line_item_tax_amount', $this->tax_amount, $this );
	}

	/**
	 * @param float $tax_amount
	 */
	public function set_tax_amount( $tax_amount ) {
		$this->tax_amount = round( floatval( $tax_amount ), 2 );
	}

	/**
	 * Format the line item to XML and return the XML string
	 *
	 * @return string
	 */
	public function to_xml() {
		$xml = '<LineItem>';

		// Description
		if ( '' !== $this->get_description() ) {
			$xml .= '<Description>' . $this->get_description() . '</Description>';
		}

		// Account code
		if ( '' !== $this->get_account_code() ) {
			$xml .= '<AccountCode>' . $this->get_account_code() . '</AccountCode>';
		}

		// Check if there's an item code
		if ( '' !== $this->get_item_code() ) {
			$xml .= '<ItemCode>' . $this->get_item_code() . '</ItemCode>';
		}

		// Check if we need to add a unit amount
//		if ( $this->get_unit_amount() != 0 ) {}
		$xml .= '<UnitAmount>' . $this->get_unit_amount() . '</UnitAmount>';

		// Quantity
		$xml .= '<Quantity>' . $this->get_quantity() . '</Quantity>';

		// Line Amount
		if ( null !== $this->get_line_amount() ) {
			$xml .= '<LineAmount>' . $this->get_line_amount() . '</LineAmount>';
		}

		// Tax Amount
//		if ( $this->get_tax_amount() > 0 ) {}
		$xml .= '<TaxAmount>' . $this->get_tax_amount() . '</TaxAmount>';

		$xml .= '</LineItem>';

		return $xml;
	}
}