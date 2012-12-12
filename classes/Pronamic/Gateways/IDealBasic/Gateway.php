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
	public function __construct( $configuration ) {
		parent::__construct();

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTML_FORM );
		$this->set_has_feedback( false );
		$this->set_require_issue_select( false );
		$this->set_amount_minimum( 0.01 );

		$this->client = new Pronamic_Gateways_IDealBasic_IDealBasic();
		
		$this->client->setPaymentServerUrl( $configuration->getPaymentServerUrl() );
		$this->client->setMerchantId( $configuration->getMerchantId() );
		$this->client->setSubId( $configuration->getSubId() );
		$this->client->setHashKey( $configuration->hashKey );
	}
	
	/////////////////////////////////////////////////

	public function start() {
		$this->transaction_id = md5( time() . $this->data->getOrderId() );
		$this->action_url     = $this->client->getPaymentServerUrl();
		
		$this->client->setLanguage( $this->data->getLanguageIso639Code() );
		$this->client->setCurrency( $this->data->getCurrencyAlphabeticCode() );
		$this->client->setPurchaseId( $this->data->getOrderId() );
		$this->client->setDescription( $this->data->getDescription() );
		$this->client->setItems( $this->data->getItems() );
		$this->client->setCancelUrl( $this->data->getCancelUrl() );
		$this->client->setSuccessUrl( $this->data->getSuccessUrl() );
		$this->client->setErrorUrl( $this->data->getErrorUrl() );
	}
	
	/////////////////////////////////////////////////

	public function get_action_url() {
		return $this->client->getPaymentServerUrl();
	}
	
	/////////////////////////////////////////////////

	public function get_output_html() {
		return $this->client->getHtmlFields();
	}
}
