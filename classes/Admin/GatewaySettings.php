<?php

namespace Pronamic\WordPress\Pay\Admin;

/**
 * Title: WordPress admin gateway settings
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 3.8.0
 * @since 3.8.0
 */
class GatewaySettings {
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
		// Default sections
		$sections = array(
			'general' => array(),
		);

		return apply_filters( 'pronamic_pay_gateway_sections', $sections );
	}

	public function get_fields() {
		// Default fields
		$fields = array(
			array(
				'filter'   => FILTER_SANITIZE_STRING,
				'section'  => 'general',
				'meta_key' => '_pronamic_gateway_mode',
				'name'     => 'mode',
				'id'       => 'pronamic_ideal_mode',
				'title'    => __( 'Mode', 'pronamic_ideal' ),
				'type'     => 'select',
				'options'  => array(
					array(
						'options' => array(
							'test' => __( 'Test', 'pronamic_ideal' ),
							'live' => __( 'Live', 'pronamic_ideal' ),
						),
					),
				),
			),
		);

		return apply_filters( 'pronamic_pay_gateway_fields', $fields );
	}
}
