<?php

/**
 * Title: PayDutch gateway
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_PayDutch_Gateway extends Pronamic_Gateways_Gateway {
	/**
	 * Slug of this gateway
	 * 
	 * @var string
	 */
	const SLUG = 'paydutch';	
	
	/////////////////////////////////////////////////

	/**
	 * Constructs and initializes an PayDutch gateway
	 * 
	 * @param Pronamic_Gateways_PayDutch_Config $config
	 */
	public function __construct( Pronamic_Gateways_PayDutch_Config $config ) {
		parent::__construct( $config );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTTP_REDIRECT );
		$this->set_has_feedback( true );
		$this->set_amount_minimum( 1.20 );
		$this->set_slug( self::SLUG );

		$this->client = new Pronamic_Gateways_PayDutch_PayDutch( $config->username, $config->password );
	}
	
	/////////////////////////////////////////////////

	/**
	 * Get issuers
	 * 
	 * @see Pronamic_Gateways_Gateway::get_issuers()
	 */
	public function get_issuers() {
		$groups = array();

		$result = $this->client->get_bank_list();

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
		$transaction_request = $this->client->get_transaction_request();

		$transaction_request->reference   = $payment->get_id();
		$transaction_request->description = $data->get_description();
		$transaction_request->amount      = $data->get_amount();
		$transaction_request->method_code = Pronamic_Gateways_PayDutch_Methods::WEDEAL;
		$transaction_request->issuer_id   = $data->get_issuer_id();
		$transaction_request->test        = true;
		$transaction_request->success_url = add_query_arg( 'payment', $payment->get_id(), home_url( '/' ) );
		$transaction_request->fail_url    = add_query_arg( 'payment', $payment->get_id(), home_url( '/' ) );

		$result = $this->client->request_transaction( $transaction_request );

		if ( $result !== false ) {		
			$payment->set_action_url( $result->url );
			$payment->set_transaction_id( $result->id );
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
		$result = $this->client->get_payment_status( $payment->get_id() );

		if ( $result ) {
			$payment->set_status( Pronamic_Gateways_PayDutch_PayDutch::transform_state( $result->state ) );
			$payment->set_consumer_name( $result->consumername );
			$payment->set_consumer_account_number( $result->consumeraccount );
			$payment->set_consumer_city( $result->consumercity );
			// $payment->set_consumer_country( $result->consumercountry );
		} else {
			$this->error = $this->client->get_error();
		}
	}
}
