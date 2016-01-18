<?php
/**
 * Main eWAY Gateway Class
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WC_Gateway_EWAY' ) ) {

	class WC_Gateway_EWAY extends WC_Payment_Gateway {

		protected $api;
		protected $plugin_url;

		public function __construct() {

			$this->id                   = 'eway';
			$this->method_title         = __( 'eWAY', 'wc-eway' );
			$this->method_description   = __( 'Allow customers to securely save their credit card to their account for use with single purchases and subscriptions.', 'wc-eway' );
			$this->supports = array(
				'subscriptions',
				'products',
				'refunds',
				'subscription_cancellation',
				'subscription_reactivation',
				'subscription_suspension',
				'subscription_amount_changes',
				'subscription_date_changes'
			);

			$this->has_fields = true;

			$this->icon = apply_filters( 'woocommerce_eway_icon', '' );

			$this->card_types = '';

			// Load the form fields
			$this->init_form_fields();

			// Load the settings
			$this->init_settings();

			// Define user set variables
			foreach ( $this->settings as $setting_key => $setting ) {
				$this->$setting_key = $setting;
			}

			$this->saved_cards = $this->get_option( 'saved_cards' ) === "yes" ? true : false;

			// pay page fallback
			add_action( 'woocommerce_receipt_' . $this->id, array( $this, 'receipt_page' ) );

			// Save settings
			if ( is_admin() ) {
				add_action( 'woocommerce_update_options_payment_gateways', array( $this, 'process_admin_options' ) );
				add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
			}

			// Enqueu some JS functions and CSS
			add_filter( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			// Listen for results from eWAY
			add_action( 'woocommerce_api_' . strtolower( get_class( $this ) ), array( $this, 'response_listener' ) );
		}

		/**
		 * Initialize Gateway Settings form fields
		 *
		 * @access public
		 * @return void
		 */
		function init_form_fields() {
			$this->form_fields = array(
				'enabled' => array(
					'title' => __( 'Enable/Disable', 'wc-eway' ),
					'label' => __( 'Enable eWAY', 'wc-eway' ),
					'type' => 'checkbox',
					'description' => '',
					'default' => 'no'
				),
				'title' => array(
					'title' => __( 'Title', 'wc-eway' ),
					'type' => 'text',
					'description' => __( 'This controls the title which the user sees during checkout.', 'wc-eway' ),
					'default' => __( 'Credit Card', 'wc-eway' )
				),
				'description' => array(
					'title' => __( 'Description', 'wc-eway' ),
					'type' => 'textarea',
					'description' => __( 'This controls the description which the user sees during checkout.', 'wc-eway' ),
					'default' => 'Pay securely using your credit card.'
				),
				'customer_api' => array(
					'title' 		=> __( 'eWAY Customer API Key', 'wc-eway' ),
					'type'			=> 'text',
					'description' 	=> __( 'User API Key can be found in MYeWAY.', 'wc-eway' ),
					'default' 		=> '',
					'css' 			=> 'width: 400px'
				),
				'customer_password' => array(
					'title' 		=> __( 'eWAY Customer Password', 'wc-eway' ),
					'type' 			=> 'password',
					'description' 	=> __( 'Your eWAY Password.', 'wc-eway' ),
					'default' 		=> ''
				),
				'card_types' => array(
					'title' 		=> __( 'Allowed Card Types', 'wc-eway' ),
					'type' 			=> 'multiselect',
					'class'			=> 'chosen_select',
					'css'			=> 'width: 450px;',
					'default' 		=> array( 'visa', 'mastercard', 'discover', 'amex', 'dinersclub', 'maestro', 'laser', 'unionpay' ),
					'options'		=> array( 'visa' => 'Visa', 'mastercard' => 'MasterCard', 'discover' => 'Discover', 'amex' => 'AmEx', 'dinersclub' => 'Diners', 'maestro' => 'Maestro', 'laser' => 'Laser', 'unionpay' => 'UnionPay' )
				),
				'saved_cards' => array(
					'title'       => __( 'Saved cards', 'wc-eway' ),
					'label'       => __( 'Enable saved cards', 'wc-eway' ),
					'type'        => 'checkbox',
					'description' => __( 'If enabled, users will be able to pay with a saved card during checkout. Card details are saved on eWAY servers, not on your store.', 'wc-eway' ),
					'default'     => 'no'
				),
				'testmode' => array(
					'title' => __( 'eWAY Sandbox', 'wc-eway' ),
					'label' => __( 'Enable eWAY sandbox', 'wc-eway' ),
					'type' => 'checkbox',
					'description' => __( 'Place the payment gateway in development mode.', 'wc-eway' ),
					'default' => 'no'
				),
				'debug_mode' => array(
					'title'       => __( 'Debug Mode', 'wc-eway' ),
					'type'        => 'select',
					'desc_tip'    => __( 'Show Detailed Error Messages and API requests / responses on the checkout page.', 'wc-eway' ),
					'default'     => 'off',
					'options' => array(
						'off'      => __( 'Off', 'wc-eway' ),
						'on' => __( 'On', 'wc-eway' ),
					),
				),
			);
		}

		/**
		 * Check if gateway meets all the requirements to be used
		 *
		 * @access public
		 * @return bool
		 */
		function is_available() {
			// is enabled check
			$is_available = parent::is_available();

			// Required fields check
			if ( ! $this->customer_api && ! $this->customer_password )
				$is_available = false;

			return apply_filters( 'woocommerce_eway_is_available', $is_available );
		}

		/**
		 * Check for token payments and process the payment, otherwise redirect to pay page.
		 * @param int $order_id
		 * @return vpod
		 */
		public function process_payment( $order_id ) {
			global $woocommerce;
			$order = new WC_Order( $order_id );

			// Token payment
			if ( is_user_logged_in() && isset( $_POST['eway_card_id'] ) && 'new' !== $_POST['eway_card_id'] ) {
				$eway_token_customer_id = sanitize_text_field( $_POST['eway_card_id'] );
				try {
					$result = json_decode( $this->get_api()->direct_payment( $order, $eway_token_customer_id, $order->get_total() * 100.00 ) );
					$order = new WC_Order( intval( $result->Payment->InvoiceReference ) );
					if ( ! $order ) {
						throw new Exception( __( 'Order does not exist.', 'wc-eway' ) );
					}

					switch ( $result->ResponseMessage ) {
						case 'A2000' :
						case 'A2008' :
						case 'A2010' :
						case 'A2011' :
						case 'A2016' :
							$order->add_order_note( sprintf( __( 'eWAY token payment completed - %s', 'wc-eway' ), $this->response_message_lookup( $result->ResponseMessage ) ) );
							// WC 2.2
							if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '2.2' ) >= 0 ) {
								$order->payment_complete( $result->TransactionID );
							} else {
								// < WC 2.2
								update_post_meta( $order_id, '_transaction_id', $result->TransactionID );
								$order->payment_complete();
							}
							update_post_meta( $order->id, '_eway_token_customer_id', $eway_token_customer_id );
							break;
						default:
							if ( isset( $result->Errors ) && ! is_null( $result->Errors ) ) {
								$order->update_status( 'failed', sprintf( __( 'eWAY token payment failed - %s', 'wc-eway' ), $this->response_message_lookup( $result->Errors ) ) );
							} else {
								$order->update_status( 'failed', sprintf( __( 'eWAY token payment failed - %s', 'wc-eway' ), $this->response_message_lookup( $result->ResponseMessage ) ) );
							}
							break;
					}

					if ( defined( 'WC_VERSION' ) ) {
						WC()->cart->empty_cart();
					} else {
						global $woocommerce;
						$woocommerce->cart->empty_cart();
					}

					// return to thankyou page if successfull
					return array(
						'result' 	=> 'success',
						'redirect'	=> $this->get_return_url( $order )
					);

				} catch ( Exception $e ) {
					if ( defined( 'WC_VERSION' ) ) {
						wc_add_notice( $e->getMessage(), 'error' );
					} else {
						global $woocommerce;
						$woocommerce->add_error( $e->getMessage() );
					}
					return;
				}
			} else if ( is_user_logged_in() && isset( $_POST['eway_card_id'] ) && 'new' == $_POST['eway_card_id'] ) {
				update_post_meta( $order->id, '_eway_token_customer_id', 'new' );
			}
			// Redirect to pay/receipt page to follow normal credit card payment
			return array(
				'result' 	=> 'success',
				'redirect'	=> $order->get_checkout_payment_url( true )
			);
		}

		/**
		 * Load the payment form
		 * @param  int $order_id
		 * @return void
		 */
		public function receipt_page( $order_id ) {
			wp_enqueue_script( 'eway-credit-card-form' );

			// Get the order
			$order = new WC_Order( $order_id );

			try {
				$token_payment = get_post_meta( $order->id, '_eway_token_customer_id', true );
				if ( $token_payment && 'new' == $token_payment ) {
					$result = json_decode( $this->get_api()->request_access_code( $order, 'TokenPayment', 'Recurring' ) );
				} else {
					$result = json_decode( $this->get_api()->request_access_code( $order ) );
				}
				$form_url = $result->FormActionURL;

				ob_start();
				woocommerce_get_template( 'eway-cc-form.php', array(), 'eway/', plugin_dir_path( __FILE__ ) . '../templates/' );
				echo '<input type="hidden" name="EWAY_ACCESSCODE" value="' . $result->AccessCode .'"/>';
				echo '<input type="hidden" name="EWAY_CARDNAME" value="' . $order->billing_first_name . ' ' . $order->billing_last_name . '"/>';
				echo '<input type="hidden" name="EWAY_CARDNUMBER" id="EWAY_CARDNUMBER" value=""/>';
				echo '<input type="hidden" name="EWAY_CARDEXPIRYMONTH" id="EWAY_CARDEXPIRYMONTH" value=""/>';
				echo '<input type="hidden" name="EWAY_CARDEXPIRYYEAR" id="EWAY_CARDEXPIRYYEAR" value=""/>';
				$form = '<form method="post" action="' . $form_url . '" id="eway_credit_card_form">';
				$form .= ob_get_clean();
				$form .= '</form>';
				echo $form;
			} catch ( Exception $e ) {
				if ( defined( 'WC_VERSION' ) ) {
					wc_add_notice( $e->getMessage() . ': ' . __( 'Please check your eWAY API key and password.', 'wc-eway' ), 'error' );
				} else {
					global $woocommerce;
					$woocommerce->add_error( $e->getMessage() );
				}
				return;
			}
		}

		/**
		 * Get the eWAY API object
		 * @return object WC_EWAY_API
		 */
		public function get_api() {
			if ( is_object( $this->api ) ) {
				return $this->api;
			}

			require 'class-wc-eway-api.php';

			return $this->api = new WC_EWAY_API( $this->customer_api, $this->customer_password, $this->testmode == 'yes' ? 'sandbox' : 'production', $this->debug_mode );
		}

		/**
		 * Enqueue scripts
		 * @return void
		 */
		public function enqueue_scripts() {
			$suffix  = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			// Load new CC form in WC < 2.1
			if ( ! defined( 'WC_VERSION' ) ) {
				wp_register_script( 'jquery-payment', $this->plugin_url() . 'assets/js/frontend/jquery.payment' . $suffix . '.js', array( 'jquery' ), '3.0.2', true );
			}
			wp_register_script( 'eway-credit-card-form', $this->plugin_url() . 'assets/js/frontend/eway-credit-card-form.js', array( 'jquery', 'jquery-payment' ), '3.0.2', true );
			wp_localize_script( 'eway-credit-card-form', 'eway_settings', array( 'card_types' => $this->card_types ) );
		}

		/**
		 * Get the plugin URL
		 * @return string
		 */
		private function plugin_url() {
			if ( isset( $this->plugin_url ) ) return trailingslashit( $this->plugin_url );

			if ( is_ssl() ) {
				return trailingslashit( $this->plugin_url = str_replace( 'http://', 'https://', WP_PLUGIN_URL ) . "/" . plugin_basename( dirname( dirname( __FILE__ ) ) ) );
			} else {
				return trailingslashit( $this->plugin_url = WP_PLUGIN_URL . "/" . plugin_basename( dirname( dirname( __FILE__ ) ) ) );
			}
		}

		/**
		 * Listen for a response from eWAY on API URL and Process request
		 * @return void
		 */
		public function response_listener() {
			if ( isset( $_GET['AccessCode'] ) ) {
				$access_code = $_GET['AccessCode'];
				$result = json_decode( $this->get_api()->get_access_code_result( $access_code ) );
				// Use InvoiceRef temp, untill eWAY sorts out empty options echo
				$order = new WC_Order( intval( $result->InvoiceReference ) );
				if ( ! $order ) {
					return;
				}
				switch ( $result->ResponseMessage ) {
					case 'A2000' :
					case 'A2008' :
					case 'A2010' :
					case 'A2011' :
					case 'A2016' :
						if ( isset( $result->TokenCustomerID ) && ! empty( $result->TokenCustomerID ) ) {
							// Save Token ID to customer meta and order meta
							$eway_cards = get_user_meta( $order->user_id, '_eway_token_cards', true );
							if ( ! $eway_cards ) {
								$eway_cards = array();
							}

							// Check if masked card number was passed otherwise look it up
							if ( isset( $result->Customer->CardDetails ) && ! empty( $result->Customer->CardDetails ) ) {
								$masked_card_number = $result->Customer->CardDetails->Number;
								$eway_cards[ $result->TokenCustomerID ] = array( 'id' => $result->TokenCustomerID, 'number' => $masked_card_number, 'exp_month' => $result->Customer->CardDetails->ExpiryMonth, 'exp_year' => $result->Customer->CardDetails->ExpiryYear );
							} else {
								$customer_result = json_decode( $this->get_api()->lookup_customer( $result->TokenCustomerID ) );
								if ( isset( $customer_result->Customers[0] ) ) {
									$masked_card_number = $customer_result->Customers[0]->CardDetails->Number;
									$eway_cards[ $result->TokenCustomerID ] = array( 'id' => $result->TokenCustomerID, 'number' => $masked_card_number, 'exp_month' => $customer_result->Customers[0]->CardDetails->ExpiryMonth, 'exp_year' => $customer_result->Customers[0]->CardDetails->ExpiryYear );
								}
							}

							// Save Token ID to customer meta and order meta
							if ( isset( $eway_cards[0] ) ) {
								unset( $eway_cards[0] );
							}
							update_user_meta( $order->user_id, '_eway_token_cards', $eway_cards );
							update_post_meta( $order->id, '_eway_token_customer_id', $result->TokenCustomerID );
							$order->add_order_note( sprintf( __( 'eWAY Token Customer Created - TokenCustomerID: %s Masked Card: %s', 'wc-eway' ), $result->TokenCustomerID, $masked_card_number ) );
						}
						$order->add_order_note( sprintf( __( 'eWAY payment completed - %s', 'wc-eway' ), $this->response_message_lookup( $result->ResponseMessage ) ) );
						// WC 2.2
						if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '2.2' ) >= 0 ) {
							$order->payment_complete( $result->TransactionID );
						} else {
							// < WC 2.2
							update_post_meta( $order->id, '_transaction_id', $result->TransactionID );
							$order->payment_complete();
						}
						break;
					default:
						$order->update_status( 'failed', sprintf( __( 'eWAY payment failed - %s', 'wc-eway' ), $this->response_message_lookup( $result->ResponseMessage ) ) );
						break;
				}
				if ( defined( 'WC_VERSION' ) ) {
					WC()->cart->empty_cart();
				} else {
					global $woocommerce;
					$woocommerce->cart->empty_cart();
				}
				wp_redirect( $this->get_return_url( $order ) );
			}
		}

		/**
		 * Show description and option to save cards, or pay with new cards on checkout.
		 * @return void
		 */
		public function payment_fields() {
			$checked = 1;
			if ( $this->description ) {
				echo '<p>' . wp_kses_post( $this->description ) . '</p>';
			}

			if ( $this->saved_cards && is_user_logged_in() ) {
				$eway_cards = get_user_meta( get_current_user_id(), '_eway_token_cards', true );
				?>
					<p class="form-row form-row-wide">
						<a class="button" style="float:right;" href="<?php echo apply_filters( 'wc_eway_manage_saved_cards_url', get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>#saved-cards"><?php _e( 'Manage cards', 'wc-eway' ); ?></a>
						<?php if ( $eway_cards ) : ?>
							<?php foreach ( (array) $eway_cards as $card ) : ?>
								<label for="eway_card_<?php echo $card['id']; ?>">
									<input type="radio" id="eway_card_<?php echo $card['id']; ?>" name="eway_card_id" value="<?php echo $card['id']; ?>" <?php checked( $checked, 1 ) ?> />
									<?php printf( __( '%s (Expires %s/%s)', 'wc-eway' ), $card['number'], $card['exp_month'], $card['exp_year'] ); ?>
								</label>
								<?php $checked = 0; endforeach; ?>
						<?php endif; ?>
						<label for="new">
							<input type="radio" id="new" name="eway_card_id" <?php checked( $checked, 1 ) ?> value="new" /> 
							<?php _e( 'Use a new credit card', 'wc-eway' ); ?>
						</label>
					</p>
				<?php
			}
		}

		/**
		 * Process refunds for WC 2.2+
		 * @param  int $order_id
		 * @param  int $amount
		 * @param  string $reason
		 * @return bool|WP_Error
		 */
		public function process_refund( $order_id, $amount = null, $reason = null ) {
			$order = new WC_Order( $order_id );

			if ( ! $order ) {
				return new WP_Error( 'eway_refund_error', __( 'Order not valid', 'wc-eway' ) );
			}

			$transction_id = get_post_meta( $order_id, '_transaction_id', true );

			if ( ! $transction_id || empty( $transction_id ) ) {
				return new WP_Error( 'eway_refund_error', __( 'No valid Transaction ID found', 'wc-eway' ) );
			}

			if ( is_null( $amount ) || $amount <= 0 ) {
				return new WP_Error( 'eway_refund_error', __( 'Amount not valid', 'wc-eway' ) );
			}

			if ( is_null( $reason ) || '' == $reason ) {
				$reason = sprintf( __( 'Refund for Order %s', 'wc-eway' ), $order->get_order_number() );
			}

			try {
				$result = json_decode( $this->get_api()->direct_refund( $order, $transction_id, $amount * 100, $reason ) );
				switch ( $result->ResponseMessage ) {
					case 'A2000' :
					case 'A2008' :
					case 'A2010' :
					case 'A2011' :
					case 'A2016' :
						return true;
						break;
					default:
						if ( isset( $result->Errors ) && ! is_null( $result->Errors ) ) {
							return new WP_Error( 'eway_refund_error', $this->response_message_lookup( $result->Errors ) );
						} else {
							return new WP_Error( 'eway_refund_error', $this->response_message_lookup( $result->ResponseMessage ) );
						}
						break;
				}
			} catch ( Exception $e ) {
				return new WP_Error( 'eway_refund_error', $e->getMessage() );
			}
		}

		/**
		 * Lookup Response / Error messages based on codes
		 * @param  string $response_message
		 * @return string
		 */
		public function response_message_lookup( $response_message ) {
			$messages = array(
				'A2000' => 'Transaction Approved',
				'A2008' => 'Honour With Identification',
				'A2010' => 'Approved For Partial Amount',
				'A2011' => 'Approved, VIP',
				'A2016' => 'Approved, Update Track 3',
				'D4401' => 'Refer to Issuer',
				'D4402' => 'Refer to Issuer, special',
				'D4403' => 'No Merchant',
				'D4404' => 'Pick Up Card',
				'D4405' => 'Do Not Honour',
				'D4406' => 'Error',
				'D4407' => 'Pick Up Card, Special',
				'D4409' => 'Request In Progress',
				'D4412' => 'Invalid Transaction',
				'D4413' => 'Invalid Amount',
				'D4414' => 'Invalid Card Number',
				'D4415' => 'No Issuer',
				'D4419' => 'Re-enter Last Transaction',
				'D4421' => 'No Action Taken',
				'D4422' => 'Suspected Malfunction',
				'D4423' => 'Unacceptable Transaction Fee',
				'D4425' => 'Unable to Locate Record On File',
				'D4430' => 'Format Error',
				'D4431' => 'Bank Not Supported By Switch',
				'D4433' => 'Expired Card, Capture',
				'D4434' => 'Suspected Fraud, Retain Card',
				'D4435' => 'Card Acceptor, Contact Acquirer, Retain Card',
				'D4436' => 'Restricted Card, Retain Card',
				'D4437' => 'Contact Acquirer Security Department, Retain Card',
				'D4438' => 'PIN Tries Exceeded, Capture',
				'D4439' => 'No Credit Account',
				'D4440' => 'Function Not Supported',
				'D4441' => 'Lost Card',
				'D4442' => 'No Universal Account',
				'D4443' => 'Stolen Card',
				'D4444' => 'No Investment Account',
				'D4451' => 'Insufficient Funds',
				'D4452' => 'No Cheque Account',
				'D4453' => 'No Savings Account',
				'D4454' => 'Expired Card',
				'D4455' => 'Incorrect PIN',
				'D4456' => 'No Card Record',
				'D4457' => 'Function Not Permitted to Cardholder',
				'D4458' => 'Function Not Permitted to Terminal',
				'D4459' => 'Suspected Fraud',
				'D4460' => 'Acceptor Contact Acquirer',
				'D4461' => 'Exceeds Withdrawal Limit',
				'D4462' => 'Restricted Card',
				'D4463' => 'Security Violation',
				'D4464' => 'Original Amount Incorrect',
				'D4466' => 'Acceptor Contact Acquirer, Security',
				'D4467' => 'Capture Card',
				'D4475' => 'PIN Tries Exceeded',
				'D4482' => 'CVV Validation Error',
				'D4490' => 'Cut off In Progress',
				'D4491' => 'Card Issuer Unavailable',
				'D4492' => 'Unable To Route Transaction',
				'D4493' => 'Cannot Complete, Violation Of The Law',
				'D4494' => 'Duplicate Transaction',
				'D4496' => 'System Error',
				'D4497' => 'MasterPass Error',
				'D4498' => 'PayPal Create Transaction Error',
				'S5000' => 'System Error',
				'S5085' => 'Started 3dSecure',
				'S5086' => 'Routed 3dSecure',
				'S5087' => 'Completed 3dSecure',
				'S5088' => 'PayPal Transaction Created',
				'S5099' => 'Incomplete (Access Code in progress/incomplete)',
				'S5010' => 'Unknown error returned by gateway',
				'V6000' => 'Validation error',
				'V6001' => 'Invalid CustomerIP',
				'V6002' => 'Invalid DeviceID',
				'V6003' => 'Invalid Request PartnerID',
				'V6004' => 'Invalid Request Method',
				'V6010' => 'Invalid TransactionType, account not certified for eCome only MOTO or Recurring available',
				'V6011' => 'Invalid Payment TotalAmount',
				'V6012' => 'Invalid Payment InvoiceDescription',
				'V6013' => 'Invalid Payment InvoiceNumber',
				'V6014' => 'Invalid Payment InvoiceReference',
				'V6015' => 'Invalid Payment CurrencyCode',
				'V6016' => 'Payment Required',
				'V6017' => 'Payment CurrencyCode Required',
				'V6018' => 'Unknown Payment CurrencyCode',
				'V6021' => 'EWAY_CARDHOLDERNAME Required',
				'V6022' => 'EWAY_CARDNUMBER Required',
				'V6023' => 'EWAY_CARDCVN Required',
				'V6033' => 'Invalid Expiry Date',
				'V6034' => 'Invalid Issue Number',
				'V6035' => 'Invalid Valid From Date',
				'V6040' => 'Invalid TokenCustomerID',
				'V6041' => 'Customer Required',
				'V6042' => 'Customer FirstName Required',
				'V6043' => 'Customer LastName Required',
				'V6044' => 'Customer CountryCode Required',
				'V6045' => 'Customer Title Required',
				'V6046' => 'TokenCustomerID Required',
				'V6047' => 'RedirectURL Required',
				'V6051' => 'Invalid Customer FirstName',
				'V6052' => 'Invalid Customer LastName',
				'V6053' => 'Invalid Customer CountryCode',
				'V6058' => 'Invalid Customer Title',
				'V6059' => 'Invalid RedirectURL',
				'V6060' => 'Invalid TokenCustomerID',
				'V6061' => 'Invalid Customer Reference',
				'V6062' => 'Invalid Customer CompanyName',
				'V6063' => 'Invalid Customer JobDescription',
				'V6064' => 'Invalid Customer Street1',
				'V6065' => 'Invalid Customer Street2',
				'V6066' => 'Invalid Customer City',
				'V6067' => 'Invalid Customer State',
				'V6068' => 'Invalid Customer PostalCode',
				'V6069' => 'Invalid Customer Email',
				'V6070' => 'Invalid Customer Phone',
				'V6071' => 'Invalid Customer Mobile',
				'V6072' => 'Invalid Customer Comments',
				'V6073' => 'Invalid Customer Fax',
				'V6074' => 'Invalid Customer URL',
				'V6075' => 'Invalid ShippingAddress FirstName',
				'V6076' => 'Invalid ShippingAddress LastName',
				'V6077' => 'Invalid ShippingAddress Street1',
				'V6078' => 'Invalid ShippingAddress Street2',
				'V6079' => 'Invalid ShippingAddress City',
				'V6080' => 'Invalid ShippingAddress State',
				'V6081' => 'Invalid ShippingAddress PostalCode',
				'V6082' => 'Invalid ShippingAddress Email',
				'V6083' => 'Invalid ShippingAddress Phone',
				'V6084' => 'Invalid ShippingAddress Country',
				'V6085' => 'Invalid ShippingAddress ShippingMethod',
				'V6086' => 'Invalid ShippingAddress Fax ',
				'V6091' => 'Unknown Customer CountryCode',
				'V6092' => 'Unknown ShippingAddress CountryCode',
				'V6100' => 'Invalid EWAY_CARDNAME',
				'V6101' => 'Invalid EWAY_CARDEXPIRYMONTH',
				'V6102' => 'Invalid EWAY_CARDEXPIRYYEAR',
				'V6103' => 'Invalid EWAY_CARDSTARTMONTH',
				'V6104' => 'Invalid EWAY_CARDSTARTYEAR',
				'V6105' => 'Invalid EWAY_CARDISSUENUMBER',
				'V6106' => 'Invalid EWAY_CARDCVN',
				'V6107' => 'Invalid EWAY_ACCESSCODE',
				'V6108' => 'Invalid CustomerHostAddress',
				'V6109' => 'Invalid UserAgent',
				'V6110' => 'Invalid EWAY_CARDNUMBER',
				'V6111' => 'Unauthorised API Access, Account Not PCI Certified'
			);
			if ( isset( $messages[ $response_message ] ) ) {
				return $messages[ $response_message ];
			}
			return $response_message;
		}

	}
}