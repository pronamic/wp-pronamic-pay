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
	/**
	 * Indicator for the Websiteket parameter
	 * Insite Pronamic called HASHKEY	 
	 * @var string
	 */
	const WEBSITE_KEY = 'brq_websitekey';
	
	/**
	 * Indicator for the Websiteket parameter
	 * Insite Pronamic called HASHKEY	 
	 * @var string
	 */
	const SIGNATURE = 'brq_signature';

	/**
	 * Indicator for the ORDERID parameter
	 * @var string
	 */
	const INVOICE_NUMBER = 'brq_invoicenumber';

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
	const CULTURE = 'brq_culture';

	/**
	 * Indicator for the LANGUAGE parameter
	 * @var string
	 */
	const DESCRIPTION = 'brq_description';
	
	//////////////////////////////////////////////////

	/**
	 * Indicator for the ACCEPTURL parameter
	 * @var string
	 */
	const RETURN_URL = 'brq_return';

	/**
	 * Indicator for the DECLINEURL parameter
	 * @var string
	 */
	const RETURN_REJECT_URL = 'brq_returnreject';

	/**
	 * Indicator for the EXCEPTIONURL parameter
	 * @var string
	 */
	const RETURN_ERROR_URL = 'brq_returnerror';

	/**
	 * Indicator for the CANCELURL parameter
	 * @var string
	 */
	const RETURN_CANCEL_URL = 'brq_returncancel';
}
