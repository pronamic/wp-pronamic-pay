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

	/**
	 * The text domain of this plugin
	 * 
	 * @var string
	 */
	const TEXT_DOMAIN = 'pronamic-ideal';

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
	const VERSION = 'beta-0.6';

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

		load_plugin_textdomain(self::TEXT_DOMAIN, false, $relPath);

		// Gravity Forms Add-On
		if(self::canBeUsed()) {
			Pronamic_GravityForms_IDeal_AddOn::bootstrap();
		}

		// Hooks and filters
		if(is_admin()) {
			Pronamic_WordPress_IDeal_Admin::bootstrap();
		}

		add_action('plugins_loaded', array(__CLASS__, 'setup'));
		
		// On parsing the query parameter handle an possible return from iDEAL
		add_action('parse_query', array(__CLASS__, 'handleIDealReturn'));
		
		// Check the payment status on an iDEAL return
		add_action('pronamic_ideal_return', array(__CLASS__, 'checkPaymentStatus'));

		// The 'pronamic_ideal_check_transaction_status' hook is scheduled the status requests
		add_action('pronamic_ideal_check_transaction_status', array(__CLASS__, 'checkStatus'));
		
		add_action('admin_notices', array(__CLASS__, 'maybeShowLicenseMessage'));
	}

	//////////////////////////////////////////////////
	
	/**
	 * Check status of the specified payment
	 * 
	 * @param string $paymentId
	 */
	public static function checkStatus($paymentId) {
		$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentById($paymentId);
	}

	/**
	 * Check the status of the specified payment
	 * 
	 * @param unknown_type $payment
	 */
	public static function checkPaymentStatus(Pronamic_WordPress_IDeal_Payment $payment) {
		$configuration = $payment->configuration;
		$variant = $configuration->getVariant();

		$iDealClient = new Pronamic_IDeal_IDealClient();
		$iDealClient->setAcquirerUrl($configuration->getPaymentServerUrl());
		$iDealClient->setPrivateKey($configuration->privateKey);
		$iDealClient->setPrivateKeyPassword($configuration->privateKeyPassword);
		$iDealClient->setPrivateCertificate($configuration->privateCertificate);
		
		$message = new Pronamic_IDeal_XML_StatusRequestMessage();

		$merchant = $message->getMerchant();
		$merchant->id = $configuration->merchantId;
		$merchant->subId = $configuration->subId;
		$merchant->authentication = Pronamic_IDeal_IDeal::AUTHENTICATION_SHA1_RSA;
		$merchant->returnUrl = home_url();
		$merchant->token = Pronamic_IDeal_Security::getShaFingerprint($configuration->privateCertificate);

		$message->merchant = $merchant;
		$message->transaction = $payment->transaction;
		$message->sign($configuration->privateKey, $configuration->privateKeyPassword);

		$responseMessage = $iDealClient->getStatus($message);

		$updated = Pronamic_WordPress_IDeal_PaymentsRepository::updateStatus($payment);
		
		do_action('pronamic_ideal_status_update', $payment);
	}

	//////////////////////////////////////////////////

	/**
	 * Handle iDEAL
	 */
	public static function handleIDealReturn() {
		$transactionId = filter_input(INPUT_GET, 'trxid', FILTER_SANITIZE_STRING);
		$entranceCode = filter_input(INPUT_GET, 'ec', FILTER_SANITIZE_STRING);

		if(!empty($transactionId) && !empty($entranceCode)) {
			$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentByIdAndEc($transactionId, $entranceCode);

			if($payment != null) {
				do_action('pronamic_ideal_return', $payment);
			}
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
					'url' => home_url() 
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
	 * Check if the plugin can be used
	 * 
	 * @return boolean true if plugin can be used, false otherwise
	 */
	public static function canBeUsed() {
		return self::hasValidKey() || Pronamic_WordPress_IDeal_PaymentsRepository::getNumberPayments() <= self::PAYMENTS_MAX_LICENSE_FREE;
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
					__('<strong>Pronamic iDEAL limited:</strong> You exceeded the maximum free payments of %d, you should enter an valid license key on the %s.', self::TEXT_DOMAIN) , 
					self::PAYMENTS_MAX_LICENSE_FREE , 
					sprintf(
						'<a href="%s">%s</a>' , 
						add_query_arg('page', 'pronamic_ideal_settings', get_admin_url(null, 'admin.php')) , 
						__('iDEAL settings page', self::TEXT_DOMAIN)
					) 
				);
				
				?>
			</p>
		</div>
		
		<?php endif;
	}

	//////////////////////////////////////////////////

	/**
	 * Setup, creates or updates database tables. Will only run when version changes
	 */
	public static function setup() {
		if(get_option(self::OPTION_VERSION) != self::VERSION) {
			Pronamic_WordPress_IDeal_ConfigurationsRepository::updateTable();
			Pronamic_WordPress_IDeal_PaymentsRepository::updateTable();

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
		
		// Uninstall Add-Ons
		Pronamic_GravityForms_IDeal_AddOn::uninstall();
	}
}
