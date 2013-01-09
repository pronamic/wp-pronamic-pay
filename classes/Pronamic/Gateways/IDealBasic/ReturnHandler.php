<?php

/**
 * Title: Gateway return handler
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
abstract class Pronamic_Gateways_IDealBasic_ReturnHandler extends Pronamic_Gateways_ReturnHandler {
	public function listen() {
		if ( isset( $_GET['transaction_id'] ) && isset( $_GET['transaction_code'] ) && isset( $_GET['status'] ) ) {
			do_action( 'pronamic_ideal_basic_return_raw', $http_raw_post_data );
		}

		if ( isset( $_GET['xml_notifaction'] ) ) {
			$xml_notifaction = filter_input( INPUT_GET, 'xml_notifaction', FILTER_VALIDATE_BOOLEAN );
			
			if ( $xml_notifaction ) {
				$http_raw_post_data = file_get_contents( 'php://input');
				
				do_action( 'pronamic_ideal_basic_xml_return_raw', $http_raw_post_data );
			}
		}
	}

	public function returns( $data ) {
		
	}
}
