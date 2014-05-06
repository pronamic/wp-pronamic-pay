<?php

/**
 *  ICEPAY Basicmode API 2
 *  Paymentmethod filter sample script
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
define('MERCHANTID',12345);//<--- Change this into your own merchant ID
define('SECRETCODE',"xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx");//<--- Change this into your own merchant ID

// Include the API into your project
require_once '../api/icepay_api_basic.php';

/* Apply logging rules */
$logger = Icepay_Api_Logger::getInstance();
$logger->enableLogging()
        ->setLoggingLevel(Icepay_Api_Logger::LEVEL_ALL)
        ->logToFile()
        ->setLoggingDirectory(realpath("../logs"))
        ->setLoggingFile("filter.txt")
        ->logToScreen();


// Read paymentmethods from folder, load the classes and filter the data
$api = Icepay_Api_Basic::getInstance()
        ->readFolder(realpath('../api/paymentmethods'))
        ->prepareFiltering()
        ->filterByCurrency("EUR")
        ->filterByCountry("NL")
        ->filterByAmount(1000)
        ->filterByLanguage("EN");

// Store the filtered data in an array;
$paymentmethods = $api->getArray();

// Checking if the user selected a paymentmethod
if (isset($_POST["paymentmethod"]) && $_POST["paymentmethod"] != ""){
    $postData = $_POST["paymentmethod"];
    //load the paymentmethod class
    $paymentmethod = new $postData();
    //Store the issuers for this paymentmethod into an array
    $issuers = $paymentmethod->getSupportedIssuers();
}

if (isset($_POST["issuer"]) && $_POST["issuer"] != ""){
    try {
       
        
        /* Set the paymentObject */
        $paymentObj = new Icepay_PaymentObject();
        $paymentObj->useBasicPaymentmethodClass($paymentmethod)
                    ->setAmount(1000)
                    ->setCountry("NL")
                    ->setLanguage("NL")
                    ->setReference("My Sample Website")
                    ->setDescription("My Sample Payment")
                    ->setCurrency("EUR")
                    ->setIssuer($_POST["issuer"]) //Ofcourse you should NEVER use POST values directly in your script like this.
                    ->setOrderID(); // You should always set the order ID, however, this is ommitted here for testing purposes
        
        // Merchant Settings
        $basicmode = Icepay_Basicmode::getInstance();
        $basicmode->setMerchantID(MERCHANTID)
                ->setSecretCode(SECRETCODE)
                ->validatePayment($paymentObj)
                ->setProtocol("http");// <--- Remove this if you're not on a local machine

        // In this testscript we're printing the url on screen.
        echo(sprintf("<a href=\"%s\">%s</a>",$basicmode->getURL(),$basicmode->getURL()));

    } catch (Exception $e){
        echo($e->getMessage());
    }

};

/* The following script displays selectboxes.
 * Once a valid paymentmethod and issuer have been selected,
 * the URL will be generated.
 * Note that no OrderID is being used for testing purposes.
 */
?>
<form  action="?" method="POST">
    <?php if (!isset($postData)): ?>
    <p> Select an Payment method:
        <select name="paymentmethod">
            <option value="" selected>Please select...</option>
            <?php
                foreach($paymentmethods as $name => $value){
                    echo("<option value=\"{$value}\" >{$name}</option>");
                }
            ?>
        </select>
    </p>
    <?php endif; ?>
    <?php if (isset($postData)): ?>
        <input type="hidden" name="paymentmethod" value="<?php echo($postData); ?>"/>
    <p> Select an Issuer:
        <select name="issuer">
            <option value="" selected>Please select...</option>
            <?php
                foreach($issuers as $issuer => $name){
                    echo("<option value=\"{$name}\">{$name}</option>");
                }
            ?>
        </select>
    </p>
    <p><input type="button" name="Back" value="<- Back" onClick="javascript:(window.location='?')"/></p>
    <?php endif; ?>
    <input type="submit" name="Submit" value="Submit changes"/>
</form>