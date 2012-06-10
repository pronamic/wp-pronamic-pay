<?php

class Pronamic_Gateways_MultiSafepay_MultiSafepay {
	const ACTION_PAY = 'pay';
	
	//////////////////////////////////////////////////

	const CURRENCY_EURO = 'EUR';
	
	//////////////////////////////////////////////////

	const URL_LIVE = 'https://api.multisafepay.com/ewx/post.php';

	const URL_TEST = 'https://testapi.multisafepay.com/ewx/post.php';
	
	//////////////////////////////////////////////////

	private $currency;

	private $action;
	
	//////////////////////////////////////////////////
	
	public function __construct() {
		$this->setCurrency(self::CURRENCY_EURO);
		$this->setAction(self::ACTION_PAY);
	}

	//////////////////////////////////////////////////

	public function getCurrency() {
		return $this->currency;
	}

	public function setCurrency($currency) {
		$this->currency = $currency;
	}

	//////////////////////////////////////////////////

	public function getAction() {
		return $this->action;
	}

	public function setAction($action) {
		$this->action = $action;
	}

	//////////////////////////////////////////////////
	
	/**
	 * Get HTML fields
	 *
	 * @return string
	 */
	public function getHtmlFields() {
		$html  = '';
	
		$html .= sprintf('<input type="hidden" name="currency" value="%s" />', $this->getCurrency());
		$html .= sprintf('<input type="hidden" name="action" value="%s" />', $this->getAction());
		$html .= sprintf('<input type="hidden" name="account" value="%s" />', $this->getAccount());
		$html .= sprintf('<input type="hidden" name="site_id" value="%s" />', $this->getSiteId());
		$html .= sprintf('<input type="hidden" name="site_secure_code" value="%s" />', $this->getSiteSecureCode());
		$html .= sprintf('<input type="hidden" name="amount" value="%s" />', $this->getAmount());
		$html .= sprintf('<input type="hidden" name="description" value="%s" />', $this->getDescription());
		$html .= sprintf('<input type="hidden" name="items" value="%s" />', $this->getItems());
	
		return $html;
	}
}
