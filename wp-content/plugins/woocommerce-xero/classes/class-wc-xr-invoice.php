<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class WC_XR_Invoice {

	/**
	 * @var string
	 */
	private $type = 'ACCREC';

	/**
	 * @var WC_XR_Contact
	 */
	private $contact = array();

	/**
	 * @var string
	 */
	private $date = '';

	/**
	 * @var string
	 */
	private $due_date = '';

	/**
	 * @var string
	 */
	private $invoice_number;

	/**
	 * @var array<WC_XR_Line_Item>
	 */
	private $line_items = array();

	/**
	 * @var string
	 */
	private $currency_code = '';

	/**
	 * @var float
	 */
	private $total_tax = 0;

	/**
	 * @var float
	 */
	private $total = 0;

	/**
	 * Construct
	 *
	 * @param WC_XR_Contact $contact
	 * @param string $date
	 * @param string $due_date
	 * @param string $invoice_number
	 * @param array $line_items
	 * @param string $currency_code
	 * @param float $total_tax
	 * @param float $total
	 */
	public function __construct( $contact, $date, $due_date, $invoice_number, $line_items, $currency_code, $total_tax, $total ) {
		$this->contact        = $contact;
		$this->date           = $date;
		$this->due_date       = $due_date;
		$this->invoice_number = $invoice_number;
		$this->line_items     = $line_items;
		$this->currency_code  = $currency_code;
		$this->total_tax      = $total_tax;
		$this->total          = $total;
	}

	/**
	 * @return string
	 */
	public function get_type() {
		return apply_filters( 'woocommerce_xero_invoice_type', $this->type, $this );
	}

	/**
	 * @param string $type
	 */
	public function set_type( $type ) {
		$this->type = $type;
	}

	/**
	 * @return WC_XR_Contact
	 */
	public function get_contact() {
		return apply_filters( 'woocommerce_xero_invoice_contact', $this->contact, $this );
	}

	/**
	 * @param WC_XR_Contact $contact
	 */
	public function set_contact( $contact ) {
		$this->contact = $contact;
	}

	/**
	 * @return string
	 */
	public function get_date() {
		return apply_filters( 'woocommerce_xero_invoice_date', $this->date, $this );
	}

	/**
	 * @param string $date
	 */
	public function set_date( $date ) {
		$this->date = $date;
	}

	/**
	 * @return string
	 */
	public function get_due_date() {
		return apply_filters( 'woocommerce_xero_invoice_due_date', $this->due_date, $this );
	}

	/**
	 * @param string $due_date
	 */
	public function set_due_date( $due_date ) {
		$this->due_date = $due_date;
	}

	/**
	 * @return string
	 */
	public function get_invoice_number() {

		// Settings object
		$settings = new WC_XR_Settings();

		// Load invoice prefix
		$prefix = trim( $settings->get_option( 'invoice_prefix' ) );

		// Set invoice number
		$invoice_number = $this->invoice_number;

		// Check prefix
		if ( $prefix !== '' ) {
			// Prefix invoice number with prefix
			$invoice_number = $prefix . $invoice_number;
		}

		return apply_filters( 'woocommerce_xero_invoice_invoice_number', $invoice_number, $this );
	}

	/**
	 * @param string $invoice_number
	 */
	public function set_invoice_number( $invoice_number ) {
		$this->invoice_number = $invoice_number;
	}

	/**
	 * @return array
	 */
	public function get_line_items() {
		return apply_filters( 'woocommerce_xero_invoice_line_items', $this->line_items, $this );
	}

	/**
	 * @param array $line_items
	 */
	public function set_line_items( $line_items ) {
		$this->line_items = $line_items;
	}

	/**
	 * @return string
	 */
	public function get_currency_code() {
		return apply_filters( 'woocommerce_xero_invoice_currency_code', $this->currency_code, $this );
	}

	/**
	 * @param string $currency_code
	 */
	public function set_currency_code( $currency_code ) {
		$this->currency_code = $currency_code;
	}

	/**
	 * @return float
	 */
	public function get_total_tax() {
		return apply_filters( 'woocommerce_xero_invoice_total_tax', $this->total_tax, $this );
	}

	/**
	 * @param float $total_tax
	 */
	public function set_total_tax( $total_tax ) {
		$this->total_tax = floatval( $total_tax );
	}

	/**
	 * @return float
	 */
	public function get_total() {
		return apply_filters( 'woocommerce_xero_invoice_total', $this->total, $this );
	}

	/**
	 * @param float $total
	 */
	public function set_total( $total ) {
		$this->total = floatval( $total );
	}

	/**
	 * Format the invoice to XML and return the XML string
	 *
	 * @return string
	 */
	public function to_xml() {

		// Start Invoice
		$xml = '<Invoice>';

		// Type
		$xml .= '<Type>' . $this->get_type() . '</Type>';

		// Add Contact
		$xml .= $this->get_contact()->to_xml();

		// Date
		$xml .= '<Date>' . $this->get_date() . '</Date>';

		// Due Date
		$xml .= '<DueDate>' . $this->get_due_date() . '</DueDate>';

		// Invoice Number
		$xml .= '<InvoiceNumber>' . $this->get_invoice_number() . '</InvoiceNumber>';

		// Line Amount Types. Always send prices exclusive VAT.
		$xml .= '<LineAmountTypes>Exclusive</LineAmountTypes>';

		// Get Line Items
		$line_items = $this->get_line_items();

		// Check line items
		if ( count( $line_items ) ) {

			// Line Items wrapper open
			$xml .= '<LineItems>';

			// Loop
			foreach ( $line_items as $line_item ) {

				// Add
				$xml .= $line_item->to_xml();

			}

			// Line Items wrapper close
			$xml .= '</LineItems>';
		}

		// Currency Code
		$xml .= '<CurrencyCode>' . $this->get_currency_code() . '</CurrencyCode>';

		// Status
		$xml .= '<Status>AUTHORISED</Status>';

		// Total Tax
		$xml .= '<TotalTax>' . $this->get_total_tax() . '</TotalTax>';

		// Total
		$xml .= '<Total>' . $this->get_total() . '</Total>';

		// End Invoice
		$xml .= '</Invoice>';

		return $xml;
	}

}