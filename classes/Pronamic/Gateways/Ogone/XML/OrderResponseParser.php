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
	 * @param Pronamic_Pay_Gateways_Ogone_DirectLink_OrderResponse $order_response
	 */
	public static function parse( SimpleXMLElement $xml, $order_response = null ) {
		if ( ! $order_response instanceof Pronamic_Pay_Gateways_Ogone_DirectLink_OrderResponse ) {
			$order_response = new Pronamic_Pay_Gateways_Ogone_DirectLink_OrderResponse();
		}

		$order_response->order_id      = Pronamic_XML_Util::filter( $xml[Pronamic_Pay_Gateways_Ogone_Parameters::ORDERID] );
		$order_response->pay_id        = Pronamic_XML_Util::filter( $xml['PAYID'] );
		$order_response->nc_status     = Pronamic_XML_Util::filter( $xml[Pronamic_Pay_Gateways_Ogone_Parameters::NC_STATUS] );
		$order_response->nc_error      = Pronamic_XML_Util::filter( $xml[Pronamic_Pay_Gateways_Ogone_Parameters::NC_ERROR] );
		$order_response->nc_error_plus = Pronamic_XML_Util::filter( $xml[Pronamic_Pay_Gateways_Ogone_Parameters::NC_ERROR_PLUS] );
		$order_response->acceptance    = Pronamic_XML_Util::filter( $xml['ACCEPTANCE'] );
		$order_response->status        = Pronamic_XML_Util::filter( $xml[Pronamic_Pay_Gateways_Ogone_Parameters::STATUS] );
		$order_response->eci           = Pronamic_XML_Util::filter( $xml['ECI'] );
		$order_response->amount        = Pronamic_XML_Util::filter( $xml[Pronamic_Pay_Gateways_Ogone_Parameters::AMOUNT] );
		$order_response->currency      = Pronamic_XML_Util::filter( $xml[Pronamic_Pay_Gateways_Ogone_Parameters::CURRENCY] );
		$order_response->pm            = Pronamic_XML_Util::filter( $xml['PM'] );
		$order_response->brand         = Pronamic_XML_Util::filter( $xml['BRAND'] );

		return $order_response;
	}
}
