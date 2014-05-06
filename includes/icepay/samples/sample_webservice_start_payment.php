<?php

/**
 *  ICEPAY Basicmode API 2: Webservices - Start a payment
 *  Sample script: Start a payment and redirect customer to the paymentscreen
 *
 *  @version 1.0.0
 *  @author Olaf Abbenhuis
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

/*  Define your ICEPAY Merchant ID and Secret code. The values below are sample values and will not work, Change them to your own merchant settings. */
define('MERCHANTID',12345);//<--- Change this into your own merchant ID
define('SECRETCODE',"xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx");//<--- Change this into your own merchant ID

// Include the API into your project
require_once '../api/icepay_api_webservice.php';


/* Set the payment */
$paymentObj = new Icepay_PaymentObject();
$paymentObj->setCountry('NL')
            ->setLanguage("EN")
            ->setCurrency("EUR")
            ->setAmount(300)
            ->setPaymentMethod("IDEAL")
            ->setIssuer("ING")
            ->setOrderID(1);

try {
    // Set the service
    $service = Icepay_Api_Webservice::getInstance()->paymentService();
    
    // Merchant Settings
    $service->setMerchantID(MERCHANTID)
            ->setSecretCode(SECRETCODE);
    
    /* Start the transaction */
    $transactionObj = $service->checkOut($paymentObj);

    /* Display the PaymentScreen URL */
    echo("<a href='".$transactionObj->getPaymentScreenURL()."'>".$transactionObj->getPaymentScreenURL()."</a>");

} catch (Exception $e){
    echo($e->getMessage());
}

?>
