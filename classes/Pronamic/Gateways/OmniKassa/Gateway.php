<?php

/**
 * Title: OmniKassa
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_OmniKassa_Gateway extends Pronamic_Gateways_Gateway {
	public function __construct( $configuration, $data_proxy ) {
		parent::__construct(  );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTML_FORM );
		$this->set_require_issue_select( false );
		$this->set_amount_minimum( 0.01 );

		$this->client = new Pronamic_Gateways_OmniKassa_OmniKassa();
		
		$this->client->setPaymentServerUrl( $configuration->getPaymentServerUrl() );
		$this->client->setMerchantId( $configuration->getMerchantId() );
		$this->client->setKeyVersion( $configuration->getSubId() );
		$this->client->setSecretKey( $configuration->getHashKey() );
		
		$this->client->setCustomerLanguage( $data_proxy->getLanguageIso639Code() );
		$this->client->setCurrencyNumericCode( $data_proxy->getCurrencyNumericCode() );
		$this->client->setOrderId( $data_proxy->getOrderId() );
		$this->client->setNormalReturnUrl( site_url('/') );
		$this->client->setAutomaticResponseUrl( site_url('/') );
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
