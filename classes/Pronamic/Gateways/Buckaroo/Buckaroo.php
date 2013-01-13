<?php

class Pronamic_Gateways_Buckaroo_Buckaroo {
	const MODE_LIVE = '0';

	const MODE_TEST = '1';
	
	/////////////////////////////////////////////////

	const URL_REQUEST_FOR_AUTHORIZATION = 'https://payment.buckaroo.nl/sslplus/request_for_authorization.asphttps://payment.buckaroo.nl/sslplus/request_for_authorization.asp';

	const URL_TRANSFER = 'https://payment.buckaroo.nl/gateway/transfer.asp';

	const URL_DIRECT_DEBIT = 'https://payment.buckaroo.nl/gateway/machtiging.asp';

	const URL_IDEAL = 'https://payment.buckaroo.nl/gateway/ideal_payment.asp';

	/////////////////////////////////////////////////

	private $merchantId;

	private $hashKey;

	private $amount;

	private $currency;

	private $invoice;

	private $description;

	private $language;

	private $locale;

	private $timestamp;

	private $mode;

	private $returnMethod;

	//////////////////////////////////////////////////

	public function __construct() {
		
	}

	//////////////////////////////////////////////////

	public function getMerchantId() {
		return $this->merchantId;
	}

	public function setMerchantId($merchantId) {
		$this->merchantId = $merchantId;
	}

	//////////////////////////////////////////////////

	public function getHashKey() {
		return $this->hashKey;
	}

	public function setHashKey($hashKey) {
		$this->hashKey = $hashKey;
	}

	//////////////////////////////////////////////////

	public function getInvoice() {
		return $this->invoice;
	}

	public function setInvoice($invoice) {
		$this->invoice = $invoice;
	}

	//////////////////////////////////////////////////

	public function getAmount() {
		return $this->amount;
	}

	public function setAmount($amount) {
		$this->amount = $amount;
	}

	//////////////////////////////////////////////////

	public function getCurrency() {
		return $this->currency;
	}

	public function setCurrency($currency) {
		$this->currency = $currency;
	}

	//////////////////////////////////////////////////

	public function getMode() {
		return $this->mode;
	}

	public function setMode($mode) {
		$this->mode = $mode;
	}

	//////////////////////////////////////////////////

	public function getHashData() {
		$data = array();
	
		$data[] = $this->getMerchantId();

		$data[] = $this->getInvoice();

		$data[] = Pronamic_WordPress_Util::amount_to_cents( $this->getAmount() );

		$data[] = $this->getCurrency();

		$data[] = $this->getMode();

		$data[] = $this->getHashKey();

		return $data;
	}

	public function getHashString() {
		return implode($this->getHashData());
	}

	public function getSignature() {
		return md5($this->getHashString());
	}

	//////////////////////////////////////////////////

	/**
	 * Get HTML fields
	 * 
	 * @return string
	 */
	public function getHtmlFields() {
		$html  = '';

		$html .= sprintf('<input type="hidden" name="BPE_Merchant" value="%s" />', $this->getMerchantId());
		$html .= sprintf('<input type="hidden" name="BPE_Amount" value="%s" />', $this->getAmount());
		$html .= sprintf('<input type="hidden" name="BPE_Currency" value="%s" />', $this->getCurrency());
		$html .= sprintf('<input type="hidden" name="BPE_Invoice" value="%s" />', $this->getInvoice());
		$html .= sprintf('<input type="hidden" name="BPE_Description" value="%s" />', $this->getDescription());
		$html .= sprintf('<input type="hidden" name="BPE_Language" value="%s" />', $this->getLanguage());
		$html .= sprintf('<input type="hidden" name="BPE_Locale" value="%s" />', $this->getLocale());
		$html .= sprintf('<input type="hidden" name="BPE_Timestamp" value="%s" />', $this->getTimestamp());
		$html .= sprintf('<input type="hidden" name="BPE_Mode" value="%s" />', $this->getMode());
		$html .= sprintf('<input type="hidden" name="BPE_Reference" value="%s" />', $this->getReference());
		$html .= sprintf('<input type="hidden" name="BPE_Return_Method" value="%s" />', $this->getReturnMethod());
		$html .= sprintf('<input type="hidden" name="BPE_Signature2" value="%s" />', $this->getSignature2());
		$html .= sprintf('<input type="hidden" name="BPE_Return_Reject" value="%s" />', $this->getReturnUrlReject());
		$html .= sprintf('<input type="hidden" name="BPE_Return_Success" value="%s" />', $this->getReturnUrlSuccess());
		$html .= sprintf('<input type="hidden" name="BPE_Return_Error" value="%s" />', $this->getReturnUrlError());
		
		return $html;
	}
}

