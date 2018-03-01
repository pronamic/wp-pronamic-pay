<?php
/**
 * Gateway Settings
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Admin
 */

namespace Pronamic\WordPress\Pay\Admin;

/**
 * WordPress admin gateway settings
 *
 * @author Remco Tolsma
 * @version 3.8.0
 * @since 3.8.0
 */
class GatewaySettings {
	/**
	 * Settings.
	 *
	 * @var array
	 */
	private $settings;

	/**
	 * Constructs and initializes an admin gateway settings object.
	 */
	public function __construct() {
		$this->settings = array();

		// Classes.
		$classes = apply_filters( 'pronamic_pay_gateway_settings', array() );

		foreach ( $classes as $class ) {
			$this->settings[] = new $class();
		}
	}

	/**
	 * Get sections.
	 *
	 * @return array
	 */
	public function get_sections() {
		// Default sections.
		$sections = array(
			'general' => array(),
		);

		return apply_filters( 'pronamic_pay_gateway_sections', $sections );
	}

	/**
	 * Get fields.
	 *
	 * @return array
	 */
	public function get_fields() {
		// Default fields.
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
