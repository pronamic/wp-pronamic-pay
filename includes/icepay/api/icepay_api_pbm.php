<?php

// Include API base 
require_once(realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'icepay_api_base.php');

/**
 * PBM Object Interface
 * 
 * @since 1.0.0
 * @author Wouter van Tilburg
 * @copyright Copyright (c) 2013, ICEPAY
 */
interface Icepay_PbmObject_Interface_Abstract {

    public function setAmount($amount);
    
    public function getAmount();

    public function setCurrency($currency);
    
    public function getCurrency();

    public function setCountry($country);
    
    public function getCountry();

    public function setLanguage($language);
    
    public function getLanguage();

    public function setOrderID($orderID);
    
    public function getOrderID();
    
    public function setReference($reference);
    
    public function getReference();
    
    public function setDescription($description);
    
    public function getDescription();
}

/**
 * ICEPAY API - Pay By Mail
 * 
 * @version 1.0.0
 * @author Wouter van Tilburg
 * @copyright Copyright (c) 2013, ICEPAY
 *
 */
class Icepay_Api_Pbm extends Icepay_Api_Base {

    private $url = 'http://pbm.icepay.com/api';
    private static $instance;

    /**
     * Create an instance
     * 
     * @since version 1.0.0
     * @access public
     * @return instance of self
     */
    public static function getInstance()
    {
        if (!self::$instance)
            self::$instance = new self();
        return self::$instance;
    }

    /**
     * Create a PBM link
     * 
     * @since 1.0.0
     * @param Icepay_Pbm_Object $pbmObject
     * @return string
     */
    public function createLink(Icepay_Pbm_Object $pbmObject)
    {
        $this->validateSettings();
        
        $linkObj = new StdClass();
        $linkObj->merchantid = $this->getMerchantID();
        $linkObj->timestamp = $this->getTimestamp();
        $linkObj->amount = $pbmObject->getAmount();
        $linkObj->currency = $pbmObject->getCurrency();
        $linkObj->language = $pbmObject->getLanguage();
        $linkObj->orderid = $pbmObject->getOrderID();
        $linkObj->country = $pbmObject->getCountry();
        $linkObj->description = $pbmObject->getDescription();
        $linkObj->reference = $pbmObject->getReference();
        $linkObj->checksum = $this->generateChecksum($linkObj);

        $result = $this->generateURL($linkObj);

        return json_decode($result);
    }

    /**
     * Calls PBM platform and returns generated PBM link
     * 
     * @since 1.0.0
     * @param object $parameters
     * @return string
     */
    private function generateURL($parameters)
    {
        $ch = curl_init();

        $parameters = http_build_query($parameters);

        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);

        $result = curl_exec($ch);

        curl_close($ch);
        
        return $result;
    }

    /**
     * Generates PBM checksum
     * 
     * @since 1.0.0
     * @param obj $linkObj
     * @return string
     */
    private function generateChecksum($linkObj)
    {
        $arr = (array)$linkObj;
        $arr[] = $this->getSecretCode();

        return sha1(implode("|", $arr));
    }

    /**
     * Validate the merchant settings
     * 
     * @since 1.0.0
     * @throws Exception
     */
    private function validateSettings()
    {
        // Validate Merchant ID
        if (!Icepay_Parameter_Validation::merchantID($this->getMerchantID()))
            throw new Exception('Merchant ID not set, use the setMerchantID() method', 1001);

        // Validate SecretCode
        if (!Icepay_Parameter_Validation::secretCode($this->getSecretCode()))
            throw new Exception('Secretcode ID not set, use the setSecretCode() method', 1002);
    }

}

/**
 * ICEPAY API - Pay By Mail Object
 *
 * @version 1.0.0
 * @author Wouter van Tilburg
 * @copyright Copyright (c) 2013, ICEPAY
 *
 */
class Icepay_Pbm_Object implements Icepay_PbmObject_Interface_Abstract {

    private $amount;
    private $currency = 'EUR';
    private $country = 'NL';
    private $language = 'NL';
    private $orderID;
    private $description = '';
    private $reference = '';

    /**
     * Set Amount
     * 
     * @since 1.0.0
     * @param int $amount
     * @return \Icepay_Pbm_Object
     * @throws Exception
     */
    public function setAmount($amount)
    {
        if (!Icepay_Parameter_Validation::amount($amount))
            throw new Exception('Please enter a valid amount (in cents)', 1003);

        $this->amount = $amount;
        return $this;
    }

    /**
     * Get Amount
     * 
     * @since 1.0.0
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set Currency
     * 
     * @since 1.0.0
     * @param string $currency
     * @return \Icepay_Pbm_Object
     * @throws Exception
     */
    public function setCurrency($currency)
    {
        if (!Icepay_Parameter_Validation::currency($currency))
            throw new Exception('Please enter a valid currency format (ISO 4217)', 1004);

        $this->currency = $currency;
        return $this;
    }

    /**
     * Get Currency
     * 
     * @since 1.0.0
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set Country
     * 
     * @since 1.0.0
     * @param string $countryCode
     * @return \Icepay_Pbm_Object
     * @throws Exception
     */
    public function setCountry($countryCode)
    {
        if (!Icepay_Parameter_Validation::country($countryCode))
            throw new Exception('Please enter a valid country format (ISO 3166-1)', 1005);

        $this->country = $countryCode;
        return $this;
    }

    /**
     * Get Country
     * 
     * @since 1.0.0
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set Language
     * 
     * @since 1.0.0
     * @param string $language
     * @return \Icepay_Pbm_Object
     * @throws Exception
     */
    public function setLanguage($language)
    {
        if (!Icepay_Parameter_Validation::language($language))
            throw new Exception('Please enter a valid language (ISO 639-1)', 1006);

        $this->language = $language;
        return $this;
    }

    /**
     * Get Language
     * 
     * @since 1.0.0
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set OrderID
     * 
     * @param string $orderID
     * @return \Icepay_Pbm_Object
     * @throws Exception
     */
    public function setOrderID($orderID)
    {
        if (!Icepay_Parameter_Validation::orderID($orderID))
            throw new Exception('The Order ID cannot be longer than 10 characters', 1007);

        $this->orderID = $orderID;
        return $this;
    }

    /**
     * Get Order ID
     * 
     * @since 1.0.0
     * @return string
     */
    public function getOrderID()
    {
        return $this->orderID;
    }
    
    /**
     * Set Description
     * 
     * @since 1.0.0
     * @param string $description
     * @return \Icepay_Pbm_Object
     */
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }
    
    /**
     * Get Description
     * 
     * @since 1.0.0
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }
    
    /**
     * Set Reference
     * 
     * @since 1.0.0
     * @param string $reference
     * @return \Icepay_Pbm_Object
     */
    public function setReference($reference) {
        $this->reference = $reference;
        return $this;
    }
    
    /**
     * Get Reference
     * 
     * @since 1.0.0
     * @return string
     */
    public function getReference() {
        return $this->reference;
    }

}