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
	 * Slug of this gateway
	 * 
	 * @var string
	 */
	const SLUG = 'mollie';	
	
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

		$this->client = new Pronamic_Gateways_Mollie_Mollie( $configuration->molliePartnerId );
		$this->client->set_test_mode( $configuration->mode == Pronamic_IDeal_IDeal::MODE_TEST );
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
			'choices'  => $this->get_transient_issuers()
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
		$result = $this->client->create_payment(
			$data->get_issuer_id(),
			Pronamic_WordPress_Util::amount_to_cents( $data->getAmount() ),
			$data->getDescription(),
			add_query_arg( 'gateway', 'mollie', site_url( '/' ) ),
			add_query_arg( 'gateway', 'mollie', site_url( '/' ) )
		);
		
		if ( $result !== false ) {
			$this->set_transaction_id( $result->transaction_id );
			$this->set_action_url( $result->url );
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
		$result = $this->client->check_payment( $payment->transaction_id );

		if ( $result !== false ) {
			$consumer = $result->consumer;

			switch ( $result->status ) {
				case Pronamic_Gateways_Mollie_Statuses::SUCCESS:
					$payment->consumer_name           = $consumer->name;
					$payment->consumer_account_number = $consumer->account;
					$payment->consumer_city           = $consumer->city;
				case Pronamic_Gateways_Mollie_Statuses::CANCELLED:
				case Pronamic_Gateways_Mollie_Statuses::EXPIRED:
				case Pronamic_Gateways_Mollie_Statuses::FAILURE:
					$payment->status = $result->status;
					
					break;
				case Pronamic_Gateways_Mollie_Statuses::CHECKED_BEFORE:
					// Nothing to do here

					break;
			}
		} else {
			$this->error = $this->client->get_error();
		}
	}
}
