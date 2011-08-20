<?php

namespace Pronamic\WordPress\IDeal;

use Pronamic\IDeal\IDeal as IDealCore;

/**
 * Title: WordPress iDEAL plugin
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Plugin {
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
		\Pronamic\GravityForms\IDeal\AddOn::bootstrap();

		// Hooks and filters
		if(is_admin()) {
			Admin::bootstrap();
		}

		add_action('plugins_loaded', array(__CLASS__, 'setup'));
		
		add_action('parse_query', array(__CLASS__, 'handleIDealReturn'));
		
		add_action('pronamic_ideal_check_transaction_status', array(__CLASS__, 'checkStatus'));
	}
	
	public static function checkStatus($id) {
		$payment = PaymentsRepository::getPaymentById($id);
	}

	public static function checkPaymentStatus($payment) {
		$configuration = $payment->configuration;
		$variant = $configuration->getVariant();

		$iDealClient = new \Pronamic\IDeal\IDealClient();
		$iDealClient->setAcquirerUrl($configuration->getPaymentServerUrl());
		$iDealClient->setPrivateKey($configuration->privateKey);
		$iDealClient->setPrivateKeyPassword($configuration->privateKeyPassword);
		$iDealClient->setPrivateCertificate($configuration->privateCertificate);
		
		$message = new \Pronamic\IDeal\XML\StatusRequestMessage();

		$merchant = $message->getMerchant();
		$merchant->id = $configuration->merchantId;
		$merchant->subId = $configuration->subId;
		$merchant->authentication = IDealCore::AUTHENTICATION_SHA1_RSA;
		$merchant->returnUrl = home_url();
		$merchant->token = \Pronamic\IDeal\Security::getShaFingerprint($configuration->privateCertificate);

		$message->merchant = $merchant;
		$message->transaction = $payment->transaction;
		$message->sign($configuration->privateKey, $configuration->privateKeyPassword);

		$responseMessage = $iDealClient->getStatus($message);

		$updated = PaymentsRepository::updateStatus($payment);
	}

	//////////////////////////////////////////////////

	/**
	 * Handle iDEAL
	 */
	public static function handleIDealReturn() {
		$transactionId = filter_input(INPUT_GET, 'trxid', FILTER_SANITIZE_STRING);
		$entranceCode = filter_input(INPUT_GET, 'ec', FILTER_SANITIZE_STRING);

		if(!empty($transactionId) && !empty($entranceCode)) {
			$payment = PaymentsRepository::getPaymentByIdAndEc($transactionId, $entranceCode);

			if($payment != null) {
				self::checkPaymentStatus($payment);

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
			ConfigurationsRepository::updateTable();
			PaymentsRepository::updateTable();

			update_option(self::OPTION_VERSION, self::VERSION);
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Uninstall
	 */
	public static function uninstall() {
		// Drop tables
		ConfigurationsRepository::dropTables();
		PaymentsRepository::dropTables();

		// Delete options
		delete_option(self::OPTION_VERSION);
		
		// Uninstall Add-Ons
		\Pronamic\GravityForms\IDeal\AddOn::uninstall();
	}
}
