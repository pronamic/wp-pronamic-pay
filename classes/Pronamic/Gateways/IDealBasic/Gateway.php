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
	public function __construct( $configuration, $data_proxy ) {
		parent::__construct();

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTML_FORM );
		$this->set_require_issue_select( false );
		$this->set_amount_minimum( 0.01 );

		$this->client = new Pronamic_Gateways_IDealBasic_IDealBasic();
		
		$this->client->setPaymentServerUrl( $configuration->getPaymentServerUrl() );
		$this->client->setMerchantId( $configuration->getMerchantId() );
		$this->client->setSubId( $configuration->getSubId() );
		$this->client->setHashKey( $configuration->hashKey );
		
		$this->client->setLanguage( $data_proxy->getLanguageIso639Code() );
		$this->client->setCurrency( $data_proxy->getCurrencyAlphabeticCode() );
		$this->client->setPurchaseId( $data_proxy->getOrderId() );
		$this->client->setDescription( $data_proxy->getDescription() );
		$this->client->setItems( $data_proxy->getItems() );
		$this->client->setCancelUrl( $data_proxy->getCancelUrl() );
		$this->client->setSuccessUrl( $data_proxy->getSuccessUrl() );
		$this->client->setErrorUrl( $data_proxy->getErrorUrl() );
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
