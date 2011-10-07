<?php

/**
 * Title: WooCommerce iDEAL gateway
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WooCommerce_IDeal_IDealGateway extends woocommerce_payment_gateway {
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
		$this->id = self::ID;
		$this->method_title = __('Pronamic iDEAL', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN);
		$this->icon = plugins_url('images/icon-24x24.png', Pronamic_WordPress_IDeal_Plugin::$file);
		$this->has_fields = false;
		
		// Load the form fields.
		$this->init_form_fields();
		
		// Load the settings.
		$this->init_settings();
		
		// Define user set variables
		$this->title 			= $this->settings['title'];
		$this->description      = $this->settings['description'];
		$this->configurationId = $this->settings['configuration_id'];
		
		// Actions
		add_action('woocommerce_update_options_payment_gateways', array(&$this, 'process_admin_options'));
    	add_action('woocommerce_thankyou_' . self::ID, array(&$this, 'thankyou_page'));
		add_action('woocommerce_receipt_' . self::ID, array(&$this, 'receipt_page'));
    } 

	/**
     * Initialise Gateway Settings Form Fields
     */
    function init_form_fields() {
    	$configurations = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurations();
    	$configurationOptions = array('' => __('&mdash; Select configuration &mdash; ', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN));
    	foreach($configurations as $configuration) {
    		$configurationOptions[$configuration->getId()] = $configuration->getName();
    	}
    
    	$this->form_fields = array(
    		'enabled' => array(
				'title' => __( 'Enable/Disable', 'woothemes' ) , 
				'type' => 'checkbox' , 
				'label' => __( 'Enable iDEAL', 'woothemes' ) , 
				'default' => 'yes'
			) ,  
			'title' => array(
				'title' => __( 'Title', 'woothemes' ) , 
				'type' => 'text' , 
				'description' => __( 'This controls the title which the user sees during checkout.', 'woothemes' ) , 
				'default' => __( 'iDEAL', 'woothemes' ) 
			) , 
			'description' => array(
				'title' => __( 'Customer Message', 'woothemes' ) , 
				'type' => 'textarea' , 
				'description' => __( 'Give the customer instructions for paying via iDEAL, and let them know that their order won\'t be shipping until the money is received.', 'woothemes' ) , 
				'default' => __('Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order wont be shipped until the funds have cleared in our account.', 'woothemes')
			) , 
			'configuration_id' => array(
				'title' => __( 'Configuration', 'woothemes' ) , 
				'type' => 'select' , 
				'description' => __( 'Select an iDEAL configuration', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) , 
				'default' => '' , 
				'options' => $configurationOptions 
			)
		);
    }

	//////////////////////////////////////////////////
    
	/**
	 * Admin Panel Options 
	 * - Options for bits like 'title' and availability on a country-by-country basis
	 *
	 * @since 1.0.0
	 */
	public function admin_options() {
    	?>
    	<h3>
    		<?php _e('Pronamic iDEAL', 'woothemes'); ?>
    	</h3>
    	
    	<table class="form-table">
    		<?php $this->generate_settings_html(); ?>
		</table>
    	<?php
    }

	//////////////////////////////////////////////////

    /**
	 * There are no payment fields for bacs, but we want to show the description if set.
	 */
	function payment_fields() {
		if($this->description) {
			echo wpautop(wptexturize($this->description));
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Thankyou page
	 */
    function thankyou_page() {
		if($this->description) {
			echo wpautop(wptexturize($this->description));
		}
    }
	
	/**
	 * receipt_page
	 **/
	function receipt_page( $order ) {
		
		echo '<p>'.__('Thank you for your order, please click the button below to pay with iDEAL.', 'woothemes').'</p>';
		
		
	}

	//////////////////////////////////////////////////

    /**
    * Process the payment and return the result
    **/
    function process_payment( $order_id ) {
    	global $woocommerce;
    	
		$order = &new woocommerce_order( $order_id );
		
		// Mark as on-hold (we're awaiting the payment)
		$order->update_status('on-hold', __('Awaiting BACS payment', 'woothemes'));
		
		// Remove cart
		$woocommerce->cart->empty_cart();
		
		// Empty awaiting payment session
		unset($_SESSION['order_awaiting_payment']);
		
		// Return thankyou redirect
		return array(
			'result' 	=> 'success',
			'redirect'	=> add_query_arg('key', $order->order_key, add_query_arg('order', $order_id, get_permalink(get_option('woocommerce_thanks_page_id'))))
		);
    }
}
