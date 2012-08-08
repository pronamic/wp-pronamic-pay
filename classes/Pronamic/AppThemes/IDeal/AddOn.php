<?php 

/**
 * Title: AppThemes iDEAL Add-On
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_AppThemes_IDeal_AddOn {
	/**
	 * Slug
	 * 
	 * @var string
	 */
	const SLUG = 'appthemes';

	//////////////////////////////////////////////////

	/**
	 * Bootstrap
	 */
	public static function bootstrap() {
		add_action( 'appthemes_load_gateways', array( __CLASS__, 'load_gateway' ), 100 );
	}

	//////////////////////////////////////////////////

	/**
	 * Initialize
	 */
	public static function load_gateway() {
		appthemes_register_gateway( 'Pronamic_AppThemes_IDeal_IDealGateway' );
	}

	//////////////////////////////////////////////////
	
	/**
	 * Source column
	 */
	public static function sourceColumn($text, $payment) {
		$text  = '';
		$text .= __('AppThemes', 'pronamic_ideal') . '<br />';
		$text .= sprintf('<a href="%s">', get_edit_post_link($payment->getSourceId()));
		$text .= sprintf(__('Order #%s', 'pronamic_ideal'), $payment->getSourceId());
		$text .= '</a>';

		return $text;
	}
}
