<?php
/**
 *  ICEPAY Basicmode API 2: Webservices - Get Payment
 *  Sample script: Get current details of a payment
 *
 *  @version 1.0.0
 *  @author Olaf Abbenhuis
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

/*  Define your ICEPAY Merchant ID and Secret code. The values below are sample values and will not work, Change them to your own merchant settings. */
define('MERCHANTID', 12345); //<--- Change this into your own merchant ID
define('SECRETCODE', "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"); //<--- Change this into your own merchant ID
define('PAYMENTID', 1234567); // <--- Change this into the payment ID

// Include the API into your project
require_once '../api/icepay_api_webservice.php';

$payment = Icepay_Api_Webservice::getInstance()->paymentService();
$logger  = Icepay_Api_Logger::getInstance();

try {
    // Merchant Settings
    $payment->setMerchantID(MERCHANTID)
            ->setSecretCode(SECRETCODE);

    /* Apply logging rules */
    $logger->enableLogging()
        ->setLoggingLevel(Icepay_Api_Logger::LEVEL_ALL)
        ->logToFile()
        ->setLoggingDirectory(realpath("../logs"))
        ->setLoggingFile("getpayment.txt")
        ->logToScreen()
        ->log('Sample log entry for sample_webservice_getpayment.php', Icepay_Api_Logger::NOTICE);
    
} catch (Exception $e) {
    echo($e->getMessage());
}
?>

<pre>
<?php
try {
    var_dump($payment->getPayment(PAYMENTID));
} catch (Exception $e) {
    echo($e->getMessage());
}
?>
</pre>