<?php

/**
 *  ICEPAY Basicmode API 2: Webservices - Reporting
 *  Sample script: ICEPAY Webservices Reporting Example Script
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


// Define your merchant ID, Pintcode, User and Useragent
// Note: The values below are sample values and will not work, Change them to your own merchant settings.
define('MERCHANTID', 12345);
define('REPORTINGPINCODE', 123456);// <--- The pincode needs to be requested via ICEPAY seperatly. Support -> New case "Request pincode"
define('REPORTINGUSER', "xxxxxxx");
define('REPORTINGUSERAGENT', "xxxxxxxxxxxxxxxxxxx");

// Include the API into your project
require_once '../api/icepay_api_webservice.php';

// Initiate the Refund instance
$reporting = Icepay_Api_Webservice::getInstance()->reportingService();

$reporting->setUserName(REPORTINGUSER)
          ->setPinCode(REPORTINGPINCODE)
          ->setUserAgent(REPORTINGUSERAGENT)
          ->setMerchantID(MERCHANTID)
          ->useCookie()
          ->usePHPSession()
          ->initSession(); /* creates session if not loaded from cookie or PHP session */


// To kill your session simply call the killSession() function
// 
//$reporting->killSession();
//exit;
?>
<!doctype html>
<html>
<head>
    <title>Icepay - Webservice Samples</title>

    <style>
    div#container {    width: 1000px;    min-height: 600px;    height: auto;    border: 1px solid #000;    margin: 50px auto;    padding: 10px;}
    div#sample {    margin: 10px 0;}
    fieldset {    margin-top: 30px;    display: block;}
    legend {    font-weight: bold;}
    p {    margin-top: 0;}
    p.note {    font-size: 13px;    margin-top: 10px;}
    th {    text-align: left;    padding-right: 30px;}
    h1 img {    float: left;    margin-right: 130px;}
    input[type=submit] {    cursor: pointer;    border: 1px solid #000;    padding: 0 15px;}
    a {    text-decoration: none;    color: darkblue;    font-weight: normal;}
    </style>
</head>
<body>

<div id="container">
    <h1>Sample Webservice Reporting</h1> 
    <div style="clear: both;"></div>
    
    
    
    
    
    
    
    <!-- Get Merchants !-->    
    <fieldset>
        <legend>Get Merchants            
            <a href="" id="merchants" onclick="showHide('merchants'); return false;">[ Hide this Section ]</a>
        </legend>
        
        <div id="merchants-show">        
            <?php if (isset($_POST['runMerchants'])) {
                $merchants = $reporting->getMerchants();               
            ?>            
            <table>
                <tr>
                    <th>Merchant ID</th>
                    <th>Description</th>
                    <th>Testmode</th>
                </tr>
            <?php foreach ($merchants as $merchant) { ?>
                <tr>
                    <td><?php echo $merchant->MerchantID; ?></td>
                    <td><?php echo $merchant->Description; ?></td>
                    <td><?php echo ($merchant->TestMode) ? 'True' : 'False'; ?></td>                    
                </tr>
            <?php } ?>
            </table>            
            <?php } else { ?>        
                <form action="" method="post">       
                    <p>This method allows you to retrieve a list of merchants that belong to your ICEPAY account</p>
                    <input type="submit" name="runMerchants" value="Run!" />
                </form>     
            <?php } ?>
        </div>
    </fieldset>
    
    
    
    
    
    
    
    <!-- Get Payment Methods !-->
    <fieldset>
        <legend>Get Payment Methods
            <a href="" id="paymentmethods" onclick="showHide('paymentmethods'); return false;">[ Hide this Section ]</a>
        </legend>
        
        <div id="paymentmethods-show">
            <?php if (isset($_POST['runGetPaymentMethods'])) {
                $paymentmethods = $reporting->getPaymentMethods();
            ?>
            <table>
                <tr>
                    <th>Description</th>
                    <th>Payment Method Code</th>
                </tr>
            <?php foreach ($paymentmethods as $method) { ?>
                <tr>
                    <td><?php echo $method->Description; ?></td>
                    <td><?php echo $method->PaymentMethodCode; ?></td>
                </tr>
            <?php } ?>
            </table>
            <?php } else { ?>        
                <form action="" method="post">  
                    <p>This method allows you to retrieve a list of all supported payment methods by ICEPAY</p>
                    <input type="submit" name="runGetPaymentMethods" value="Run!" />
                </form>     
            <?php } ?>    
        </div>
    </fieldset>
    
    
    
    
    
    
    
    <!-- Monthly Turnover Totals !-->
    <fieldset>
        <legend>Monthly Turnover Totals
            <a href="" id="monthlyturnovertotals" onclick="showHide('monthlyturnovertotals'); return false;">[ Hide this Section ]</a>            
        </legend>    
        <div id="monthlyturnovertotals-show">
            <?php if (isset($_POST['runMonthlyTurnoverTotals'])) {
                $month = $_POST['month'];
                $year  = $_POST['year'];               
                
                $dayStatistics = $reporting->MonthlyTurnoverTotals($month, $year);
            ?>
            <table>
                <tr>
                    <th>Day</th>
                    <th>Month</th>
                    <th>Year</th>
                    <th>Transaction Count</th>
                    <th>Turnover</th>
                </tr>
            <?php foreach ($dayStatistics as $day) { ?>
                <tr>
                    <td><?php echo $day->Day; ?></td>                    
                    <td><?php echo $day->Month; ?></td>
                    <td><?php echo $day->Year; ?></td>
                    <td><?php echo $day->TransactionsCount; ?></td>
                    <td><?php echo $day->Turnover; ?></td>
                </tr>
            <?php } ?>
            </table>
            <?php } else { ?>        
                <form action="" method="post"><p>  
                    The MonthlyTurnoverTotals web method returns the sum of the turnover of all the transactions according to the provided criteria: month, year and currency.</p>
                    <label for="month">Month</label>
                    <input type="text" name="month" id="month" value="6" />
                    <label for="year">Year</label>
                    <input type="text" name="year" id="year" value="2012" />
                    <input type="submit" name="runMonthlyTurnoverTotals" value="Run!" />
                </form>     
            <?php } ?>    
        </div>
    </fieldset>
    
    
    
    
    
    
    
    <!-- Search Payments !-->    
    <fieldset>
        <legend>Search Payments
            <a href="" id="searchpayments" onclick="showHide('searchpayments'); return false;">[ Hide this Section ]</a>
        </legend>
        <div id="searchpayments-show">
            <?php if (isset($_POST['runSearchPayments'])) {
                $payments = $reporting->searchPayments(array("MerchantID" => $_POST['merchantID']));    
            ?>
            <table>
                <tr>
                    <th>Payment ID</th>
                    <th>Consumer IP Address</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Status Code</th>
                </tr>
            <?php foreach ($payments as $payment) { ?>
                <tr>
                    <td><?php echo $payment->PaymentID; ?></td>                    
                    <td><?php echo $payment->ConsumerIPAddress; ?></td>
                    <td><?php echo "{$payment->CurrencyCode} {$payment->Amount}"; ?></td>
                    <td><?php echo $payment->Status; ?></td>
                    <td><?php echo $payment->StatusCode; ?></td>
                </tr>
            <?php } ?>
            </table>
            <?php } else { ?>            
                <p>Use the SearchPayments web method to search for payments linked to your ICEPAY account. There are 
                several filters which you can employ for a more detailed search.</p>
                <form action="" method="post">
                    <label for="merchantID">Merchant ID</label> <input type="text" name="merchantID" value="<?php echo MERCHANTID; ?>" /> 
                    <input type="submit" name="runSearchPayments" value="Run!" />
                </form>
                <p class="note"><b>Note:</b> For more searchfilters, check the webservice manual under section 6.8 SEARCHPAYMENTS</p>
            <?php } ?>
        </div>
    </fieldset>
    
    
</div>
    
<script type="text/javascript">
function showHide(id) {
    if (document.getElementById(id)) {
        if (document.getElementById(id+'-show').style.display == 'none') {
            document.getElementById(id+'-show').style.display = 'block';
            document.getElementById(id).innerHTML = '[ Hide this Section ]';
        }
        else {
            document.getElementById(id+'-show').style.display = 'none';
            document.getElementById(id).innerHTML = '[ Show this Section ]';
        }
    }
}    
</script>

</body>
    
</html>



<?php

/* Kills the session */
//$reporting->killSession();

?>


