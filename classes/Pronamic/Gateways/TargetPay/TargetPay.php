<?php

/**
 * Title: TargetPay gateway
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_TargetPay_TargetPay {
	const URL_ISSUERS_NL = 'https://www.targetpay.com/ideal/issuers-nl.js';

	const URL_ISSUERS_EN = 'https://www.targetpay.com/ideal/issuers-en.js';
	
	//////////////////////////////////////////////////

	const URL_ISSUERS_HTML = 'https://www.targetpay.com/ideal/getissuers.php?format=html';

	const URL_ISSUERS_XML = 'https://www.targetpay.com/ideal/getissuers.php?format=xml';
	
	//////////////////////////////////////////////////

	const URL_START_TRANSACTION = 'https://www.targetpay.com/ideal/start';

	const URL_CHECK_TRANSACTION = 'https://www.targetpay.com/ideal/check';
	
	//////////////////////////////////////////////////

	const TOKEN = ' |';
	
	//////////////////////////////////////////////////

	const STATUS_OK = '000000';
	const STATUS_NO_LAYOUT_CODE = 'TP0001';
	
	//////////////////////////////////////////////////

	public function __construct() {
		
	}
	
	//////////////////////////////////////////////////

	public function start_transaction( $rtlo, $bank, $description, $amount, $returnurl, $reporturl ) {
		$url = add_query_arg( array(
			'rtlo'        => $rtlo,
			'bank'        => $bank,
			'description' => $description,
			'amount'       => $amount,
			'returnurl'   => $returnurl,
			'reporturl'   => $reporturl
		), self::URL_START_TRANSACTION );

		$response = wp_remote_get( $url );

		if ( wp_remote_retrieve_response_code( $response ) == 200 ) {
			$data = wp_remote_retrieve_body( $response );
	
			$status         = strtok( $data, self::TOKEN );
			$transaction_id = strtok( self::TOKEN );
			$url            = strtok( self::TOKEN );
	
			if ( $status == self::STATUS_OK ) {
				
			}
		}
	}
	
	//////////////////////////////////////////////////

	public function check_status( $rtlo, $transaction_id, $once, $test ) {
		$url = add_query_arg( array(
			'rtlo'  => $rtlo,
			'trxid' => $rtlo,
			'once'  => $once ? '1' : '0',
			'test'  => $test ? '1' : '0'
		), self::URL_CHECK_TRANSACTION );

		$response = wp_remote_get( $url );

		if ( wp_remote_retrieve_response_code( $response ) == 200 ) {
			$data = wp_remote_retrieve_body( $response );

			$postion_space = strpos( $data, ' ' );

			if ( $position_space !== false ) {
				$status      = substr( $data, 0, $postion_space );
				$description = substr( $data, $postion_space + 1 );
			}
		}
	}
	
	//////////////////////////////////////////////////
	
	public function get_issuers() {
		$issuers = false;

		$url = self::URL_ISSUERS_XML;

		$response = wp_remote_get( $url );

		if ( wp_remote_retrieve_response_code( $response ) == 200 ) {
			$data = wp_remote_retrieve_body( $response );

			$xml = simplexml_load_string( $data );
			
			if ( $xml !== false ) {
				$issuers = array();

				foreach ( $xml->issuer as $xml_issuer ) {
					$id   = (string) $xml_issuer['id'];
					$name = (string) $xml_issuer;

					$issuers[$id] = $name;
				}
			}
		}

		return $issuers;
	}
}
