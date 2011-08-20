<?php

namespace Pronamic\IDeal;

/**
 * Title: Variant
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Variant {
	/**
	 * The company who is providing this variant
	 * 
	 * @var Provider
	 */
	private $provider;

	//////////////////////////////////////////////////

	/**
	 * The unique ID of this iDEAL variant
	 * 
	 * @var string
	 */
	private $id;

	/**
	 * The name of this iDEAL variant
	 * 
	 * @var string
	 */
	private $name;

	//////////////////////////////////////////////////

	
	private $method;

	//////////////////////////////////////////////////

	/**
	 * The live settings of this iDEAL variant
	 * 
	 * @var mixed
	 */
	public $liveSettings;

	/**
	 * The test settings of this iDEAL variant
	 * 
	 * @var mixed
	 */
	public $testSettings;

	//////////////////////////////////////////////////

	public $certificates;

	//////////////////////////////////////////////////

	public function __construct() {
		$this->certificates = array();
	}

	//////////////////////////////////////////////////

	public function getMethod() {
		return $this->method;
	}

	public function setMethod($method) {
		$this->method = $method;
	}

	//////////////////////////////////////////////////

	public function getProvider() {
		return $this->provider;
	}

	public function setProvider($provider) {
		$this->provider = $provider;
	}

	//////////////////////////////////////////////////

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	//////////////////////////////////////////////////

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	//////////////////////////////////////////////////

	public function getPaymentServerUrl($mode = IDeal::MODE_LIVE) {
		switch($mode) {
			case IDeal::MODE_LIVE:
				return $this->liveSettings->paymentServerUrl;
			case IDeal::MODE_TEST:
				return $this->testSettings->paymentServerUrl;
		}
	}

	//////////////////////////////////////////////////

	public function getDashboardUrl($mode = IDeal::MODE_LIVE) {
		switch($mode) {
			case IDeal::MODE_LIVE:
				return $this->liveSettings->dashboardUrl;
			case IDeal::MODE_TEST:
				return $this->testSettings->dashboardUrl;
		}
	}
}
