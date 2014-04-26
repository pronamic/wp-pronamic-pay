<?php

/**
 * Title: Basic
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealBasic_Gateway extends Pronamic_Gateways_Gateway {
	/**
	 * Construct and intialize an gateway
	 *
	 * @param Pronamic_Pay_Gateways_IDealBasic_Config $config
	 */
	public function __construct( Pronamic_Pay_Gateways_IDealBasic_Config $config ) {
		parent::__construct( $config );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTML_FORM );
		$this->set_has_feedback( false );
		$this->set_amount_minimum( 0.01 );

		$this->client = new Pronamic_Gateways_IDealBasic_IDealBasic();

		$this->client->setPaymentServerUrl( $config->url );
		$this->client->setMerchantId( $config->merchant_id );
		$this->client->setSubId( $config->sub_id );
		$this->client->setHashKey( $config->hash_key );
	}

	/////////////////////////////////////////////////

	/**
	 * Get output HTML
	 *
	 * @see Pronamic_Gateways_Gateway::get_output_html()
	 * @return string
	 */
	public function get_output_html() {
		return $this->client->getHtmlFields();
	}

	/////////////////////////////////////////////////

	/**
	 * Start an transaction with the specified data
	 *
	 * @see Pronamic_Gateways_Gateway::start()
	 */
	public function start( Pronamic_Pay_PaymentDataInterface $data, Pronamic_Pay_Payment $payment ) {
		$payment->set_transaction_id( md5( time() . $data->get_order_id() ) );
		$payment->set_action_url( $this->client->getPaymentServerUrl() );

		$this->client->setLanguage( $data->get_language() );
		$this->client->setCurrency( $data->get_currency() );
		$this->client->setPurchaseId( $data->get_order_id() );
		$this->client->setDescription( $data->get_description() );
		$this->client->setItems( $data->get_items() );

		$url = add_query_arg( 'payment', $payment->get_id(), home_url( '/' ) );

		$this->client->setCancelUrl( add_query_arg( 'status', Pronamic_Pay_Gateways_IDeal_Statuses::CANCELLED, $url ) );
		$this->client->setSuccessUrl( add_query_arg( 'status', Pronamic_Pay_Gateways_IDeal_Statuses::SUCCESS, $url ) );
		$this->client->setErrorUrl( add_query_arg( 'status', Pronamic_Pay_Gateways_IDeal_Statuses::FAILURE, $url ) );
	}

	/////////////////////////////////////////////////

	/**
	 * Update status of the specified payment
	 *
	 * @param Pronamic_Pay_Payment $payment
	 */
	public function update_status( Pronamic_Pay_Payment $payment ) {
		if ( filter_has_var( INPUT_GET, 'status' ) ) {
			$status = filter_input( INPUT_GET, 'status', FILTER_SANITIZE_STRING );

			$payment->set_status( $status );
		}
	}
}
