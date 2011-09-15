<?php
/**
 * Pronamic iDEAL
 *
 * @author Pronamic
 * @version 0.1
 * @copyright Pronamic
 * @package shopp
 * @since 1.1.5
 * @subpackage IDealGatewayModule
 **/

require_once(SHOPP_PATH."/core/model/XML.php");

class IDealGatewayModule extends GatewayFramework implements GatewayModule {

	var $secure = false;
	var $url = 'http://www.mollie.nl/xml/ideal/';
	var $buttonurl = '/gateways/iDealMollie/ideal.png';

	function __construct () {
		parent::__construct();
		$this->setup('account','testmode');
		$this->buttonurl = SHOPP_PLUGINURI.$this->buttonurl;

		// Setup extra checkout inputs here so they are available
		// any time the gateway is active
		add_filter('shopp_checkout_gateway_inputs',array(&$this,'inputs'));
		add_filter('shopp_checkout_submit_button',array(&$this,'submit'),10,3);

	}

	function actions () {
		add_action('shopp_checkout_processed', array(&$this,'checkout'));

		add_action('shopp_remote_payment',array(&$this,'returned'));
		add_action('shopp_process_order',array(&$this,'process'));
	}

	function checkout () {
		global $Shopp;

		$this->Order->Billing->cardtype = "iDeal";

		$_ = array();

		$_['partnerid']				= $this->settings['account'];

		// Options
		$_['a'] 					= "fetch"; // specify fetch mode
		$_['returnurl']				= shoppurl(array('rmtpay'=>'process'),'confirm-order');
		$_['reporturl']				= shoppurl(array(
											'rmtpay'=>'process',
											'idealreport'=>1 // add a marker for reports so we can ignore them
										),'confirm-order');
		// Line Items
		$description = array();
		foreach($Shopp->Order->Cart->contents as $i => $Item)
			$description[] = $Item->quantity."x ".$Item->name.((!empty($Item->option->label))?' '.$Item->option->label:'');

		// Transaction
		$_['bank_id']				= $_POST['idealmollie-bank'];
		$_['amount']				= round(floatvalue($Shopp->Order->Cart->Totals->total)*100);
		$_['description']			= join(", ",$description);

		$request = $this->encode($_);
		$Response = $this->send($request);

		if (empty($Response) || $this->error($Response)) return false;

		$url = $Response->content('URL');
		if (!empty($url)) shopp_redirect($url);

		return false;
	}

	function returned () {
		if (isset($_GET['idealreport'])) die('1'); // Ignore Mollie.nl reports

		if (isset($_GET['transaction_id']) && !isset($_GET['idealreport']))
			do_action('shopp_process_order');

	}

	function process () {
		global $Shopp;
		if (empty($_GET['transaction_id'])) return false;

		$_['a'] 					= "check"; // specify check mode
		$_['partnerid']				= $this->settings['account'];
		$_['transaction_id']		= $_GET['transaction_id'];
		if ($this->settings['testmode'] == "on")
			$_['testmode'] = 'true';

		if (!$Shopp->Order->validate()) {
			new ShoppError(__('There is not enough customer information to process the order.','Shopp'),'invalid_order',SHOPP_TRXN_ERR);
			shopp_redirect(shoppurl(false,'cart'));
		}

		// Check for unique transaction id
		$Purchase = new Purchase($_['transaction_id'],'txnid');
		if(!empty($Purchase->id)){
			if(SHOPP_DEBUG) new ShoppError(__('Order validation failed. Received duplicate transaction id: ','Shopp').$_['transaction_id'], 'duplicate_order',SHOPP_TRXN_ERR);
			shopp_redirect(shoppurl(false,'cart'));
		}

		// Try up to 3 times
		for ($i = 3; $i > 0; $i--) {
			$request = $this->encode($_);
			$Response = $this->send($request);

			if (empty($Response) || $this->error($Response)) return false;
			$payment = $Response->content('payed');
		 	if ($payment == "true") break;
			usleep(1000); // Slight delay between requests
		}

		if ($payment == "false") {
			new ShoppError(__('Payment could not be confirmed, this order cannot be processed.','Shopp'),'ideal_mollie_transaction_error',SHOPP_TRXN_ERR);
			shopp_redirect(shoppurl(false,'cart'));
			return;
		}

		$Shopp->Order->transaction($_['transaction_id'],'CHARGED');
	}

	function error ($Response) {
		if (empty($Response)) return false;
		$code = $Response->content('errorcode');
		$message = $Response->content('message');
		if (!$code) return false;

		return new ShoppError($message,'ideal_mollie_transaction_error',SHOPP_TRXN_ERR,
			array('code'=>$code));
	}

	function send ($data) {
		$result = parent::send($data,$this->url);
		return new xmlQuery($result);
	}

	function submit ($tag=false,$options=array(),$attrs=array()) {
		$tag[$this->settings['label']] = '<input type="image" name="process" src="'.$this->buttonurl.'" alt="iDeal" width="75" height="75" '.inputattrs($options,$attrs).' />';
		return $tag;
	}

	function inputs ($options) {
		global $Shopp;

		$_ = array();
		$_['a'] = "banklist";
		if ($this->settings['testmode'] == "on") $_['testmode'] = 'true';

		$request = $this->encode($_);
		$Response = $this->send($request);

		$result = '';
		if ($banks = $Response->tag('bank')) {
			$result .= '<div id="idealmollie-fields">';
			$result .= '<h3 class="mast" for="idealmollie-bank">'.__('iDeal Payment','Shopp').'</h3>';
			$result .= '<span><select name="idealmollie-bank" id="idealmollie-bank">';
			while($bank = $banks->each()) {
				$id = $bank->content('bank_id');
				$name = $bank->content('bank_name');
 				$result .= '<option value="'.$id.'">'.$name.'</option>';
			}
			$result .= '</select>';
			$result .= '<label for="idealmollie-bank">'.__('iDeal Bank','Shopp').'</label></span>';
			$result .= '</div>';
		}

		$script = array();
		$script[] = "$(document).bind('shopp_paymethod',function (e,pm) {";
		// Explicitly use the default when establishing the callback, as this handler is established after
		// the checkout.js behaviors are loaded and processed because of order of operations
		$script[] = "	if (!pm) pm = d_pm;";
		$script[] = "	var f = $('#idealmollie-fields');";
		$script[] = "	if (pm && pm.indexOf('".sanitize_title_with_dashes($this->settings['label'])."') !== -1) f.show();";
		$script[] = "	else f.hide();";
		$script[] =  "});";

		add_storefrontjs(join("\n",$script));

		return $result;
	}

	function settings () {
		$styles = array("white"=>"On White Background","trans"=>"With Transparent Background");

		$this->ui->menu(1,array(
			'name' => 'buttonstyle',
			'selected' => $this->settings['buttonstyle']
		),$styles);
	}

} // END class iDealMollie

?>