<?php

/**
 * Icepay_Order_Helper
 *  
 * The Order Helper class contains handy fuctions to validate the input, such as a telephonenumber and zipcode check
 *  
 * @version 1.0.0
 * 
 * @author Wouter van Tilburg 
 * @author Olaf Abbenhuis 
 * @copyright Copyright (c) 2011-2012, ICEPAY  
 */
class Icepay_Order_Helper {

    private static $street;
    private static $houseNumber;
    private static $houseNumberAddition;

    /**
     * Sets and explodes the streetaddress
     * 
     * @since 1.0.0     
     * @param string Contains the street address     
     * @return Icepay_Order_Helper
     */
    public static function setStreetAddress($streetAddress) {
        self::explodeStreetAddress($streetAddress);

        return new self;
    }

    /**
     * Get the street from address
     * 
     * @since 1.0.0     
     * @param string Contains the street address      
     * @return Icepay_Order_Helper
     */
    public static function getStreetFromAddress($streetAddress = null) {
        if ($streetAddress)
            self::explodeStreetAddress($streetAddress);

        return self::$street;
    }

    /**
     * Get the housenumber from address
     * 
     * @since 1.0.0     
     * @param string Contains the street address     
     * @return Icepay_Order_Helper
     */
    public static function getHouseNumberFromAddress($streetAddress = null) {
        if ($streetAddress)
            self::explodeStreetAddress($streetAddress);

        return self::$houseNumber;
    }

    /**
     * Get the housenumber addition from address
     * 
     * @since 1.0.0     
     * @param string Contains the street address     
     * @return Icepay_Order_Helper
     */
    public static function getHouseNumberAdditionFromAddress($streetAddress = null) {
        if ($streetAddress)
            self::explodeStreetAddress($streetAddress);

        return self::$houseNumberAddition;
    }

    /**
     * Validates a zipcode based on country
     * 
     * @since 1.0.0
     * @param string $zipCode A string containing the zipcode
     * @param string $country A string containing the ISO 3166-1 alpha-2 code of the country
     * @example validateZipCode('1122AA', 'NL')
     * @return boolean
     */
    public static function validateZipCode($zipCode, $country) {
        switch (strtoupper($country)) {
            case 'NL':
                if (preg_match('/^[1-9]{1}[0-9]{3}[A-Z]{2}$/', $zipCode))
                    return true;
                break;
            case 'BE':
                if (preg_match('/^[1-9]{4}$/', $zipCode))
                    return true;
                break;
            case 'DE':
                if (preg_match('/^[1-9]{5}$/', $zipCode))
                    return true;
                break;
        }

        return false;
    }

    /**
     * Validates a phonenumber
     * 
     * @since 1.0.0
     * @param string Contains the phonenumber
     * @return boolean
     */
    public static function validatePhonenumber($phoneNumber) {
        if (strlen($phoneNumber) < 10) {
            return false;
        }

        if (preg_match('/^(?:\((\+?\d+)?\)|\+?\d+) ?\d*(-?\d{2,3} ?){0,4}$/', $phoneNumber)) {
            return true;
        }

        return false;
    }

    private static function explodeStreetAddress($streetAddress) {
        $pattern = '#^(.+\D+){1} ([0-9]{1,})\s?([\s\/]?[0-9]{0,}?[\s\S]{0,}?)?$#i';

        $aMatch = array();

        if (preg_match($pattern, $streetAddress, $aMatch)) {
            array_shift($aMatch);

            self::$street = array_shift($aMatch);
            self::$houseNumber = array_shift($aMatch);
            self::$houseNumberAddition = array_shift($aMatch);
        }
    }

}

/**
 * Icepay_Order_Product
 * 
 * The product object contains all information about the customers address
 * You can add as many products as you want, just remember that the total amount for the products must match the total amount of the Icepay Payment Object
 *  
 * @version 1.0.0
 * 
 * @author Wouter van Tilburg 
 * @author Olaf Abbenhuis
 * @copyright Copyright (c) 2011-2012, ICEPAY
 */
class Icepay_Order_Product {

    public $productID = '00';
    public $productName = '';
    public $description = '';
    public $quantity = '1';
    public $unitPrice = '0';
    public $VATCategory = 'standard';

    /**
     * Creates and returns a new Icepay_Order_Product
     * 
     * @since 1.0.0     
     * @return \Icepay_Order_Product
     */
    public static function create() {
        return new self();
    }

    /**
     * Sets the product ID
     * 
     * @since 1.0.0
     * @param string Contains the product ID
     * @return \Icepay_Order_Product
     * @throws Exception when empty
     */
    public function setProductID($productID) {
        if (empty($productID))
            throw new Exception('Product ID must be set and cannot be empty.');

        $this->productID = trim($productID);

        return $this;
    }

    /**
     * Sets the product name
     * 
     * @since 1.0.0
     * @param string Contains the product name
     * @return \Icepay_Order_Product
     * @throws Exception when empty
     */
    public function setProductName($productName) {
        if (empty($productName))
            throw new Exception('Product name must be set and cannot be empty.');

        $this->productName = trim($productName);

        return $this;
    }

    /**
     * Sets the product description
     * 
     * @since 1.0.0
     * @param string Contains the product discription
     * @return \Icepay_Order_Product
     * @throws Exception when empty
     */
    public function setDescription($description) {
        $this->description = trim($description);

        return $this;
    }

    /**
     * Sets the product quantity
     * 
     * @since 1.0.0
     * @param string Contains the quantity of the product
     * @return \Icepay_Order_Product
     * @throws Exception when empty
     */
    public function setQuantity($quantity) {
        if (empty($quantity))
            throw new Exception('Quantity must be set and cannot be empty.');

        $this->quantity = trim($quantity);

        return $this;
    }

    /**
     * Sets the product unit price
     * 
     * @since 1.0.0
     * @param string Contains the unitprice in cents
     * @return \Icepay_Order_Product
     * @throws Exception when empty
     */
    public function setUnitPrice($unitPrice) {
        $this->unitPrice = trim($unitPrice);

        return $this;
    }

    /**
     * Sets the product's VAT Category
     * 
     * @since 1.0.0
     * @param string Contains the VAT Category (Choices are: zero, reduced-low, reduced-middle, standard)
     * @return \Icepay_Order_Product
     * @throws Exception when empty
     */
    public function setVATCategory($vatCategory) {
        if (empty($vatCategory))
            throw new Exception('VAT Category must be set and cannot be empty.');

        $this->VATCategory = $vatCategory;

        return $this;
    }

}

/**
 * Icepay_Order_Address
 * 
 * The address class contains all information about the consumer's address 
 * 
 * @version 1.0.0
 * 
 * @author Wouter van Tilburg 
 * @author Olaf Abbenhuis
 * @copyright Copyright (c) 2011-2012, ICEPAY
 */
class Icepay_Order_Address {

    public $initials = '';
    public $prefix = '';
    public $lastName = '';
    public $street = '';
    public $houseNumber = '';
    public $houseNumberAddition = '';
    public $zipCode = '';
    public $city = '';
    public $country = '';

    /**
     * Creates and returns a new Icepay_Order_Address
     * 
     * @since 1.0.0     
     * @return \Icepay_Order_Address
     */
    public static function create() {
        return new self();
    }

    /**
     * Sets the initials
     * 
     * @since 1.0.0
     * @param string A string containing the initials
     * @return \Icepay_Order_Address
     * @throws Exception when empty
     */
    public function setInitials($initials) {
        if (empty($initials))
            throw new Exception('Initials must be set and cannot be empty.');

        $this->initials = trim($initials);

        return $this;
    }

    /**
     * Sets the prefix
     * 
     * @since 1.0.0
     * @param string A string containing the prefix
     * @return \Icepay_Order_Address
     * @throws Exception when empty
     */
    public function setPrefix($prefix) {
        $this->prefix = trim($prefix);

        return $this;
    }

    /**
     * Sets the last name
     * 
     * @since 1.0.0
     * @param string A string containing the family name
     * @return \Icepay_Order_Address
     * @throws Exception when empty
     */
    public function setLastName($lastName) {
        if (empty($lastName))
            throw new Exception('Lastname must be set and cannot be empty.');

        $this->lastName = trim($lastName);

        return $this;
    }

    /**
     * Sets the streetname
     * 
     * @since 1.0.0
     * @param string A string containing the streetname
     * @return \Icepay_Order_Address
     * @throws Exception when empty
     */
    public function setStreet($street) {
        if (empty($street))
            throw new Exception('Streetname must be set and cannot be empty.');

        $this->street = trim($street);

        return $this;
    }

    /**
     * Sets the housenumber
     * 
     * @since 1.0.0
     * @param string A string containing the housenumber
     * @return \Icepay_Order_Address
     * @throws Exception when empty
     */
    public function setHouseNumber($houseNumber) {
        if (empty($houseNumber))
            throw new Exception('Housenumber must be set and cannot be empty.');

        $this->houseNumber = trim($houseNumber);

        return $this;
    }

    /**
     * Sets the housenumberaddition
     * 
     * @since 1.0.0
     * @param string A string containing the housenumber addition
     * @return \Icepay_Order_Address
     */
    public function setHouseNumberAddition($houseNumberAddition) {
        $this->houseNumberAddition = trim($houseNumberAddition);

        return $this;
    }

    /**
     * Sets the address zipcode
     * 
     * @since 1.0.0
     * @param string A string containing the zipcode
     * @return \Icepay_Order_Address
     * @throws Exception when empty
     */
    public function setZipCode($zipCode) {
        if (empty($zipCode))
            throw new Exception('Zipcode must be set and cannot be empty.');

        $zipCode = str_replace(' ', '', $zipCode);

        $this->zipCode = trim($zipCode);

        return $this;
    }

    /**
     * Sets the address city
     * 
     * @since 1.0.0
     * @param string A string containing the cityname
     * @return \Icepay_Order_Address
     * @throws Exception when empty
     */
    public function setCity($city) {
        if (empty($city))
            throw new Exception('City must be set and cannot be empty.');

        $this->city = trim($city);

        return $this;
    }

    /**
     * Sets the country
     * 
     * @since 1.0.0
     * @param string A string containing the countryname
     * @return \Icepay_Order_Address
     * @throws Exception when empty
     */
    public function setCountry($country) {
        if (empty($country))
            throw new Exception('Country must be set and cannot be empty.');

        $this->country = trim($country);

        return $this;
    }

}

/**
 * Icepay_Order_Consumer
 * 
 * The consumer class contains all information about the consumer
 *  
 * @version 1.0.0 
 * 
 * @author Wouter van Tilburg
 * @author Olaf Abbenhuis
 * @copyright Copyright (c) 2011-2012, ICEPAY
 */
class Icepay_Order_Consumer {

    public $consumerID = '';
    public $email = '';
    public $phone = '';

    /**
     * Creates and returns a new Icepay_Order_Product
     * 
     * @since 1.0.0     
     * @return \Icepay_Order_Consumer
     */
    public static function create() {
        return new self();
    }

    /**
     * Sets the consumer ID
     * 
     * @since 1.0.0
     * @param string A string containing the consumerID
     * @return \Icepay_Order_Consumer
     * @throws Exception when empty
     */
    public function setConsumerID($consumerID) {
        if (empty($consumerID))
            throw new Exception('Consumer ID must be set and cannot be empty.');

        $this->consumerID = $consumerID;

        return $this;
    }

    /**
     * Sets the consumer's email
     * 
     * @since 1.0.0
     * @param string A string containing the consumer's email address.
     * @return \Icepay_Order_Consumer
     * @throws Exception when empty
     */
    public function setEmail($email) {
        if (empty($email))
            throw new Exception('Email must be set and cannot be empty.');

        $this->email = $email;

        return $this;
    }

    /**
     * Sets the consumer's phonenumber
     * 
     * @since 1.0.0
     * @param string A string containing the consumer's phonenumber
     * @return \Icepay_Order_Consumer
     * @throws Exception when empty
     */
    public function setPhone($phone) {
        $phone = trim(str_replace('-', '', $phone));

        if (empty($phone))
            throw new Exception('Phone must be set and cannot be empty.');

        $this->phone = $phone;

        return $this;
    }

}

/**
 * Icepay_Order
 * 
 * Contains all the order information and can generate it into XML for the extended checkout.
 * 
 * @version 1.0.0
 * 
 * @author Wouter van Tilburg 
 * @author Olaf Abbenhuis 
 * @copyright Copyright (c) 2011-2012, ICEPAY
 */
class Icepay_Order {

    private $_orderData;
    private $_consumerNode;
    private $_addressesNode;
    private $_productsNode;
    private static $instance;
    private $_debug = false;
    public $_data = Array();

    public function setData($id, $obj) {
        $this->_data[$id] = $obj;
    }

    /**
     * Sets the consumer information for the order
     * 
     * @since 1.0.0
     * @param obj Object containing the Icepay_Order_Consumer class
     * @return \Icepay_Order
     */
    public function setConsumer(Icepay_Order_Consumer $obj) {
        $this->setData("consumer", $obj);
        return $this;
    }

    /**
     * Sets the shipping address for the order
     * 
     * @since 1.0.0
     * @param obj Object containing the Icepay_Order_Address class
     * @return \Icepay_Order
     */
    public function setShippingAddress(Icepay_Order_Address $obj) {
        $this->setData("shippingAddress", $obj);
        return $this;
    }

    /**
     * Sets the billing address for the order
     * 
     * @since 1.0.0
     * @param obj Object containing the Icepay_Order_Address class
     * @return \Icepay_Order
     */
    public function setBillingAddress(Icepay_Order_Address $obj) {
        $this->setData("billingAddress", $obj);
        return $this;
    }

    /**
     * Adds a product to the order
     * 
     * @since 1.0.0
     * @param obj object containing the Icepay_Order_Product class
     * @return \Icepay_Order
     */
    public function addProduct(Icepay_Order_Product $obj) {
        if (!isset($this->_data["products"]))
            $this->_data["products"] = Array();
        array_push($this->_data["products"], $obj);
        return $this;
    }

    /**
     * Sets the order discount     
     * 
     * @since 1.0.0
     * @param string $amount Contains the discount amount in cents
     * @param string $name Contains the name of the discount
     * @param string $description Contains description of the discount
     * @return \Icepay_Order
     */
    public function setOrderDiscountAmount($amount, $name = 'Discount', $description = 'Order Discount') {
        $obj = Icepay_Order_Product::create()
                ->setProductID('02')
                ->setProductName($name)
                ->setDescription($description)
                ->setQuantity('1')
                ->setUnitPrice(-$amount)
                ->setVATCategory(Icepay_Order_VAT::getCategoryForPercentage(-1));

        $this->addProduct($obj);

        return $this;
    }

    /**
     * Sets the order shipping costs
     * 
     * @since 1.0.0
     * @param string $amount Contains the shipping costs in cents
     * @param int $vat Contains the VAT category in percentages
     * @param string $name Contains the shipping name
     * @return \Icepay_Order
     */
    public function setShippingCosts($amount, $vat = -1, $name = 'Shipping Costs') {
        $obj = Icepay_Order_Product::create()
                ->setProductID('01')
                ->setProductName($name)
                ->setDescription('')
                ->setQuantity('1')
                ->setUnitPrice($amount)
                ->setVATCategory(Icepay_Order_VAT::getCategoryForPercentage($vat));

        $this->addProduct($obj);

        return $this;
    }

    /**
     * Validates the Order
     * 
     * <p>Validates the order information based on the paymentmethod and country used</p>
     * <p>For example Afterpay, it will check the zipcodes and it makes sure that the billing and shipping address are in the same country</p>
     * 
     * @param obj $paymentObj
     * @throws Exception
     */
    public function validateOrder($paymentObj) {
        switch (strtoupper($paymentObj->getPaymentMethod())) {
            case 'AFTERPAY':
                if ($this->_data['shippingAddress']->country !== $this->_data['billingAddress']->country)
                    throw new Exception('Billing and Shipping country must be equal in order to use Afterpay.');

                if (!Icepay_Order_Helper::validateZipCode($this->_data['shippingAddress']->zipCode, $this->_data['shippingAddress']->country))
                    throw new Exception('Zipcode format for shipping address is incorrect.');

                if (!Icepay_Order_Helper::validateZipCode($this->_data['billingAddress']->zipCode, $this->_data['billingAddress']->country))
                    throw new Exception('Zipcode format for billing address is incorrect.');

                if (!Icepay_Order_Helper::validatePhonenumber($this->_data['consumer']->phone))
                    throw new Exception('Phonenumber is incorrect.');

                break;
        }
    }

    private function array_to_xml($childs, $node = 'Order') {
        $childs = (array) $childs;

        foreach ($childs as $key => $value) {
            $node->addChild(ucfirst($key), $value);
        }

        return $node;
    }

    /**
     * Generates the XML for the webservice
     * 
     * @since 1.0.0
     * @return XML
     */
    public function createXML() {

        $this->_orderData = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><Order></Order>");
        $this->_consumerNode = $this->_orderData->addChild('Consumer');
        $this->_addressesNode = $this->_orderData->addChild('Addresses');
        $this->_productsNode = $this->_orderData->addChild('Products');

        // Set Consumer
        $this->array_to_xml($this->_data['consumer'], $this->_consumerNode);

        // Set Addresses
        $shippingNode = $this->_addressesNode->addChild('Address');
        $shippingNode->addAttribute('id', 'shipping');

        $this->array_to_xml($this->_data['shippingAddress'], $shippingNode);

        $billingNode = $this->_addressesNode->addChild('Address');
        $billingNode->addAttribute('id', 'billing');

        $this->array_to_xml($this->_data['billingAddress'], $billingNode);

        // Set Products
        foreach ($this->_data['products'] as $product) {
            $productNode = $this->_productsNode->addChild('Product');
            $this->array_to_xml($product, $productNode);
        }

        if ($this->_debug == true) {
            header("Content-type: text/xml");
            echo $this->_orderData->asXML();
            exit;
        }

        return $this->_orderData->asXML();
    }

    public static function getInstance() {
        if (!self::$instance)
            self::$instance = new self();
        return self::$instance;
    }

}

class Icepay_Order_VAT {

    private static $categories = array();

    public static function setDefaultCategories() {
        $ranges = array(
            'zero' => 0,
            'reduced-low' => array('1', '6'),
            'reduced-middle' => array('7', '12'),
            'standard' => array('13', '100')
        );

        self::setCategories($ranges);
    }

    public static function setCategories($array) {
        self::$categories = $array;
    }

    public static function getCategories() {
        return self::$categories;
    }

    public static function getCategory($name) {
        return self::$categories[$name];
    }

    public static function getCategoryForPercentage($number = null, $default = "exempt") {
        if (!self::$categories)
            self::setDefaultCategories();

        foreach (self::getCategories() as $category => $value) {
            if (!is_array($value)) {
                if ($value == $number)
                    return $category;
            }

            if ($number >= $value[0] && $number <= $value[1])
                return $category;
        }

        return $default;
    }

}