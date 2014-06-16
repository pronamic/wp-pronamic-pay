<?php

/**
 * Title: OmniKassa listener
* Description:
* Copyright: Copyright (c) 2005 - 2011
* Company: Pronamic
* @author Remco Tolsma
* @version 1.0
*/
class Pronamic_Pay_Gateways_Ogone_Listener implements Pronamic_Pay_Gateways_ListenerInterface {
	public static function listen() {
		$method = filter_input( INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING );

		$data = array();

		switch ( $method ) {
			case 'GET':
				$data = $_GET;

				break;
			case 'POST':
				$data = $_POST;

				break;
		}

		$data = array_change_key_case( $data, CASE_UPPER );

		if ( isset(
			$data[ Pronamic_Pay_Gateways_Ogone_Parameters::NC_ERROR ],
			$data['PAYID'],
			$data[ Pronamic_Pay_Gateways_Ogone_Parameters::ORDERID ],
			$data[ Pronamic_Pay_Gateways_Ogone_Parameters::STATUS ]
		) ) {

		}
	}
}
