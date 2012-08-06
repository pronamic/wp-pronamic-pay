<?php

/**
 * Title: WP e-Commerce iDEAL merchant
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WPeCommerce_IDeal_IDealMerchant extends wpsc_merchant {
	/**
	 * Construct and initialize an Pronamic iDEAL merchant class
	 */
	public function __construct( $purchase_id = null, $is_receiving = false ) {
		parent::__construct( $purchase_id, $is_receiving );

		$this->name = __( 'Pronamic iDEAL', 'pronamic_ideal' );
	}

	//////////////////////////////////////////////////

	/**
	 * Construct value array specific data array
	 */
	public function construct_value_array() {
		// No specific data for this merchant
		return array( );
	}

	/**
	 * Submit to gateway
	 */
	public function submit() {
		$configuration_id = get_option( 'pronamic_ideal_wpsc_configuration_id' );

		$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $configuration_id );

		// Set process to 'order_received' (2)
		// @see http://plugins.trac.wordpress.org/browser/wp-e-commerce/tags/3.8.7.6.2/wpsc-includes/merchant.class.php#L301
		// @see http://plugins.trac.wordpress.org/browser/wp-e-commerce/tags/3.8.7.6.2/wpsc-core/wpsc-functions.php#L115
		$this->set_purchase_processed_by_purchid( Pronamic_WPeCommerce_WPeCommerce::PURCHASE_STATUS_ORDER_RECEIVED );

		if( $configuration !== null ) {
			$variant = $configuration->getVariant();
	
			if( $variant !== null ) {
				switch( $variant->getMethod() ) {
					case Pronamic_IDeal_IDeal::METHOD_EASY:
					case Pronamic_IDeal_IDeal::METHOD_BASIC:
					case Pronamic_IDeal_IDeal::METHOD_OMNIKASSA:
						add_action( 'wpsc_bottom_of_shopping_cart', array( $this, 'shoppingCartBottom' ) );
						
						break;
					case Pronamic_IDeal_IDeal::METHOD_ADVANCED:
						return $this->submit_advanced( $configuration );
				}
			}
		}
	}

	private function submit_advanced( $configuration ) {
		$data_proxy = new Pronamic_WPeCommerce_IDeal_IDealDataProxy( $this );

    	$issuer_id = filter_input( INPUT_POST, 'pronamic_ideal_issuer_id', FILTER_SANITIZE_STRING );

		$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentBySource( $data_proxy->getSource(), $data_proxy->getOrderId() );
    	
		if($payment == null) {
			$transaction = new Pronamic_IDeal_Transaction();
			$transaction->setAmount( $data_proxy->getAmount() ); 
			$transaction->setCurrency( $data_proxy->getCurrencyAlphabeticCode() );
			$transaction->setExpirationPeriod( 'PT1H' );
			$transaction->setLanguage( $data_proxy->getLanguageIso639Code() );
			$transaction->setEntranceCode( uniqid() );
			$transaction->setDescription( $data_proxy->getDescription() );
			$transaction->setPurchaseId( $data_proxy->getOrderId() );
	
			$payment = new Pronamic_WordPress_IDeal_Payment();
			$payment->configuration = $configuration;
			$payment->transaction = $transaction;
			$payment->setSource( $data_proxy->getSource(), $data_proxy->getOrderId() );
	
			$updated = Pronamic_WordPress_IDeal_PaymentsRepository::updatePayment( $payment );
    	}

		$url = Pronamic_WordPress_IDeal_IDeal::handleTransaction( $issuer_id, $payment, $variant );

		wp_redirect( $url );
		
		exit;
	}

	//////////////////////////////////////////////////

	/**
	 * Shopping cart bottom
	 */
	public function shoppingCartBottom() {
		$configuration_id = get_option( 'pronamic_ideal_wpsc_configuration_id' );

		$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $configuration_id );

		$data_proxy = new Pronamic_WPeCommerce_IDeal_IDealDataProxy( $this );

		$html = Pronamic_WordPress_IDeal_IDeal::getHtmlForm( $data_proxy, $configuration, true );

		// Hide the checkout page container HTML element
		echo '<style type="text/css">#checkout_page_container { display: none; }</style>';
		
		// Display the iDEAL form
		echo $html;
	}

	//////////////////////////////////////////////////

	/**
	 * Admin configuration form
	 */
	public static function adminConfigurationForm() {
		$html = '';
		
		// Select configuration
		$configurations = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurations();

		$html .= '<tr>';
		$html .= '	<td class="wpsc_CC_details">';
		$html .= '		' . __( 'iDEAL Configuration', 'pronamic_ideal');
		$html .= '	</td>';
		$html .= '	<td>';
		$html .= '		<select name="pronamic_ideal_wpsc_configuration_id">';
		$html .= '			<option>' . __('&mdash; Select configuration &mdash;', 'pronamic_ideal') . '</option>';

		foreach($configurations as $configuration) {
			$html .= sprintf(
				'<option value="%s" %s>%s</option>',  
				esc_attr($configuration->getId()) , 
				selected(get_option('pronamic_ideal_wpsc_configuration_id'), $configuration->getId(), false) ,
				$configuration->getName()
			);
	   	}

		$html .= '		</select>';
		$html .= '	</td>';
		$html .= '</tr>';
		
		return $html;
	}

	/**
	 * Admin configuration submit
	 */
	public static function adminConfigurationSubmit() {
		$name = 'pronamic_ideal_wpsc_configuration_id';

		if(isset($_POST[$name])) {
			$configurationId = filter_input(INPUT_POST, $name, FILTER_SANITIZE_STRING);

			update_option($name, $configurationId);
		}

		return true;
	}
}
