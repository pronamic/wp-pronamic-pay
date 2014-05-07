<?php

/**
 *  ICEPAY Basicmode API 2: Webservices - Retrieve all paymentmethods
 *  Sample script: Retrieves payment methods for a merchant ID and saves the data to file.
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
define('MERCHANTID', 12345); //<--- Change this into your own merchant ID
define('SECRETCODE', "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"); //<--- Change this into your own merchant ID

// Include the API into your project
require_once '../api/icepay_api_webservice.php';

// Set the service
$service = Icepay_Api_Webservice::getInstance()->paymentMethodService();

// Configure the service
$service->setMerchantID(MERCHANTID)
    ->setSecretCode(SECRETCODE) 
    ->retrieveAllPaymentmethods() 
    ->saveToFile("data",realpath("../wsdata")); /* Saves the Paymentmethod array to a file. */

/* Alternatively you can also fetch the paymentmethods object and/or array. */
$paymentMethods = $service->retrieveAllPaymentMethods()->asArray();



?>
<html>
<body>
Paymentmethods succesfully retrieved from webservice...<BR/>
Use the sample_webservice_filtering.php for an example how to use the stored date and filter it.<BR/>
<pre>
<?php var_dump($paymentMethods) ?>
</pre>
</body>
</html>