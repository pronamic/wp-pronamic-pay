<?php

/**
 *  ICEPAY Basicmode API 2: Webservices - Payment method filtering
 *  Sample script: Filter the retrieved paymentmethods
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

// Include the API into your project
require_once '../api/icepay_api_webservice.php';

/* Start the filter class */
$filter = Icepay_Api_Webservice::getInstance()->filtering();

try {
    /* Filter the paymentmethods stored by using the paymentmethod retrieval service. Sample: sample_webservice_getpaymentmethods.php */
    $filter->loadFromFile("data",realpath("../wsdata"));
} catch (Exception $e){
    echo("Something went wrong, did you use the sample_webservice_getpaymentmethods.php to collect data and store it first?\n<BR/>This is the error messsage: ".$e->getMessage());
    exit();
}

if (isset($_POST["currency"]) && $_POST["currency"] != "") $filter->filterByCurrency($_POST["currency"]);
if (isset($_POST["country"]) && $_POST["country"] != "") $filter->filterByCountry($_POST["country"]);
if (isset($_POST["amount"]) && $_POST["amount"] != "") $filter->filterByAmount($_POST["amount"]);

$sample_currencies = array("EUR","USD","GBP");
$sample_countries = array("NL","DE","GB");
$sample_amount = array(300,500,1000);

?>
<html>
<head><title>ICEPAY webservice sample: Filter stored paymentmethods</title></head>
<body>
<form  action="?" method="POST">
    <p> Select currency:
        <select name="currency">
            <option value="">Please select...</option>
            <?php
                foreach($sample_currencies as $value){
                    echo("<option value=\"{$value}\" ".((isset($_POST["currency"]) && $_POST["currency"] == $value)?"selected":"").">{$value}</option>");
                }
            ?>
        </select>
    </p>
    
    <p> Select country:
        <select name="country">
            <option value="">Please select...</option>
            <?php
                foreach($sample_countries as $value){
                    echo("<option value=\"{$value}\" ".((isset($_POST["country"]) && $_POST["country"] == $value)?"selected":"").">{$value}</option>");
                }
            ?>
        </select>
    </p>
    
    <p> Select amount:
        <select name="amount">
            <option value="">Please select...</option>
            <?php
                foreach($sample_amount as $value){
                    echo("<option value=\"{$value}\" ".((isset($_POST["amount"]) && $_POST["amount"] == $value)?"selected":"").">".floatval($value/100).".00</option>");
                }
            ?>
        </select>
    </p>
    <p>  <input type="submit" name="Submit" value="Filter paymentmethods"/> </p>
    <p> Select payment method (<?php echo count($filter->getFilteredPaymentmethods()) ?> available):
        <select name="paymentmethod">
            <option value="">Please select...</option>
            <?php
                foreach($filter->getFilteredPaymentmethods() as $value){
                    echo("<option value=\"{$value["PaymentMethodCode"]}\" ".((isset($_POST["paymentmethod"]) && $_POST["paymentmethod"] == $value["PaymentMethodCode"])?"selected":"").">{$value["Description"]}</option>");
                }
            ?>
        </select>
    </p>
    
    <?php if (isset($_POST["paymentmethod"]) && $_POST["paymentmethod"] != ""): ?>
    
    <?php 
    
        $method = Icepay_Api_Webservice::getInstance()->singleMethod()->importFromString($filter->exportAsString());
        $method->selectPaymentMethodByCode($_POST["paymentmethod"])->selectCountry($_POST["country"]);
    ?>
    <p> Select issuer (<?php echo count($method->getIssuers()) ?> available):
        <select name="issuer">
            <option value="">Please select...</option>
            <?php
                foreach($method->getIssuers() as $value){
                    echo("<option value=\"{$value["IssuerKeyword"]}\" ".((isset($_POST["issuer"]) && $_POST["issuer"] == $value["IssuerKeyword"])?"selected":"").">{$value["Description"]}</option>");
                }
            ?>
        </select>
    </p>
    <?php endif; ?>

    <p>  <input type="submit" name="Submit" value="Submit changes"/> </p>


    
</form>
<pre>
<?php

if (!isset($_POST["startpayment"]) || $_POST["startpayment"] == "") {
    var_dump($filter->getFilteredPaymentmethods());
    exit();
}

?>
</pre>
</body></html>