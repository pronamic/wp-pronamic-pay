<?php

/**
 * Title: TargetPay
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_TargetPay_Gateway extends Pronamic_Gateways_Gateway {
	/**
	 * Constructs and initializes an TargetPay gateway
	 * 
	 * @param Pronamic_WordPress_IDeal_Configuration $configuration
	 */
	public function __construct( Pronamic_WordPress_IDeal_Configuration $configuration ) {
		parent::__construct( $configuration );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTTP_REDIRECT );
		$this->set_has_feedback( true );
		$this->set_amount_minimum( 0.01 );

		$this->client = new Pronamic_Gateways_TargetPay_TargetPay();
	}
	
	/////////////////////////////////////////////////

	/**
	 * Get issuers
	 * 
	 * @see Pronamic_Gateways_Gateway::get_issuers()
	 */
	public function get_issuers() {
		return $this->client->get_issuers();
	}
	
	/////////////////////////////////////////////////

	/**
	 * Start
	 * 
	 * @see Pronamic_Gateways_Gateway::start()
	 */
	public function start( $data ) {
		$result = $this->client->start_transaction(
			$this->configuration->targetPayLayoutCode,
			$data->get_issuer_id(),
			$data->getDescription(),
			$data->getAmount(),
			$data->getReturnUrl(),
			site_url( '/' )
		);
		
		$this->set_action_url( $result->url );
		$this->set_transaction_id( $result->transaction_id );
	}
}
