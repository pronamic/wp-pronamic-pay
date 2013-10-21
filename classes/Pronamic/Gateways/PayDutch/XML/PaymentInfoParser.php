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
		
		$payment_info->test            = (string) $xml->test;
		$payment_info->id              = (string) $xml->id;
		$payment_info->description     = (string) $xml->description;
		$payment_info->amount          = (string) $xml->amount;
		$payment_info->state           = (string) $xml->state;
		$payment_info->reference       = (string) $xml->reference;
		$payment_info->methodcode      = (string) $xml->methodcode;
		$payment_info->methodname      = (string) $xml->methodname;
		$payment_info->consumername    = (string) $xml->consumername;
		$payment_info->consumercity    = (string) $xml->consumercity;
		$payment_info->consumeraccount = (string) $xml->consumeraccount;
		$payment_info->consumercountry = (string) $xml->consumercountry;
		$payment_info->created         = (string) $xml->created;
		
		return $payment_info;
	}
}
