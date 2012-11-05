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
	public function __construct( $configuration, $data_proxy ) {
		parent::__construct(  );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTML_FORM );
		$this->set_require_issue_select( false );
		$this->set_amount_minimum( 0.01 );

		$this->client = new Pronamic_Gateways_IDealEasy_IDealEasy();

		$this->client->setPaymentServerUrl( $configuration->getPaymentServerUrl() );
		$this->client->setPspId( $configuration->pspId );

		$this->client->setLanguage( $data_proxy->getLanguageIso639AndCountryIso3166Code() );
		$this->client->setCurrency( $data_proxy->getCurrencyAlphabeticCode() );
		$this->client->setOrderId( $data_proxy->getOrderId() );
		$this->client->setDescription( $data_proxy->getDescription() );
		$this->client->setAmount( $data_proxy->getAmount() );
		$this->client->setEMailAddress( $data_proxy->getEMailAddress() );
		$this->client->setCustomerName( $data_proxy->getCustomerName() );
		$this->client->setOwnerAddress( $data_proxy->getOwnerAddress() );
		$this->client->setOwnerCity( $data_proxy->getOwnerCity() );
		$this->client->setOwnerZip( $data_proxy->getOwnerZip() );
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
