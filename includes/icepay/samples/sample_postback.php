<?php

/**
 *  ICEPAY Basicmode API 2
 *  Postback sample script
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
define('SECRETCODE', "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx");//<--- Change this into your own merchant ID
define('EMAIL',"test@example.com");//<--- Change this into your own e-mail address

require_once '../api/icepay_api_basic.php';

/* Apply logging rules */
$logger = Icepay_Api_Logger::getInstance();
$logger->enableLogging()
        ->setLoggingLevel(Icepay_Api_Logger::LEVEL_ALL)
        ->logToFile()
        ->setLoggingDirectory(realpath("../logs"))
        ->setLoggingFile("postback.txt")
        ->logToScreen();

/* Start the postback class */
$icepay = new Icepay_Postback();
$icepay->setMerchantID(MERCHANTID)
        ->setSecretCode(SECRETCODE)
        ->doIPCheck(); // We encourage to enable ip checking for your own security

$order  = new Example_Order(); // This is a dummy class to depict a sample usage.

try {
    if($icepay->validate()){
        // In this example the ICEPAY OrderID is identical to the Order ID used in our project
        $order->loadByOrderID($icepay->getOrderID());

        /* Only update the status if it's a new order (NEW)
         * or update the status if the statuscode allowes it.
         * In this example the project order status is an ICEPAY statuscode.
         */
        if ($order->getStatus() == "NEW" || $icepay->canUpdateStatus($order->getStatus())){
            $order->saveStatus($icepay->getStatus()); //Update the status of your order
            $order->sendMail(sprintf("icepay_status_update_to_%s",$order->getStatus()));
        }
        $order->updateStatusHistory($icepay->getTransactionString());
        echo "Validated!";
    } else die ("Unable to validate postback data");

    
} catch (Exception $e){
    echo($e->getMessage());
}


/* Example Classes */

class Example_Order {
    protected $status = "OPEN";
    public function loadByOrderID($id) {}
    public function getStatus() {return $this->status;}
    public function saveStatus($status) {$this->status = $status;}
    public function updateStatusHistory($string) {$this->sendMail($string);}
    public function sendMail($string){mail(EMAIL, "api test: ".$this->status, $string);}
}



?>
