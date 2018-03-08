<?php
/**
 * Settings
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

namespace Pronamic\WordPress\Pay;

/**
 * Title: WordPress iDEAL admin
 *
 * @author Remco Tolsma
 * @version 1.0.0
 */
class Settings {
	/**
	 * Plugin.
	 *
	 * @var Plugin
	 */
	private $plugin;

	/**
	 * Constructs and initalize settings object.
	 *
	 * @param Plugin $plugin The plugin.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;

		// Actions.
		add_action( 'load-options.php', array( $this, 'load_options_dont_trim_all_posted_values' ) );

		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * WordPress will by default `trim` all posted option values, for a few options this
	 * is not desired. In this function we dome some 'voodoo' to fix this.
	 *
	 * @see https://github.com/WordPress/WordPress/blob/4.9/wp-admin/options.php#L203-L214
	 * @see https://github.com/WordPress/WordPress/blob/4.9/wp-admin/admin.php#L320-L332
	 */
	public function load_options_dont_trim_all_posted_values() {
		$no_trim_options = array(
			'pronamic_pay_thousands_sep',
			'pronamic_pay_decimal_sep',
		);

		foreach ( $no_trim_options as $option ) {
			add_filter( 'sanitize_option_' . $option, array( $this, 'sanitize_option_dont_trim_posted_value' ), 10, 2 );
		}
	}

	/**
	 * Sanitize option don't trim the posted option value.
	 *
	 * @param string $value  The option value to sanitize.
	 * @param string $option The option name to sanitize.
	 * @return string
	 */
	public function sanitize_option_dont_trim_posted_value( $value, $option ) {
		$screen = get_current_screen();

		if ( 'options' !== $screen->id ) {
			return $value;
		}

		// @see https://github.com/WordPress/WordPress/blob/4.9/wp-admin/options.php#L203-L214
		if ( isset( $_POST[ $option ] ) ) { // WPCS: CSRF ok.
			$value = $_POST[ $option ]; // WPCS: CSRF ok.
			$value = wp_unslash( $value );
		}

		return $value;
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

		/*
		 * Note: We deliberately did not define a sanitize callback for this setting,
		 * all the WordPress sanitize function filter/trim whitespaces and we want
		 * to allow whitespaces.
		 */
		register_setting(
			'pronamic_pay', 'pronamic_pay_thousands_sep', array(
				'type'              => 'string',
				'default'           => $wp_locale->number_format['thousands_sep'],
				'sanitize_callback' => null,
			)
		);

		/*
		 * Note: We deliberately did not define a sanitize callback for this setting,
		 * all the WordPress sanitize function filter/trim whitespaces and we want
		 * to allow whitespaces.
		 */
		register_setting(
			'pronamic_pay', 'pronamic_pay_decimal_sep', array(
				'type'              => 'string',
				'default'           => $wp_locale->number_format['decimal_point'],
				'sanitize_callback' => null,
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
	 * @param integer $value Check if the value is published post ID.
	 * @return boolean True if post status is publish, false otherwise.
	 */
	public function sanitize_published_post_id( $value ) {
		if ( 'publish' === get_post_status( $value ) ) {
			return $value;
		}

		return null;
	}
}
