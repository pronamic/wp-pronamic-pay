<?php

/**
 *  ICEPAY Basicmode API 2
 *  BasicMode sample script
 *
 *  @version 1.0.1
 *  @author Olaf Abbenhuis
 *  @copyright Copyright (c) 2011-2012, ICEPAY
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
define('MERCHANTID', 12345);//<--- Change this into your own merchant ID
define('SECRETCODE', "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx");//<--- Change this into your own merchant ID 

// Include the API
require_once '../api/icepay_api_basic.php';

/* Apply logging rules */
$logger = Icepay_Api_Logger::getInstance();
$logger->enableLogging()
        ->setLoggingLevel(Icepay_Api_Logger::LEVEL_ALL)
        ->logToFile()
        ->setLoggingDirectory(realpath("../logs"))
        ->setLoggingFile("basicmode.txt")
        ->logToScreen();


/* Set the paymentObject */
$paymentObj = new Icepay_PaymentObject();
$paymentObj->setAmount(1000)
            ->setCountry("NL")
            ->setLanguage("NL")
            ->setReference("My Sample Website")
            ->setDescription("My Sample Payment")
            ->setCurrency("EUR")
            ->setOrderID(1);


try {
    // Merchant Settings
    $basicmode = Icepay_Basicmode::getInstance();
    $basicmode->setMerchantID(MERCHANTID)
            ->setSecretCode(SECRETCODE)
            ->validatePayment($paymentObj);

    echo(sprintf("<a href=\"%s\">%s</a>",$basicmode->getURL(),$basicmode->getURL()));
    
} catch (Exception $e){
    echo($e->getMessage());
}



?>
