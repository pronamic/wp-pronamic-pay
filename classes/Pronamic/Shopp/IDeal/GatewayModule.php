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
	 * Shopp 1.1 or lower will retrieve this from the documentation block above
	 * 
	 * @var string
	 */
	const NAME = 'Pronamic iDEAL';

	//////////////////////////////////////////////////
	// Supported features
	//////////////////////////////////////////////////

	/**
	 * Flag to let Shopp know that this gateway module capture separate of authorization
	 * 
	 * @var boolean
	 */
	public $captures = true;
	
	//////////////////////////////////////////////////
	// Config settings
	//////////////////////////////////////////////////

	/**
	 * Flag to let Shopp know that this gateway module doesn't require an secure connection
	 * 
	 * @var boolean
	 */
	public $secure = false;

	//////////////////////////////////////////////////
	// Other
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
		$this->configurationId = filter_var($this->settings['pronamic_shopp_ideal_configuration'], FILTER_VALIDATE_INT);

		// Order processing
		//add_filter('shopp_purchase_order_processing', array($this, 'orderProcessing'), 20, 2);

		// Checkout gateway inputs
		add_filter('shopp_checkout_gateway_inputs', array($this, 'inputs'), 50);

		// Order receipt
		add_filter('shopp_order_receipt', array($this, 'iDealForm'));
		add_filter('shopp_order_lookup', array($this, 'iDealForm'));

		// Actions
		// @see /shopp/core/model/Gateway.php#L122
		$name = sanitize_key(__CLASS__);

		add_action('shopp_' . $name . '_sale', array($this, 'sale'));
		add_action('shopp_' . $name . '_auth', array($this, 'auth'));
		add_action('shopp_' . $name . '_capture', array($this, 'capture'));
		add_action('shopp_' . $name . '_refund', array($this, 'refund'));
		add_action('shopp_' . $name . '_void', array($this, 'void'));
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
		 * 
		 * We don't have to confuse the 'shopp_process_checkout' action routine with 
		 * the 'shopp_checkout_processed' routine. The 'shopp_checkout_processed' is called
		 * after / within the 'shopp_process_checkout' routine.
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
		
		add_action('shopp_order_success', array($this, 'orderSuccess'));		
	}

	//////////////////////////////////////////////////

	/**
	 * Sale
	 * 
	 * @param OrderEventMessage $event
	 */
	public function sale($event) {
		$this->auth($event);
	}

	/**
	 * Auth
	 * 
	 * @param OrderEventMessage $event
	 */
	function auth($event) {
		$Order = $this->Order;
		$OrderTotals = $Order->Cart->Totals;
		$Billing = $Order->Billing;
		$Paymethod = $Order->paymethod();

		shopp_add_order_event($event->order, 'authed', array(
			'txnid' => time(),
			'amount' => $OrderTotals->total,
			'fees' => 0,
			'gateway' => $Paymethod->processor,
			'paymethod' => $Paymethod->label,
			'paytype' => $Billing->cardtype,
			'payid' => $Billing->card
		));
	}

	/**
	 * Capture
	 * 
	 * @param OrderEventMessage $event
	 */
	function capture(OrderEventMessage $event) {

	}

	/**
	 * Refund
	 * 
	 * @param OrderEventMessage $event
	 */
	function refund(OrderEventMessage $event) {

	}

	/**
	 * Void
	 * 
	 * @param OrderEventMessage $event
	 */
	function void(OrderEventMessage $event) {
		
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
	 * 
	 * This function is called in the 'shopp_process_order' action routine. 
	 * The 'shopp_process_order' action routine is only executed after the 
	 * confirmation or directly when confirmation is not required. 
	 */
	public function processOrder() {
		// Sets transaction information to create the purchase record
		// This call still exists for backward-compatibility (< 1.2)
		if(version_compare(SHOPP_VERSION, '1.2', '<')) {
			$this->Order->transaction($this->txnid(), Pronamic_Shopp_Shopp::PAYMENT_STATUS_PENDING);
		}

		return true;
	}

	//////////////////////////////////////////////////

	/**
	 * Order success
	 * 
	 * In Shopp version 1.1.9 the 'shopp_order_success' the purchase is given as first parameter, 
	 * in Shopp version 1.2+ the 'shopp_order_success' the purchase is not passed as parameter anymore
	 */
	public function orderSuccess($purchase = null) {
		// Check if the purchases is passed as first parameter, if not we 
		// will load the purchase from the global Shopp variable
		if(empty($purchase)) {
			global $Shopp;

			$purchase = $Shopp->Purchase;
		}

		// Check iDEAL configuration
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
				case Pronamic_IDeal_IDeal::METHOD_OMNIKASSA:
					// Nothing to do here
					break;
				case Pronamic_IDeal_IDeal::METHOD_ADVANCED:
					$this->processIDealAdvanced($configuration, $purchase);

					break;
			}
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Process order iDEAL advanced
	 */
	public function processIDealAdvanced($configuration, $purchase) {
		global $Shopp;

		$id = $purchase->id;
		
		$dataProxy = new Pronamic_Shopp_IDeal_IDealDataProxy($purchase, $this);

		$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentBySource($dataProxy->getSource(), $dataProxy->getOrderId());

		if($payment == null) {
			$transaction = new Pronamic_IDeal_Transaction();
			$transaction->setAmount($dataProxy->getAmount()); 
			$transaction->setCurrency($dataProxy->getCurrencyAlphabeticCode());
			$transaction->setExpirationPeriod('PT1H');
			$transaction->setLanguage($dataProxy->getLanguageIso639Code());
			$transaction->setEntranceCode(uniqid());
			$transaction->setDescription($dataProxy->getDescription());
			$transaction->setPurchaseId($dataProxy->getOrderId());
	
			$payment = new Pronamic_WordPress_IDeal_Payment();
			$payment->configuration = $configuration;
			$payment->transaction = $transaction;
			$payment->setSource($dataProxy->getSource(), $dataProxy->getOrderId());

			$updated = Pronamic_WordPress_IDeal_PaymentsRepository::updatePayment($payment);
    	}

    	$issuerId = $Shopp->Order->PronamicIDealIssuerId;

		$url = Pronamic_WordPress_IDeal_IDeal::handleTransaction($issuerId, $payment, $configuration->getVariant());

		wp_redirect($url, 303);

		exit;
	}

	//////////////////////////////////////////////////

	/**
	 * Is used
	 * 
	 * @param unknown_type $purchase
	 */
	private static function isUsed($purchase) {
		$isUsed = false;

		if(version_compare(SHOPP_VERSION, '1.2', '<')) {
			$isUsed = $purchase->gateway == self::NAME;
		} else {
			$isUsed = $purchase->gateway == __CLASS__;
		}

		return $isUsed;
	}
	
	//////////////////////////////////////////////////
	
	/**
	 * iDEAL Form
	 */
	public function iDealForm($content = '') {
		global $Shopp;
		
		$purchase = $Shopp->Purchase;

		if(self::isUsed($purchase)) {
			if(!Pronamic_Shopp_Shopp::isPurchasePaid($purchase)) { 
				$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById($this->configurationId);

				$dataProxy = new Pronamic_Shopp_IDeal_IDealDataProxy($purchase, $this);

				$html = Pronamic_WordPress_IDeal_IDeal::getHtmlForm($dataProxy, $configuration);

				$content = $html . $content;
			}
		}

		return $content;
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
					$result .= '			' . __('iDEAL Issuer', 'pronamic_ideal');
					$result .= '		</label>';
					$result .= '	</div>';
					$result .= '</div>';
				} elseif($error = Pronamic_WordPress_IDeal_IDeal::getError()) {
					$result .= '<div class="shopp_error">';
					$result .= '	' . $error->getConsumerMessage();
					$result .= '</div>';
				} else {
					$result .= '<div class="shopp_error">';
					$result .= '	' . __('Paying with iDEAL is not possible. Please try again later or pay another way.', 'pronamic_ideal');
					$result .= '</div>';
				}
		
				// Only show extra fields on this paymethod/gateway
				$script = '
					(function($) {
						$(document).bind("shopp_paymethod", function(event, paymethod) {
							if(paymethod) {
								var fields = $("#pronamic_ideal_issuer");
		
								if(paymethod.indexOf("' . sanitize_key($this->settings['label']) . '") !== -1) {
									fields.show();
								} else {
									fields.hide();
								}
							}
						});
					})(jQuery);
				';
		
				add_storefrontjs($script);
			}
		}

		return $inputs . $result;
	}

	//////////////////////////////////////////////////
	
	/**
	 * Settings
	 */
	function settings() {
		$configurations = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurations();

		$options = array(__('&mdash; Select configuration &mdash;', 'pronamic_ideal'));
		foreach($configurations as $configuration) {
    		$options[$configuration->getId()] = $configuration->getName();
    	}

		$this->ui->menu(1, array(
			'name' => 'pronamic_shopp_ideal_configuration' , 
			'label' => __('Select configuration', 'pronamic_ideal') , 
			'selected' => $this->configurationId , 
			'keyed' => true
		), $options);
	}	
}
