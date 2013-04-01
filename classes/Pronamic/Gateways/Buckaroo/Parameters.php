<?php

/**
 * Title: iDEAL Internet Kassa parameters constants
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Buckaroo_Parameters {
	//////////////////////////////////////////////////
	// IN
	//////////////////////////////////////////////////

	/**
	 * Indicator for the MERCHANT_ID parameter
	 * @var string
	 */
	// const PSPID = 'PSPID';
	
	const MERCHANTID = 'MERCHANTID';
	
	/**
	 * Indicator for the Websiteket parameter
	 * Insite Pronamic called HASHKEY	 
	 * @var string
	 */
	const HASHKEY = 'Brq_websitekey';
	
	/**
	 * Indicator for the Websiteket parameter
	 * Insite Pronamic called HASHKEY	 
	 * @var string
	 */
	const SIGNATURE = 'Brq_signature';

	/**
	 * Indicator for the ORDERID parameter
	 * @var string
	 */
	const ORDERID = 'brq_invoicenumber';

	/**
	 * Indicator for the AMOUNT parameter
	 * @var string
	 */
	const AMOUNT = 'brq_amount';

	/**
	 * Indicator for the CURRENCY parameter
	 * @var string
	 */
	const CURRENCY = 'brq_currency';

	/**
	 * Indicator for the LANGUAGE parameter
	 * @var string
	 */
	const LANGUAGE = 'Brq_culture';

	/**
	 * Indicator for the LANGUAGE parameter
	 * @var string
	 */
	const COM = 'Brq_description';
	
	//////////////////////////////////////////////////

	/**
	 * Indicator for the ACCEPTURL parameter
	 * @var string
	 */
	const ACCEPT_URL = 'Brq_return';

	/**
	 * Indicator for the DECLINEURL parameter
	 * @var string
	 */
	const DECLINE_URL = 'Brq_returnreject';

	/**
	 * Indicator for the EXCEPTIONURL parameter
	 * @var string
	 */
	const EXCEPTION_URL = 'Brq_returnerror';

	/**
	 * Indicator for the CANCELURL parameter
	 * @var string
	 */
	const CANCEL_URL = 'Brq_returncancel';

	//////////////////////////////////////////////////
	// OUT
	//////////////////////////////////////////////////

	/**
	 * Indicator for the STATUS parameter
	 * @var string
	 */
	const STATUS = 'STATUS';
}
