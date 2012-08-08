<?php 

/**
 * Title: AppThemes iDEAL Gateway
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_AppThemes_IDeal_IDealGateway extends APP_Gateway {
	/**
	 * Constructs and initialize the iDEAL gateway for AppThemes
	 */
	public function __construct() {
		parent::__construct( 'pronamic_ideal', __( 'Pronamic iDEAL', 'pronamic_ideal' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Returns an array representing the form to output for admin configuration
	 */
	public function form() {
		
	}

	//////////////////////////////////////////////////

	/**
	 * Processes a payment using this Gateway
	 */
	public function process( $order, $options ) {
		
	}
}
