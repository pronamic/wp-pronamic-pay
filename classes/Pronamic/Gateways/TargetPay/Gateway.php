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
	public function __construct( $configuration ) {
		parent::__construct( $configuration );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTTP_REDIRECT );
		$this->set_has_feedback( true );
		$this->set_require_issue_select( true );
		$this->set_amount_minimum( 0.01 );

		$this->configuration = $configuration;

		$this->client = new Pronamic_Gateways_TargetPay_TargetPay();
	}
	
	/////////////////////////////////////////////////

	public function get_issuers() {
		return $this->client->get_issuers();
	}
	
	/////////////////////////////////////////////////

	public function start( $data ) {
		$result = $this->client->start_transaction(
			$this->configuration->targetPayLayoutCode,
			$data->getIssuerId(),
			$data->getDescription(),
			$data->getAmount(),
			$data->getReturnUrl(),
			site_url( '/' )
		);
		
		$this->action_url     = $result->url;
		$this->transaction_id = $result->transaction_id;
	}
}
