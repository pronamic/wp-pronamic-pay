<?php

/**
 * Title: Mollie
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Mollie_Gateway extends Pronamic_Gateways_Gateway {
	public function __construct( $configuration, $data ) {
		parent::__construct(  );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTTP_REDIRECT );
		$this->set_require_issue_select( true );
		$this->set_amount_minimum( 1.20 );

		$this->data = $data;

		$this->client = new Pronamic_Gateways_Mollie_Mollie( $configuration->molliePartnerId );
	}
	
	/////////////////////////////////////////////////

	public function get_issuers() {
		return $this->client->getBanks();
	}
	
	/////////////////////////////////////////////////

	public function start() {
		$this->client->createPayment(
			$this->data->getIssuerId(),
			$this->data->getAmount(),
			$this->data->getDescription(),
			$this->data->getReturnUrl(),
			site_url( '/' )
		);
		
		$this->transaction_id = $this->client->getTransactionId();
		$this->action_url     = $this->client->getBankURL();
	}
}
