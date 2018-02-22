<?php

namespace Pronamic\WordPress\Pay;

/**
 * Title: WordPress iDEAL admin
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.0.0
 */
class Settings {
	private $plugin;

	/**
	 * Constructs and initalize an admin object
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;

		// Actions
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Initialize.
	 *
	 * @see https://make.wordpress.org/core/2016/10/26/registering-your-settings-in-wordpress-4-7/
	 * @see https://github.com/WordPress/WordPress/blob/4.6/wp-admin/includes/plugin.php#L1767-L1795
	 * @see https://github.com/WordPress/WordPress/blob/4.7/wp-includes/option.php#L1849-L1925
	 * @see https://github.com/WordPress/WordPress/blob/4.7/wp-includes/option.php#L1715-L1847
	 */
	public function init() {
		global $wp_locale;

		register_setting(
			'pronamic_pay', 'pronamic_pay_license_key', array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		register_setting(
			'pronamic_pay', 'pronamic_pay_config_id', array(
				'type'              => 'integer',
				'sanitize_callback' => array( $this, 'sanitize_published_post_id' ),
			)
		);

		register_setting(
			'pronamic_pay', 'pronamic_pay_uninstall_clear_data', array(
				'type'    => 'boolean',
				'default' => false,
			)
		);

		register_setting(
			'pronamic_pay', 'pronamic_pay_thousands_sep', array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => $wp_locale->number_format['thousands_sep'],
			)
		);

		register_setting(
			'pronamic_pay', 'pronamic_pay_decimal_sep', array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => $wp_locale->number_format['decimal_point'],
			)
		);

		register_setting(
			'pronamic_pay', 'pronamic_pay_google_analytics_property', array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		foreach ( $this->plugin->get_pages() as $id => $label ) {
			register_setting(
				'pronamic_pay', $id, array(
					'type'              => 'integer',
					'sanitize_callback' => array( $this, 'sanitize_published_post_id' ),
				)
			);
		}
	}

	/**
	 * Sanitize published post ID.
	 *
	 * @param string $value
	 * @return value if post status is publish, false otherwise
	 */
	public function sanitize_published_post_id( $value ) {
		if ( 'publish' === get_post_status( $value ) ) {
			return $value;
		}

		return null;
	}
}
