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

use Pronamic\WordPress\Pay\Core\ConfigProvider;
use Pronamic\WordPress\Pay\Core\PaymentMethods;
use Pronamic\WordPress\Pay\Plugin;

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

		$sections = apply_filters( 'pronamic_pay_gateway_sections', $sections );

		// Payment methods section.
		$config_id = get_the_ID();

		$gateway = Plugin::get_gateway( $config_id );

		if ( $gateway ) {
			$gateway_id = get_post_meta( $config_id, '_pronamic_gateway_id', true );

			// Payment methods.
			$sections['payment_methods'] = array(
				'title'       => __( 'Payment Methods', 'pronamic_ideal' ),
				'methods'     => array(
					$gateway_id,
					str_replace( '-', '_', $gateway_id ),
				),
				'description' => __( 'Overview of supported payment methods. A green checkmark icon indicates that the payment method seems to be activated for your account.', 'pronamic_ideal' ),
			);
		}

		return $sections;
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

		// Payment methods.
		$fields[] = array(
			'section'  => 'payment_methods',
			'type'     => 'html',
			'callback' => array( $this, 'gateway_payment_methods' ),
		);

		return apply_filters( 'pronamic_pay_gateway_fields', $fields );
	}


	/**
	 * Available payment methods overview.
	 */
	public function gateway_payment_methods() {
		$gateway = Plugin::get_gateway( get_the_ID() );

		$supported = $gateway->get_supported_payment_methods();
		$available = $gateway->get_transient_available_payment_methods();

		$payment_methods = array();
		$sort            = array();

		foreach ( $supported as $payment_method ) {
			$name = PaymentMethods::get_name( $payment_method );

			$payment_methods[ $payment_method ] = array(
				'id'        => $payment_method,
				'name'      => $name,
				'available' => in_array( $payment_method, $available, true ),
			);

			$sort[ $payment_method ] = $name;
		}

		$sort_flags = SORT_STRING;

		if ( version_compare( PHP_VERSION, '5.4', '>=' ) ) {
			// SORT_FLAG_CASE is available since PHP 5.4.
			$sort_flags = SORT_STRING | SORT_FLAG_CASE;
		}

		array_multisort( $sort, $sort_flags, $payment_methods );

		require Plugin::$dirname . '/admin/meta-box-gateway-config-payment-methods.php';
	}
}
