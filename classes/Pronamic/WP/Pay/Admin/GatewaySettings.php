<?php

/**
 * Title: WordPress admin gateway settings
 * Description:
 * Copyright: Copyright (c) 2005 - 2015
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 3.8.0
 * @since 3.8.0
 */
class Pronamic_WP_Pay_Admin_GatewaySettings {
	/**
	 * Settings
	 */
	private $settings;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an admin gateway settings object
	 */
	public function __construct() {
		$this->settings = array();

		// Classes
		$classes = apply_filters( 'pronamic_pay_gateway_settings', array() );

		foreach ( $classes as $class ) {
			$this->settings[] = new $class();
		}
	}

	public function get_sections() {
		return apply_filters( 'pronamic_pay_gateway_sections', array() );
	}

	public function get_fields() {
		return apply_filters( 'pronamic_pay_gateway_fields', array() );
	}
}
