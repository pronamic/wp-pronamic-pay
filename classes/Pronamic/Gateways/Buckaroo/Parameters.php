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
	 * Indicator for the 'Brq_websitekey' parameter
	 * @var string
	 */
	const WEBSITE_KEY = 'Brq_websitekey';
	
	/**
	 * Indicator for the 'Brq_signature' parameter
	 * @var string
	 */
	const SIGNATURE = 'Brq_signature';

	/**
	 * Indicator for the 'Brq_invoicenumber' parameter
	 * @var string
	 */
	const INVOICE_NUMBER = 'Brq_invoicenumber';

	/**
	 * Indicator for the 'Brq_amount' parameter
	 * @var string
	 */
	const AMOUNT = 'Brq_amount';

	/**
	 * Indicator for the 'Brq_currency' parameter
	 * @var string
	 */
	const CURRENCY = 'Brq_currency';

	/**
	 * Indicator for the 'Brq_culture' parameter
	 * @var string
	 */
	const CULTURE = 'Brq_culture';

	/**
	 * Indicator for the 'Brq_description' parameter
	 * @var string
	 */
	const DESCRIPTION = 'Brq_description';
	
	//////////////////////////////////////////////////

	/**
	 * Indicator for the 'Brq_payment_method' parameter
	 * @var string
	 */
	const PAYMENT_METHOD = 'Brq_payment_method';
	
	//////////////////////////////////////////////////

	/**
	 * Indicator for the 'Brq_return' parameter
	 * @var string
	 */
	const RETURN_URL = 'Brq_return';

	/**
	 * Indicator for the 'Brq_returnreject' parameter
	 * @var string
	 */
	const RETURN_REJECT_URL = 'Brq_returnreject';

	/**
	 * Indicator for the 'Brq_returnerror' parameter
	 * @var string
	 */
	const RETURN_ERROR_URL = 'Brq_returnerror';

	/**
	 * Indicator for the 'Brq_returncancel' parameter
	 * @var string
	 */
	const RETURN_CANCEL_URL = 'Brq_returncancel';
}
