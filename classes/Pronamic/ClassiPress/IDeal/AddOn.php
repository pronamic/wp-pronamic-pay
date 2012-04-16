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
	}

	//////////////////////////////////////////////////

	/**
	 * Gateway value
	 */
	public static function gatewayValues() {
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
				'id' => 'pronamic_ideal_enable' 
			) ,
			// Select Box
			array(
				'type' => 'select' , 
				'name' => __( 'iDEAL Configuration', 'pronamic_ideal') , 
				'options' => $configurationOptions ,  
				'id' => ''
			) , 
            array(
            	'type' => 'tabend' , 
				'id' => ''
			)
        );
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
