<?php

/**
 * Title: Mollie
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Mollie_IDeal_Gateway extends Pronamic_Gateways_Gateway {
	/**
	 * Slug of this gateway
	 * 
	 * @var string
	 */
	const SLUG = 'mollie_ideal';	
	
	/////////////////////////////////////////////////

	/**
	 * Constructs and initializes an Mollie gateway
	 * 
	 * @param Pronamic_Gateways_Mollie_IDeal_Config $config
	 */
	public function __construct( Pronamic_Gateways_Mollie_IDeal_Config $config ) {
		parent::__construct( $config );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTTP_REDIRECT );
		$this->set_has_feedback( true );
		$this->set_amount_minimum( 1.20 );
		$this->set_slug( self::SLUG );

		$this->client = new Pronamic_Gateways_Mollie_IDeal_Mollie( $config->partner_id );
		$this->client->set_test_mode( $config->mode == Pronamic_IDeal_IDeal::MODE_TEST );
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
	 * @param Pronamic_Pay_PaymentDataInterface $data
	 * @see Pronamic_Gateways_Gateway::start()
	 */
	public function start( Pronamic_Pay_PaymentDataInterface $data, Pronamic_Pay_Payment $payment ) {
		$result = $this->client->create_payment(
			$data->get_issuer_id(),
			Pronamic_WP_Util::amount_to_cents( $data->get_amount() ),
			$data->get_description(),
			add_query_arg( 'payment', $payment->get_id(), home_url( '/' ) ),
			add_query_arg( 'payment', $payment->get_id(), home_url( '/' ) )
		);
		
		if ( $result !== false ) {
			$payment->set_transaction_id( $result->transaction_id );
			$payment->set_action_url( $result->url );
			
		} else {
			$this->error = $this->client->get_error();
		}
	}

	/////////////////////////////////////////////////

	/**
	 * Update status of the specified payment
	 * 
	 * @param Pronamic_Pay_Payment $payment
	 */
	public function update_status( Pronamic_Pay_Payment $payment ) {
		$result = $this->client->check_payment( $payment->get_transaction_id() );

		if ( $result !== false ) {
			$consumer = $result->consumer;

			switch ( $result->status ) {
				case Pronamic_Gateways_Mollie_IDeal_Statuses::SUCCESS:
					$payment->set_consumer_name( $consumer->name );
					$payment->set_consumer_account_number( $consumer->account );
					$payment->set_consumer_city( $consumer->city );
				case Pronamic_Gateways_Mollie_IDeal_Statuses::CANCELLED:
				case Pronamic_Gateways_Mollie_IDeal_Statuses::EXPIRED:
				case Pronamic_Gateways_Mollie_IDeal_Statuses::FAILURE:
					$payment->set_status( $result->status );

					break;
				case Pronamic_Gateways_Mollie_IDeal_Statuses::CHECKED_BEFORE:
					// Nothing to do here

					break;
			}
		} else {
			$this->error = $this->client->get_error();
		}
	}
}
