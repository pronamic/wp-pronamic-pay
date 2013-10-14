<?php

/**
 * Title: Qantani gateway
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
	 * Constructs and initializes an Qantani gateway
	 *
	 * @param Pronamic_Gateways_Qantani_Config $config
	 */
	public function __construct( Pronamic_Gateways_Qantani_Config $config ) {
		parent::__construct( $config );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTTP_REDIRECT );
		$this->set_has_feedback( false );
		$this->set_amount_minimum( 1.20 );
		$this->set_slug( self::SLUG );

		$this->client = new Pronamic_Gateways_Qantani_Qantani();
		$this->client->set_merchant_id( $config->merchant_id );
		$this->client->set_merchant_key( $config->merchant_key );
		$this->client->set_merchant_secret( $config->merchant_secret );
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
	 * @param Pronamic_Pay_PaymentDataInterface $data
	 * @see Pronamic_Gateways_Gateway::start()
	 */
	public function start( Pronamic_Pay_PaymentDataInterface $data, Pronamic_Pay_Payment $payment ) {
		$result = $this->client->create_transaction(
			$data->getAmount(),
			$data->getCurrencyAlphabeticCode(),
			$data->get_issuer_id(),
			$data->getDescription(),
			add_query_arg( 'payment', $payment->id, home_url( '/' ) )
		);

		if ( $result !== false ) {
			$this->set_transaction_id( $result->transaction_id );
			$this->set_action_url( $result->bank_url );
			
			update_post_meta( $payment->id, '_pronamic_payment_authentication_url', $result->bank_url );
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

	}
}
