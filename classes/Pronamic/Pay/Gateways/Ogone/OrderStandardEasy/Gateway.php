<?php

/**
 * Title: Easy
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Pay_Gateways_Ogone_OrderStandardEasy_Gateway extends Pronamic_Gateways_Gateway {
	/**
	 * Construct and intialize an iDEAL Easy gateway
	 *  
	 * @param Pronamic_Pay_Gateways_Ogone_OrderStandardEasy_Config $config
	 */
	public function __construct( Pronamic_Pay_Gateways_Ogone_OrderStandardEasy_Config $config ) {
		parent::__construct( $config );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTML_FORM );
		$this->set_has_feedback( false );
		$this->set_amount_minimum( 0.01 );

		$this->client = new Pronamic_Pay_Gateways_Ogone_OrderStandardEasy_Client();
		$this->client->setPaymentServerUrl( $config->url );
		$this->client->setPspId( $config->psp_id );
	}
	
	/////////////////////////////////////////////////

	/**
	 * Start transaction with the specified data
	 * 
	 * @see Pronamic_Gateways_Gateway::start()
	 */
	public function start( Pronamic_Pay_PaymentDataInterface $data ) {
		$this->set_transaction_id( md5( time() . $data->getOrderId() ) );
		$this->set_action_url( $this->client->getPaymentServerUrl() );

		$this->client->setLanguage( $data->getLanguageIso639AndCountryIso3166Code() );
		$this->client->setCurrency( $data->getCurrencyAlphabeticCode() );
		$this->client->setOrderId( $data->getOrderId() );
		$this->client->setDescription( $data->getDescription() );
		$this->client->setAmount( $data->getAmount() );
		$this->client->setEMailAddress( $data->getEMailAddress() );
		$this->client->setCustomerName( $data->getCustomerName() );
		$this->client->setOwnerAddress( $data->getOwnerAddress() );
		$this->client->setOwnerCity( $data->getOwnerCity() );
		$this->client->setOwnerZip( $data->getOwnerZip() );

		$url = add_query_arg(
			array(
				'gateway'        => 'ideal_easy',
				'transaction_id' => $this->get_transaction_id()
			),
			home_url( '/' )
		);
		
		$this->client->set_accept_url( add_query_arg( 'status', Pronamic_Gateways_IDealAdvancedV3_Status::SUCCESS, $url ) );
		$this->client->set_decline_url( add_query_arg( 'status', Pronamic_Gateways_IDealAdvancedV3_Status::FAILURE, $url ) );
		$this->client->set_exception_url( add_query_arg( 'status', Pronamic_Gateways_IDealAdvancedV3_Status::FAILURE, $url ) );
		$this->client->set_cancel_url( add_query_arg( 'status', Pronamic_Gateways_IDealAdvancedV3_Status::CANCELLED, $url ) );
		$this->client->set_back_url( home_url( '/' ) );
		$this->client->set_home_url( home_url( '/' ) );
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
}
