<?php

/**
 * Title: Payment info parser
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_PayDutch_XML_PaymentInfoParser extends Pronamic_Gateways_PayDutch_XML_Parser {
	/**
	 * Parse the specified XML element into an iDEAL transaction object
	 * 
	 * @param SimpleXMLElement $xml
	 */
	public static function parse( SimpleXMLElement $xml ) {
		$payment_info = new Pronamic_Gateways_PayDutch_PaymentInfo();
		
		$payment_info->test            = Pronamic_XML_Util::filter( $xml->test, FILTER_VALIDATE_BOOLEAN );
		$payment_info->id              = Pronamic_XML_Util::filter( $xml->id, FILTER_SANITIZE_STRING );
		$payment_info->description     = Pronamic_XML_Util::filter( $xml->description, FILTER_SANITIZE_STRING );
		$payment_info->amount          = Pronamic_Gateways_PayDutch_PayDutch::parse_amount( Pronamic_XML_Util::filter( $xml->amount, FILTER_SANITIZE_STRING ) );
		$payment_info->state           = Pronamic_XML_Util::filter( $xml->state, FILTER_SANITIZE_STRING );
		$payment_info->reference       = Pronamic_XML_Util::filter( $xml->reference, FILTER_SANITIZE_STRING );
		$payment_info->methodcode      = Pronamic_XML_Util::filter( $xml->methodcode, FILTER_SANITIZE_STRING );
		$payment_info->methodname      = Pronamic_XML_Util::filter( $xml->methodname, FILTER_SANITIZE_STRING );
		$payment_info->consumername    = Pronamic_XML_Util::filter( $xml->consumername, FILTER_SANITIZE_STRING );
		$payment_info->consumercity    = Pronamic_XML_Util::filter( $xml->consumercity, FILTER_SANITIZE_STRING );
		$payment_info->consumeraccount = Pronamic_XML_Util::filter( $xml->consumeraccount, FILTER_SANITIZE_STRING );
		$payment_info->consumercountry = Pronamic_XML_Util::filter( $xml->consumercountry, FILTER_SANITIZE_STRING );
		$payment_info->created         = Pronamic_XML_Util::filter( $xml->created, FILTER_SANITIZE_STRING );
		
		return $payment_info;
	}
}
