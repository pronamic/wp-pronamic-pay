<?php
/**
 * Pronamic iDEAL
 *
 * @author Pronamic
 * @version 1.0
 * @copyright Pronamic
 * @package shopp
 * @since 1.1.9
 * @subpackage Pronamic_Shopp_IDeal_GatewayModule
 **/

require_once(SHOPP_PATH."/core/model/XML.php");

class Pronamic_Shopp_IDeal_GatewayModule extends GatewayFramework implements GatewayModule {
	public $secure = false;
	private $buttonurl = 'ideal.png';
	private $configurationId;
	
	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
		$this->setup('pronamic_shopp_ideal_configuration');
		$this->buttonurl = plugins_url('', __FILE__ ).'/'.$this->buttonurl;
		
		// ConfigurationId
		$this->configurationId = $this->settings['pronamic_shopp_ideal_configuration'];
		
		// Setup extra checkout inputs here so they are available any time the gateway is active
		add_filter('shopp_checkout_gateway_inputs', array(&$this, 'inputs'));
		add_filter('shopp_checkout_submit_button', array(&$this, 'submit'), 10, 3);
		add_filter('shopp_order_receipt', array(&$this, 'form'));
	}
	
	/**
	 * Add actions
	 */
	function actions() {
		add_action('shopp_checkout_processed', array(&$this,'checkout'));
		add_action('shopp_init_checkout', array(&$this, 'process'));
	}
	
	/**
	 * Set order to confirmed after checkout
	 */
	function checkout() {
		$this->Order->transaction($this->txnid());
		return true;
	}
	
	/**
	 * Before going to the paypage this is what is set in the background
	 * All fields are hidden for the user, who will see a receipt.
	 */
	function form($output = ''){
		global $Shopp;
		//foreach($Shopp->Purchase as $key => $value) echo $key.' => '.$value.'<br />';
		$form = '';
		if($Shopp->Purchase->txnstatus == 'PENDING'){
			$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById($this->configurationId);
			if($configuration !== null){				
				// New iDeal object.
				$iDeal = new Pronamic_IDeal_Basic();
				
				// Order ID
				$iDeal->setPurchaseId($Shopp->Order->purchase);
				
				// Build the iDeal Object
				$iDeal = $this->buildIDeal($iDeal, $configuration);		
				//var_dump($Shopp->Order->Purchase);
				// Entrancecode
				$entranceCode = md5($sOrderId . '_' . date('YmdHis'));
				
				// $payment
				$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentBySource('shopp', $iDeal->getPurchaseId());
				if($payment == null){
					// Update payment
					$transaction = new Pronamic_IDeal_Transaction();
					$transaction->setAmount($iDeal->getAmount()); 
					$transaction->setCurrency($iDeal->getCurrency());
					$transaction->setLanguage($iDeal->getLanguage());
					$transaction->setEntranceCode($entranceCode);
					$transaction->setDescription($iDeal->getDescription());
					if($this->isAdvancedPayment()) $transaction->setExpirationPeriod('PT1H');
					
					$payment = new Pronamic_WordPress_IDeal_Payment();
					$payment->configuration = $configuration;
					$payment->transaction = $transaction;
					$payment->setSource('shopp', $iDeal->getPurchaseId());
					
					Pronamic_WordPress_IDeal_PaymentsRepository::updatePayment($payment);
				}
				
				// Basic and Advanced payment
				if(!$this->isAdvancedPayment()){ // BASIC
					// Set urls for basic transaction
					$_SESSION['ec'] = $entranceCode;
					$iDeal->setSuccessUrl(add_query_arg(array('purchase' => $iDeal->getPurchaseId(), 'transactid' => $Shopp->Purchase->txnid, 'ec' => sha1($entranceCode)), shoppurl(false, 'thanks')));
					$iDeal->setCancelUrl(shoppurl(array('messagetype' => 'cancelled'), 'thanks'));
					$iDeal->setErrorUrl(shoppurl(array('messagetype' => 'error'), 'thanks'));
					
					// Output
					$form .= '<form method="post" action="'.$configuration->getPaymentServerUrl().'">';
					$form .= $iDeal->getHtmlFields();
					$form .= '<input type="submit" value="'.__('Pay with iDEAL', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN).'" name="submit" /></form>';
				}elseif($this->isAdvancedPayment()){ // ADVANCED (Is called earlier in process because of the headers it needs to modify)
					// Get tansaction url.
					$issuerId = $_POST['pronamic_ideal_issuer'];
					$url = Pronamic_WordPress_IDeal_IDeal::handleTransaction($issuerId, $payment, $variant);
					
					// Direct to advanced ideal payment site
					header('Location: '.$url);
					die;
				}
			}
		}
		return $form.$output;
	}
	
	/**
	 * Build the iDeal object for both payment configurations,
	 * basic and advanced.
	 * 
	 * @param Pronamic_IDeal_Basic $IDeal
	 * @param Pronamic_WordPress_IDeal_Configuration $configuration
	 * @return IDeal $IDeal
	 */
	function buildIDeal(Pronamic_IDeal_Basic $iDeal, Pronamic_WordPress_IDeal_Configuration $configuration){
		global $Shopp;
		
		// Items (price)
		$items = array();
		foreach($Shopp->Purchase->purchased as $key => $item){
			// Item
			$items[$key] = new Pronamic_IDeal_Item();
			$items[$key]->setNumber($item->id);
			$items[$key]->setDescription($item->name);
			$items[$key]->setQuantity($item->quantity);
			$items[$key]->setPrice($item->unitprice);
			
			// Add item to iDeal Object
			$iDeal->addItem($items[$key]);
		}
		
		// Add shipping costs if the product needs shipping
		if($Shopp->Purchase->freight > 0){
			$shipping = new Pronamic_IDeal_Item();
			$shipping->setNumber(-9001);
			$shipping->setDescription(__('Shipping', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN));
			$shipping->setQuantity(1);
			$shipping->setPrice($Shopp->Purchase->freight);
			$iDeal->addItem($shipping);
		}
		
		// Add tax if there is any
		if($Shopp->Purchase->tax > 0){
			$tax = new Pronamic_IDeal_Item();
			$tax->setNumber(-9002);
			$tax->setDescription(__('Tax', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN));
			$tax->setQuantity(1);
			$tax->setPrice($Shopp->Purchase->tax);
			$iDeal->addItem($tax);
		}
		
		// Data
		$iDeal->setPaymentServerUrl($configuration->getPaymentServerUrl());
		$iDeal->setMerchantId($configuration->getMerchantId());
		$iDeal->setSubId($configuration->getSubId());
		$iDeal->setHashKey($configuration->hashKey);
		$iDeal->setLanguage('nl');
		$iDeal->setDescription(sprintf(__('Order %s', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN), $iDeal->getPurchaseId()));
		$iDeal->setCurrency('EUR');
		
		return $iDeal;
	}
	
	/**
	 * Process the Basic payment order
	 */
	function process(){
		global $Shopp, $wpdb; DatabaseObject::updates();
		var_dump();
		if(!$this->isAdvancedPayment() && !isset($_GET['messagetype'])){ // BASIC
			if(isset($_GET['purchase']) && isset($_GET['transactid']) && isset($_GET['ec'])){
				if($_GET['ec'] == sha1($_SESSION['ec'])){
					$wpdb->update($wpdb->prefix.SHOPP_DBPREFIX.'purchase', array('txnstatus' => 'CHARGED'), array('id' => $_GET['purchase'], 'txnid' => $_GET['transactid']));
					$Shopp->resession();
					return true;
				}
			}
		}
		return false;
	}
	
	/**
	 * Filter for submit button
	 */
	function submit ($tag=false,$options=array(),$attrs=array()) {
		$tag[$this->settings['label']] = '<input type="submit" value="'.__('Pay with iDEAL', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN).'" name="process" '.inputattrs($options,$attrs).' />';
		return $tag;
	}
	
	/**
	 * Add extra fields to your checkout form here.
	 */
	function inputs ($options) {
		$result = '';
		$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById($this->configurationId);
		if($configuration !== null){
			$variant = $configuration->getVariant();
			if($variant !== null && $variant->getMethod() == Pronamic_IDeal_IDeal::METHOD_ADVANCED){
				$lists = Pronamic_WordPress_IDeal_IDeal::getTransientIssuersLists($configuration);
				
				// Fill $result with a list of banks
				if($lists) {
					$result .= '
						<div id="pronamic_ideal_issuer">
							<p class="pronamic_ideal_issuer">
								<label for="pronamic_ideal_issuer_id">
									'.__('Choose your bank', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN).'
								</label>
								<select name="pronamic_ideal_issuer" id="order-data-pronamic-ideal-issuer"  title="" value="" class="required">';
									foreach($lists as $list){
										foreach($list as $value) $result .= '<option value="'.$value->getId().'">'.$value->getName().'</option>';
									}
								$result .= '</select>
							</p>
						</div>';
				}elseif($error = Pronamic_WordPress_IDeal_IDeal::getError()){
					$result .= '
						<div class="shopp_error">
							'.$error->getConsumerMessage().'
						</div>
					';
				}else{
					$result .= '
						<div class="shopp_error">
							'.__('Paying with iDEAL is not possible. Please try again later or pay another way.', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN).'
						</div>
					';
				}
				
				// Script to hide banks when another gateway is selected
				$script = array();
				$script[] = "$(document).bind('shopp_paymethod',function (e,pm) {";
				// Explicitly use the default when establishing the callback, as this handler is established after
				// the checkout.js behaviors are loaded and processed because of order of operations
				$script[] = "	if (!pm) pm = d_pm;";
				$script[] = "	var f = $('#pronamic_ideal_issuer');";
				$script[] = "	if (pm && pm.indexOf('".sanitize_title_with_dashes($this->settings['label'])."') !== -1) f.show();";
				$script[] = "	else f.hide();";
				$script[] =  "});";
				
				// Add script to WordPress
				add_storefrontjs(join("\n",$script));
			}
		}
		return $result;
	}
	
	/**
	 * Tests wether the payment is an advanced payment or not
	 * 
	 * @return boolean $advanced (true on advanced)
	 */
	function isAdvancedPayment(){
		$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById($this->settings['pronamic_shopp_ideal_configuration']);
		if($configuration !== null){
			$variant = $configuration->getVariant();
			if($variant !== null && $variant->getMethod() == Pronamic_IDeal_IDeal::METHOD_ADVANCED){
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Gateway settings
	 */
	function settings () {
		$configurations = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurations();
		$configurationOptions = array('' => __('&mdash; Select configuration &mdash; ', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN));
		foreach($configurations as $configuration) {
    		$configurationOptions[$configuration->getId()] = $configuration->getName();
    	}
		
		$this->ui->menu(1,array(
			'name' => 'pronamic_shopp_ideal_configuration',
			'label' => __('Select configuration', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN),
			'selected' => $this->settings['pronamic_shopp_ideal_configuration']
		),$configurationOptions);
	}	
}
