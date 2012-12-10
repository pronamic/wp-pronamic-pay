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
	 * @return Pronamic_Gateways_IDealAdvanced_IDealClient
	 */
	public static function getClient() {
		if(self::$client == null) {
            self::$client = new Pronamic_Gateways_IDealAdvanced_IDealClient();
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
			
			$message = new Pronamic_Gateways_IDealAdvanced_XML_DirectoryRequestMessage();
			$merchant = $message->getMerchant();
			$merchant->id = $configuration->getMerchantId();
			$merchant->subId = $configuration->getSubId();
			$merchant->authentication = Pronamic_IDeal_IDeal::AUTHENTICATION_SHA1_RSA;
			$merchant->token = Pronamic_Gateways_IDealAdvanced_Security::getShaFingerprint($configuration->privateCertificate);
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
		$message = new Pronamic_Gateways_IDealAdvanced_XML_TransactionRequestMessage();

		$issuer = new Pronamic_Gateways_IDealAdvanced_Issuer();
		$issuer->setId($issuerId);

		$merchant = $message->getMerchant();
		$merchant->id = $configuration->getMerchantId();
		$merchant->subId = $configuration->getSubId();
		$merchant->authentication = Pronamic_IDeal_IDeal::AUTHENTICATION_SHA1_RSA;
		$merchant->returnUrl = site_url('/');
		$merchant->token = Pronamic_Gateways_IDealAdvanced_Security::getShaFingerprint($configuration->privateCertificate);

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
			wp_schedule_single_event( $time +    30, 'pronamic_ideal_check_transaction_status', array( 'payment_id' => $payment->getId(), 'seconds' =>   30 ) );
			// Half-way through an expirationPeriod
			wp_schedule_single_event( $time +  1800, 'pronamic_ideal_check_transaction_status', array( 'payment_id' => $payment->getId(), 'seconds' =>  1800 ) );
			// Just after an expirationPeriod
			wp_schedule_single_event( $time +  3600, 'pronamic_ideal_check_transaction_status', array( 'payment_id' => $payment->getId(), 'seconds' =>  3600 ) );
			// A certain period after the end of the expirationPeriod
			wp_schedule_single_event( $time + 86400, 'pronamic_ideal_check_transaction_status', array( 'payment_id' => $payment->getId(), 'seconds' => 86400 ) );
	
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
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_CANCELLED:
				return __('Cancelled', 'pronamic_ideal');
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_EXPIRED:
				return __('Expired', 'pronamic_ideal');
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_FAILURE:
				return __('Failure', 'pronamic_ideal');
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_OPEN:
				return __('Open', 'pronamic_ideal');
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_SUCCESS:
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
				$html = self::get_html_form( $dataProxy, $configuration );
		
				if($autoSubmit) {
					$html .= '<script type="text/javascript">';
					$html .= '	document.pronamic_ideal_form.submit();';
					$html .= '</script>';
				}
			}
		}
		
		return $html;
	}
	
	public static function get_gateway( Pronamic_IDeal_IDealDataProxy $data, Pronamic_WordPress_IDeal_Configuration $configuration ) {		
		if($configuration !== null) {
			$variant = $configuration->getVariant();

			if($variant !== null) {
				switch($variant->getMethod()) {
					case Pronamic_IDeal_IDeal::METHOD_EASY:
						return new Pronamic_Gateways_IDealEasy_Gateway( $configuration, $data );
					case Pronamic_IDeal_IDeal::METHOD_BASIC:
						return new Pronamic_Gateways_IDealBasic_Gateway( $configuration, $data );
					case Pronamic_IDeal_IDeal::METHOD_INTERNETKASSA:
						return new Pronamic_Gateways_IDealInternetKassa_Gateway( $configuration, $data );
					case Pronamic_IDeal_IDeal::METHOD_OMNIKASSA:
						return new Pronamic_Gateways_OmniKassa_Gateway( $configuration, $data );
					case 'mollie':
						return new Pronamic_Gateways_Mollie_Gateway( $configuration, $data );
					case 'targetpay':
						return new Pronamic_Gateways_TargetPay_Gateway( $configuration, $data );
				}
			}
		}				
	}

	public static function get_html_form( Pronamic_IDeal_IDealDataProxy $data, Pronamic_WordPress_IDeal_Configuration $configuration ) {
		$html = '';

		$gateway = self::get_gateway( $data, $configuration );
		
		if ( !empty( $gateway ) ) {
			$gateway->start( $data );

			// Update payment
			$id = md5( time() . $data->getOrderId() );

			$transaction = new Pronamic_Gateways_IDealAdvanced_Transaction();
			$transaction->setId( $id );
			$transaction->setAmount( $data->getAmount() );
			$transaction->setCurrency( $data->getCurrencyAlphabeticCode() );
			$transaction->setLanguage( $data->getLanguageIso639Code() );
			$transaction->setEntranceCode( uniqid() );
			$transaction->setDescription( $data->getDescription() );
			$transaction->setPurchaseId( $data->getOrderId() );

			$payment = new Pronamic_WordPress_IDeal_Payment();
			$payment->configuration = $configuration;
			$payment->transaction_id          = $gateway->transaction_id;
			$payment->purchase_id             = $data->getOrderId();
			$payment->description             = $data->getDescription();
			$payment->amount                  = $data->getAmount();
			$payment->currency                = $data->getCurrencyAlphabeticCode();
			$payment->language                = $data->getLanguageIso639Code();
			$payment->entrance_code           = $data->get_entrance_code();
			$payment->expiration_period       = null;
			$payment->status                  = null;
			$payment->consumer_name           = null;
			$payment->consumer_account_number = null;
			$payment->consumer_city           = null;
			$payment->transaction = $transaction;
			$payment->setSource( $data->getSource(), $data->getOrderId() );
				
			$updated = Pronamic_WordPress_IDeal_PaymentsRepository::updatePayment( $payment );

			// HTML
			$html  = '';
			$html .= sprintf('<form id="pronamic_ideal_form" name="pronamic_ideal_form" method="post" action="%s">', esc_attr( $gateway->get_action_url() ) );
			$html .= 	$gateway->get_html_fields();
			$html .= 	sprintf('<input class="ideal-button" type="submit" name="ideal" value="%s" />', __('Pay with iDEAL', 'pronamic_ideal'));
			$html .= '</form>';
		}
			
		return $html;
	}

	public static function process_ideal_advanced( $configuration, $data_proxy ) {
		$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentBySource( $data_proxy->getSource(), $data_proxy->getOrderId() );
		
		if ( $payment == null ) {
			$transaction = new Pronamic_Gateways_IDealAdvanced_Transaction();
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
		
		$issuer_id = $data_proxy->get_issuer_id();

		$url = Pronamic_WordPress_IDeal_IDeal::handleTransaction( $issuer_id, $payment, $configuration->getVariant() );

		return $url;
	}

	public static function get_targetpay_issuers() {
		$targetpay = new Pronamic_Gateways_TargetPay_TargetPay();
		
		$issuers = $targetpay->get_issuers();
		
		$output = '<select name="pronamic_ideal_issuer_id" id="pronamic_ideal_issuer_id">';
		foreach ( $issuers as $id => $name ) {
			$output .= '<option value="' . $id . '">' . $name . '</option>';
		}
		$output .= '</select>';

		return $output;
	}

	public static function get_mollie_issuers( $configuration ) {
		$mollie = new Pronamic_Gateways_Mollie_Mollie( $configuration->molliePartnerId );
		
		$issuers = $mollie->getBanks();
		
		$output = '<select name="pronamic_ideal_issuer_id" id="pronamic_ideal_issuer_id">';
		foreach ( $issuers as $id => $name ) {
			$output .= '<option value="' . $id . '">' . $name . '</option>';
		}
		$output .= '</select>';

		return $output;
	}
	
	public static function get_fields( $configuration ) {
		$fields = array();

		if ( $configuration !== null ) {
			$variant = $configuration->getVariant();
		
			if ( $variant !== null && $variant->getMethod() == Pronamic_IDeal_IDeal::METHOD_ADVANCED) {
				$lists = Pronamic_WordPress_IDeal_IDeal::getTransientIssuersLists( $configuration );
		
				if ( $lists ) {
					$fields[] = array(
						'label' => __( 'Choose your bank', 'pronamic_ideal' ),
						'input' => Pronamic_IDeal_HTML_Helper::issuersSelect( 'pronamic_ideal_issuer_id', $lists )
					);
				} elseif ( $error = Pronamic_WordPress_IDeal_IDeal::getError() ) {
					$fields[] = array(
						'error' => $error->getConsumerMessage()
					);
				} else {
					$fields[] = array(
						'error' => __( 'Paying with iDEAL is not possible. Please try again later or pay another way.', 'pronamic_ideal' )
					); 
				}
			}
			
			if ( $variant !== null && $variant->getMethod() == 'targetpay') {
				$fields[] = array(
					'label' => __( 'Choose your bank', 'pronamic_ideal' ),
					'input' => self::get_targetpay_issuers( $configuration )
				);
			}
			
			if ( $variant !== null && $variant->getMethod() == 'mollie') {
				$fields[] = array(
					'label' => __( 'Choose your bank', 'pronamic_ideal' ),
					'input' => self::get_mollie_issuers( $configuration )
				);
			}
		}
		
		return $fields;
	}
}
