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

class Pronamic_Shopp_IDeal_GatewayModule extends GatewayFramework implements GatewayModule {
	/**
	 * Flag to let Shopp know that this is gateway module doesn't require an secure connection
	 * 
	 * @var boolean
	 */
	public $secure = false;

	//////////////////////////////////////////////////

	/**
	 * The unique iDEAL configuration ID
	 * 
	 * @var string
	 */
	private $configurationId;

	//////////////////////////////////////////////////
	
	/**
	 * Constructs and initialize an iDEAL gateway module
	 */
	public function __construct() {
		parent::__construct();

		// Setup
		$this->setup('pronamic_shopp_ideal_configuration');
		
		// Configuration ID
		$this->configurationId = $this->settings['pronamic_shopp_ideal_configuration'];
		
		// Checkout gateway inputs
		add_filter('shopp_checkout_gateway_inputs', array($this, 'inputs'), 50);

		// Order receipt
		add_filter('shopp_order_receipt', array($this, 'iDealForm'));
		add_filter('shopp_order_lookup', array($this, 'iDealForm'));
	}

	//////////////////////////////////////////////////
	
	/**
	 * Add actions
	 */
	function actions() {
		/*
		 * In case of iDEAL advanced we have to store the chosen issuer ID on the 
		 * checkout page. We will store the chosen issuer ID in the 'shopp_checkout_processed'
		 * action routine. This routine is triggered after processing all the 
		 * checkout information.
		 */
		add_action('shopp_checkout_processed', array($this, 'checkoutProcessed'));

		/*
		 * In the Shopp settings checkout page you can require an confirmation for the 
		 * order with order with tax or always. The 'shopp_process_order' action routine 
		 * is only executed after the confirmation or directly when confirmation is not 
		 * required (@see Order.php #357 and #396).
		 * 
		 * We set the priority a little higher then the default priority becease our 
		 * function is probably redirecting the user. We want to make sure all actions
		 * added by other plugins are executed.
		 */
		add_action('shopp_process_order', array($this, 'processOrder'), 50);
	}

	//////////////////////////////////////////////////

	/**
	 * Checkout processed
	 */
	public function checkoutProcessed() {
		global $Shopp;

		$issuerId = filter_input(INPUT_POST, 'pronamic_ideal_issuer', FILTER_SANITIZE_STRING);
		if(!empty($issuerId)) {
			$Shopp->Order->PronamicIDealIssuerId = $issuerId;
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Process order
	 */
	public function processOrder() {
		global $Shopp;

		$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById($this->configurationId);

		if($configuration !== null) {
			$variant = $configuration->getVariant();

			switch($variant->getMethod()) {
				case Pronamic_IDeal_IDeal::METHOD_EASY:
					// Nothing to do here
					break;
				case Pronamic_IDeal_IDeal::METHOD_BASIC:
					// Nothing to do here
					break;
				case Pronamic_IDeal_IDeal::METHOD_ADVANCED:
					$this->processOrderIDealAdvanced($configuration, $Shopp->Order);
					break;
			}
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Process order iDEAL advanced
	 */
	public function processOrderIDealAdvanced($configuration, $order) {
		global $Shopp;

		$id = $order->purchase;

		$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentBySource('shopp', $id);

		if($payment == null) {
			$amount = $order->Cart->Totals->total;
			$currency = $this->baseop['currency']['code'];

			$transaction = new Pronamic_IDeal_Transaction();
			$transaction->setAmount($amount); 
			$transaction->setCurrency($currency);
			$transaction->setExpirationPeriod('PT1H');
			$transaction->setLanguage('nl');
			$transaction->setEntranceCode(uniqid());
			$transaction->setDescription(sprintf(__('Order %s', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN), $id));
	
			$payment = new Pronamic_WordPress_IDeal_Payment();
			$payment->configuration = $configuration;
			$payment->transaction = $transaction;
			$payment->setSource('shopp', $id);
	
			$updated = Pronamic_WordPress_IDeal_PaymentsRepository::updatePayment($payment);
    	}

    	$issuerId = $Shopp->Order->PronamicIDealIssuerId;

		$url = Pronamic_WordPress_IDeal_IDeal::handleTransaction($issuerId, $payment, $configuration->getVariant());

		wp_redirect($url, 303);

		exit;
	}

	//////////////////////////////////////////////////
	
	/**
	 * iDEAL Form
	 */
	public function iDealForm($content = '') {
		global $Shopp;

		if($Shopp->Purchase->txnstatus == Pronamic_Shopp_Shopp::PAYMENT_STATUS_PENDING) {
			$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById($this->configurationId);

			if($configuration !== null) {
				$variant = $configuration->getVariant();

				switch($variant->getMethod()) {
					case Pronamic_IDeal_IDeal::METHOD_EASY:
						return $content;
					case Pronamic_IDeal_IDeal::METHOD_BASIC:
						return $this->iDealBasicForm($content, $configuration, $Shopp->Purchase);
					case Pronamic_IDeal_IDeal::METHOD_ADVANCED:
						return $content;
				}
			}
		}

		return $content;
	}
	
	/**
	 * iDEAL Basic Form
	 */
	private function iDealBasicForm($content = '', $configuration, $purchase) {
		$iDeal = new Pronamic_IDeal_Basic();

		$iDeal->setPaymentServerUrl($configuration->getPaymentServerUrl());
		$iDeal->setMerchantId($configuration->getMerchantId());
		$iDeal->setSubId($configuration->getSubId());
		$iDeal->setHashKey($configuration->hashKey);
		$iDeal->setLanguage('nl');
		$iDeal->setDescription(sprintf(__('Order %s', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN), $iDeal->getPurchaseId()));
		$iDeal->setCurrency('EUR');
		$iDeal->setPurchaseId($Shopp->Order->purchase);

		$iDeal->setSuccessUrl(shoppurl(false, 'thanks'));
		$iDeal->setCancelUrl(shoppurl(array('messagetype' => 'cancelled'), 'receipt'));
		$iDeal->setErrorUrl(shoppurl(array('messagetype' => 'error'), 'receipt'));

        // Items
		$items = self::getIDealItemsFromShoppPurchase($purchase);
		
		$iDeal->setItems($items);		
		
		// Payment
		$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentBySource('shopp', $iDeal->getPurchaseId());
		if($payment == null){
			// Update payment
			$transaction = new Pronamic_IDeal_Transaction();
			$transaction->setAmount($iDeal->getAmount()); 
			$transaction->setCurrency($iDeal->getCurrency());
			$transaction->setLanguage($iDeal->getLanguage());
			$transaction->setEntranceCode(uniqid());
			$transaction->setDescription($iDeal->getDescription());
			
			$payment = new Pronamic_WordPress_IDeal_Payment();
			$payment->configuration = $configuration;
			$payment->transaction = $transaction;
			$payment->setSource('shopp', $iDeal->getPurchaseId());
			
			Pronamic_WordPress_IDeal_PaymentsRepository::updatePayment($payment);
		}
		
		// Output
		$form .= '<form method="post" action="'.$configuration->getPaymentServerUrl().'">';
		$form .= '	' . $iDeal->getHtmlFields();
		$form .= '	<input type="submit" value="'.__('Pay with iDEAL', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN).'" name="submit" />';
		$form .= '</form>';

		return $form . $content;

		/*
		// Basic and Advanced payment
		if(!$this->isAdvancedPayment()){ // BASIC
			// Set urls for basic transaction
			$_SESSION['ec'] = $entranceCode;
			$iDeal->setSuccessUrl(add_query_arg(array('purchase' => $iDeal->getPurchaseId(), 'transactid' => $Shopp->Purchase->txnid, 'ec' => sha1($entranceCode)), shoppurl(false, 'thanks')));
			$iDeal->setCancelUrl(shoppurl(array('messagetype' => 'cancelled'), 'thanks'));
			$iDeal->setErrorUrl(shoppurl(array('messagetype' => 'error'), 'thanks'));
			
			// Output
			$form .= '<form method="post" action="'.$configuration->getPaymentServerUrl().'">';
			$form .= '	' . $iDeal->getHtmlFields();
			$form .= '	<input type="submit" value="'.__('Pay with iDEAL', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN).'" name="submit" />';
			$form .= '</form>';
		}elseif($this->isAdvancedPayment()){ // ADVANCED (Is called earlier in process because of the headers it needs to modify)
			// Get tansaction url.
			$issuerId = $_POST['pronamic_ideal_issuer'];
			$url = Pronamic_WordPress_IDeal_IDeal::handleTransaction($issuerId, $payment, $variant);
			
			// Direct to advanced ideal payment site
			wp_redirect($url, 303);

			exit;
		}
		*/
	}
	
	/**
	 * Build the iDeal object for both payment configurations,
	 * basic and advanced.
	 * 
	 * @param Pronamic_IDeal_Basic $IDeal
	 * @param Pronamic_WordPress_IDeal_Configuration $configuration
	 * @return IDeal $IDeal
	 */
	public static function getIDealItemsFromShoppPurchase($purchase) {
		$items = new Pronamic_IDeal_Items();

		// Purchased
		foreach($purchase->purchased as $p) {
			// Item
			$item = new Pronamic_IDeal_Item();
			$item->setNumber($p->id);
			$item->setDescription($p->name);
			$item->setQuantity($p->quantity);
			$item->setPrice($p->unitprice);

			$items->addItem($item);
		}
		
		// Freight
		if($purchase->freight > 0){
			$item = new Pronamic_IDeal_Item();
			$item->setNumber('freight');
			$item->setDescription(__('Shipping', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN));
			$item->setQuantity(1);
			$item->setPrice($purchase->freight);

			$items->addItem($item);
		}
		
		// Tax
		if($purchase->tax > 0){
			$item = new Pronamic_IDeal_Item();
			$item->setNumber('tax');
			$item->setDescription(__('Tax', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN));
			$item->setQuantity(1);
			$item->setPrice($purchase->tax);

			$items->addItem($item);
		}

		return $items;
	}

	//////////////////////////////////////////////////
	
	/**
	 * Inputs
	 */
	public function inputs($inputs) {
		$result = '';

		$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById($this->configurationId);

		if($configuration !== null){
			$variant = $configuration->getVariant();

			if($variant !== null && $variant->getMethod() == Pronamic_IDeal_IDeal::METHOD_ADVANCED){
				$lists = Pronamic_WordPress_IDeal_IDeal::getTransientIssuersLists($configuration);

				if($lists) {
					$result .= '<div id="pronamic_ideal_issuer">';
					$result .= '	<div class="left">';

					$result .= '		<select name="pronamic_ideal_issuer" id="order-data-pronamic-ideal-issuer"  title="" value="" class="required">';
					
					foreach($lists as $list) {
						foreach($list as $value) {
							$result .= '	<option value="'.$value->getId().'">' . $value->getName() . '</option>';
						}
					}

					$result .= '		</select>';

					$result .= '		<label for="pronamic_ideal_issuer_id">';
					$result .= '			' . __('iDEAL Issuer', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN);
					$result .= '		</label>';
					$result .= '	</div>';
					$result .= '</div>';
				} elseif($error = Pronamic_WordPress_IDeal_IDeal::getError()) {
					$result .= '<div class="shopp_error">';
					$result .= '	' . $error->getConsumerMessage();
					$result .= '</div>';
				} else {
					$result .= '<div class="shopp_error">';
					$result .= '	' . __('Paying with iDEAL is not possible. Please try again later or pay another way.', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN);
					$result .= '</div>';
				}
		
				// Only show extra fields on this paymethod/gateway
				$script = '
					$(document).bind("shopp_paymethod", function(event, paymethod) {
						if(paymethod) {
							var fields = $("#pronamic_ideal_issuer");
		
							if(paymethod.indexOf("' . sanitize_title_with_dashes($this->settings['label']) . '") !== -1) {
								fields.show();
							} else {
								fields.hide();
							}
						}
					});
				';
		
				add_storefrontjs($script);
			}
		}

		return $inputs . $result;
	}

	//////////////////////////////////////////////////
	
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

	//////////////////////////////////////////////////
	
	/**
	 * Settings
	 */
	function settings () {
		$configurations = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurations();

		$options = array(__('&mdash; Select configuration &mdash;', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN));
		foreach($configurations as $configuration) {
    		$options[$configuration->getId()] = $configuration->getName();
    	}
		
		$this->ui->menu(1, array(
			'name' => 'pronamic_shopp_ideal_configuration' , 
			'label' => __('Select configuration', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) , 
			'selected' => $this->settings['pronamic_shopp_ideal_configuration']
		), $options);
	}	
}
