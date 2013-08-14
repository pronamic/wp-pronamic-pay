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
		$order_response = false;

		$result = Pronamic_WordPress_Util::remote_get_body( Pronamic_Gateways_Ogone_DirectLink::API_URL, 200, array(
			'method'    => 'POST',
			'sslverify' => false,
			'body'      => array(
					'data' => $data
			)
		) );
		
		if ( is_wp_error( $result ) ) {
			$this->error = $result;
		} else {
			$xml = Pronamic_WordPress_Util::simplexml_load_string( $result );
		
			if ( is_wp_error( $xml ) ) {
				$this->error = $xml;
			} else {
				$order_response = Pronamic_Gateways_Ogone_XML_OrderResponseParser::parse( $xml );
				
				if ( ! empty( $order_response->nc_error ) ) {
					$ogone_error = new Pronamic_Gateways_Ogone_Error();
					$ogone_error->code        = $order_response->nc_error;
					$ogone_error->explanation = $order_response->nc_error_plus;
					
					$this->error = new WP_Error( 'ogone_error', (string) $ogone_error, $ogone_error );
				}
			}
		}

		echo '<pre>';
		var_dump( $order_response );
		echo '</pre>';
		
		echo '<pre>';
		echo htmlspecialchars( $result );
		echo '</pre>';
		
		echo '<pre>';
		echo $this->error->get_error_message();
		echo '</pre>';
	}
}
