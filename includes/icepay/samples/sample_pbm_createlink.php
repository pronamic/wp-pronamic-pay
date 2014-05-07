<?php

/**
 *  ICEPAY PayByMail
 *  Sample script: Create a PBM link
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
require_once '../api/icepay_api_pbm.php';

try {
    /* Set the payment */
    $pbmObj = new Icepay_Pbm_Object();
    $pbmObj->setCurrency('EUR') // Default EUR
            ->setAmount(200)
            ->setCountry('NL') // Default NL
            ->setLanguage('NL') // Default NL
            ->setDescription('Test API')
            ->setReference('API REFERENCE') // By setting a unique reference, you could catch our postback.
            ->setOrderID('pbm1000001'); // Order ID does not work yet.

    $pbm = Icepay_Api_Pbm::getInstance();

    $pbm->setMerchantID(MERCHANTID)
            ->setSecretCode(SECRETCODE);

    $result = $pbm->createLink($pbmObj);
    
    if (true === $reesult->success) {
        echo $result->url;
    } else {
        echo $result->errorCode . '<br />';
        echo $result->errorMessage;
    }
} catch (Exception $e) {
    echo $e->getCode() . '<br />';
    echo $e->getMessage();
}

