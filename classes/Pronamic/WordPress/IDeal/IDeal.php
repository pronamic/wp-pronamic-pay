<?php

/**
 * Title: iDEAL
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WordPress_IDeal_IDeal {
	/**
	 * The global iDEAL client used within WordPress
	 * 
	 * @var IDealClient
	 */
	private static $client;

	//////////////////////////////////////////////////

	/**
	 * Error
	 * 
	 * @var Error
	 */
	private static $error;

	//////////////////////////////////////////////////

	/**
	 * Get the iDEAL client
	 * 
	 * @return Pronamic_IDeal_IDealClient
	 */
	public static function getClient() {
		if(self::$client == null) {
            self::$client = new Pronamic_IDeal_IDealClient();
        }

        return self::$client;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the error
	 * 
	 * @return Error
	 */
	public static function getError() {
		return self::$error;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the issuers lists for the specified configuration
	 * 
	 * @param Configuration $configuration
	 */
	public static function getIssuersLists(Pronamic_WordPress_IDeal_Configuration $configuration) {
		$lists = null;

		$variant = $configuration->getVariant();
		if($variant != null) {
			$iDealClient = self::getClient();
			$iDealClient->setAcquirerUrl($configuration->getPaymentServerUrl());
			$iDealClient->setPrivateKey($configuration->privateKey);
			$iDealClient->setPrivateKeyPassword($configuration->privateKeyPassword);
			$iDealClient->setPrivateCertificate($configuration->privateCertificate);
	
			foreach($variant->certificates as $certificate) {
				$iDealClient->addPublicCertificate($certificate);
			}
			
			$message = new Pronamic_IDeal_XML_DirectoryRequestMessage();
			$merchant = $message->getMerchant();
			$merchant->id = $configuration->getMerchantId();
			$merchant->subId = $configuration->getSubId();
			$merchant->authentication = Pronamic_IDeal_IDeal::AUTHENTICATION_SHA1_RSA;
			$merchant->token = Pronamic_IDeal_Security::getShaFingerprint($configuration->privateCertificate);
			$message->sign($configuration->privateKey, $configuration->privateKeyPassword);

			$result = $iDealClient->getIssuerLists($message);
			if($result !== null) {
				$lists = $result;
			} elseif($error = $iDealClient->getError()) {
				self::$error = $error;
			}
		}
		
		return $lists;
	}

	/**
	 * Get the issuers transient lists for the specified configuration
	 * 
	 * @param Configuration $configuration
	 */
	public static function getTransientIssuersLists(Pronamic_WordPress_IDeal_Configuration $configuration) {
		$issuersList = null;

		$transient = 'pronamic-ideal-issuers-list-' . $configuration->getId();

		$result = get_transient($transient);
		if($result instanceof Pronamic_IDeal_Error) {
			self::$error = $result;
		} elseif($result === false) {
			$client = self::getClient();

			$issuersList = self::getIssuersLists($configuration);
		
			if($issuersList) {
				// 60 * 60 * 24 = 24 hours = 1 day
				set_transient($transient, $issuersList, 60 * 60 * 24);
			} elseif($error = $client->getError()) {
				self::$error = $error;

				// 60 * 30 = 30 minutes
				set_transient($transient, $error, 60 * 30 * 1);
			}
		} elseif(is_array($result)) {
			$issuersList = $result;
		}

		return $issuersList;
	}

	/**
	 * Delete the transient for the specified configuration
	 * 
	 * @param Configuration $configuration
	 */
	public static function deleteConfigurationTransient(Pronamic_WordPress_IDeal_Configuration $configuration) {
		delete_transient('pronamic-ideal-issuers-list-' . $configuration->getId());
	}

	//////////////////////////////////////////////////

	/**
	 * Handle transaction
	 * 
	 * @param string $issuerId
	 * @param Payment $payment
	 * @param Variant $variant
	 */
	public static function handleTransaction($issuerId, $payment, $variant) {
		$transaction = $payment->transaction;
		$configuration = $payment->configuration;

		// Transaction request message
		$message = new Pronamic_IDeal_XML_TransactionRequestMessage();

		$issuer = new Pronamic_IDeal_Issuer();
		$issuer->setId($issuerId);

		$merchant = $message->getMerchant();
		$merchant->id = $configuration->getMerchantId();
		$merchant->subId = $configuration->getSubId();
		$merchant->authentication = Pronamic_IDeal_IDeal::AUTHENTICATION_SHA1_RSA;
		$merchant->returnUrl = home_url();
		$merchant->token = Pronamic_IDeal_Security::getShaFingerprint($configuration->privateCertificate);

		$message->issuer = $issuer;
		$message->merchant = $merchant;
		$message->transaction = $transaction;
		$message->sign($configuration->privateKey, $configuration->privateKeyPassword);

		$iDealClient = new Pronamic_IDeal_IDealClient();
		$iDealClient->setAcquirerUrl($configuration->getPaymentServerUrl());
		$iDealClient->setPrivateKey($configuration->privateKey);
		$iDealClient->setPrivateKeyPassword($configuration->privateKeyPassword);
		$iDealClient->setPrivateCertificate($configuration->privateCertificate);

		foreach($variant->certificates as $certificate) {
			$iDealClient->addPublicCertificate($certificate);
		}

		$responseMessage = $iDealClient->createTransaction($message);

		$updated = Pronamic_WordPress_IDeal_PaymentsRepository::updatePayment($payment);

		/*
		 * Schedule status requests	
		 * http://pronamic.nl/wp-content/uploads/2011/12/iDEAL_Advanced_PHP_EN_V2.2.pdf (page 19)
		 * 
		 * @todo
		 * Considering the number of status requests per transaction:
		 * - Maximum of five times per transaction;
		 * - Maximum of two times during the expirationPeriod;
		 * - After the expirationPeriod not more often than once per 60 minutes;
		 * - No status request after a final status has been received for a transaction;
		 * - No status request for transactions older than 7 days.
		 */
		$args = array($payment->getId());
	
		/*
		 * The function wp_schedule_single_event() uses the arguments array as an key for the event, 
		 * that's why we also add the time to this array, besides that it's also much clearer on 
		 * the Cron View (http://wordpress.org/extend/plugins/cron-view/) page
		 */

		$time = time();

		// Examples of possible times when a status request can be executed:

		// 30 seconds after a transaction request is sent
		wp_schedule_single_event($time +    30, 'pronamic_ideal_check_transaction_status', array('payment_id' => $payment->getId(), 'seconds' => 30));
		// Half-way through an expirationPeriod
		wp_schedule_single_event($time +  1800, 'pronamic_ideal_check_transaction_status', array('payment_id' => $payment->getId(), 'seconds' => 1800));
		// Just after an expirationPeriod
		wp_schedule_single_event($time +  3600, 'pronamic_ideal_check_transaction_status', array('payment_id' => $payment->getId(), 'seconds' => 3600));
		// A certain period after the end of the expirationPeriod
		wp_schedule_single_event($time + 86400, 'pronamic_ideal_check_transaction_status', array('payment_id' => $payment->getId(), 'seconds' => 86400));

		return $issuer->authenticationUrl;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the translaction of the specified status notifier
	 * 
	 * @param string $status
	 * @return string
	 */
	public static function translateStatus($status) {
		switch($status) {
			case Pronamic_IDeal_Transaction::STATUS_CANCELLED:
				return __('Cancelled', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN);
			case Pronamic_IDeal_Transaction::STATUS_EXPIRED:
				return __('Expired', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN);
			case Pronamic_IDeal_Transaction::STATUS_FAILURE:
				return __('Failure', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN);
			case Pronamic_IDeal_Transaction::STATUS_OPEN:
				return __('Open', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN);
			case Pronamic_IDeal_Transaction::STATUS_SUCCESS:
				return __('Success', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN);
			default:
				return __('Unknown', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN);
		}
	}
}
