<?php

/**
 * Title: Basic
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvanced_Gateway extends Pronamic_Gateways_Gateway {
	public function __construct( $configuration ) {
		parent::__construct( $configuration );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTTP_REDIRECT );
		$this->set_has_feedback( true );
		$this->set_require_issue_select( true );
		$this->set_amount_minimum( 0.01 );

		// Client
		$client = new Pronamic_Gateways_IDealAdvanced_Client();
		$client->setAcquirerUrl( $configuration->getPaymentServerUrl() );
		$client->merchant_id = $configuration->getMerchantId();
		$client->sub_id = $configuration->getSubId();
		$client->setPrivateKey($configuration->privateKey);
		$client->setPrivateKeyPassword($configuration->privateKeyPassword);
		$client->setPrivateCertificate($configuration->privateCertificate);

		$variant = $configuration->getVariant();
		foreach ( $variant->certificates as $certificate ) {
			$client->addPublicCertificate( $certificate );
		}
		
		$this->client = $client;
	}
	
	/////////////////////////////////////////////////

	public function get_issuers() {
		$result = $this->client->getIssuerLists();

		$lists = null;
		
		if($result !== null) {
			$lists = $result;
		} elseif($error = $this->client->getError()) {
			$this->error = $error;
		}
		
		return $lists;
	}
	
	/////////////////////////////////////////////////

	public function get_issuer_field() {
		$choices = array();
		
		$list = $this->get_issuers();
		
		foreach( $list as $name => $issuers ) {
			$options = array();

			foreach ( $issuers as $issuer ) {
				$options[$issuer->getId()] = $issuer->getName(); 
			}
	
			$choices[] = array(
				'name'    => ( $name == 'Long' ) ? __( '&mdash; Other banks &mdash;', 'pronamic_ideal' ) : false,
				'options' => $options
			);
		}
		
		return array(
			'id'       => 'pronamic_ideal_issuer_id',
			'name'     => 'pronamic_ideal_issuer_id',
			'label'    => __( 'Choose your bank', 'pronamic_ideal' ),
			'required' => true,
			'type'     => 'select',
			'choices'  => $choices
		);
	}
	
	/////////////////////////////////////////////////

	public function get_input_fields() {
		$fields = array();
		
		$fields[] = $this->get_issuer_field(); 

		return $fields;
	}
	
	/////////////////////////////////////////////////

	public function start( Pronamic_IDeal_IDealDataProxy $data ) {
		// Transaction request message
		$transaction = new Pronamic_Gateways_IDealAdvanced_Transaction();
		$transaction->setPurchaseId( $data->getOrderId() );
		$transaction->setAmount( $data->getAmount() );
		$transaction->setCurrency( $data->getCurrencyAlphabeticCode() );
		$transaction->setExpirationPeriod( 'PT3M30S' );
		$transaction->setLanguage( $data->getLanguageIso639Code() );
		$transaction->setDescription( $data->getDescription() );
		$transaction->setEntranceCode( $data->get_entrance_code() );

		$result = $this->client->create_transaction( $transaction, $data->get_issuer_id() );

		$error = $this->client->get_error();
		
		if ( $error !== null ) {
			var_dump( $error );
		} else {
			$issuer = $result->issuer;

			$this->action_url     = $result->issuer->authenticationUrl;
			$this->transaction_id = $result->transaction->getId();
		}
		
	}
	
	/////////////////////////////////////////////////

	public function start2() {


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

	}
	
	/////////////////////////////////////////////////

	/**
	 * Update status of the specified payment
	 * 
	 * @param Pronamic_WordPress_IDeal_Payment $payment
	 */
	public function update_status( Pronamic_WordPress_IDeal_Payment $payment ) {
		$result = $this->client->get_status( $payment->transaction_id );

		$error = $this->client->get_error();

		if ( $error !== null ) {
			var_dump( $error );
		} else {
			$transaction = $result->transaction;

			$payment->status                  = $transaction->getStatus();
			$payment->consumer_name           = $transaction->getConsumerName();
			$payment->consumer_account_number = $transaction->getConsumerAccountNumber();
			$payment->consumer_city           = $transaction->getConsumerCity();
		}
	}
}
