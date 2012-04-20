<?php 

/**
 * Title: ClassiPress iDEAL Add-On
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_ClassiPress_IDeal_AddOn {
	/**
	 * Slug
	 * 
	 * @var string
	 */
	const SLUG = 'classipress';

	//////////////////////////////////////////////////

	/**
	 * Bootstrap
	 */
	public static function bootstrap() {
		add_action('cp_action_gateway_values', array(__CLASS__, 'gatewayValues'));
		add_action('cp_action_payment_method', array(__CLASS__, 'paymentMethod'));
		add_action('cp_action_gateway', array(__CLASS__, 'gatewayProcess'));
	}

	//////////////////////////////////////////////////

	/**
	 * Gateway value
	 */
	public static function gatewayValues() {
		global $app_abbr;

		// Configurations
    	$configurations = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurations();
    	$configurationOptions = array('' => __('&mdash; Select configuration &mdash;', 'pronamic_ideal'));
    	foreach($configurations as $configuration) {
    		$configurationOptions[$configuration->getId()] = $configuration->getName();
    	}

		// Gateway values
		global $action_gateway_values;

		$action_gateway_values = array(
			// Tab Start
			array(
				'type' => 'tab' , 
				'tabname' => __('iDEAL', 'pronamic_ideal' ) ,  
				'id' => ''
			) , 
			// Title
			array(
				'type' => 'title' , 
				'name' => __( 'iDEAL Options', 'pronamic_ideal' ) , 
				'id' => ''
			) , 
			// Logo/Picture
			array(
				'type' => 'logo' , 
				'name' => sprintf('<img src="%s" alt="" />', plugins_url('images/icon-32x32.png', Pronamic_WordPress_IDeal_Plugin::$file)) ,  
				'id' => ''
			) , 
            // Select Box
            array(
            	'type' => 'select' , 
				'name' => __( 'Enable iDEAL', 'pronamic_ideal' ),
				'options' => array(
					'yes' => __( 'Yes', 'pronamic_ideal' ) , 
					'no'  => __( 'No', 'pronamic_ideal')
				) , 
				'id' => $app_abbr . '_pronamic_ideal_enable' 
			) ,
			// Select Box
			array(
				'type' => 'select' , 
				'name' => __( 'iDEAL Configuration', 'pronamic_ideal') , 
				'options' => $configurationOptions ,  
				'id' => $app_abbr . '_pronamic_ideal_configuration_id'
			) , 
            array(
            	'type' => 'tabend' , 
				'id' => ''
			)
        );
	}

	//////////////////////////////////////////////////
	
	/**
	 * Add the option to the payment drop-down list on checkout
	 */
	public static function paymentMethod() {
	    global $app_abbr, $gateway_name;

	    if(get_option($app_abbr . '_pronamic_ideal_enable') == 'yes') {
	        echo '<option value="pronamic_ideal">' . __( 'iDEAL', 'pronamic_ideal' ) . '</option>';
	    }
	}

	//////////////////////////////////////////////////

	/**
	 * Process gateway
	 */
	public static function gatewayProcess($order_vals) {
		global $gateway_name, $app_abbr, $post_url;

		// if gateway wasn't selected then exit
		if($order_vals['cp_payment_method'] != 'pronamic_ideal') { 
			return;
		}

		$dataProxy = new Pronamic_ClassiPress_IDeal_IDealDataProxy($order_vals);

		$configurationId = get_option($app_abbr . '_pronamic_ideal_configuration_id');
		$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById($configurationId);
		
		$autoSubmit = true;

		$html = Pronamic_WordPress_IDeal_IDeal::getHtmlForm($dataProxy, $configuration, $autoSubmit);
		
		echo $html;
	}

	//////////////////////////////////////////////////
	
	/**
	 * Update lead status of the specified payment
	 * 
	 * @param string $payment
	 */
	public static function updateStatus(Pronamic_WordPress_IDeal_Payment $payment, $canRedirect = false) {
		
	}

	//////////////////////////////////////////////////
	
	/**
	 * Source column
	 */
	public static function sourceColumn($text, $payment) {
		$text  = '';
		$text .= __('ClassiPress', 'pronamic_ideal') . '<br />';
		$text .= sprintf('<a href="%s">', get_edit_post_link($payment->getSourceId()));
		$text .= sprintf(__('Order #%s', 'pronamic_ideal'), $payment->getSourceId());
		$text .= '</a>';

		return $text;
	}
}
