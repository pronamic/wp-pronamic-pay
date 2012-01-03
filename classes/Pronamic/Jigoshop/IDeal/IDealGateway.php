<?php

/**
 * Title: Jigoshop iDEAL gateway
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Jigoshop_IDeal_IDealGateway extends jigoshop_payment_gateway {
	/**
	 * The unique ID of this payment gateway
	 * 
	 * @var string
	 */
	const ID = 'pronamic_ideal';

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize an iDEAL gateway
	 */
    public function __construct() { 
    	// Give this gateway an unique ID so Jigoshop can identiy this gateway
		$this->id = self::ID;

		// The method title that Jigoshop will display in the admin
		$this->method_title = __('Pronamic iDEAL', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN);
		
		// The icon that Jigoshop will display on the payment methods radio list
		$this->icon = plugins_url('images/icon-24x24.png', Pronamic_WordPress_IDeal_Plugin::$file);

		// Let Jigoshop know that this gateway has field
		// Technically only iDEAL advanced variants has fields
		$this->has_fields = true;

		// Set default Jigoshop variables, load them form the WordPress options
		$this->enabled = get_option('jigoshop_pronamic_ideal_enabled');
		$this->title = get_option('jigoshop_pronamic_ideal_title');
		$this->description = get_option('jigoshop_pronamic_ideal_description');
		
		// Set own variables, load them form the WordPress options
		$this->configurationId = get_option('jigoshop_pronamic_ideal_configuration_id');

		// Actions
		add_action('jigoshop_update_options', array(&$this, 'processAdminOptions'));
		
		add_action('receipt_' . self::ID, array(&$this, 'receiptPage'));

		// Add options
		add_option('jigoshop_pronamic_ideal_enabled', 'yes');
		add_option('jigoshop_pronamic_ideal_title', __('iDEAL', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN));
		add_option('jigoshop_pronamic_ideal_description', __('With iDEAL you can easily pay online in the secure environment of your own bank.', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN));
		add_option('jigoshop_pronamic_ideal_configuration_id', '');
    } 

	//////////////////////////////////////////////////
    
	/**
	 * Admin Panel Options 
	 * - Options for bits like 'title' and availability on a country-by-country basis
	 *
	 * @since 1.0.0
	 */
	public function admin_options() {
    	$configurations = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurations();
    	$configurationOptions = array('' => __('&mdash; Select configuration &mdash;', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN));
    	foreach($configurations as $configuration) {
    		$configurationOptions[$configuration->getId()] = $configuration->getName();
    	}

    	?>
    	<thead>
    		<tr>
    			<th scope="col" width="200px">
    				<?php _e('Pronamic iDEAL', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
    			</th>
    			<th scope="col" class="desc">
    			
    			</th>
    		</tr>
    	</thead>
		<tr>
			<td class="titledesc">
				<?php _e('Enable iDEAL', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) ?>:
			</td>
			<td class="forminp">
				<select name="jigoshop_pronamic_ideal_enabled" id="jigoshop_pronamic_ideal_enabled" style="min-width:100px;">
					<option value="yes" <?php selected($this->enabled, 'yes'); ?>><?php _e('Yes', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?></option>
					<option value="no" <?php selected($this->enabled, 'no'); ?>><?php _e('No', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?></option>
				</select>
	        </td>
	    </tr>
	    <tr>
	        <td class="titledesc"><a href="#" tip="<?php _e('This controls the title which the user sees during checkout.', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) ?>" class="tips" tabindex="99"></a><?php _e('Title', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>:</td>
	        <td class="forminp">
		        <input class="input-text" type="text" name="jigoshop_pronamic_ideal_title" id="jigoshop_pronamic_ideal_title" style="min-width:50px;" value="<?php if ($value = get_option('jigoshop_pronamic_ideal_title')) echo $value; else _e('iDEAL', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>" />
	        </td>
	    </tr>
	    <tr>
	        <td class="titledesc"><a href="#" tip="<?php _e('This controls the description which the user sees during checkout.', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) ?>" class="tips" tabindex="99"></a><?php _e('Description', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) ?>:</td>
	        <td class="forminp">
		        <input class="input-text wide-input" type="text" name="jigoshop_pronamic_ideal_description" id="jigoshop_pronamic_ideal_description" style="min-width:50px;" value="<?php if ($value = get_option('jigoshop_pronamic_ideal_description')) echo $value; ?>" />
	        </td>
	    </tr>
		<tr>
			<td class="titledesc">
				<?php _e('Configuration', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) ?>:
			</td>
			<td class="forminp">
				<select name="jigoshop_pronamic_ideal_configuration_id" id="jigoshop_pronamic_ideal_configuration_id" style="min-width:100px;">
					<?php foreach($configurationOptions as $id => $name): ?>
					<option value="<?php echo $id; ?>" <?php selected($this->configurationId, $id); ?>><?php echo $name; ?></option>
					<?php endforeach; ?>
				</select>
	        </td>
	    </tr>
    	<?php
    }

	//////////////////////////////////////////////////

    /**
     * Process admin options
     */
    public function processAdminOptions() {
    	if(isset($_POST['jigoshop_pronamic_ideal_enabled'])) update_option('jigoshop_pronamic_ideal_enabled', jigowatt_clean($_POST['jigoshop_pronamic_ideal_enabled'])); else @delete_option('jigoshop_pronamic_ideal_enabled');
   		if(isset($_POST['jigoshop_pronamic_ideal_title'])) update_option('jigoshop_pronamic_ideal_title', jigowatt_clean($_POST['jigoshop_pronamic_ideal_title'])); else @delete_option('jigoshop_pronamic_ideal_title');
   		if(isset($_POST['jigoshop_pronamic_ideal_description'])) update_option('jigoshop_pronamic_ideal_description', jigowatt_clean($_POST['jigoshop_pronamic_ideal_description'])); else @delete_option('jigoshop_pronamic_ideal_description');
    	if(isset($_POST['jigoshop_pronamic_ideal_configuration_id'])) update_option('jigoshop_pronamic_ideal_configuration_id', jigowatt_clean($_POST['jigoshop_pronamic_ideal_configuration_id'])); else @delete_option('jigoshop_pronamic_ideal_configuration_id');
    }

	//////////////////////////////////////////////////

    /**
	 * Payment fields
	 */
	function payment_fields() {
		if(!empty($this->description)) {
			echo wpautop(wptexturize($this->description));
		}

		$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById($this->configurationId);
		if($configuration !== null) {
			$variant = $configuration->getVariant();

			if($variant !== null && $variant->getMethod() == Pronamic_IDeal_IDeal::METHOD_ADVANCED) {
				$lists = Pronamic_WordPress_IDeal_IDeal::getTransientIssuersLists($configuration);
				
				if($lists) {
					?>
					<p class="pronamic_ideal_issuer">
						<label for="pronamic_ideal_issuer_id">
							<?php _e('Choose your bank', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
						</label>
						
						<?php echo Pronamic_IDeal_HTML_Helper::issuersSelect('pronamic_ideal_issuer_id', $lists); ?>
					</p>
					<?php 
				} elseif($error = Pronamic_WordPress_IDeal_IDeal::getError()) {
					?>
					<div class="jigoshop_error">
						<?php echo $error->getConsumerMessage(); ?>
					</div>
					<?php
				} else {
					?>
					<div class="jigoshop_error">
						<?php echo __('Paying with iDEAL is not possible. Please try again later or pay another way.', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
					</div>
					<?php 
				}
			}
		}
	}

	//////////////////////////////////////////////////
	
	public static function getIDealItemsFromJigoshopOrder($order) {
		// Items
		$items = new Pronamic_IDeal_Items();

		// Item
		// We only add one total item, because iDEAL cant work with negative price items (discount)
		$item = new Pronamic_IDeal_Item();
		$item->setNumber($order->id);
		$item->setDescription(sprintf(__('Order %s', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN), $order->id));
		$item->setPrice($order->order_total);
		$item->setQuantity(1);

		$items->addItem($item);

		return $items;
	}

	//////////////////////////////////////////////////
	
	/**
	 * Receipt page
	 */
	function receiptPage( $order_id ) {
		$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById($this->configurationId);
		if($configuration !== null) {
			$variant = $configuration->getVariant();
	
			if($variant !== null) {				
				$order = &new jigoshop_order($order_id);

				switch($variant->getMethod()) {
					case Pronamic_IDeal_IDeal::METHOD_EASY:
						$this->receiptPageIDealEasy($order, $configuration, $variant);
						break;
					case Pronamic_IDeal_IDeal::METHOD_BASIC:
						$this->receiptPageIDealBasic($order, $configuration, $variant);
						break;
				}
			}
		}
	}
	
	function receiptPageIDealEasy($order, $configuration, $variant) {
		$iDeal = new Pronamic_IDeal_Easy();

		$iDeal->setPaymentServerUrl($configuration->getPaymentServerUrl());
		$iDeal->setMerchantId($configuration->getMerchantId());
		$iDeal->setLanguage('nl');
		$iDeal->setCurrency(get_option('jigoshop_currency'));
		$iDeal->setOrderId($order->id);
		$iDeal->setDescription(sprintf(__('Order %s', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN), $order->id));
        $iDeal->setAmount($order->order_total);
        $iDeal->setEMailAddress($order->billing_email);
        $iDeal->setCustomerName($order->billing_first_name . ' ' . $order->billing_last_name);
		$iDeal->setOwnerAddress($order->billing_address_1);
		$iDeal->setOwnerCity($order->billing_city);
		$iDeal->setOwnerZip($order->billing_postcode);

		// Payment
		$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentBySource('jigoshop', $order->id);

		if($payment == null) {
			// Update payment
			$transaction = new Pronamic_IDeal_Transaction();
			$transaction->setAmount($iDeal->getAmount()); 
			$transaction->setCurrency($iDeal->getCurrency());
			$transaction->setLanguage('nl');
			$transaction->setEntranceCode(uniqid());
			$transaction->setDescription($iDeal->getDescription());
			$transaction->setPurchaseId($order->id);
			
			$payment = new Pronamic_WordPress_IDeal_Payment();
			$payment->configuration = $configuration;
			$payment->transaction = $transaction;
			$payment->setSource('jigoshop', $order->id);
			
			$updated = Pronamic_WordPress_IDeal_PaymentsRepository::updatePayment($payment);
		}
		
		// HTML
		$html  = '';
		$html .= sprintf('<form method="post" action="%s">', esc_attr($iDeal->getPaymentServerUrl()));
		$html .= 	$iDeal->getHtmlFields();
		$html .= 	sprintf('<input class="ideal-button" type="submit" name="ideal" value="%s" />', __('Pay with iDEAL', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN));
		$html .= '</form>';
			        
		echo $html;
	}

	function receiptPageIDealBasic($order, $configuration, $variant) {
		$iDeal = new Pronamic_IDeal_Basic();

		$iDeal->setPaymentServerUrl($configuration->getPaymentServerUrl());
		$iDeal->setMerchantId($configuration->getMerchantId());
		$iDeal->setSubId($configuration->getSubId());
		$iDeal->setLanguage('nl');
		$iDeal->setHashKey($configuration->hashKey);
		$iDeal->setCurrency(get_option('jigoshop_currency'));
		$iDeal->setPurchaseId($order->id);
		$iDeal->setDescription(sprintf(__('Order %s', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN), $order->id));

		$iDeal->setCancelUrl($order->get_cancel_order_url());
		$iDeal->setSuccessUrl(add_query_arg('key', $order->order_key, add_query_arg('order', $order->id, get_permalink(get_option('jigoshop_thanks_page_id')))));
		$iDeal->setErrorUrl($order->get_checkout_payment_url());

        // Items
        $items = self::getIDealItemsFromJigoshopOrder($order);

		$iDeal->setItems($items);

		// Payment
		$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentBySource('jigoshop', $order->id);

		if($payment == null) {
			// Update payment
			$transaction = new Pronamic_IDeal_Transaction();
			$transaction->setAmount($iDeal->getAmount()); 
			$transaction->setCurrency($iDeal->getCurrency());
			$transaction->setLanguage('nl');
			$transaction->setEntranceCode(uniqid());
			$transaction->setDescription($iDeal->getDescription());
			$transaction->setPurchaseId($order->id);

			$payment = new Pronamic_WordPress_IDeal_Payment();
			$payment->configuration = $configuration;
			$payment->transaction = $transaction;
			$payment->setSource('jigoshop', $order->id);

			$updated = Pronamic_WordPress_IDeal_PaymentsRepository::updatePayment($payment);
		}
		
		// HTML
		$html  = '';
		$html .= sprintf('<form method="post" action="%s">', esc_attr($iDeal->getPaymentServerUrl()));
		$html .= 	$iDeal->getHtmlFields();
		$html .= 	sprintf('<input class="ideal-button" type="submit" name="ideal" value="%s" />', __('Pay with iDEAL', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN));
		$html .= '</form>';
			        
		echo $html;
	}

	//////////////////////////////////////////////////
	
	/**
	 * Process the payment and return the result
	 */
	function process_payment($order_id) {
		$order = &new jigoshop_order($order_id);

		// Mark as on-hold (we're awaiting the payment)
		$order->update_status('pending', __('Pending iDEAL payment.', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN));

		// Empty cart
		jigoshop_cart::empty_cart();

		// Do specifiek iDEAL variant processing
		$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById($this->configurationId);
		if($configuration !== null) {
			$variant = $configuration->getVariant();
	
			if($variant !== null) {
				switch($variant->getMethod()) {
					case Pronamic_IDeal_IDeal::METHOD_EASY:
						return $this->processIDealEasyPayment($order, $configuration, $variant);
					case Pronamic_IDeal_IDeal::METHOD_BASIC:
						return $this->processIDealBasicPayment($order, $configuration, $variant);
					case Pronamic_IDeal_IDeal::METHOD_ADVANCED:
						return $this->processIDealAdvancedPayment($order, $configuration, $variant);
				}
			}
		}
    }
    
    private function processIDealEasyPayment($order, $configuration, $variant) {
		// Return pay page redirect
		return array(
			'result' 	=> 'success',
			'redirect'	=> add_query_arg('order', $order->id, add_query_arg('key', $order->order_key, get_permalink(get_option('jigoshop_pay_page_id'))))
		);
    }
    
    private function processIDealBasicPayment($order, $configuration, $variant) {
		// Return pay page redirect
		return array(
			'result' 	=> 'success',
			'redirect'	=> add_query_arg('order', $order->id, add_query_arg('key', $order->order_key, get_permalink(get_option('jigoshop_pay_page_id'))))
		);
    }
    
    private function processIDealAdvancedPayment($order, $configuration, $variant) {
		$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentBySource('jigoshop', $order->id);
    	
		if($payment == null) {
			$transaction = new Pronamic_IDeal_Transaction();
			$transaction->setAmount($order->order_total); 
			$transaction->setCurrency(get_option('jigoshop_currency'));
			$transaction->setExpirationPeriod('PT1H');
			$transaction->setLanguage('nl');
			$transaction->setEntranceCode(uniqid());
			$transaction->setDescription(sprintf(__('Order %s', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN), $order->id));
			$transaction->setPurchaseId($order->id);
	
			$payment = new Pronamic_WordPress_IDeal_Payment();
			$payment->configuration = $configuration;
			$payment->transaction = $transaction;
			$payment->setSource('jigoshop', $order->id);
	
			$updated = Pronamic_WordPress_IDeal_PaymentsRepository::updatePayment($payment);
    	}

		$url = Pronamic_WordPress_IDeal_IDeal::handleTransaction($issuerId, $payment, $variant);

		return array(
			'result' 	=> 'success',
			'redirect'	=> $url
		);
    }
}
