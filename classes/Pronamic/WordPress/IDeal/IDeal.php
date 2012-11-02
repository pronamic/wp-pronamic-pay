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
		$merchant->returnUrl = site_url('/');
		$merchant->token = Pronamic_IDeal_Security::getShaFingerprint($configuration->privateCertificate);

		$message->issuer = $issuer;
		$message->merchant = $merchant;
		$message->transaction = $transaction;
		$message->sign($configuration->privateKey, $configuration->privateKeyPassword);

		$iDealClient = self::getClient();
		$iDealClient->setAcquirerUrl($configuration->getPaymentServerUrl());
		$iDealClient->setPrivateKey($configuration->privateKey);
		$iDealClient->setPrivateKeyPassword($configuration->privateKeyPassword);
		$iDealClient->setPrivateCertificate($configuration->privateCertificate);

		foreach($variant->certificates as $certificate) {
			$iDealClient->addPublicCertificate($certificate);
		}

		$responseMessage = $iDealClient->createTransaction($message);

		if($error = $iDealClient->getError()) {
			self::$error = $error;

			return null;
		} else {
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
				return __('Cancelled', 'pronamic_ideal');
			case Pronamic_IDeal_Transaction::STATUS_EXPIRED:
				return __('Expired', 'pronamic_ideal');
			case Pronamic_IDeal_Transaction::STATUS_FAILURE:
				return __('Failure', 'pronamic_ideal');
			case Pronamic_IDeal_Transaction::STATUS_OPEN:
				return __('Open', 'pronamic_ideal');
			case Pronamic_IDeal_Transaction::STATUS_SUCCESS:
				return __('Success', 'pronamic_ideal');
			default:
				return __('Unknown', 'pronamic_ideal');
		}
	}

	//////////////////////////////////////////////////

	public static function getHtmlForm(Pronamic_IDeal_IDealDataProxy $dataProxy, Pronamic_WordPress_IDeal_Configuration $configuration = null, $autoSubmit = false) {
		$html = '';

		if($configuration !== null) {
			$variant = $configuration->getVariant();
	
			if($variant !== null) {
				switch($variant->getMethod()) {
					case Pronamic_IDeal_IDeal::METHOD_EASY:
						$html = self::getHtmlIDealEasyForm($dataProxy, $configuration);
						break;
					case Pronamic_IDeal_IDeal::METHOD_BASIC:
						$html = self::getHtmlIDealBasicForm($dataProxy, $configuration);
						break;
					case Pronamic_IDeal_IDeal::METHOD_INTERNETKASSA:
						$html = self::getHtmlIDealKassaForm($dataProxy, $configuration);
						break;
					case Pronamic_IDeal_IDeal::METHOD_OMNIKASSA:
						$html = self::getHtmlIDealOmniKassaForm($dataProxy, $configuration);
						break;
				}
		
				if($autoSubmit) {
					$html .= '<script type="text/javascript">';
					$html .= '	document.pronamic_ideal_form.submit();';
					$html .= '</script>';
				}
			}
		}
		
		return $html;
	}
	
	public static function getHtmlIDealEasyForm(Pronamic_IDeal_IDealDataProxy $dataProxy, Pronamic_WordPress_IDeal_Configuration $configuration) {
		$iDeal = new Pronamic_Gateways_IDealEasy_IDealEasy();

		$iDeal->setPaymentServerUrl($configuration->getPaymentServerUrl());
		$iDeal->setPspId($configuration->pspId);

		$iDeal->setLanguage($dataProxy->getLanguageIso639AndCountryIso3166Code());
		$iDeal->setCurrency($dataProxy->getCurrencyAlphabeticCode());
		$iDeal->setOrderId($dataProxy->getOrderId());
		$iDeal->setDescription($dataProxy->getDescription());
        $iDeal->setAmount($dataProxy->getAmount());
        $iDeal->setEMailAddress($dataProxy->getEMailAddress());
        $iDeal->setCustomerName($dataProxy->getCustomerName());
		$iDeal->setOwnerAddress($dataProxy->getOwnerAddress());
		$iDeal->setOwnerCity($dataProxy->getOwnerCity());
		$iDeal->setOwnerZip($dataProxy->getOwnerZip());

		// Payment
		$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentBySource($dataProxy->getSource(), $dataProxy->getOrderId());
    	
		if($payment == null) {
			// Update payment
			$transaction = new Pronamic_IDeal_Transaction();
			$transaction->setAmount($dataProxy->getAmount()); 
			$transaction->setCurrency($dataProxy->getCurrencyAlphabeticCode());
			$transaction->setLanguage($dataProxy->getLanguageIso639Code());
			$transaction->setEntranceCode(uniqid());
			$transaction->setDescription($dataProxy->getDescription());
			$transaction->setPurchaseId($dataProxy->getOrderId());
			
			$payment = new Pronamic_WordPress_IDeal_Payment();
			$payment->configuration = $configuration;
			$payment->transaction = $transaction;
			$payment->setSource($dataProxy->getSource(), $dataProxy->getOrderId());
			
			$updated = Pronamic_WordPress_IDeal_PaymentsRepository::updatePayment($payment);
		}
		
		// HTML
		$html  = '';
		$html .= sprintf('<form id="pronamic_ideal_form" name="pronamic_ideal_form" method="post" action="%s">', esc_attr($configuration->getPaymentServerUrl()));
		$html .= 	$iDeal->getHtmlFields();
		$html .= 	sprintf('<input class="ideal-button" type="submit" name="ideal" value="%s" />', __('Pay with iDEAL', 'pronamic_ideal'));
		$html .= '</form>';

		return $html;
	}
	
	public static function getHtmlIDealBasicForm(Pronamic_IDeal_IDealDataProxy $dataProxy, $configuration) {
		$iDeal = new Pronamic_IDeal_Basic();

		$iDeal->setPaymentServerUrl($configuration->getPaymentServerUrl());
		$iDeal->setMerchantId($configuration->getMerchantId());
		$iDeal->setSubId($configuration->getSubId());
		$iDeal->setHashKey($configuration->hashKey);
		
		$iDeal->setLanguage($dataProxy->getLanguageIso639Code());
		$iDeal->setCurrency($dataProxy->getCurrencyAlphabeticCode());
		$iDeal->setPurchaseId($dataProxy->getOrderId());
		$iDeal->setDescription($dataProxy->getDescription());
		$iDeal->setItems($dataProxy->getItems());
		$iDeal->setCancelUrl($dataProxy->getCancelUrl());
		$iDeal->setSuccessUrl($dataProxy->getSuccessUrl());
		$iDeal->setErrorUrl($dataProxy->getErrorUrl());

		// Payment
		$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentBySource($dataProxy->getSource(), $dataProxy->getOrderId());
    	
		if($payment == null) {
			// Update payment
			$transaction = new Pronamic_IDeal_Transaction();
			$transaction->setAmount($dataProxy->getAmount()); 
			$transaction->setCurrency($dataProxy->getCurrencyAlphabeticCode());
			$transaction->setLanguage($dataProxy->getLanguageIso639Code());
			$transaction->setEntranceCode(uniqid());
			$transaction->setDescription($dataProxy->getDescription());
			$transaction->setPurchaseId($dataProxy->getOrderId());
			
			$payment = new Pronamic_WordPress_IDeal_Payment();
			$payment->configuration = $configuration;
			$payment->transaction = $transaction;
			$payment->setSource($dataProxy->getSource(), $dataProxy->getOrderId());
			
			$updated = Pronamic_WordPress_IDeal_PaymentsRepository::updatePayment($payment);
		}
		
		// HTML
		$html  = '';
		$html .= sprintf('<form id="pronamic_ideal_form" name="pronamic_ideal_form" method="post" action="%s">', esc_attr($configuration->getPaymentServerUrl()));
		$html .= 	$iDeal->getHtmlFields();
		$html .= 	sprintf('<input class="ideal-button" type="submit" name="ideal" value="%s" />', __('Pay with iDEAL', 'pronamic_ideal'));
		$html .= '</form>';
			        
		return $html;
	}
	
	public static function getHtmlIDealKassaForm(Pronamic_IDeal_IDealDataProxy $dataProxy, $configuration) {
		$iDeal = new Pronamic_Gateways_IDealInternetKassa_IDealInternetKassa();

		$file = dirname(Pronamic_WordPress_IDeal_Plugin::$file) . '/other/calculations-parameters-sha-in.txt';
		$iDeal->setCalculationsParametersIn( file($file, FILE_IGNORE_NEW_LINES) );
	
		$file = dirname(Pronamic_WordPress_IDeal_Plugin::$file) . '/other/calculations-parameters-sha-out.txt';
		$iDeal->setCalculationsParametersOut( file($file, FILE_IGNORE_NEW_LINES) );

		$iDeal->setPaymentServerUrl($configuration->getPaymentServerUrl());
		$iDeal->setPspId($configuration->pspId);
		$iDeal->setPassPhraseIn($configuration->shaInPassPhrase);
		$iDeal->setPassPhraseOut($configuration->shaOutPassPhrase);
		
		$iDeal->setLanguage($dataProxy->getLanguageIso639AndCountryIso3166Code());
		$iDeal->setCurrency($dataProxy->getCurrencyAlphabeticCode());
		$iDeal->setOrderDescription($dataProxy->getDescription());
		$iDeal->setAmount($dataProxy->getAmount());

		// Payment
		$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentBySource($dataProxy->getSource(), $dataProxy->getOrderId());
    	
		if($payment == null) {
			// Update payment
			$transaction = new Pronamic_IDeal_Transaction();
			$transaction->setAmount($dataProxy->getAmount()); 
			$transaction->setCurrency($dataProxy->getCurrencyAlphabeticCode());
			$transaction->setLanguage($dataProxy->getLanguageIso639Code());
			$transaction->setEntranceCode(uniqid());
			$transaction->setDescription($dataProxy->getDescription());
			$transaction->setPurchaseId($dataProxy->getOrderId());
			
			$payment = new Pronamic_WordPress_IDeal_Payment();
			$payment->configuration = $configuration;
			$payment->transaction = $transaction;
			$payment->setSource($dataProxy->getSource(), $dataProxy->getOrderId());
			
			$updated = Pronamic_WordPress_IDeal_PaymentsRepository::updatePayment($payment);
		}

		$iDeal->setOrderId($payment->getId());

		// HTML
		$html  = '';
		$html .= sprintf('<form id="pronamic_ideal_form" name="pronamic_ideal_form" method="post" action="%s">', esc_attr($configuration->getPaymentServerUrl()));
		$html .= 	$iDeal->getHtmlFields();
		$html .= 	sprintf('<input class="ideal-button" type="submit" name="ideal" value="%s" />', __('Pay with iDEAL', 'pronamic_ideal'));
		$html .= '</form>';
			        
		return $html;
	}
	
	public static function getHtmlIDealOmniKassaForm($dataProxy, $configuration) {
		$iDeal = new Pronamic_IDeal_OmniKassa();

		$iDeal->setPaymentServerUrl($configuration->getPaymentServerUrl());
		$iDeal->setMerchantId($configuration->getMerchantId());
		$iDeal->setKeyVersion($configuration->getSubId());
		$iDeal->setSecretKey($configuration->getHashKey());

		$iDeal->setCustomerLanguage($dataProxy->getLanguageIso639Code());
		$iDeal->setCurrencyNumericCode($dataProxy->getCurrencyNumericCode());
		$iDeal->setOrderId($dataProxy->getOrderId());
		$iDeal->setNormalReturnUrl(site_url('/'));
		$iDeal->setAutomaticResponseUrl(site_url('/'));
		$iDeal->setAmount($dataProxy->getAmount());

		// Payment
		$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentBySource($dataProxy->getSource(), $dataProxy->getOrderId());
    	
		if($payment == null) {
			$id = md5(time() . $dataProxy->getOrderId());

			// Update payment
			$transaction = new Pronamic_IDeal_Transaction();
			$transaction->setId($id);
			$transaction->setAmount($dataProxy->getAmount()); 
			$transaction->setCurrency($dataProxy->getCurrencyAlphabeticCode());
			$transaction->setLanguage($dataProxy->getLanguageIso639Code());
			$transaction->setEntranceCode(uniqid());
			$transaction->setDescription($dataProxy->getDescription());
			$transaction->setPurchaseId($dataProxy->getOrderId());

			$payment = new Pronamic_WordPress_IDeal_Payment();
			$payment->configuration = $configuration;
			$payment->transaction = $transaction;
			$payment->setSource($dataProxy->getSource(), $dataProxy->getOrderId());

			$updated = Pronamic_WordPress_IDeal_PaymentsRepository::updatePayment($payment);
		}
		
		$iDeal->setTransactionReference($payment->transaction->getId());

		// HTML
		$html  = '';
		$html .= sprintf('<form id="pronamic_ideal_form" name="pronamic_ideal_form" method="post" action="%s">', esc_attr($configuration->getPaymentServerUrl()));
		$html .= 	$iDeal->getHtmlFields();
		$html .= 	sprintf('<input class="ideal-button" type="submit" name="ideal" value="%s" />', __('Pay with iDEAL', 'pronamic_ideal'));
		$html .= '</form>';

		return $html;
	}

	public static function process_ideal_advanced( $configuration, $data_proxy, $issuer_id ) {
		$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentBySource( $data_proxy->getSource(), $data_proxy->getOrderId() );
		
		if ( $payment == null ) {
			$transaction = new Pronamic_IDeal_Transaction();
			$transaction->setAmount( $data_proxy->getAmount() );
			$transaction->setCurrency( $data_proxy->getCurrencyAlphabeticCode() );
			$transaction->setExpirationPeriod( 'PT1H' );
			$transaction->setLanguage( $data_proxy->getLanguageIso639Code() );
			$transaction->setEntranceCode( uniqid() );
			$transaction->setDescription( $data_proxy->getDescription() );
			$transaction->setPurchaseId( $data_proxy->getOrderId() );
		
			$payment = new Pronamic_WordPress_IDeal_Payment();
			$payment->configuration = $configuration;
			$payment->transaction = $transaction;
			$payment->setSource( $data_proxy->getSource(), $data_proxy->getOrderId() );
		
			$updated = Pronamic_WordPress_IDeal_PaymentsRepository::updatePayment( $payment );
		}
		
		$url = Pronamic_WordPress_IDeal_IDeal::handleTransaction( $issuer_id, $payment, $configuration->getVariant() );

		return $url;
	}
}
