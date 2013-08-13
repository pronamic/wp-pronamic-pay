<?php

/**
 * Title: Ogone
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Ogone_DirectLink_Gateway extends Pronamic_Gateways_Gateway {
	/**
	 * Slug of this gateway
	 * 
	 * @var string
	 */
	const SLUG = 'ogone-directlink';	
	
	/////////////////////////////////////////////////

	/**
	 * Constructs and initializes an Ogone DirectLink gateway
	 * 
	 * @param Pronamic_WordPress_IDeal_Configuration $configuration
	 */
	public function __construct( Pronamic_WordPress_IDeal_Configuration $configuration ) {
		parent::__construct( $configuration );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTTP_REDIRECT );
		$this->set_has_feedback( true );
		$this->set_amount_minimum( 1.20 );
		$this->set_slug( self::SLUG );

		
	}
}
