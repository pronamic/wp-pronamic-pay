<?php

/**
 * Title: Easy
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealEasy_Gateway extends Pronamic_Gateways_Gateway {
	public function __construct( $configuration, $data ) {
		parent::__construct(  );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTML_FORM );
		$this->set_require_issue_select( false );
		$this->set_amount_minimum( 0.01 );

		$this->data = $data;

		$this->client = new Pronamic_Gateways_IDealEasy_IDealEasy();

		$this->client->setPaymentServerUrl( $configuration->getPaymentServerUrl() );
		$this->client->setPspId( $configuration->pspId );
	}
	
	/////////////////////////////////////////////////

	public function start() {
		$this->transaction_id = md5( time() . $this->data->getOrderId() );
		$this->action_url     = $this->client->getPaymentServerUrl();

		$this->client->setLanguage( $this->data->getLanguageIso639AndCountryIso3166Code() );
		$this->client->setCurrency( $this->data->getCurrencyAlphabeticCode() );
		$this->client->setOrderId( $this->data->getOrderId() );
		$this->client->setDescription( $this->data->getDescription() );
		$this->client->setAmount( $this->data->getAmount() );
		$this->client->setEMailAddress( $this->data->getEMailAddress() );
		$this->client->setCustomerName( $this->data->getCustomerName() );
		$this->client->setOwnerAddress( $this->data->getOwnerAddress() );
		$this->client->setOwnerCity( $this->data->getOwnerCity() );
		$this->client->setOwnerZip( $this->data->getOwnerZip() );
	}
	
	/////////////////////////////////////////////////

	public function get_html_fields() {
		return $this->client->getHtmlFields();
	}
}
