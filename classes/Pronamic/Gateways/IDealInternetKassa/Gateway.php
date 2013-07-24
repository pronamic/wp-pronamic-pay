<?php

/**
 * Title: Easy
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealInternetKassa_Gateway extends Pronamic_Gateways_Gateway {
	/**
	 * Slug of this gateway
	 * 
	 * @var string
	 */
	const SLUG = 'internetkassa';

	/////////////////////////////////////////////////

	/**
	 * Constructs and initializes an InternetKassa gateway
	 * 
	 * @param Pronamic_WordPress_IDeal_Configuration $configuration
	 */
	public function __construct( Pronamic_WordPress_IDeal_Configuration $configuration ) {
		parent::__construct( $configuration );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTML_FORM );
		$this->set_has_feedback( true );
		$this->set_amount_minimum( 0.01 );
		$this->set_slug( self::SLUG );

		$this->client = new Pronamic_Gateways_IDealInternetKassa_IDealInternetKassa();

		$this->client->setPaymentServerUrl( $configuration->getPaymentServerUrl() );
		$this->client->setPspId( $configuration->pspId );
		$this->client->setPassPhraseIn( $configuration->shaInPassPhrase );
		$this->client->setPassPhraseOut( $configuration->shaOutPassPhrase );
	}

	/////////////////////////////////////////////////

	/**
	 * Start
	 * 
	 * @param Pronamic_IDeal_IDealDataProxy $data
	 * @see Pronamic_Gateways_Gateway::start()
	 */
	public function start( Pronamic_IDeal_IDealDataProxy $data ) {
		$this->set_transaction_id( md5( time() . $data->getOrderId() ) );
		$this->set_action_url( $this->client->getPaymentServerUrl() );

		$this->client->setLanguage( $data->getLanguageIso639AndCountryIso3166Code() );
		$this->client->setCurrency( $data->getCurrencyAlphabeticCode() );
		$this->client->setOrderId( $data->getOrderId() );
		$this->client->setOrderDescription( $data->getDescription() );
		$this->client->setAmount( $data->getAmount() );
		
		$this->client->setCustomerName( $data->getCustomerName() );
		$this->client->setEMailAddress( $data->getEMailAddress() );

		$url = add_query_arg(
			array(
				'gateway'        => 'internetkassa',
				'transaction_id' => $this->get_transaction_id()
			),
			home_url( '/' )
		);

		$this->client->setAcceptUrl( add_query_arg( 'status', 'accept', $url ) );
		$this->client->setCancelUrl( add_query_arg( 'status', 'cancel', $url ) );
		$this->client->setDeclineUrl( add_query_arg( 'status', 'decline', $url ) );
		$this->client->setExceptionUrl( add_query_arg( 'status', 'exception', $url ) );
	}
	
	/////////////////////////////////////////////////

	/**
	 * Get output HTML
	 * 
	 * @see Pronamic_Gateways_Gateway::get_output_html()
	 */
	public function get_output_html() {
		return $this->client->getHtmlFields();
	}
}
