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
	 * The slug of this plugin
	 * 
	 * @var string
	 */
	const SLUG = 'pronamic_ideal';

	//////////////////////////////////////////////////

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
	const VERSION = 'beta-0.9.9';

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

	//////////////////////////////////////////////////

	/**
	 * Bootstrap
	 * 
	 * @param string $file
	 */
	public static function bootstrap($file) {
		self::$file = $file;

		// Load plugin text domain
		$relPath = dirname(plugin_basename(self::$file)) . '/languages/';

		load_plugin_textdomain('pronamic_ideal', false, $relPath);

		// Bootstrap the add-ons
		if(self::canBeUsed()) {
			Pronamic_WooCommerce_IDeal_AddOn::bootstrap();
			Pronamic_GravityForms_IDeal_AddOn::bootstrap();
			Pronamic_Shopp_IDeal_AddOn::bootstrap();
			Pronamic_Jigoshop_IDeal_AddOn::bootstrap();
			Pronamic_WPeCommerce_IDeal_AddOn::bootstrap();
			Pronamic_ClassiPress_IDeal_AddOn::bootstrap();
			Pronamic_EShop_IDeal_AddOn::bootstrap();
			Pronamic_EventEspresso_IDeal_AddOn::bootstrap();
		}

		// Hooks and filters
		if(is_admin()) {
			Pronamic_WordPress_IDeal_Admin::bootstrap();
		}

		add_action('plugins_loaded', array(__CLASS__, 'setup'));
		
		// Initialize
		add_action('init', array(__CLASS__, 'init'));
		
		// On template redirect handle an possible return from iDEAL
		add_action('template_redirect', array(__CLASS__, 'handleIDealAdvancedReturn'));
		add_action('template_redirect', array(__CLASS__, 'handleIDealKassaReturn'));
		add_action('template_redirect', array(__CLASS__, 'handleOmniKassaReturn'));
		
		// Check the payment status on an iDEAL return
		add_action('pronamic_ideal_return', array(__CLASS__, 'checkPaymentStatus'), 10, 2);
		add_action('pronamic_ideal_kassa_return', array(__CLASS__, 'updateKassaPaymentStatus'), 10, 2);
		add_action('pronamic_ideal_omnikassa_return', array(__CLASS__, 'updateOmniKassaPaymentStatus'), 10, 2);

		// The 'pronamic_ideal_check_transaction_status' hook is scheduled the status requests
		add_action('pronamic_ideal_check_transaction_status', array(__CLASS__, 'checkStatus'));

		// Show license message if the license is not valid
		add_action('admin_notices', array(__CLASS__, 'maybeShowLicenseMessage'));

		// Uninstall
		register_uninstall_hook(self::$file, array(__CLASS__, 'uninstall'));
	}

	//////////////////////////////////////////////////

	/**
	 * Initialize
	 */
	public static function init() {
		self::maybeDownloadPrivateCertificate();
		self::maybeDownloadPrivateKey();
	}

	/**
	 * Download private certificate
	 */
	public static function maybeDownloadPrivateCertificate() {
		if(isset($_POST['download_private_certificate'])) {
			$id = filter_input(INPUT_POST, 'pronamic_ideal_configuration_id', FILTER_SANITIZE_STRING);

			$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById($id);

			if(!empty($configuration)) {
				$filename = "ideal-private-certificate-" . $id . ".cer";

				header('Content-Description: File Transfer');
				header("Content-Disposition: attachment; filename=$filename");
				header('Content-Type: application/x-x509-ca-cert; charset=' . get_option('blog_charset'), true);
				echo $configuration->privateCertificate;
				die();
			}
		}
	}

	/**
	 * Download private key
	 */
	public static function maybeDownloadPrivateKey() {
		if(isset($_POST['download_private_key'])) {
			$id = filter_input(INPUT_POST, 'pronamic_ideal_configuration_id', FILTER_SANITIZE_STRING);

			$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById($id);

			if(!empty($configuration)) {
				$filename = 'ideal-private-key-' . $id . '.key';

				header('Content-Description: File Transfer');
				header('Content-Disposition: attachment; filename=' . $filename);
				header('Content-Type: application/pgp-keys; charset=' . get_option('blog_charset'), true);
				echo $configuration->privateKey;
				die();
			}
		}
	}

	//////////////////////////////////////////////////
	
	/**
	 * Check status of the specified payment
	 * 
	 * @param string $paymentId
	 */
	public static function checkStatus($paymentId = null) {
		$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentById($paymentId);

		if($payment !== null) {
			// http://pronamic.nl/wp-content/uploads/2011/12/iDEAL_Advanced_PHP_EN_V2.2.pdf (page 19)
			// - No status request after a final status has been received for a transaction;
			$status = $payment->transaction->getStatus();

			if(empty($status) || $status === Pronamic_IDeal_Transaction::STATUS_OPEN) {
				self::checkPaymentStatus($payment);
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
	public static function checkPaymentStatus(Pronamic_WordPress_IDeal_Payment $payment, $canRedirect = false) {
		$configuration = $payment->configuration;
		$variant = $configuration->getVariant();

		$iDealClient = new Pronamic_IDeal_IDealClient();
		$iDealClient->setAcquirerUrl($configuration->getPaymentServerUrl());
		$iDealClient->setPrivateKey($configuration->privateKey);
		$iDealClient->setPrivateKeyPassword($configuration->privateKeyPassword);
		$iDealClient->setPrivateCertificate($configuration->privateCertificate);
		
		$message = new Pronamic_IDeal_XML_StatusRequestMessage();

		$merchant = $message->getMerchant();
		$merchant->id = $configuration->getMerchantId();
		$merchant->subId = $configuration->getSubId();
		$merchant->authentication = Pronamic_IDeal_IDeal::AUTHENTICATION_SHA1_RSA;
		$merchant->returnUrl = site_url('/');
		$merchant->token = Pronamic_IDeal_Security::getShaFingerprint($configuration->privateCertificate);

		$message->merchant = $merchant;
		$message->transaction = $payment->transaction;
		$message->sign($configuration->privateKey, $configuration->privateKeyPassword);

		$responseMessage = $iDealClient->getStatus($message);

		$updated = Pronamic_WordPress_IDeal_PaymentsRepository::updateStatus($payment);

		do_action('pronamic_ideal_status_update', $payment, $canRedirect);
	}

	//////////////////////////////////////////////////

	/**
	 * Handle iDEAL advanced return
	 */
	public static function handleIDealAdvancedReturn() {
		if(isset($_GET['trxid'], $_GET['ec'])) {
			$transactionId = filter_input(INPUT_GET, 'trxid', FILTER_SANITIZE_STRING);
			$entranceCode = filter_input(INPUT_GET, 'ec', FILTER_SANITIZE_STRING);
	
			if(!empty($transactionId) && !empty($entranceCode)) {
				$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentByIdAndEc($transactionId, $entranceCode);
	
				if($payment != null) {
					$canRedirect = true;
	
					do_action('pronamic_ideal_return', $payment, $canRedirect);
				}
			}
		}
	}

	/**
	 * Handle iDEAL kassa return
	 */
	public static function handleIDealKassaReturn() {
		if(isset($_GET['SHASIGN'])) {
			$configurations = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurations();

			foreach($configurations as $configuration) {
				if($configuration->getVariant() instanceof Pronamic_IDeal_VariantKassa) {
					$iDeal = new Pronamic_Gateways_IDealInternetKassa_IDealInternetKassa();

					$iDeal->setPspId($configuration->pspId);
					$iDeal->setPassPhraseIn($configuration->shaInPassPhrase);
					$iDeal->setPassPhraseOut($configuration->shaOutPassPhrase);

					$file = dirname(Pronamic_WordPress_IDeal_Plugin::$file) . '/other/calculations-parameters-sha-in.txt';
					$iDeal->setCalculationsParametersIn( file($file, FILE_IGNORE_NEW_LINES) );

					$file = dirname(Pronamic_WordPress_IDeal_Plugin::$file) . '/other/calculations-parameters-sha-out.txt';
					$iDeal->setCalculationsParametersOut( file($file, FILE_IGNORE_NEW_LINES) );

					$result = $iDeal->verifyRequest($_GET);

					if($result !== false) {
						do_action('pronamic_ideal_kassa_return', $result, $canRedirect = true);
						
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
	public static function updateKassaPaymentStatus($data, $canRedirect = false) {
		$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentById($data['ORDERID']);

		if($payment != null) {
			$status = null;

			switch($data['STATUS']) {
				case Pronamic_Gateways_IDealInternetKassa_Statuses::INCOMPLETE_OR_INVALID:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::AUTHORIZATION_REFUSED:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::AUTHOR_DELETION_REFUSED:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::PAYMENT_DELETION_REFUSED:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::REFUND_REFUSED:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::PAYMENT_DECLIEND_BY_THE_ACQUIRER:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::PAYMENT_REFUSED:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::REFUND_DECLINED_BY_THE_ACQUIRER:
					$status = Pronamic_IDeal_Transaction::STATUS_FAILURE;
					break;
				case Pronamic_Gateways_IDealInternetKassa_Statuses::CANCELLED_BY_CLIENT:
				case Pronamic_Gateways_IDealInternetKassa_Statuses::AUTHORIZED_AND_CANCELLED:
					$status = Pronamic_IDeal_Transaction::STATUS_CANCELLED;
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
					$status = Pronamic_IDeal_Transaction::STATUS_SUCCESS;
					break;
			}
			
			if($status != null) {
				$payment->transaction->setStatus($status);

				$updated = Pronamic_WordPress_IDeal_PaymentsRepository::updateStatus($payment);
			}

			do_action('pronamic_ideal_status_update', $payment, $canRedirect);
		}
	}

	/**
	 * Handle OmniKassa return
	 */
	public static function handleOmniKassaReturn() {
		if(isset($_POST['Data'], $_POST['Seal'])) {
			$postData = filter_input(INPUT_POST, 'Data', FILTER_SANITIZE_STRING);
			$postSeal = filter_input(INPUT_POST, 'Seal', FILTER_SANITIZE_STRING);
			
			if(!empty($postData) && !empty($postSeal)) {
				$data = Pronamic_IDeal_OmniKassa::parsePipedString($postData);
	
				$transactionReference = $data['transactionReference'];

				$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentByIdAndEc($transactionReference);

				if($payment != null) {
					$seal = Pronamic_IDeal_OmniKassa::computeSeal($postData, $payment->configuration->getHashKey());
	
					// Check if the posted seal is equal to our seal
					if(strcasecmp($postSeal, $seal) === 0) {
						do_action('pronamic_ideal_omnikassa_return', $data, $canRedirect = true);
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
	public static function updateOmniKassaPaymentStatus($data, $canRedirect = false) {
		$transactionReference = $data['transactionReference'];
		
		$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentByIdAndEc($transactionReference);
		
		if($payment != null) {
			$responseCode = $data['responseCode'];

			$status = Pronamic_IDeal_Transaction::STATUS_OPEN;

			switch($responseCode) {
				case Pronamic_IDeal_OmniKassa::RESPONSE_CODE_TRANSACTION_SUCCES:
					$status = Pronamic_IDeal_Transaction::STATUS_SUCCESS;
					break;
				case Pronamic_IDeal_OmniKassa::RESPONSE_CODE_CANCELLATION_OF_PAYMENT:
					$status = Pronamic_IDeal_Transaction::STATUS_CANCELLED;
					break;
			}
			
			$payment->transaction->setStatus($status);

			$updated = Pronamic_WordPress_IDeal_PaymentsRepository::updateStatus($payment);

			do_action('pronamic_ideal_status_update', $payment, $canRedirect);
		}
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
		return self::isInstalled() && (self::hasValidKey() || Pronamic_WordPress_IDeal_PaymentsRepository::getNumberPayments() <= self::PAYMENTS_MAX_LICENSE_FREE);
	}

	//////////////////////////////////////////////////
	
	/**
	 * Maybe show an license message
	 */
	public static function maybeShowLicenseMessage() {
		if(!self::canBeUsed()): ?>
		
		<div class="error">
			<p>
				<?php 
				
				printf(
					__('<strong>Pronamic iDEAL limited:</strong> You exceeded the maximum free payments of %d, you should enter an valid license key on the %s.', 'pronamic_ideal') , 
					self::PAYMENTS_MAX_LICENSE_FREE , 
					sprintf(
						'<a href="%s">%s</a>' , 
						add_query_arg('page', 'pronamic_ideal_settings', get_admin_url(null, 'admin.php')) , 
						__('iDEAL settings page', 'pronamic_ideal')
					) 
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
	public static function setRoles($roles) {
		global $wp_roles;

		if(!isset($wp_roles)) {
			$wp_roles = new WP_Roles();
		}

		foreach($roles as $role => $data) {
			if(isset($data['display_name'], $data['capabilities'])) {
				$display_name = $data['display_name'];
				$capabilities = $data['capabilities'];
	
				if($wp_roles->is_role($role)) {
					foreach($capabilities as $cap => $grant) {
						$wp_roles->add_cap($role, $cap, $grant);
					}
				} else {
					$wp_roles->add_role($role, $display_name, $capabilities);
				}
			}
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Setup, creates or updates database tables. Will only run when version changes
	 */
	public static function setup() {
		if(get_option(self::OPTION_VERSION) != self::VERSION) {
			// Update tables
			Pronamic_WordPress_IDeal_ConfigurationsRepository::updateTable();
			Pronamic_WordPress_IDeal_PaymentsRepository::updateTable();

			// Add some new capabilities
			$capabilities = array(
				'read' => true , 
				'pronamic_ideal' => true ,
				'pronamic_ideal_configurations' => true ,
				'pronamic_ideal_payments' => true ,  
				'pronamic_ideal_settings' => true ,
				'pronamic_ideal_pages_generator' => true , 
				'pronamic_ideal_variants' => true ,
				'pronamic_ideal_documentation' => true
			);
			
			$roles = array(
				'pronamic_ideal_administrator' => array(
					'display_name' => __('iDEAL Administrator', 'pronamic_ideal') ,	
					'capabilities' => $capabilities
				) , 
				'administrator' => array(
					'display_name' => __('Administrator', 'pronamic_ideal') ,	
					'capabilities' => $capabilities
				)
			);
			
			self::setRoles($roles);

			// Update version
			update_option(self::OPTION_VERSION, self::VERSION);
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Uninstall
	 */
	public static function uninstall() {
		// Drop tables
		Pronamic_WordPress_IDeal_ConfigurationsRepository::dropTables();
		Pronamic_WordPress_IDeal_PaymentsRepository::dropTables();

		// Delete options
		delete_option(self::OPTION_VERSION);
		delete_option(self::OPTION_KEY);
		
		// Delete transient
		delete_transient(self::TRANSIENT_LICENSE_INFO);
		
		// Uninstall Add-Ons
		Pronamic_GravityForms_IDeal_AddOn::uninstall();
	}
}
