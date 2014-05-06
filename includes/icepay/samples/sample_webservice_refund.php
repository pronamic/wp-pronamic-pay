<?php

/**
 *  ICEPAY Basicmode API 2: Webservices - Refund request
 *  Sample script: ICEPAY Webservices Refund Example Script
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

/*  Define your ICEPAY Merchant ID and Secret code. The values below are sample values and will not work, Change them to your own merchant settings. */
define('MERCHANTID', 12345); //<--- Change this into your own merchant ID
define('SECRETCODE', "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"); //<--- Change this into your own merchant ID

// Include the API into your project
require_once '../api/icepay_api_webservice.php';

// Initiate the Refund instance
$refund = Icepay_Api_Webservice::getInstance()->refundService();
$refund->setMerchantID(MERCHANTID)
       ->setSecretCode(SECRETCODE);
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
    <h1>Sample Webservice Refund</h1> 
    <div style="clear: both;"></div>
    
    
    
    
    <!-- Get Merchants !-->    
    <fieldset>
        <legend>Get Payment Refunds          
            <a href="" id="paymentrefunds" onclick="showHide('paymentrefunds'); return false;">[ Hide this Section ]</a>
        </legend>
        
        <div id="paymentrefunds-show">        
            <?php if (isset($_POST['runGetPaymentRefunds']) && is_numeric($_POST['paymentID'])) {                  
            
                $refunds = $refund->getPaymentRefunds($_POST['paymentID']);
            
            if (!empty($refunds)) { ?>
                <table>
                    <tr>
                        <th>Refund ID</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Request Date</th>
                    </tr> 
                <?php
                foreach ($refunds as $refund) { ?>
                    <tr>
                        <td><?php echo $refund->RefundID; ?></td>
                        <td><?php echo $refund->Status; ?></td>
                        <td><?php echo $refund->RefundAmount; ?></td>
                        <td><?php echo $refund->DateCreated; ?></td>
                    </tr>
                <?php } ?>
                </table>            
            <?php } else { echo "No Refunds Found!"; } 
            } else { ?>        
                <form action="" method="post">       
                    <p>The GetPaymentRefunds web method allows you to query refund request information that belongs to the payment.</p>
                    <label for="paymentID">Payment ID</label> <input type="text" name="paymentID" /> <input type="submit" name="runGetPaymentRefunds" value="Run!" />
                </form>     
            <?php } ?>
        </div>
    </fieldset>   
    
    
    
    
    
    
    
    <!-- Request Refund !-->
    <fieldset>
        <legend>Request Refund          
            <a href="" id="requestrefund" onclick="showHide('requestrefund'); return false;">[ Hide this Section ]</a>
        </legend>
        
        <div id="requestrefund-show">        
            <?php if (isset($_POST['runRequestrefund'])) {
                $paymentID       = $_POST['paymentID'];
                $paymentAmount   = $_POST['paymentAmount'];
                $paymentCurrency = $_POST['paymentCurrency'];
                
                $refund = $refund->requestRefund($paymentID, $paymentAmount, $paymentCurrency);
            ?>
            <table>                                        
                <tr>
                    <th>Refund ID</th>
                    <th>Refund Amount</th>
                    <th>Refund Remaining Amount</th>
                    <th>Timestamp</th>
                </tr>
                <tr>
                    <td><?php echo $refund['RefundID']; ?></td>      
                    <td><?php echo $refund['RefundAmount']; ?></td>   
                    <td><?php echo $refund['RemainingRefundAmount']; ?></td>   
                    <td><?php echo $refund['Timestamp']; ?></td>   
                </tr>
            </table>           
            <?php } else { ?>        
            <form action="" method="post">       
                <p>The RequestRefund web method allows you to initiate a refund request for a payment. You can request 
                the entire amount to be refunded or just a part of it. If you request only a partial amount to be refunded 
                then you  are allowed to perform refund requests for the same payment until you have reached its full 
                amount. After that you cannot request refunds anymore for that payment.</p>
                <label for="paymentID">Payment ID</label> <input type="text" name="paymentID" />
                <label for="paymentAmount">Amount (cents)</label> <input type="text" name="paymentAmount" value="20" />
                <label for="paymentCurrency">Currency</label> <input type="text" name="paymentCurrency" value="EUR" />
                <input type="submit" name="runRequestrefund" value="Run!" />
            </form>     
            <?php } ?>
        </div>
    </fieldset>
    
    
    
    
    
    
    
    <!-- Cancel Refund !-->
    <fieldset>
        <legend>Cancel Refund          
            <a href="" id="cancelrefund" onclick="showHide('cancelrefund'); return false;">[ Hide this Section ]</a>
        </legend>
        
        <div id="cancelrefund-show">        
            <?php if (isset($_POST['runCancelRefund'])) {
                $refundID  = $_POST['refundID'];
                $paymentID = $_POST['paymentID'];
                
                $refund = $refund->cancelRefund($refundID, $paymentID);
                
                $message = $refund['Success'] ? "Refund was completed" : "Error occured";                
                echo $message;
            } else { ?>        
            <form action="" method="post">       
                <p>The CancelRefund web method allows you to cancel a refund request if it has not already been processed.</p>
                <label for="refundtID">Refund ID</label> <input type="text" name="refundID" />
                <label for="paymentID">Payment ID</label> <input type="text" name="paymentID"/>
                <input type="submit" name="runCancelRefund" value="Run!" />
            </form>     
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
