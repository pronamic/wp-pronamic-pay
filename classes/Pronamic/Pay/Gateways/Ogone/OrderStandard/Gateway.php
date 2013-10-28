<?php

/**
 * Title: Ogone order standard gateway
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Pay_Gateways_Ogone_OrderStandard_Gateway extends Pronamic_Gateways_Gateway {
	/**
	 * Slug of this gateway
	 * 
	 * @var string
	 */
	const SLUG = 'ogone_orderstandard';
	
	/////////////////////////////////////////////////

	/**
	 * Get output HTML
	 * 
	 * @see Pronamic_Gateways_Gateway::get_output_html()
	 */
	public function get_output_html() {
		return $this->client->getHtmlFields();
	}

	/////////////////////////////////////////////////

	/**
	 * Constructs and initializes an InternetKassa gateway
	 * 
	 * @param Pronamic_WordPress_IDeal_Configuration $config
	 */
	public function __construct( Pronamic_Pay_Gateways_Ogone_OrderStandard_Config $config ) {
		parent::__construct( $config );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTML_FORM );
		$this->set_has_feedback( true );
		$this->set_amount_minimum( 0.01 );
		$this->set_slug( self::SLUG );

		$this->client = new Pronamic_Pay_Gateways_Ogone_OrderStandard_Client();

		$this->client->setPaymentServerUrl( $config->url );
		$this->client->setPspId( $config->psp_id );
		$this->client->setPassPhraseIn( $config->sha_in_pass_phrase );
		$this->client->setPassPhraseOut( $config->sha_out_pass_phrase );
		
		if ( ! empty( $config->hash_algorithm ) ) {
			$this->client->set_hash_algorithm( $config->hash_algorithm );
		}
	}

	/////////////////////////////////////////////////

	/**
	 * Start
	 * 
	 * @param Pronamic_Pay_PaymentDataInterface $data
	 * @see Pronamic_Gateways_Gateway::start()
	 */
	public function start( Pronamic_Pay_PaymentDataInterface $data, Pronamic_Pay_Payment $payment ) {
		$payment->set_transaction_id( md5( time() . $data->get_order_id() ) );
		$payment->set_action_url( $this->client->getPaymentServerUrl() );

		$this->client->setLanguage( $data->get_language_and_country() );
		$this->client->setCurrency( $data->get_currency() );
		$this->client->setOrderId( $data->get_order_id() );
		$this->client->setOrderDescription( $data->get_description() );
		$this->client->setAmount( $data->get_amount() );
		
		$this->client->setCustomerName( $data->getCustomerName() );
		$this->client->setEMailAddress( $data->get_email() );

		$url = add_query_arg( 'payment', $payment->get_id(), home_url( '/' ) );

		$this->client->setAcceptUrl( add_query_arg( 'status', 'accept', $url ) );
		$this->client->setCancelUrl( add_query_arg( 'status', 'cancel', $url ) );
		$this->client->setDeclineUrl( add_query_arg( 'status', 'decline', $url ) );
		$this->client->setExceptionUrl( add_query_arg( 'status', 'exception', $url ) );
	}
	
	/////////////////////////////////////////////////
	
	/**
	 * Update status of the specified payment
	 *
	 * @param Pronamic_Pay_Payment $payment
	 */
	public function update_status( Pronamic_Pay_Payment $payment ) {
		$inputs = array(
			INPUT_GET  => $_GET,
			INPUT_POST => $_POST
		);
			
		foreach ( $inputs as $input => $data ) {
			$data = $this->client->verifyRequest( $data );

			if ( $data !== false ) {
				$payment->set_status( Pronamic_Pay_Gateways_Ogone_Statuses::transform( $data[Pronamic_Pay_Gateways_Ogone_Parameters::STATUS] ) );
			}
		}
	}
}
