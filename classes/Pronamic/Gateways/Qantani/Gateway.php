<?php

/**
 * Title: Qantani
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Qantani_Gateway extends Pronamic_Gateways_Gateway {
	/**
	 * Slug of this gateway
	 * 
	 * @var string
	 */
	const SLUG = 'qantani';	
	
	/////////////////////////////////////////////////

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
		$this->set_slug( self::SLUG );

		$this->client = new Pronamic_Gateways_Qantani_Qantani();
		$this->client->set_merchant_id( $configuration->qantani_merchant_id );
		$this->client->set_merchant_key( $configuration->qantani_merchant_key );
		$this->client->set_merchant_secret( $configuration->qantani_merchant_secret );
	}
	
	/////////////////////////////////////////////////

	/**
	 * Get issuers
	 * 
	 * @see Pronamic_Gateways_Gateway::get_issuers()
	 */
	public function get_issuers() {
		$groups = array();

		$result = $this->client->get_banks();

		if ( $result ) {
			$groups[] = array(
				'options' => $result
			);
		} else {
			$this->error = $this->client->get_error();
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
		$result = $this->client->create_transaction(
			$data->getAmount(),
			$data->getCurrencyAlphabeticCode(),
			$data->get_issuer_id(),
			$data->getDescription(),
			add_query_arg( 'gateway', 'qantani', home_url( '/' ) )
		);
		
		if ( $result !== false ) {
			$this->set_transaction_id( $result->transaction_id );
			$this->set_action_url( $result->bank_url );
		} else {
			$this->error = $this->client->get_error();
		}
	}

	/////////////////////////////////////////////////

	/**
	 * Update status of the specified payment
	 * 
	 * @param Pronamic_WordPress_IDeal_Payment $payment
	 */
	public function update_status( Pronamic_WordPress_IDeal_Payment $payment ) {
		
	}
}
