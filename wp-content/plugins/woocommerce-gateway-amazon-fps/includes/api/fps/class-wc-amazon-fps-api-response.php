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
class WC_Amazon_FPS_API_Response extends SimpleXMLElement {

	/* Cannot override __construct when extending SimpleXMLElement */

	/**
	 * Check if the response has any errors
	 *
	 * @since 2.0
	 * @return bool, true if the response has error messages, false otherwise
	 */
	public function has_error() {

		return ( isset( $this->Errors ) );
	}


	/**
	 * Get any error messages in the response
	 *
	 * @since 2.0
	 * @return string the error messages separated by a comma, or N/A
	 */
	public function get_errors() {

		$errors = array();

		foreach ( $this->Errors->Error as $error ) {

			$errors[] = (string) $error->Code . ' - ' . (string) $error->Message;
		}

		return ( ! empty( $errors ) ) ? implode( ', ', $errors ) : __( 'N/A', WC_Amazon_FPS:: TEXT_DOMAIN );
	}


	/**
	 * Checks if the response to the VerifySignature API call indicates the provided signature is valid
	 *
	 * @since 2.0
	 * @return bool true if the signature was valid, false otherwise
	 */
	public function is_signature_valid() {

		return ( isset( $this->VerifySignatureResult->VerificationStatus ) && 'Success' === (string) $this->VerifySignatureResult->VerificationStatus );
	}


	/**
	 * Get the transaction status from a Pay request
	 *
	 * @since 2.0
	 * @return string|null the transaction status
	 */
	public function get_transaction_status() {

		return ( isset( $this->PayResult->TransactionStatus ) ) ? (string) $this->PayResult->TransactionStatus : null;
	}


	/**
	 * Get the transaction ID from a Pay request
	 *
	 * @since 2.0
	 * @return string|null the transaction ID
	 */
	public function get_transaction_id() {

		return ( isset( $this->PayResult->TransactionId ) ) ? (string) $this->PayResult->TransactionId : null;
	}


	/**
	 * Get the request ID from an API request
	 *
	 * @since 2.0
	 * @return string|null the request ID
	 */
	public function get_request_id() {

		return ( isset( $this->ResponseMetadata->RequestId ) ) ? (string) $this->ResponseMetadata->RequestId : null;
	}


}
