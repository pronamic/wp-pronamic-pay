<?php

/**
 *  ICEPAY Basicmode API 2: Webservices - Start an extended payment
 *  Sample script: Start a payment and redirect customer to the paymentscreen
 *
 *  @version 1.0.0
 *  @author Wouter van Tilburg
 *  @copyright Copyright (c) 2012, ICEPAY
 *
 *  Disclaimer:
 *  These sample scripts are used for training purposes only and
 *  should not be used in a live environment. The software is provided
 *  "as is", without warranty of any kind, express or implied, including
 *  but not limited to the warranties of merchantability, fitness for
 *  a particular purpose and non-infringement. In no event shall the
 *  authors or copyright holders be liable for any claim, damages or
 *  other liability, whether in an action of contract, tort or otherwise,
 *  arising from, out of or in connection with the software or the use
 *  of other dealings in the software.
 *
 */
// Define your ICEPAY Merchant ID and Secret code. The values below are sample values and will not work, Change them to your own merchant settings.
define('MERCHANTID', 'xxxxxx');
define('SECRETCODE', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');

// Include the API into your project
require_once '../api/icepay_api_webservice.php';

$address = Icepay_Order_Address::create()
        ->setInitials('Dhr.')
        ->setPrefix('')
        ->setLastName('Kwek')
        ->setStreet('Zandstraat')
        ->setHouseNumber('22')
        ->setHouseNumberAddition('')
        ->setZipCode('1058EA')
        ->setCity('Amsterdam')
        ->setCountry('NL');

Icepay_Order::getInstance()
        ->setConsumer(Icepay_Order_Consumer::create()
                ->setConsumerID('1')
                ->setEmail('consumer@email.com')
                ->setPhone('0611223344')
        )
        ->setShippingAddress($address)
        ->setBillingAddress($address)
        ->addProduct(Icepay_Order_Product::create()
                ->setProductID('1')
                ->setProductName('iPhone')
                ->setDescription('Test Description')
                ->setQuantity('1')
                ->setUnitPrice('200')
                ->setVATCategory(Icepay_Order_VAT::getCategoryForPercentage(21))
        )
        ->setShippingCosts(200);

$paymentObj = new Icepay_PaymentObject();
$paymentObj->setAmount(400)
        ->setCountry("NL")
        ->setLanguage("NL")
        ->setIssuer('ACCEPTGIRO')
        ->setPaymentMethod('AFTERPAY')
        ->setReference("My Sample Website")
        ->setDescription("My Sample Payment")
        ->setCurrency("EUR")
        ->setOrderID('test01');


try {
    $webservice = Icepay_Api_Webservice::getInstance()->paymentService();
    $webservice->setMerchantID(MERCHANTID)
            ->setSecretCode(SECRETCODE);

    $transactionObj = $webservice->extendedCheckout($paymentObj);

    printf('<a href="%s">%s</a>', $transactionObj->getPaymentScreenURL(), $transactionObj->getPaymentScreenURL());
} catch (Exception $e) {
    echo "<pre>";
    var_dump($e);
    echo "</pre>";
}

