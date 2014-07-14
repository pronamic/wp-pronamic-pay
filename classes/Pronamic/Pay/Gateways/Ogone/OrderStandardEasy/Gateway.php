<?php

/**
 * Title: Easy
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Pay_Gateways_Ogone_OrderStandardEasy_Gateway extends Pronamic_WP_Pay_Gateway {
	/**
	 * Construct and intialize an iDEAL Easy gateway
	 *
	 * @param Pronamic_Pay_Gateways_Ogone_OrderStandardEasy_Config $config
	 */
	public function __construct( Pronamic_Pay_Gateways_Ogone_OrderStandardEasy_Config $config ) {
		parent::__construct( $config );

		$this->set_method( Pronamic_WP_Pay_Gateway::METHOD_HTML_FORM );
		$this->set_has_feedback( false );
		$this->set_amount_minimum( 0.01 );

		$this->client = new Pronamic_Pay_Gateways_Ogone_OrderStandardEasy_Client();
		$this->client->setPaymentServerUrl( $config->url );
		$this->client->setPspId( $config->psp_id );
	}

	/////////////////////////////////////////////////

	/**
	 * Get output HTML
	 *
	 * @see Pronamic_WP_Pay_Gateway::get_output_html()
	 * @return string
	 */
	public function get_output_html() {
		return $this->client->getHtmlFields();
	}

	/////////////////////////////////////////////////

	/**
	 * Start transaction with the specified data
	 *
	 * @see Pronamic_WP_Pay_Gateway::start()
	 */
	public function start( Pronamic_Pay_PaymentDataInterface $data, Pronamic_Pay_Payment $payment ) {
		$payment->set_transaction_id( md5( time() . $data->get_order_id() ) );
		$payment->set_action_url( $this->client->getPaymentServerUrl() );

		$order_id = $data->get_order_id() . ' - ' . $payment->get_id();

		$this->client->setLanguage( $data->get_language_and_country() );
		$this->client->setCurrency( $data->get_currency() );
		$this->client->setOrderId( $order_id );
		$this->client->setDescription( $data->get_description() );
		$this->client->setAmount( $data->get_amount() );
		$this->client->setEMailAddress( $data->get_email() );
		$this->client->setCustomerName( $data->getCustomerName() );
		$this->client->setOwnerAddress( $data->getOwnerAddress() );
		$this->client->setOwnerCity( $data->getOwnerCity() );
		$this->client->setOwnerZip( $data->getOwnerZip() );

		$url = add_query_arg( 'payment', $payment->get_id(), home_url( '/' ) );

		$this->client->set_accept_url( add_query_arg( 'status', Pronamic_Gateways_IDealAdvancedV3_Status::SUCCESS, $url ) );
		$this->client->set_decline_url( add_query_arg( 'status', Pronamic_Gateways_IDealAdvancedV3_Status::FAILURE, $url ) );
		$this->client->set_exception_url( add_query_arg( 'status', Pronamic_Gateways_IDealAdvancedV3_Status::FAILURE, $url ) );
		$this->client->set_cancel_url( add_query_arg( 'status', Pronamic_Gateways_IDealAdvancedV3_Status::CANCELLED, $url ) );
		$this->client->set_back_url( home_url( '/' ) );
		$this->client->set_home_url( home_url( '/' ) );
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
