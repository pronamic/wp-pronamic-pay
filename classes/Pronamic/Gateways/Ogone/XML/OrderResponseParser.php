<?php

/**
 * Title: Ogone DirectLink order response XML parser
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Ogone_XML_OrderResponseParser extends Pronamic_Gateways_IDealAdvancedV3_XML_Parser {
	/**
	 * Parse the specified XML element into an iDEAL transaction object
	 * 
	 * @param SimpleXMLElement $xml
	 * @param Pronamic_Gateways_Ogone_DirectLink_OrderResponse $transaction
	 */
	public static function parse( SimpleXMLElement $xml, $order_response = null ) {
		if ( ! $order_response instanceof Pronamic_Gateways_Ogone_DirectLink_OrderResponse ) {
			$order_response = new Pronamic_Gateways_Ogone_DirectLink_OrderResponse();
		}

		$order_response->order_id      = (string) $xml[Pronamic_Gateways_Ogone_Parameters::ORDERID];
		$order_response->pay_id        = (string) $xml['PAYID'];
		$order_response->nc_status     = (string) $xml[Pronamic_Gateways_Ogone_Parameters::NC_STATUS];
		$order_response->nc_error      = (string) $xml[Pronamic_Gateways_Ogone_Parameters::NC_ERROR];
		$order_response->nc_error_plus = (string) $xml[Pronamic_Gateways_Ogone_Parameters::NC_ERROR_PLUS];
		$order_response->acceptance    = (string) $xml['ACCEPTANCE'];
		$order_response->status        = (string) $xml[Pronamic_Gateways_Ogone_Parameters::STATUS];
		$order_response->eci           = (string) $xml['ECI'];
		$order_response->amount        = (string) $xml[Pronamic_Gateways_Ogone_Parameters::AMOUNT];
		$order_response->currency      = (string) $xml[Pronamic_Gateways_Ogone_Parameters::CURRENCY];
		$order_response->pm            = (string) $xml['PM'];
		$order_response->brand         = (string) $xml['BRAND'];

		return $order_response;
	}
}
