<?php

/**
 * Title: Buckaroo parameters constants
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Buckaroo_Parameters {
	/**
	 * Indicator for the 'brq_websitekey' parameter
	 *
	 * The unique key of the website for which the payment is placed.
	 *
	 * @required true
	 * @var string
	 */
	const WEBSITE_KEY = 'brq_websitekey';

	/**
	 * Indicator for the 'brq_signature' parameter
	 *
	 * The digital signature. Refer to section ‘Digital signature’ for information on calculating the signature.
	 *
	 * @required true
	 * @var string
	 */
	const SIGNATURE = 'brq_signature';

	/**
	 * Indicator for the 'brq_invoicenumber' parameter
	 *
	 * The unique invoice number that identifies the payment. This is a free text field of max. 255 characters.
	 *
	 * @required true
	 * @var string
	 */
	const INVOICE_NUMBER = 'brq_invoicenumber';

	/**
	 * Indicator for the 'brq_amount' parameter
	 *
	 * The amount to pay in the format 12.34 (always use a dot as a decimal separator)
	 *
	 * @required true
	 * @var string
	 */
	const AMOUNT = 'brq_amount';

	/**
	 * Indicator for the 'brq_currency' parameter
	 *
	 * The currency code (e.g. EUR, USD, GBP). Make sure the specified payment method supports the specified currency.
	 *
	 * @required true
	 * @var string
	 */
	const CURRENCY = 'brq_currency';

	/**
	 * Indicator for the 'brq_culture' parameter
	 *
	 * ISO culture code that specifies the language and/or country of residence of the consumer. Examples: en-US, en GB, de-DE, EN or DE.
	 * The language part of the culture code is used to apply language localization to the gateway.
	 * Currently the following languages are supported: NL, EN, DE.
	 * When the culture parameter is not supplied, the default culture en-US is used.
	 *
	 * @required false
	 * @var string
	 */
	const CULTURE = 'brq_culture';

	/**
	 * Indicator for the 'brq_description' parameter
	 *
	 * A description of the payment to aid the consumer.
	 *
	 * @required false
	 * @var string
	 */
	const DESCRIPTION = 'brq_description';

	//////////////////////////////////////////////////

	/**
	 * Indicator for the 'brq_payment_method' parameter
	 *
	 * The service code for the payment method. Ex.: visa, mastercard, paypal
	 *
	 * @required false
	 * @var string
	 */
	const PAYMENT_METHOD = 'brq_payment_method';

	//////////////////////////////////////////////////

	/**
	 * Indicator for the 'brq_return' parameter
	 *
	 * The return URL where the consumer is redirected after payment. If not
	 * supplied, the value specified in the Payment Plaza is used.
	 *
	 * @required false
	 * @var string
	 */
	const RETURN_URL = 'brq_return';

	/**
	 * Indicator for the 'brq_returnreject' parameter
	 *
	 * The return URL used when the payment is rejected by the processor.
	 * Fallback is the value in brq_return
	 *
	 * @required false
	 * @var string
	 */
	const RETURN_REJECT_URL = 'brq_returnreject';

	/**
	 * Indicator for the 'brq_returnerror' parameter
	 *
	 * The return URL used when the request results in an error. Fallback is
	 * the value in brq_return
	 *
	 * @required false
	 * @var string
	 */
	const RETURN_ERROR_URL = 'brq_returnerror';

	/**
	 * Indicator for the 'brq_returncancel' parameter
	 *
	 * The return URL used when the consumer cancels the payment. Fallback is
	 * the value in brq_return
	 *
	 * @required false
	 * @var string
	 */
	const RETURN_CANCEL_URL = 'brq_returncancel';

	//////////////////////////////////////////////////
	// Payment response
	//////////////////////////////////////////////////

	/**
	 * Indicator for the 'brq_payment' parameter
	 *
	 * Unique key referring to the payment
	 *
	 * @var string
	 */
	const PAYMENT = 'brq_payment';

	/**
	 * Indicator for the 'brq_statuscode' parameter
	 *
	 * Code that indicates the status of the payment
	 *
	 * @var string
	 */
	const STATUS_CODE = 'brq_statuscode';

	/**
	 * Indicator for the 'brq_statuscode_detail' parameter
	 *
	 * @var string
	 */
	const STATUS_CODE_DETAIL = 'brq_statuscode_detail';

	/**
	 * Indicator for the 'brq_statusmessage' parameter
	 *
	 * Description of the payment status, localized to the consumer culture
	 *
	 * @var string
	 */
	const STATUS_MESSAGE = 'brq_statusmessage';

	/**
	 * Indicator for the 'brq_timestamp' parameter
	 *
	 * Date/timestamp for the payment (yyyy-MM-dd hh:mm:ss)
	 *
	 * @var string
	 */
	const TIMESTAMP = 'brq_timestamp';

	/**
	 * Indicator for the 'brq_transactions' parameter
	 *
	 * One or more unique keys referring to transactions which are linked to
	 * this payment. Multiple keys are separated by a comma (,)
	 *
	 * @var string
	 */
	const TRANSACTIONS = 'brq_transactions';

	//////////////////////////////////////////////////
	// iDEAL
	//////////////////////////////////////////////////

	/**
	 * The name of the issuer (bank) of the consumer
	 *
	 * When: On success
	 *
	 * @var string
	 */
	const SERVICE_IDEAL_CONSUMER_ISSUER = 'brq_service_ideal_consumerissuer';

	/**
	 * The beneficiary of the bank account from which the payment was made
	 *
	 * When: On success
	 *
	 * @var string
	 */
	const SERVICE_IDEAL_CONSUMER_NAME = 'brq_service_ideal_consumername';

	/**
	 * The international bank account number (iban code) of the bank of the consumer.
	 *
	 * When: On success
	 *
	 * @var string
	 */
	const SERVICE_IDEAL_CONSUMER_IBAN = 'brq_service_ideal_consumeriban';

	/**
	 * The bank identifier (bic code) of the bank of the consumer.
	 *
	 * Please note: This field is optional. In some countries, banks are not
	 * allowed to provide this information to third parties.
	 *
	 * When: On success
	 *
	 * @var string
	 */
	const SERVICE_IDEAL_CONSUMER_BIC = 'brq_service_ideal_consumerbic';

	/**
	 * The account from which the payment was made
	 *
	 * Please note: this field is returned in version 1 only
	 *
	 * When: On success
	 *
	 * @var string
	 */
	const SERVICE_IDEAL_CONSUMER_ACCOUNT_NUMBER = 'brq_service_ideal_consumeraccountnumber';

	/**
	 * The place of residence of the account holder
	 *
	 * When: On success
	 *
	 * @var string
	 */
	const SERVICE_IDEAL_CONSUMER_CITY = 'brq_service_ideal_consumercity';
}
