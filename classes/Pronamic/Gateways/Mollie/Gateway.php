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
	public function __construct( $configuration ) {
		parent::__construct( $configuration );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTTP_REDIRECT );
		$this->set_has_feedback( false );
		$this->set_amount_minimum( 1.20 );

		$this->client = new Pronamic_Gateways_Mollie_Mollie( $configuration->molliePartnerId );
	}
	
	/////////////////////////////////////////////////

	public function get_issuers() {
		return $this->client->getBanks();
	}
	
	/////////////////////////////////////////////////

	public function start( $data ) {
		$this->client->createPayment(
			$data->getIssuerId(),
			$data->getAmount(),
			$data->getDescription(),
			$data->getReturnUrl(),
			site_url( '/' )
		);
		
		$this->transaction_id = $this->client->getTransactionId();
		$this->action_url     = $this->client->getBankURL();
	}
}
