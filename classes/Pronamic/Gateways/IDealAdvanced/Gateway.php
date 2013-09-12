<?php

/**
 * Title: iDEAL Advanced gateway
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvanced_Gateway extends Pronamic_Gateways_Gateway {
	/**
	 * Constructs and initializes an iDEAL Advanced gateway
	 * 
	 * @param Pronamic_WordPress_IDeal_Configuration $configuration
	 */
	public function __construct( $configuration_id ) {
		parent::__construct( $configuration_id );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTTP_REDIRECT );
		$this->set_has_feedback( true );
		$this->set_amount_minimum( 0.01 );

		// Client
		global $pronamic_pay_gateways;
		
		$gateway_id = get_post_meta( $configuration_id, '_pronamic_gateway_id', true );
		$mode       = get_post_meta( $configuration_id, '_pronamic_gateway_mode', true );

		$gateway  = $pronamic_pay_gateways[$gateway_id];
		$settings = $gateway[$mode];
		
		$url = $settings['payment_server_url'];

		$client = new Pronamic_Gateways_IDealAdvanced_Client();
		$client->setAcquirerUrl( $url );
		$client->merchant_id = get_post_meta( $configuration_id, '_pronamic_gateway_ideal_merchant_id', true );
		$client->sub_id = get_post_meta( $configuration_id, '_pronamic_gateway_ideal_sub_id', true );
		$client->setPrivateKey( get_post_meta( $configuration_id, '_pronamic_gateway_ideal_private_key', true ) );
		$client->setPrivateKeyPassword( get_post_meta( $configuration_id, '_pronamic_gateway_ideal_private_key_password', true ) );
		$client->setPrivateCertificate( get_post_meta( $configuration_id, '_pronamic_gateway_ideal_private_certificate', true ) );

		if ( isset( $settings['directory_request_url'] ) ) {
			$client->directory_request_url = $settings['directory_request_url']; 
		}
		if ( isset( $settings['transaction_request_url'] ) ) {
			$client->transaction_request_url = $settings['transaction_request_url']; 
		}
		if ( isset( $settings['status_request_url'] ) ) {
			$client->status_request_url = $settings['status_request_url']; 
		}
		
		foreach ( $gateway['certificates'] as $certificate ) {
			$client->addPublicCertificate( $certificate );
		}

		$this->client = $client;
	}
	
	/////////////////////////////////////////////////

	/**
	 * Get issuers
	 * 
	 * @see Pronamic_Gateways_Gateway::get_issuers()
	 */
	public function get_issuers() {
		$result = $this->client->getIssuerLists();

		$lists = null;
		
		if ( $result !== null ) {
			$lists = $result;
		} elseif ( $error = $this->client->get_error() ) {
			$this->error = $error;
		}

		return $lists;
	}
	
	/////////////////////////////////////////////////

	/**
	 * Get issuer field
	 * 
	 * @see Pronamic_Gateways_Gateway::get_issuer_field()
	 */
	public function get_issuer_field() {
		$choices = array();
		
		$list = $this->get_transient_issuers();

		if ( $list ) {
			foreach( $list as $name => $issuers ) {
				$options = array();
	
				foreach ( $issuers as $issuer ) {
					$options[$issuer->getId()] = $issuer->getName(); 
				}

				$choices[] = array(
					'name'    => ( $name == Pronamic_Gateways_IDealAdvanced_Issuer::LIST_LONG ) ? __( '&mdash; Other banks &mdash;', 'pronamic_ideal' ) : false,
					'options' => $options
				);
			}
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

	/**
	 * Start transaction with the specified date
	 * 
	 * @see Pronamic_Gateways_Gateway::start()
	 */
	public function start( Pronamic_Pay_PaymentDataInterface $data, $payment_id = false ) {
		// Transaction request message
		$transaction = new Pronamic_Gateways_IDealAdvanced_Transaction();
		$transaction->setPurchaseId( $data->getOrderId() );
		$transaction->setAmount( $data->getAmount() );
		$transaction->setCurrency( $data->getCurrencyAlphabeticCode() );
		$transaction->setExpirationPeriod( 'PT3M30S' );
		$transaction->setLanguage( $data->getLanguageIso639Code() );
		$transaction->setDescription( $data->getDescription() );
		$transaction->setEntranceCode( $data->get_entrance_code() );

		$return_url = add_query_arg( 'payment', $payment_id, home_url( '/' ) );

		$result = $this->client->create_transaction( $transaction, $return_url, $data->get_issuer_id() );

		$error = $this->client->get_error();

		if ( $error !== null ) {
			$this->error = $error;
		} else {
			$issuer = $result->issuer;

			$this->set_action_url( $result->issuer->authenticationUrl );
			$this->set_transaction_id( $result->transaction->getId() );
			
			update_post_meta( $payment_id, '_pronamic_payment_authentication_url', $result->issuer->authenticationUrl );
			update_post_meta( $payment_id, '_pronamic_payment_transaction_id', $result->transaction->getId() );
		}
	}
	
	/////////////////////////////////////////////////

	/**
	 * Payment
	 * 
	 * @see Pronamic_Gateways_Gateway::payment()
	 * @param Pronamic_WordPress_IDeal_Payment $payment
	 */
	public function payment( $payment_id ) {
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
	
		/*
		 * The function wp_schedule_single_event() uses the arguments array as an key for the event, 
		 * that's why we also add the time to this array, besides that it's also much clearer on 
		 * the Cron View (http://wordpress.org/extend/plugins/cron-view/) page
		 */

		$time = time();

		// Examples of possible times when a status request can be executed:

		// 30 seconds after a transaction request is sent
		wp_schedule_single_event( $time +    30, 'pronamic_ideal_check_transaction_status', array( 'payment_id' => $payment_id, 'seconds' =>    30 ) );
		// Half-way through an expirationPeriod
		wp_schedule_single_event( $time +  1800, 'pronamic_ideal_check_transaction_status', array( 'payment_id' => $payment_id, 'seconds' =>  1800 ) );
		// Just after an expirationPeriod
		wp_schedule_single_event( $time +  3600, 'pronamic_ideal_check_transaction_status', array( 'payment_id' => $payment_id, 'seconds' =>  3600 ) );
		// A certain period after the end of the expirationPeriod
		wp_schedule_single_event( $time + 86400, 'pronamic_ideal_check_transaction_status', array( 'payment_id' => $payment_id, 'seconds' => 86400 ) );
	}
	
	/////////////////////////////////////////////////

	/**
	 * Update status of the specified payment
	 * 
	 * @param Pronamic_WordPress_IDeal_Payment $payment
	 */
	public function update_status( $payment_id ) {
		$transaction_id = get_post_meta( $payment_id, '_pronamic_payment_transaction_id', true );

		$result = $this->client->get_status( $transaction_id );

		$error = $this->client->get_error();

		if ( $error !== null ) {
			$this->set_error( $error );
		} else {
			$transaction = $result->transaction;

			update_post_meta( $payment_id, '_pronamic_payment_status', $transaction->getStatus() );
			update_post_meta( $payment_id, '_pronamic_payment_consumer_name', $transaction->getConsumerName() );
			update_post_meta( $payment_id, '_pronamic_payment_consumer_account_number', $transaction->getConsumerAccountNumber() );
			update_post_meta( $payment_id, '_pronamic_payment_consumer_city', $transaction->getConsumerCity() );
		}
	}
}
