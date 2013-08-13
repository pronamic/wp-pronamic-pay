<?php

/**
 * Title: Ogone DirectLink
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Ogone_DirectLink_Client {
	/**
	 * Ogone DirectLink API endpoint URL
	 * 
	 * @var string
	 */
	const API_URL = 'https://secure.ogone.com/ncol/test/orderdirect.asp';

	/////////////////////////////////////////////////

	/**
	 * Constructs and initializes an Ogone DirectLink client
	 */
	public function __construct() {
		
	}

	/////////////////////////////////////////////////

	/**
	 * Order direct
	 * 
	 * @param array $data
	 */
	public function order_direct( array $data = array() ) {
		$response = wp_remote_post( 
			Pronamic_Gateways_Ogone_DirectLink::API_URL,
			array(
				'method'  => 'POST',
				'body'    => $data
			)
		);

		echo '<pre>';
		var_dump( $data );
		echo '</pre>';
		
		$body = wp_remote_retrieve_body( $response );
		
		echo '<pre>';
		echo htmlspecialchars( $body );
		echo '</pre>';
		
	}
}
