<?php

/**
 * Title: Mollie
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Mollie_Gateway extends Pronamic_Gateways_Gateway {
	/**
	 * Constructs and initializes an Mollie gateway
	 * 
	 * @param Pronamic_WordPress_IDeal_Configuration $configuration
	 */
	public function __construct( Pronamic_WordPress_IDeal_Configuration $configuration ) {
		parent::__construct( $configuration );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTTP_REDIRECT );
		$this->set_has_feedback( false );
		$this->set_amount_minimum( 1.20 );

		$this->client = new Pronamic_Gateways_Mollie_Mollie( $configuration->molliePartnerId );
		$this->client->setTestmode( $configuration->mode == Pronamic_IDeal_IDeal::MODE_TEST );
	}
	
	/////////////////////////////////////////////////

	/**
	 * Get issuers
	 * 
	 * @see Pronamic_Gateways_Gateway::get_issuers()
	 */
	public function get_issuers() {
		$groups = array();

		$result = $this->client->getBanks();
		
		if ( $result ) {
			$groups[] = array(
				'options' => $result
			);
		}

		return $groups;
	}
	
	/////////////////////////////////////////////////

	public function get_issuer_field() {
		return array(
			'id'       => 'pronamic_ideal_issuer_id',
			'name'     => 'pronamic_ideal_issuer_id',
			'label'    => __( 'Choose your bank', 'pronamic_ideal' ),
			'required' => true,
			'type'     => 'select',
			'choices'  => $this->get_issuers()
		);
	}
	
	/////////////////////////////////////////////////

	/**
	 * Start 
	 * 
	 * @param Pronamic_IDeal_IDealDataProxy $data
	 * @see Pronamic_Gateways_Gateway::start()
	 */
	public function start( Pronamic_IDeal_IDealDataProxy $data ) {
		$result = $this->client->createPayment(
			$data->get_issuer_id(),
			Pronamic_WordPress_IDeal_Util::amount_to_cents( $data->getAmount() ),
			$data->getDescription(),
			$data->getNormalReturnUrl(),
			site_url( '/' )
		);
		
		if ( $result === true ) {
			$this->set_transaction_id( $this->client->getTransactionId() );
			$this->set_action_url( $this->client->getBankURL() );
		} else {
			$error = $this->client->getErrorMessage();
			
			var_dump( $error );
		}
	}
	
	/////////////////////////////////////////////////

	/**
	 * Update status of the specified payment
	 * 
	 * @param Pronamic_WordPress_IDeal_Payment $payment
	 */
	public function update_status( Pronamic_WordPress_IDeal_Payment $payment ) {
		$result = $this->client->checkPayment( $payment->transaction_id );

		if ( $result === true ) {
			$paid_status = $this->client->getPaidStatus();
			$bank_status = $this->client->getBankStatus();
			$consumer_info = $this->client->getConsumerInfo();
			
			var_dump( $paid_status );
			var_dump( $bank_status );
			var_dump( $consumer_info );
			
			exit;
		} else {
			$error = $this->client->getErrorMessage();
			
			var_dump( $error );
		}
	}
}
