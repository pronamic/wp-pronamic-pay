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
		$this->setCurrency( self::CURRENCY_EURO );
		$this->setAction( self::ACTION_PAY );
	}

	//////////////////////////////////////////////////

	public function getCurrency() {
		return $this->currency;
	}

	public function setCurrency( $currency ) {
		$this->currency = $currency;
	}

	//////////////////////////////////////////////////

	public function getAction() {
		return $this->action;
	}

	public function setAction( $action ) {
		$this->action = $action;
	}

	//////////////////////////////////////////////////

	/**
	 * Get HTML fields
	 *
	 * @return string
	 */
	public function getHtmlFields() {
		return Pronamic_IDeal_IDeal::htmlHiddenFields( array(
			'currency'         => $this->getCurrency(),
			'action'           => $this->getAction(),
			'account'          => $this->getAccount(),
			'site_id'          => $this->getSiteId(),
			'site_secure_code' => $this->getSiteSecureCode(),
			'amount'           => $this->get_amount(),
			'description'      => $this->get_description(),
			'items'            => $this->get_items(),
		) );
	}
}
