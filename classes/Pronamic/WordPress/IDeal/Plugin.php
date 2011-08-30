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
	 * Option version
	 * 
	 * @var string
	 */
	const OPTION_VERSION = 'pronamic_ideal_version';

	/**
	 * The current version of this plugin
	 * 
	 * @var string
	 */
	const VERSION = '1.0';

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
		Pronamic_GravityForms_IDeal_AddOn::bootstrap();

		// Hooks and filters
		if(is_admin()) {
			Pronamic_WordPress_IDeal_Admin::bootstrap();
		}

		add_action('plugins_loaded', array(__CLASS__, 'setup'));
		
		// On parsing the query parameter handle an possible return from iDEAL
		add_action('parse_query', array(__CLASS__, 'handleIDealReturn'));
		
		// Check the payment status on an iDEAL return
		add_action('pronamic_ideal_return', array(__CLASS__, 'checkPaymentStatus'));

		// @todo Where was this for?
		add_action('pronamic_ideal_check_transaction_status', array(__CLASS__, 'checkStatus'));
	}
	
	public static function checkStatus($id) {
		$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentById($id);
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
