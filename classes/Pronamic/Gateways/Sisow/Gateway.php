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
	public function __construct( Pronamic_Gateways_Sisow_Config $config ) {
		parent::__construct( $config );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTTP_REDIRECT );
		$this->set_has_feedback( true );
		$this->set_amount_minimum( 0.01 );

		$this->client = new Pronamic_Gateways_Sisow_Sisow( $config->merchant_id, $config->merchant_key );
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
	public function start( Pronamic_Pay_PaymentDataInterface $data, Pronamic_Pay_Payment $payment ) {
		$result = $this->client->create_transaction(
			$data->get_issuer_id(),
			$data->getOrderId(),
			$data->getAmount(),
			$data->get_description(),
			$data->get_entrance_code(),
			add_query_arg( 'payment', $payment->id, home_url( '/' ) )
		);

		if ( $result !== false ) {
			$this->set_transaction_id( $result->id );
			$this->set_action_url( $result->issuer_url );
			
			update_post_meta( $payment->id, '_pronamic_payment_authentication_url', $result->issuer_url );
			update_post_meta( $payment->id, '_pronamic_payment_transaction_id', $result->id );
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
		$result = $this->client->get_status( $payment->get_transaction_id() );

		if ( $result !== false ) {
			$transaction = $result;

			update_post_meta( $payment->id, '_pronamic_payment_status', $transaction->status );
			update_post_meta( $payment->id, '_pronamic_payment_consumer_name', $transaction->consumer_name );
			update_post_meta( $payment->id, '_pronamic_payment_consumer_account_number', $transaction->consumer_account );
			update_post_meta( $payment->id, '_pronamic_payment_consumer_city', $transaction->consumer_city );
		} else {
			$this->error = $this->client->get_error();
		}
	}
}
