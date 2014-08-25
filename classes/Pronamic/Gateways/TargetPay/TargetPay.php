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
			'sslverify' => false,
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

		if ( false !== $data ) {
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

				$error = new Pronamic_WP_Pay_Gateways_TargetPay_Error( $code, $description );

				$this->error = new WP_Error( 'targetpay_error', (string) $error, $error );
			}
		}
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

		if ( false !== $data ) {
			$result = Pronamic_WP_Pay_Gateways_TargetPay_StatusStringParser::parse( $data );
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

		if ( false !== $data ) {
			$xml = Pronamic_WP_Util::simplexml_load_string( $data );

			if ( is_wp_error( $xml ) ) {
				$this->error = $xml;
			} else {
				$issuers = array();

				foreach ( $xml->issuer as $xml_issuer ) {
					$id   = Pronamic_XML_Util::filter( $xml_issuer['id'] );
					$name = Pronamic_XML_Util::filter( $xml_issuer );

					$issuers[ $id ] = $name;
				}
			}
		}

		return $issuers;
	}
}
