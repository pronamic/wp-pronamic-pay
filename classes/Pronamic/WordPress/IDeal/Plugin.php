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
	 * The license provider API URL
	 * 
	 * @var string
	 */
	const LICENSE_PROVIDER_API_URL = 'http://in.pronamic.nl/api/';

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
	const VERSION = '1.1';

	//////////////////////////////////////////////////

	/**
	 * Option version
	 * 
	 * @var string
	 */
	const OPTION_VERSION = 'pronamic_ideal_version';
	
	/**
	 * Option product / license key
	 * 
	 * @var string
	 */
	const OPTION_KEY = 'pronamic_ideal_key';

	//////////////////////////////////////////////////
	
	/**
	 * Transient key for license information
	 * 
	 * @var string
	 */
	const TRANSIENT_LICENSE_INFO = 'pronamic_ideal_license_info';

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

		// Load plugin text domain
		$rel_path = dirname( plugin_basename( self::$file ) ) . '/languages/';

		load_plugin_textdomain( 'pronamic_ideal', false, $rel_path );

		// Bootstrap the add-ons
		if ( self::canBeUsed() ) {
			Pronamic_WooCommerce_IDeal_AddOn::bootstrap();
			Pronamic_GravityForms_IDeal_AddOn::bootstrap();
			Pronamic_Shopp_IDeal_AddOn::bootstrap();
			Pronamic_Jigoshop_IDeal_AddOn::bootstrap();
			Pronamic_WPeCommerce_IDeal_AddOn::bootstrap();
			Pronamic_ClassiPress_IDeal_AddOn::bootstrap();
			Pronamic_EShop_IDeal_AddOn::bootstrap();
			Pronamic_EventEspresso_IDeal_AddOn::bootstrap();
			// Pronamic_AppThemes_IDeal_AddOn::bootstrap();
		}

		// Admin
		if ( is_admin() ) {
			Pronamic_WordPress_IDeal_Admin::bootstrap();
		}

		add_action( 'plugins_loaded', array( __CLASS__, 'setup' ) );
		
		// Initialize
		add_action( 'init', array( __CLASS__, 'init' ) );
		
		// On template redirect handle an possible return from iDEAL
		add_action( 'template_redirect', array( __CLASS__, 'handle_ideal_advanced_return' ) );
		add_action( 'template_redirect', array( __CLASS__, 'handle_ideal_internetkassa_return' ) );
		add_action( 'template_redirect', array( __CLASS__, 'handle_omnikassa_return' ) );
		add_action( 'template_redirect', array( __CLASS__, 'handle_mollie_return' ) );
		
		// Check the payment status on an iDEAL return
		add_action( 'pronamic_ideal_advanced_return',      array( __CLASS__, 'checkPaymentStatus' ),               10, 2 );
		add_action( 'pronamic_ideal_internetkassa_return', array( __CLASS__, 'updateInternetKassaPaymentStatus' ), 10, 2 );
		add_action( 'pronamic_ideal_omnikassa_return',     array( __CLASS__, 'update_omnikassa_payment_status' ),  10, 2 );
		add_action( 'pronamic_ideal_mollie_return',        array( __CLASS__, 'update_mollie_payment_status' ),     10, 2 );

		// The 'pronamic_ideal_check_transaction_status' hook is scheduled the status requests
		add_action( 'pronamic_ideal_check_transaction_status', array( __CLASS__, 'checkStatus' ) );

		// Show license message if the license is not valid
		add_action( 'admin_notices', array( __CLASS__, 'admin_notices' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Initialize
	 */
	public static function init() {
		// Requirements
		require_once self::$dirname . '/includes/xmlseclibs/xmlseclibs.php';
		require_once self::$dirname . '/includes/wp-e-commerce.php';
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
	 * Handle iDEAL advanced return
	 */
	public static function handle_ideal_advanced_return() {
		if ( isset( $_GET['trxid'], $_GET['ec'] ) ) {
			$transaction_id = filter_input( INPUT_GET, 'trxid', FILTER_SANITIZE_STRING );
			$entrance_code  = filter_input( INPUT_GET, 'ec', FILTER_SANITIZE_STRING );
	
			if ( ! empty( $transaction_id ) && ! empty( $entrance_code ) ) {
				$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentByIdAndEc( $transaction_id, $entrance_code );

				if ( $payment != null ) {
					$can_redirect = true;

					do_action( 'pronamic_ideal_advanced_return', $payment, $can_redirect );
				}
			}
		}
	}

	public static function handle_mollie_return() {
		if ( isset( $_GET['transaction_id'] ) ) {
			$transaction_id = filter_input( INPUT_GET, 'transaction_id', FILTER_SANITIZE_STRING );

			if ( ! empty( $transaction_id ) ) {
				$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentByIdAndEc( $transaction_id );

				if ( $payment != null ) {
					$can_redirect = true;

					do_action( 'pronamic_ideal_mollie_return', $payment, $can_redirect );
				}
			}
		}
	}

	/**
	 * Handle iDEAL kassa return
	 */
	public static function handle_ideal_internetkassa_return() {
		if ( isset( $_GET['SHASIGN'] ) ) {
			$configurations = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurations();

			foreach ( $configurations as $configuration ) {
				$variant = $configuration->getVariant();
				
				if ( $variant != null && $variant->getMethod() == 'internetkassa' ) {
					$iDeal = new Pronamic_Gateways_IDealInternetKassa_IDealInternetKassa();

					$iDeal->setPspId($configuration->pspId);
					$iDeal->setPassPhraseIn($configuration->shaInPassPhrase);
					$iDeal->setPassPhraseOut($configuration->shaOutPassPhrase);

					$file = self::$dirname . '/other/calculations-parameters-sha-in.txt';
					$iDeal->setCalculationsParametersIn( file($file, FILE_IGNORE_NEW_LINES) );

					$file = self::$dirname . '/other/calculations-parameters-sha-out.txt';
					$iDeal->setCalculationsParametersOut( file($file, FILE_IGNORE_NEW_LINES) );

					$result = $iDeal->verifyRequest( $_GET );

					if ( $result !== false ) {
						do_action( 'pronamic_ideal_internetkassa_return', $result, $can_redirect = true );
						
						break;
					}
				}
			}
		}
	}

	/**
	 * Update kassa payment status
	 * 
	 * @param array $data
	 * @param boolean $canRedirect
	 */
	public static function updateInternetKassaPaymentStatus( $data, $can_redirect = false ) {
		$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentById($data['ORDERID']);

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
	 * Handle OmniKassa return
	 */
	public static function handle_omnikassa_return() {
		if ( isset( $_POST['Data'], $_POST['Seal'] ) ) {
			$post_data = filter_input( INPUT_POST, 'Data', FILTER_SANITIZE_STRING );
			$post_seal = filter_input( INPUT_POST, 'Seal', FILTER_SANITIZE_STRING );
			
			if ( ! empty( $post_data ) && ! empty( $post_seal ) ) {
				$data = Pronamic_Gateways_OmniKassa_OmniKassa::parsePipedString( $post_data );
	
				$transaction_reference = $data['transactionReference'];

				$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentByIdAndEc( $transaction_reference );

				if ( $payment != null ) {
					$seal = Pronamic_Gateways_OmniKassa_OmniKassa::computeSeal( $post_data, $payment->configuration->getHashKey() );
	
					// Check if the posted seal is equal to our seal
					if ( strcasecmp( $post_seal, $seal ) === 0 ) {
						do_action( 'pronamic_ideal_omnikassa_return', $data, $can_redirect = true );
					}
				}
			}
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

	//////////////////////////////////////////////////
	
	/**
	 * Get the key
	 * 
	 * @return string
	 */
	public static function getKey() {
		return get_option(self::OPTION_KEY);
	}

	/**
	 * Set the key
	 * 
	 * @param string $key
	 */
	public static function setKey($key) {
		$currentKey = get_option(self::OPTION_KEY);

		if(empty($key)) {
			delete_option(self::OPTION_KEY);
			delete_transient(self::TRANSIENT_LICENSE_INFO);
		} elseif($key != $currentKey) {
			update_option(self::OPTION_KEY, md5(trim($key)));
			delete_transient(self::TRANSIENT_LICENSE_INFO);
		}
	}
	
	/**
	 * Get the license info for the current installation on the blogin
	 * 
	 * @return stdClass an onbject with license information or null
	 */
	public static function getLicenseInfo() {
		$licenseInfo = null;

		$transient = get_transient(self::TRANSIENT_LICENSE_INFO);
		if($transient === false) {
			$url = self::LICENSE_PROVIDER_API_URL . 'licenses/show';

			$response = wp_remote_post($url, array(
				'body' => array(
					'key' => self::getKey() , 
					'url' => site_url() 
				)
			));

			if(is_wp_error($response)) {
				$licenseInfo = new stdClass();
				// Benefit of the doubt
				$licenseInfo->isValid = true;
			} else {
				$licenseInfo = json_decode($response['body']);
			}

			// Check every day for new license information, an license kan expire every day (60 * 60 * 24)
			set_transient(self::TRANSIENT_LICENSE_INFO, $licenseInfo, 86400);
		} else {
			$licenseInfo = $transient;
		}
		
		return $licenseInfo;
	}

	/**
	 * Check if there is an valid license key
	 * 
	 * @return boolean
	 */
	public static function hasValidKey() {
		$result = false;

		$licenseInfo = self::getLicenseInfo();
		
		if($licenseInfo != null && isset($licenseInfo->isValid)) {
			$result = $licenseInfo->isValid;
		}

		return $result;
	}

	/**
	 * Checks if the plugin is installed
	 */
	public static function isInstalled() {
		return get_option(self::OPTION_VERSION, false) !== false;
	}

	/**
	 * Check if the plugin can be used
	 * 
	 * @return boolean true if plugin can be used, false otherwise
	 */
	public static function canBeUsed() {
		return self::isInstalled() && (self::hasValidKey() || Pronamic_WordPress_IDeal_PaymentsRepository::get_number_payments() <= self::PAYMENTS_MAX_LICENSE_FREE);
	}

	//////////////////////////////////////////////////
	
	/**
	 * Maybe show an license message
	 */
	public static function admin_notices() {
		if ( ! self::canBeUsed() ): ?>
		
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

		<?php elseif ( ! self::hasValidKey() ) : ?>
		
			<div class="updated">
				<p>
					<?php 

					printf(
						__( 'You can <a href="%s">enter your Pronamic iDEAL API key</a> to use extra extensions, get support and more then %d payments.', 'pronamic_ideal' ),
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
		if ( get_option( self::OPTION_VERSION ) != self::VERSION ) {
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
			update_option( self::OPTION_VERSION, self::VERSION );
		}
	}
}
