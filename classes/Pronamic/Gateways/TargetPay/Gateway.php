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
	private $client;

	public function __construct( $configuration ) {
		parent::__construct( );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTTP_REDIRECT );
		$this->set_require_issue_select( true );
		$this->set_amount_minimum( 0.01 );

		$this->client = new Pronamic_Gateways_TargetPay_TargetPay();
	}

	public function get_issuers() {
		return $this->client->get_issuers();
	}	
}
