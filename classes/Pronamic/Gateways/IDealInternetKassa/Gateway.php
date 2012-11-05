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
	public function __construct( $configuration, $data_proxy ) {
		parent::__construct(  );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTML_FORM );
		$this->set_require_issue_select( false );
		$this->set_amount_minimum( 0.01 );
		
		$this->client = new Pronamic_Gateways_IDealInternetKassa_IDealInternetKassa();

		$file = dirname( Pronamic_WordPress_IDeal_Plugin::$file ) . '/other/calculations-parameters-sha-in.txt';
		$this->client->setCalculationsParametersIn( file( $file, FILE_IGNORE_NEW_LINES ) );
	
		$file = dirname( Pronamic_WordPress_IDeal_Plugin::$file ) . '/other/calculations-parameters-sha-out.txt';
		$this->client->setCalculationsParametersOut( file( $file, FILE_IGNORE_NEW_LINES ) );

		$this->client->setPaymentServerUrl( $configuration->getPaymentServerUrl() );
		$this->client->setPspId( $configuration->pspId );
		$this->client->setPassPhraseIn( $configuration->shaInPassPhrase );
		$this->client->setPassPhraseOut( $configuration->shaOutPassPhrase );
		
		$this->client->setLanguage( $data_proxy->getLanguageIso639AndCountryIso3166Code() );
		$this->client->setCurrency( $data_proxy->getCurrencyAlphabeticCode() );
		$this->client->setOrderDescription( $data_proxy->getDescription() );
		$this->client->setAmount( $data_proxy->getAmount() );
	}
	
	/////////////////////////////////////////////////

	public function get_action_url() {
		return $this->client->getPaymentServerUrl();
	}
	
	/////////////////////////////////////////////////

	public function get_html_fields() {
		return $this->client->getHtmlFields();
	}
}
