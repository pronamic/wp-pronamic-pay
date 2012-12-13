<?php

/**
 * Title: Basic
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvanced_Gateway extends Pronamic_Gateways_Gateway {
	public function __construct( $configuration ) {
		parent::__construct();

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTTP_REDIRECT );
		$this->set_has_feedback( true );
		$this->set_require_issue_select( true );
		$this->set_amount_minimum( 0.01 );

		// Client
		$client = new Pronamic_Gateways_IDealAdvanced_Client();
		$client->setAcquirerUrl( $configuration->getPaymentServerUrl() );
		$client->setAcquirerUrl($configuration->getPaymentServerUrl());
		$client->setPrivateKey($configuration->privateKey);
		$client->setPrivateKeyPassword($configuration->privateKeyPassword);
		$client->setPrivateCertificate($configuration->privateCertificate);

		$variant = $configuration->getVariant();
		foreach($variant->certificates as $certificate) {
			$client->addPublicCertificate($certificate);
		}
		
		$this->client = $client;
		$this->configuration = $configuration;	
	}
	
	/////////////////////////////////////////////////

	public function get_issuers() {
		$client = $this->client;
		$configuration = $this->configuration;
			
		$message = new Pronamic_Gateways_IDealAdvanced_XML_DirectoryRequestMessage();
		$merchant = $message->getMerchant();
		$merchant->id = $configuration->getMerchantId();
		$merchant->subId = $configuration->getSubId();
		$merchant->authentication = Pronamic_IDeal_IDeal::AUTHENTICATION_SHA1_RSA;
		$merchant->token = Pronamic_Gateways_IDealAdvanced_Security::getShaFingerprint($configuration->privateCertificate);
		$message->sign($configuration->privateKey, $configuration->privateKeyPassword);

		$result = $client->getIssuerLists($message);

		$lists = null;
		
		if($result !== null) {
			$lists = $result;
		} elseif($error = $client->getError()) {
			$this->error = $error;
		}
		
		return $lists;
	}
	
	/////////////////////////////////////////////////

	public function get_issuer_field() {
		$choices = array();
		
		$list = $this->get_issuers();
		
		foreach( $list as $name => $issuers ) {
			$options = array();

			foreach ( $issuers as $issuer ) {
				$options[$issuer->getId()] = $issuer->getName(); 
			}
	
			$choices[] = array(
				'name'    => ( $name == 'Long' ) ? __( '&mdash; Other banks &mdash;', 'pronamic_ideal' ) : false,
				'options' => $options
			);
		}
		
		return array(
			'id'       => 'pronamic_ideal_issuer_id',
			'name'     => 'pronamic_ideal_issuer_id',
			'label'    => __( 'Choose your bank', 'pronamic_ideal' ),
			'required' => true,
			'type'     => 'select',
			'choices'  => $choices
		);
	}
	
	/////////////////////////////////////////////////

	public function get_input_fields() {
		$fields = array();
		
		$fields[] = $this->get_issuer_field(); 

		return $fields;
	}
}
