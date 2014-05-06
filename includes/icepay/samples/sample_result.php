<?php

/**
 *  ICEPAY Basicmode API 2
 *  Result sample script
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

require_once '../api/icepay_api_basic.php';


/* Apply logging rules */
$logger = Icepay_Api_Logger::getInstance();
$logger->enableLogging()
        ->setLoggingLevel(Icepay_Api_Logger::LEVEL_ALL)
        ->logToFile()
        ->setLoggingDirectory(realpath("../logs"))
        ->setLoggingFile("result.txt")
        ->logToScreen();

$icepay = new Icepay_Result();
$icepay->setMerchantID(MERCHANTID)
        ->setSecretCode(SECRETCODE);

$order = new Example_Order(); // This is a dummy class to depict a sample usage.

try {
    if($icepay->validate()){

        switch ($icepay->getStatus()){
            case Icepay_StatusCode::OPEN:
                // Close the cart
                echo("Thank you, awaiting payment verification.");
                break;
            case Icepay_StatusCode::SUCCESS:
                // Close the cart
                echo("Thank you for your purchase. The payment has been received.");
                break;
            case Icepay_StatusCode::ERROR:
                //Redirect to cart
                echo(sprintf("An error occurred: %s",$icepay->getStatus(true)));
                break;
        }

        $order->updateStatusHistory(sprintf("Customer returned with statuscode %s",$icepay->getStatus(true)));

    } else die ("Unable to validate data");
} catch (Exception $e){
    echo($e->getMessage());
}


/* Example Classes */

class Example_Order {
    protected $status = "OPEN";
    public function getStatus() {return $this->status;}
    public function saveStatus($status) {$this->status = $status;}
    public function updateStatusHistory($string) {}
    public function sendMail(){}
}



?>
