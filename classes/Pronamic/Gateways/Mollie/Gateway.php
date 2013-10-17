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
	 * @param Pronamic_Gateways_Mollie_Config $config
	 */
	public function __construct( Pronamic_Gateways_Mollie_Config $config ) {
		parent::__construct( $config );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTTP_REDIRECT );
		$this->set_has_feedback( true );
		$this->set_amount_minimum( 1.20 );
		$this->set_slug( self::SLUG );

		$this->client = new Pronamic_Gateways_Mollie_Mollie( $config->partner_id );
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
			Pronamic_WordPress_Util::amount_to_cents( $data->getAmount() ),
			$data->get_description(),
			add_query_arg( 'payment', $payment->id, home_url( '/' ) ),
			add_query_arg( 'payment', $payment->id, home_url( '/' ) )
		);
		
		if ( $result !== false ) {
			$this->set_transaction_id( $result->transaction_id );
			$this->set_action_url( $result->url );
			
			update_post_meta( $payment->id, '_pronamic_payment_authentication_url', $result->url );
			update_post_meta( $payment->id, '_pronamic_payment_transaction_id', $result->transaction_id );
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
				case Pronamic_Gateways_Mollie_Statuses::SUCCESS:
					update_post_meta( $payment->id, '_pronamic_payment_consumer_name', $consumer->name );
					update_post_meta( $payment->id, '_pronamic_payment_consumer_account_number', $consumer->account );
					update_post_meta( $payment->id, '_pronamic_payment_consumer_city', $consumer->city );
				case Pronamic_Gateways_Mollie_Statuses::CANCELLED:
				case Pronamic_Gateways_Mollie_Statuses::EXPIRED:
				case Pronamic_Gateways_Mollie_Statuses::FAILURE:
					update_post_meta( $payment->id, '_pronamic_payment_status', $result->status );

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
