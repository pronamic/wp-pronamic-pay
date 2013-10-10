<?php

/**
 * Title: WordPress iDEAL plugin
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WordPress_IDeal_Plugin {
	/**
	 * The maximum number of payments that can be done without an license
	 *
	 * @var int
	 */
	const PAYMENTS_MAX_LICENSE_FREE = 20;

	//////////////////////////////////////////////////

	/**
	 * The current version of this plugin
	 *
	 * @var string
	 */
	const VERSION = '1.3.4';

	//////////////////////////////////////////////////

	/**
	 * The root file of this WordPress plugin
	 *
	 * @var string
	 */
	public static $file;

	/**
	 * The plugin dirname
	 *
	 * @var string
	 */
	public static $dirname;

	//////////////////////////////////////////////////

	/**
	 * Bootstrap
	 *
	 * @param string $file
	 */
	public static function bootstrap( $file ) {
		self::$file    = $file;
		self::$dirname = dirname( $file );

		// Bootstrap the add-ons
		if ( self::can_be_used() ) {
			Pronamic_WooCommerce_IDeal_AddOn::bootstrap();
			Pronamic_GravityForms_IDeal_AddOn::bootstrap();
			Pronamic_Shopp_IDeal_AddOn::bootstrap();
			Pronamic_Jigoshop_IDeal_AddOn::bootstrap();
			Pronamic_WPeCommerce_IDeal_AddOn::bootstrap();
			Pronamic_ClassiPress_IDeal_AddOn::bootstrap();
			Pronamic_EShop_IDeal_AddOn::bootstrap();
			Pronamic_EventEspresso_IDeal_AddOn::bootstrap();
			Pronamic_AppThemes_IDeal_AddOn::bootstrap();
			Pronamic_S2Member_IDeal_AddOn::bootstrap();
			Pronamic_Membership_IDeal_AddOn::bootstrap();
		}

		// Admin
		if ( is_admin() ) {
			Pronamic_WordPress_IDeal_Admin::bootstrap();
		}

		add_action( 'plugins_loaded', array( __CLASS__, 'setup' ) );

		// Initialize requirements
		require_once self::$dirname . '/includes/post.php';
		require_once self::$dirname . '/includes/wp-e-commerce.php';

		// xmlseclibs is a library written in PHP for working with XML Encryption and Signatures. 
		// @see https://code.google.com/p/xmlseclibs/
		require_once self::$dirname . '/includes/xmlseclibs/xmlseclibs.php';

		// On template redirect handle an possible return from iDEAL
		add_action( 'template_redirect', array( __CLASS__, 'handle_returns' ) );

		add_action( 'pronamic_ideal_advanced_return_raw',      array( 'Pronamic_Gateways_IDealAdvanced_ReturnHandler', 'returns' ), 10, 2 );
		add_action( 'pronamic_ideal_advanced_v3_return_raw',   array( 'Pronamic_Gateways_IDealAdvancedV3_ReturnHandler', 'returns' ), 10, 2 );
		add_action( 'pronamic_ideal_easy_return_raw',          array( 'Pronamic_Gateways_IDealEasy_ReturnHandler', 'returns' ), 10, 2 );
		add_action( 'pronamic_ideal_basic_return_raw',         array( 'Pronamic_Gateways_IDealBasic_ReturnHandler', 'returns' ), 10, 2 );
		add_action( 'pronamic_ideal_internetkassa_return_raw', array( 'Pronamic_Gateways_IDealInternetKassa_ReturnHandler', 'returns' ), 10, 2 );
		add_action( 'pronamic_ideal_mollie_return_raw',        array( 'Pronamic_Gateways_Mollie_ReturnHandler', 'returns' ) );
		add_action( 'pronamic_ideal_buckaroo_return_raw',      array( 'Pronamic_Gateways_Buckaroo_ReturnHandler', 'returns' ), 10, 2);
		add_action( 'pronamic_ideal_omnikassa_return_raw',     array( 'Pronamic_Gateways_OmniKassa_ReturnHandler', 'returns' ), 10, 2 );
		add_action( 'pronamic_ideal_targetpay_return_raw',     array( 'Pronamic_Gateways_TargetPay_ReturnHandler', 'returns' ), 10, 2 );
		add_action( 'pronamic_ideal_icepay_return_raw',        array( 'Pronamic_Gateways_Icepay_ReturnHandler', 'returns' ), 10, 2 );
		add_action( 'pronamic_ideal_sisow_return_raw',         array( 'Pronamic_Gateways_Sisow_ReturnHandler', 'returns' ), 10, 4 );
		add_action( 'pronamic_ideal_qantani_return_raw',       array( 'Pronamic_Gateways_Qantani_ReturnHandler', 'returns' ), 10, 4 );

		// Check the payment status on an iDEAL return
		add_action( 'pronamic_ideal_advanced_return',       array( __CLASS__, 'checkPaymentStatus' ),                  10, 2 );
		add_action( 'pronamic_ideal_advanced_v3_return',    array( __CLASS__, 'checkPaymentStatus' ),                  10, 2 );
		add_action( 'pronamic_ideal_basic_return',          array( __CLASS__, 'update_ideal_basic_payment_status' ),   10, 3 );
		add_action( 'pronamic_ideal_easy_return',           array( __CLASS__, 'update_ideal_easy_payment_status' ),   10, 3 );
		add_action( 'pronamic_ideal_internetkassa_return',  array( __CLASS__, 'update_internetkassa_payment_status' ), 10, 2 );
		add_action( 'pronamic_ideal_omnikassa_return',      array( __CLASS__, 'update_omnikassa_payment_status' ),     10, 2 );
		add_action( 'pronamic_ideal_mollie_return',         array( __CLASS__, 'update_mollie_payment_status' ),        10, 2 );
		add_action( 'pronamic_ideal_targetpay_return',      array( __CLASS__, 'update_targetpay_payment_status' ),     10, 2 );
		add_action( 'pronamic_ideal_buckaroo_return',       array( __CLASS__, 'update_buckaroo_payment_status' ),      10, 2 );
		add_action( 'pronamic_ideal_icepay_return',         array( __CLASS__, 'update_icepay_payment_status' ),        10, 2 );
		add_action( 'pronamic_ideal_sisow_return',          array( __CLASS__, 'update_sisow_payment_status' ),         10, 2 );
		add_action( 'pronamic_ideal_qantani_return',        array( __CLASS__, 'update_qantani_payment_status' ),         10, 2 );

		// The 'pronamic_ideal_check_transaction_status' hook is scheduled the status requests
		add_action( 'pronamic_ideal_check_transaction_status', array( __CLASS__, 'checkStatus' ) );

		// Show license message if the license is not valid
		add_action( 'admin_notices', array( __CLASS__, 'admin_notices' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Check status of the specified payment
	 *
	 * @param string $paymentId
	 */
	public static function checkStatus( $payment_id = null ) {
		$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentById( $payment_id );

		if ( $payment !== null ) {
			// http://pronamic.nl/wp-content/uploads/2011/12/iDEAL_Advanced_PHP_EN_V2.2.pdf (page 19)
			// - No status request after a final status has been received for a transaction;
			$status = $payment->status;

			if ( empty( $status ) || $status === Pronamic_Gateways_IDealAdvancedV3_Status::OPEN ) {
				self::checkPaymentStatus( $payment );
			}
		} else {
			// Payment with the specified ID could not be found, can't check the status
		}
	}

	/**
	 * Check the status of the specified payment
	 *
	 * @param unknown_type $payment
	 */
	public static function checkPaymentStatus( Pronamic_WordPress_IDeal_Payment $payment, $can_redirect = false ) {
		$configuration = $payment->configuration;
		$variant = $configuration->getVariant();

		if ( $variant->getMethod() == 'advanced_v3' ) {
			$gateway = new Pronamic_Gateways_IDealAdvancedV3_Gateway( $configuration );

			$gateway->update_status( $payment );

			$updated = Pronamic_WordPress_IDeal_PaymentsRepository::updateStatus( $payment );

			do_action( 'pronamic_ideal_status_update', $payment, $can_redirect );
		}

		if ( $variant->getMethod() == 'advanced' ) {
			$gateway = new Pronamic_Gateways_IDealAdvanced_Gateway( $configuration );

			$gateway->update_status( $payment );

			$updated = Pronamic_WordPress_IDeal_PaymentsRepository::updateStatus( $payment );

			do_action( 'pronamic_ideal_status_update', $payment, $can_redirect );
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Handle returns
	 */
	public static function handle_returns() {
		Pronamic_Gateways_IDealAdvanced_ReturnHandler::listen();
		Pronamic_Gateways_IDealAdvancedV3_ReturnHandler::listen();
		Pronamic_Gateways_IDealBasic_ReturnHandler::listen();
		Pronamic_Gateways_IDealEasy_ReturnHandler::listen();
		Pronamic_Gateways_IDealInternetKassa_ReturnHandler::listen();
		Pronamic_Gateways_Mollie_ReturnHandler::listen();
		Pronamic_Gateways_Buckaroo_ReturnHandler::listen();
		Pronamic_Gateways_OmniKassa_ReturnHandler::listen();
		Pronamic_Gateways_TargetPay_ReturnHandler::listen();
		Pronamic_Gateways_Icepay_ReturnHandler::listen();
		Pronamic_Gateways_Sisow_ReturnHandler::listen();
		Pronamic_Gateways_Qantani_ReturnHandler::listen();
	}

	/**
	 * Update kassa payment status
	 *
	 * @param array $data
	 * @param boolean $canRedirect
	 */
	public static function update_internetkassa_payment_status( $data, $can_redirect = false ) {
		$payment = Pronamic_WordPress_IDeal_PaymentsRepository::get_payment_by_purchase_id( array( 'purchase_id' => $data['ORDERID'] ) );

		if ( $payment != null ) {
			$status = null;

			switch ( $data['STATUS'] ) {
				case Pronamic_Gateways_IDealInternetKassa_Statuses::INCOMPLETE_OR_INVALID:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::AUTHORIZATION_REFUSED:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::AUTHOR_DELETION_REFUSED:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::PAYMENT_DELETION_REFUSED:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::REFUND_REFUSED:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::PAYMENT_DECLIEND_BY_THE_ACQUIRER:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::PAYMENT_REFUSED:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::REFUND_DECLINED_BY_THE_ACQUIRER:
					$status = Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_FAILURE;
					break;
				case Pronamic_Gateways_IDealInternetKassa_Statuses::CANCELLED_BY_CLIENT:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::AUTHORIZED_AND_CANCELLED:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::AUTHORIZED_AND_CANCELLED_64:
					$status = Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_CANCELLED;
					break;
				case Pronamic_Gateways_IDealInternetKassa_Statuses::ORDER_STORED:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::STORED_WAITING_EXTERNAL_RESULT:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::WAITING_CLIENT_PAYMENT:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::AUHTORIZED_WAITING_EXTERNAL_RESULT:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::AUTHORIZATION_WAITING:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::AUTHORIZATION_NOT_KNOWN:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::STAND_BY:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::OK_WITH_SCHEDULED_PAYMENTS:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::ERROR_IN_SCHEDULED_PAYMENTS:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::AUHORIZ_TO_GET_MANUALLY:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::AUTHOR_DELETION_WAITING:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::AUTHOR_DELETION_UNCERTAIN:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::PAYMENT_DELETION_PENDING:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::PAYMENT_DELETION_UNCERTAIN:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::PAYMENT_DELETED_74:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::DELETION_PROCESSED_BY_MERCHANT:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::REFUND_PENDING:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::REFUND_UNCERTAIN:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::PAYMENT_UNCERTAIN:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::PAYMENT_PROCESSING:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::BEING_PROCESSED:
					// pending
					break;
				case Pronamic_Gateways_IDealInternetKassa_Statuses::AUTHORIZED:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::PAYMENT_DELETED:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::REFUND:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::REFUND_PROCESSED_BY_MERCHANT:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::PAYMENT_REQUESTED:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::PAYMENT_PROCESSED_BY_MERCHANT:
					$status = Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_SUCCESS;
					break;
			}

			if ( $status != null ) {
				$payment->status = $status;

				$updated = Pronamic_WordPress_IDeal_PaymentsRepository::updateStatus( $payment );
			}

			do_action( 'pronamic_ideal_status_update', $payment, $can_redirect );
		}
	}

	/**
	 * Update OmniKassa payment status
	 *
	 * @param array $result
	 * @param boolean $canRedirect
	 */
	public static function update_omnikassa_payment_status( $data, $can_redirect = false ) {
		$transaction_reference = $data['transactionReference'];

		$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentByIdAndEc( $transaction_reference );

		if ( $payment != null ) {
			$response_code = $data['responseCode'];

			$status = Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_OPEN;

			switch ( $response_code ) {
				case Pronamic_Gateways_OmniKassa_OmniKassa::RESPONSE_CODE_TRANSACTION_SUCCES:
					$status = Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_SUCCESS;
					break;
				case Pronamic_Gateways_OmniKassa_OmniKassa::RESPONSE_CODE_CANCELLATION_OF_PAYMENT:
					$status = Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_CANCELLED;
					break;
			}

			$payment->status = $status;

			$updated = Pronamic_WordPress_IDeal_PaymentsRepository::updateStatus( $payment );

			do_action( 'pronamic_ideal_status_update', $payment, $can_redirect );
		}
	}

	/**
	 * Update Mollie payment status
	 *
	 * @param array $result
	 * @param boolean $canRedirect
	 */
	public static function update_mollie_payment_status( $payment, $can_redirect = false ) {
		$transaction_id = $payment->transaction_id;

		$configuration = $payment->configuration;

		$gateway = new Pronamic_Gateways_Mollie_Gateway( $configuration );

		$gateway->update_status( $payment );

		$updated = Pronamic_WordPress_IDeal_PaymentsRepository::updateStatus( $payment );

		do_action( 'pronamic_ideal_status_update', $payment, $can_redirect );
	}
  	/**
	 * Update Buckaroo payment status
	 *
	 * @param array $result
	 * @param boolean $canRedirect
	 */
	public static function update_buckaroo_payment_status( $data, $can_redirect = false ) {
		$invoice_number = $data[Pronamic_Gateways_Buckaroo_Parameters::INVOICE_NUMBER];
		$status_code    = $data[Pronamic_Gateways_Buckaroo_Parameters::STATUS_CODE];

		$payment = Pronamic_WordPress_IDeal_PaymentsRepository::get_payment_by_purchase_id( $invoice_number );

		if ( $payment != null ) {
			$status = null;

			switch ( $status_code ) {
				case Pronamic_Gateways_Buckaroo_Statuses::PAYMENT_SUCCESS:
					$status = Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_SUCCESS;
					break;
				case Pronamic_Gateways_Buckaroo_Statuses::PAYMENT_FAILURE:
				case Pronamic_Gateways_Buckaroo_Statuses::VALIDATION_FAILURE:
				case Pronamic_Gateways_Buckaroo_Statuses::TECHNICAL_ERROR:
				case Pronamic_Gateways_Buckaroo_Statuses::PAYMENT_REJECTED:
					$status = Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_FAILURE;
					break;
				case Pronamic_Gateways_Buckaroo_Statuses::WAITING_FOR_USER_INPUT:
				case Pronamic_Gateways_Buckaroo_Statuses::WAITING_FOR_PROCESSOR:
				case Pronamic_Gateways_Buckaroo_Statuses::WAITING_ON_CONSUMER_ACTION:
				case Pronamic_Gateways_Buckaroo_Statuses::PAYMENT_ON_HOLD:
					$status = Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_OPEN;
					break;
				case Pronamic_Gateways_Buckaroo_Statuses::CANCELLED_BY_CONSUMER:
				case Pronamic_Gateways_Buckaroo_Statuses::CANCELLED_BY_MERCHANT:
					$status = Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_CANCELLED;
					break;
			}

			if ( $status != null ) {
				$payment->status        = $status;
				$payment->consumer_iban = $data[Pronamic_Gateways_Buckaroo_Parameters::SERVICE_IDEAL_CONSUMER_IBAN];
				$payment->consumer_bic  = $data[Pronamic_Gateways_Buckaroo_Parameters::SERVICE_IDEAL_CONSUMER_BIC];
				$payment->consumer_name = $data[Pronamic_Gateways_Buckaroo_Parameters::SERVICE_IDEAL_CONSUMER_NAME];

				$updated = Pronamic_WordPress_IDeal_PaymentsRepository::updateStatus( $payment );
			}

			do_action( 'pronamic_ideal_status_update', $payment, $can_redirect );
		}
	}

	public static function update_icepay_payment_status( $payment, $can_redirect = false ) {
		$configuration = $payment->configuration;

		$gateway = new Pronamic_Gateways_Icepay_Gateway( $configuration );
		$gateway->update_status( $payment );

		Pronamic_WordPress_IDeal_PaymentsRepository::updateStatus( $payment );

		do_action( 'pronamic_ideal_status_update', $payment, $can_redirect );
	}

	public static function update_sisow_payment_status( $payment, $can_redirect = false ) {
		$configuration = $payment->configuration;

		$gateway = new Pronamic_Gateways_Sisow_Gateway( $configuration );
		$gateway->update_status( $payment );

		Pronamic_WordPress_IDeal_PaymentsRepository::updateStatus( $payment );

		do_action( 'pronamic_ideal_status_update', $payment, $can_redirect );
	}

	public static function update_qantani_payment_status( $payment, $can_redirect = false ) {
		Pronamic_WordPress_IDeal_PaymentsRepository::updateStatus( $payment );

		do_action( 'pronamic_ideal_status_update', $payment, $can_redirect );
	}

	/**
	 * Update TargetPay payment status
	 *
	 * @param array $result
	 * @param boolean $canRedirect
	 */
	public static function update_targetpay_payment_status( $payment, $can_redirect = false ) {
		$transaction_id = $payment->transaction_id;

		$configuration = $payment->configuration;

		$gateway = new Pronamic_Gateways_TargetPay_Gateway( $configuration );

		$gateway->update_status( $payment );

		$updated = Pronamic_WordPress_IDeal_PaymentsRepository::updateStatus( $payment );

		do_action( 'pronamic_ideal_status_update', $payment, $can_redirect );
	}

	/**
	 * Update iDEAL Basic payment status
	 *
	 * @param array $result
	 * @param boolean $canRedirect
	 */
	public static function update_ideal_basic_payment_status( $payment, $status, $can_redirect = false ) {
		$payment->status = $status;

		$updated = Pronamic_WordPress_IDeal_PaymentsRepository::updateStatus( $payment );

		do_action( 'pronamic_ideal_status_update', $payment, $can_redirect );
	}

	/**
	 * Update iDEAL Easy payment status
	 *
	 * @param array $result
	 * @param boolean $canRedirect
	 */
	public static function update_ideal_easy_payment_status( $payment, $status, $can_redirect = false ) {
		$payment->status = $status;

		$updated = Pronamic_WordPress_IDeal_PaymentsRepository::updateStatus( $payment );

		do_action( 'pronamic_ideal_status_update', $payment, $can_redirect );
	}

	//////////////////////////////////////////////////

	/**
	 * Get the key
	 *
	 * @return string
	 */
	public static function get_key() {
		return get_option( 'pronamic_ideal_key' );
	}

	/**
	 * Set the key
	 *
	 * @param string $key
	 */
	public static function set_key( $key ) {
		$current_key = get_option( 'pronamic_ideal_key' );

		if ( empty( $key ) ) {
			delete_option( 'pronamic_ideal_key' );
		} elseif ( $key != $current_key ) {
			update_option( 'pronamic_ideal_key', md5( trim( $key ) ) );
		}
	}

	/**
	 * Get the license info for the current installation on the blogin
	 *
	 * @return stdClass an onbject with license information or null
	 */
	public static function get_license_info() {
		 return null;
	}

	/**
	 * Check if there is an valid license key
	 *
	 * @return boolean
	 */
	public static function has_valid_key() {
		$result = strlen( self::get_key() ) == 32;

		$license_info = self::get_license_info();

		if ( $license_info != null && isset( $license_info->isValid ) ) {
			$result = $license_info->isValid;
		}

		return $result;
	}

	/**
	 * Checks if the plugin is installed
	 */
	public static function is_installed() {
		return get_option( 'pronamic_ideal_version', false ) !== false;
	}

	/**
	 * Check if the plugin can be used
	 *
	 * @return boolean true if plugin can be used, false otherwise
	 */
	public static function can_be_used() {
		return self::is_installed() && (self::has_valid_key() || Pronamic_WordPress_IDeal_PaymentsRepository::get_number_payments() <= self::PAYMENTS_MAX_LICENSE_FREE);
	}

	//////////////////////////////////////////////////

	/**
	 * Maybe show an license message
	 */
	public static function admin_notices() {
		if ( ! self::can_be_used() ): ?>

			<div class="error">
				<p>
					<?php

					printf(
						__( '<strong>Pronamic iDEAL limited:</strong> You exceeded the maximum free payments of %d, you should enter an valid license key on the <a href="%s">iDEAL settings page</a>.', 'pronamic_ideal' ),
						self::PAYMENTS_MAX_LICENSE_FREE,
						add_query_arg( 'page', 'pronamic_ideal_settings', get_admin_url( null, 'admin.php' ) )
					);

					?>
				</p>
			</div>

		<?php elseif ( ! self::has_valid_key() ) : ?>

			<div class="updated">
				<p>
					<?php

					printf(
						__( 'You can <a href="%s">enter your Pronamic iDEAL API key</a> to use extra extensions, get support and more than %d payments.', 'pronamic_ideal' ),
						add_query_arg( 'page', 'pronamic_ideal_settings', get_admin_url( null, 'admin.php' ) ),
						self::PAYMENTS_MAX_LICENSE_FREE
					);

					?>
				</p>
			</div>

		<?php endif;
	}

	//////////////////////////////////////////////////

	/**
	 * Configure the specified roles
	 *
	 * @param array $roles
	 */
	public static function set_roles( $roles ) {
		global $wp_roles;

		if ( ! isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles();
		}

		foreach ( $roles as $role => $data ) {
			if ( isset( $data['display_name'], $data['capabilities'] ) ) {
				$display_name = $data['display_name'];
				$capabilities = $data['capabilities'];

				if ( $wp_roles->is_role( $role ) ) {
					foreach ( $capabilities as $cap => $grant ) {
						$wp_roles->add_cap( $role, $cap, $grant );
					}
				} else {
					$wp_roles->add_role( $role, $display_name, $capabilities );
				}
			}
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Setup, creates or updates database tables. Will only run when version changes
	 */
	public static function setup() {
		// Load plugin text domain
		$rel_path = dirname( plugin_basename( self::$file ) ) . '/languages/';

		load_plugin_textdomain( 'pronamic_ideal', false, $rel_path );

		if ( get_option( 'pronamic_ideal_version' ) != self::VERSION ) {
			// Update tables
			Pronamic_WordPress_IDeal_ConfigurationsRepository::update_table();
			Pronamic_WordPress_IDeal_PaymentsRepository::update_table();

			// Add some new capabilities
			$capabilities = array(
				'read'                           => true,
				'pronamic_ideal'                 => true,
				'pronamic_ideal_configurations'  => true,
				'pronamic_ideal_payments'        => true,
				'pronamic_ideal_settings'        => true,
				'pronamic_ideal_pages_generator' => true,
				'pronamic_ideal_status'          => true,
				'pronamic_ideal_providers'       => true,
				'pronamic_ideal_variants'        => true,
				'pronamic_ideal_documentation'   => true,
				'pronamic_ideal_branding'        => true
			);

			$roles = array(
				'pronamic_ideal_administrator' => array(
					'display_name' => __( 'iDEAL Administrator', 'pronamic_ideal' ),
					'capabilities' => $capabilities
				) ,
				'administrator' => array(
					'display_name' => __( 'Administrator', 'pronamic_ideal' ),
					'capabilities' => $capabilities
				)
			);

			self::set_roles( $roles );

			// Update version
			update_option( 'pronamic_ideal_version', self::VERSION );
		}
	}
}
