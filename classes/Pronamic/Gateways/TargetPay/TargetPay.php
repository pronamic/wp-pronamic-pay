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
	/**
	 * URL for issuers in Dutch language
	 * 
	 * @var string
	 */
	const URL_ISSUERS_NL = 'https://www.targetpay.com/ideal/issuers-nl.js';

	/**
	 * URL for issuers in English language
	 * 
	 * @var string
	 */
	const URL_ISSUERS_EN = 'https://www.targetpay.com/ideal/issuers-en.js';
	
	//////////////////////////////////////////////////

	/**
	 * URL for retrieving issuers in HTL format
	 * 
	 * @var string
	 */
	const URL_ISSUERS_HTML = 'https://www.targetpay.com/ideal/getissuers.php?format=html';
	
	/**
	 * URL for retrieving issuers in XML format
	 *
	 * @var string
	 */
	const URL_ISSUERS_XML = 'https://www.targetpay.com/ideal/getissuers.php?format=xml';
	
	//////////////////////////////////////////////////

	/**
	 * URL to start an transaction
	 * 
	 * @var string
	 */
	const URL_START_TRANSACTION = 'https://www.targetpay.com/ideal/start';

	/**
	 * URL to check an transaction
	 * 
	 * @var string
	 */
	const URL_CHECK_TRANSACTION = 'https://www.targetpay.com/ideal/check';
	
	//////////////////////////////////////////////////

	/**
	 * Token used by TargetPay to separate some values
	 * 
	 * @var string
	 */
	const TOKEN = ' |';
	
	//////////////////////////////////////////////////

	/**
	 * Status indicator for 'Ok'
	 * 
	 * @var string
	 */
	const STATUS_OK = '000000';

	/**
	 * Status indicator for 'No layout code'
	 * 
	 * @var string
	 */
	const STATUS_NO_LAYOUT_CODE = 'TP0001';
	
	//////////////////////////////////////////////////

	/**
	 * Error
	 * 
	 * @var WP_Error
	 */
	private $error;
	
	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an TargetPay client object
	 */
	public function __construct() {
		
	}
	
	//////////////////////////////////////////////////

	public function get_error() {
		return $this->error;
	}
	
	//////////////////////////////////////////////////

	private function remote_get( $url ) {
		return Pronamic_WP_Util::remote_get_body( $url, 200, array(
			'sslverify' => false
		) );
	}
	
	//////////////////////////////////////////////////

	/**
	 * Start transaction
	 * 
	 * @param string $rtlo
	 * @param string $bank
	 * @param string $description
	 * @param float $amount
	 * @param string $returnurl
	 * @param string $reporturl
	 * @param string $cinfo_in_callback https://www.targetpay.com/info/directdebit-docu
	 */
	public function start_transaction( $rtlo, $bank, $description, $amount, $returnurl, $reporturl, $cinfo_in_callback = 1 ) {
		$url = Pronamic_WP_Util::build_url(
			self::URL_START_TRANSACTION,
			array(
				'rtlo'              => $rtlo,
				'bank'              => $bank,
				'description'       => $description,
				'amount'            => Pronamic_WP_Util::amount_to_cents( $amount ),
				'returnurl'         => $returnurl,
				'reporturl'         => $reporturl,
				'cinfo_in_callback' => Pronamic_WP_Util::to_numeric_boolean( $cinfo_in_callback )
			)
		);

		$data = self::remote_get( $url );
		
		if ( $data !== false ) {
			$status = strtok( $data, self::TOKEN );

			if ( $status == self::STATUS_OK ) {
				$result = new stdClass();

				$result->status         = $status;
				$result->transaction_id = strtok( self::TOKEN );
				$result->url            = strtok( self::TOKEN );

				return $result;
			} else {
				$code        = $status;
				$description = substr( $data, 7 );

				$error = new Pronamic_Gateways_TargetPay_Error( $code, $description );

				$this->error = new WP_Error( 'targetpay_error', (string) $error, $error );
			}
		}
	}
	
	//////////////////////////////////////////////////

	/**
	 * Parse an TargetPay status string to an object
	 * 
	 * @param string $string an TargetPay status string
	 * @return stdClass
	 */
	public static function parse_status_string( $string ) {
		$status = new Pronamic_Gateways_TargetPay_Status();

		$position_space = strpos( $string, ' ' );
		$position_pipe  = strpos( $string, '|' );
		
		if ( $position_space !== false ) {
			/*
			 * @see https://www.targetpay.com/info/ideal-docu
			 *
			 * If the payment is valid the following response will be returned:
			 * 000000 OK
			 *
			 * If the payment is not valid (yet) the following response will be returned:
			 * TP0010 Transaction has not been completed, try again later
			 * TP0011 Transaction has been cancelled
			 * TP0012 Transaction has expired (max. 10 minutes)
			 * TP0013 The transaction could not be processed
			 * TP0014 Already used
			 *
			 * TP0020 Layoutcode not entered
			 * TP0021 Tansaction ID not entered
			 * TP0022 No transaction found with this ID
			 * TP0023 Layoutcode does not match this transaction
			 */
			$status->code = substr( $string, 0, $position_space );

			$position_description = $position_space + 1;
			if ( $position_pipe !== false ) {
				$length = $position_pipe - $position_description;

				$status->description = substr( $string, $position_description, $length );
			} else {
				$status->description = substr( $string, $position_description );
			}

			if ( $position_pipe !== false ) {
				$extra = substr( $string, $position_pipe + 1 );

				/*
				 * @see https://www.targetpay.com/info/directdebit-docu
				 *
				 * The response of the ideal/check call will be:
				 * 00000 OK|accountnumber|accountname|accountcity
				 * You may use accountnumber and accountname as input for the cbank and cname parameters
				 */
				$status->account_number = strtok( $extra, self::TOKEN );
				$status->account_name   = strtok( self::TOKEN );
				$status->account_city   = strtok( self::TOKEN );
			}
		}

		return $status;
	}
	
	//////////////////////////////////////////////////

	/**
	 * Check status
	 * 
	 * @param string $rtlo
	 * @param string $transaction_id
	 * @param string $once
	 * @param string $test
	 */
	public function check_status( $rtlo, $transaction_id, $once, $test ) {
		$result = null;

		$url = Pronamic_WP_Util::build_url(
			self::URL_CHECK_TRANSACTION,
			array(
				'rtlo'  => $rtlo,
				'trxid' => $transaction_id,
				'once'  => Pronamic_WP_Util::to_numeric_boolean( $once ),
				'test'  => Pronamic_WP_Util::to_numeric_boolean( $test )
			)
		);

		$data = self::remote_get( $url );

		if ( $data !== false ) {
			$result = self::parse_status_string( $data );
		}

		return $result;
	}
	
	//////////////////////////////////////////////////

	/**
	 * Get issuers
	 * 
	 * @return array
	 */
	public function get_issuers() {
		$issuers = false;

		$url = self::URL_ISSUERS_XML;

		$data = self::remote_get( $url );
		
		if ( $data !== false ) {	
			$xml = Pronamic_WP_Util::simplexml_load_string( $data );
				
			if ( is_wp_error( $xml ) ) {
				$this->error = $xml;
			} else {
				$issuers = array();

				foreach ( $xml->issuer as $xml_issuer ) {
					$id   = Pronamic_XML_Util::filter( $xml_issuer['id'] );
					$name = Pronamic_XML_Util::filter( $xml_issuer );
	
					$issuers[$id] = $name;
				}
			}
		}

		return $issuers;
	}
}
