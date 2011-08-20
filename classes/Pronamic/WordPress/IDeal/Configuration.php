<?php

namespace Pronamic\WordPress\IDeal;

/**
 * Title: WordPress iDEAL configuration
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Configuration {
	/**
	 * Unique ID of configuration
	 * 
	 * @var string
	 */
	private $id;

	public $variant;

	public $merchantId;

	public $subId;

	public $mode;

	public $hashKey;
	
	public $privateKey;
	
	public $privateKeyPassword;
	
	public $privateCertificate;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize an configuration
	 */
	public function __construct() {

	}

	//////////////////////////////////////////////////

	/**
	 * Get the ID of this configuration
	 * 
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Set the ID of this configuration
	 * 
	 * @param string $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the name of this configuration
	 * 
	 * @return string
	 */
	public function getName() {
		$name = '';

		$variant = $this->getVariant();
		if($variant != null) {
			$provider = $variant->getProvider();
			
			if($provider != null) {
				$name .= $provider->getName() . ' - ';
			}
			
			$name .= $variant->getName();
		}

		return $name;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the variant
	 * 
	 * @return string
	 */
	public function getVariant() {
		return $this->variant;
	}

	/**
	 * Set the variant
	 * 
	 * @param string $variant
	 */
	public function setVariant($variant) {
		$this->variant = $variant;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the merchant ID
	 * 
	 * @return string
	 */
	public function getMerchantId() {
		return $this->merchantId;
	}

	/**
	 * Get the sub ID
	 * 
	 * @return string
	 */
	public function getSubId() {
		return $this->subId;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the payment server URL
	 * 
	 * @return string
	 */
	public function getPaymentServerUrl() {
		$url = null;
		
		if($variant = $this->getVariant()) {
			$url = $variant->getPaymentServerUrl($this->mode);
		}
	
		return $url;
	}
	
	/**
	 * Get the provider dashboard URL
	 * 
	 * @return string
	 */
	public function getDashboardUrl() {
		$url = null;
		
		if($variant = $this->getVariant()) {
			$url = $variant->getDashboardUrl($this->mode);
		}
	
		return $url;
	}
}