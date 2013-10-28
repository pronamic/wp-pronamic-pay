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
		
		$payment_info->test            = filter_var( (string) $xml->test, FILTER_VALIDATE_BOOLEAN );
		$payment_info->id              = filter_var( (string) $xml->id, FILTER_SANITIZE_STRING );
		$payment_info->description     = filter_var( $xml->description, FILTER_SANITIZE_STRING );
		$payment_info->amount          = Pronamic_Gateways_PayDutch_PayDutch::parse_amount( $xml->amount );
		$payment_info->state           = filter_var( $xml->state, FILTER_SANITIZE_STRING );
		$payment_info->reference       = filter_var( $xml->reference, FILTER_SANITIZE_STRING );
		$payment_info->methodcode      = filter_var( $xml->methodcode, FILTER_SANITIZE_STRING );
		$payment_info->methodname      = filter_var( $xml->methodname, FILTER_SANITIZE_STRING );
		$payment_info->consumername    = filter_var( $xml->consumername, FILTER_SANITIZE_STRING );
		$payment_info->consumercity    = filter_var( $xml->consumercity, FILTER_SANITIZE_STRING );
		$payment_info->consumeraccount = filter_var( $xml->consumeraccount, FILTER_SANITIZE_STRING );
		$payment_info->consumercountry = filter_var( $xml->consumercountry, FILTER_SANITIZE_STRING );
		$payment_info->created         = filter_var( $xml->created, FILTER_SANITIZE_STRING );
		
		return $payment_info;
	}
}
