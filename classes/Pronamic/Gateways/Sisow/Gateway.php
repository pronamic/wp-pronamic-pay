<?php

/**
 * Title: Sisow
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Sisow_Gateway extends Pronamic_Gateways_Gateway {
	public function __construct( $configuration ) {
		parent::__construct( $configuration );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTTP_REDIRECT );
		$this->set_has_feedback( true );
		$this->set_amount_minimum( 0.01 );

		$this->client = new Pronamic_Gateways_Sisow_Sisow( $configuration->sisowMerchantId, $configuration->sisowMerchantKey );
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

		$result = $this->client->get_directory();

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
	public function start( Pronamic_Pay_PaymentDataInterface $data ) {
		$result = $this->client->create_transaction(
			$data->get_issuer_id(),
			$data->getOrderId(),
			$data->getAmount(),
			$data->getDescription(),
			$data->get_entrance_code(),
			add_query_arg( 'gateway', 'sisow', home_url( '/' ) )
		);

		if ( $result !== false ) {
			$this->set_transaction_id( $result->id );
			$this->set_action_url( $result->issuer_url );
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
		$result = $this->client->get_status( $payment->transaction_id );

		if ( $result !== false ) {
			$transaction = $result;

			$payment->status                  = $transaction->status;
			$payment->consumer_name           = $transaction->consumer_name;
			$payment->consumer_account_number = $transaction->consumer_account;
			$payment->consumer_city           = $transaction->consumer_city;
		} else {
			$this->error = $this->client->get_error();
		}
	}
}
