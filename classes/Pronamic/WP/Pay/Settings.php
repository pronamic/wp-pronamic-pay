<?php

/**
 * Title: WordPress iDEAL admin
 * Description:
 * Copyright: Copyright (c) 2005 - 2017
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.0.0
 */
class Pronamic_WP_Pay_Settings {
	/**
	 * Constructs and initalize an admin object
	 */
	public function __construct() {
		// Actions
		add_action( 'init', array( $this, 'init' ) );

		add_action( 'admin_init', array( $this, 'admin_init' ) );
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

		register_setting( 'pronamic_pay', 'pronamic_pay_license_key', array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
		) );

		register_setting( 'pronamic_pay', 'pronamic_pay_config_id', array(
			'type'              => 'integer',
			'sanitize_callback' => array( $this, 'sanitize_published_post_id' ),
		) );

		register_setting( 'pronamic_pay', 'pronamic_pay_uninstall_clear_data', array(
			'type'              => 'integer',
			'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
		) );

		register_setting( 'pronamic_pay', 'pronamic_pay_thousands_sep', array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $wp_locale->number_format['thousands_sep'],
		) );

		register_setting( 'pronamic_pay', 'pronamic_pay_decimal_sep', array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $wp_locale->number_format['decimal_point'],
		) );

		foreach ( $this->get_pages() as $id => $label ) {
			register_setting( 'pronamic_pay', $id, array(
				'type'              => 'integer',
				'sanitize_callback' => array( $this, 'sanitize_published_post_id' ),
			) );
		}
	}

	/**
	 * Admin initialize.
	 */
	public function admin_init() {
		// Settings - General
		add_settings_section(
			'pronamic_pay_general', // id
			__( 'General', 'pronamic_ideal' ), // title
			array( $this, 'settings_section' ), // callback
			'pronamic_pay' // page
		);

		add_settings_field(
			'pronamic_pay_license_key', // id
			__( 'Support License Key', 'pronamic_ideal' ), // title
			array( $this, 'input_license_key' ), // callback
			'pronamic_pay', // page
			'pronamic_pay_general', // section
			array(
				'label_for' => 'pronamic_pay_license_key',
				'classes'   => 'regular-text code',
			) // args
		);

		// Default Config
		add_settings_field(
			'pronamic_pay_config_id', // id
			__( 'Default Gateway', 'pronamic_ideal' ), // title
			array( $this, 'input_page' ), // callback
			'pronamic_pay', // page
			'pronamic_pay_general', // section
			array(
				'post_type'        => 'pronamic_gateway',
				'show_option_none' => __( '— Select a gateway —', 'pronamic_ideal' ),
				'label_for'        => 'pronamic_pay_config_id',
			) // args
		);

		// Remove data on uninstall
		add_settings_field(
			'pronamic_pay_uninstall_clear_data', // id
			__( 'Remove data', 'pronamic_ideal' ), // title
			array( $this, 'input_element' ), // callback
			'pronamic_pay', // page
			'pronamic_pay_general', // section
			array(  // args
			        'description' => __( 'Remove all plugin data on uninstall', 'pronamic_ideal' ),
			        'label_for' => 'pronamic_pay_uninstall_clear_data',
			        'classes'   => 'regular-text',
			        'type'      => 'checkbox',
			)
		);

		// Settings - Pages
		add_settings_section(
			'pronamic_pay_pages', // id
			__( 'Payment Status Pages', 'pronamic_ideal' ), // title
			array( $this, 'settings_section' ), // callback
			'pronamic_pay' // page
		);

		foreach ( $this->get_pages() as $id => $label ) {
			add_settings_field(
				$id, // id
				$label, // title
				array( $this, 'input_page' ), // callback
				'pronamic_pay', // page
				'pronamic_pay_pages', // section
				array(
					'label_for' => $id,
				) // args
			);
		}

		// Settings - Currency
		add_settings_section(
			'pronamic_pay_currency', // id
			__( 'Currency', 'pronamic_ideal' ), // title
			array( $this, 'settings_section' ), // callback
			'pronamic_pay' // page
		);

		add_settings_field(
			'pronamic_pay_thousands_sep', // id
			__( 'Thousands Seperator', 'pronamic_ideal' ), // title
			array( $this, 'input_element' ), // callback
			'pronamic_pay', // page
			'pronamic_pay_currency', // section
			array( // args
				'label_for' => 'pronamic_pay_thousands_sep',
				'classes'   => 'tiny-text',
			)
		);

		add_settings_field(
			'pronamic_pay_decimal_sep', // id
			__( 'Decimal Seperator', 'pronamic_ideal' ), // title
			array( $this, 'input_element' ), // callback
			'pronamic_pay', // page
			'pronamic_pay_currency', // section
			array(  // args
				'label_for' => 'pronamic_pay_decimal_sep',
				'classes'   => 'tiny-text',
			)
		);
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

	/**
	 * Sanitize checkbox.
	 *
	 * @param string $value
	 * @return 1 or null
	 */
	public function sanitize_checkbox( $value ) {
		if ( 'on' === $value ) {
			return '1';
		}

		return null;
	}

	/**
	 * Get pages.
	 *
	 * @return array
	 */
	private function get_pages() {
		$return = array();

		$pages = array(
			'completed' => __( 'Completed', 'pronamic_ideal' ),
			'cancel'    => __( 'Canceled', 'pronamic_ideal' ),
			'expired'   => __( 'Expired', 'pronamic_ideal' ),
			'error'     => __( 'Error', 'pronamic_ideal' ),
			'unknown'   => __( 'Unknown', 'pronamic_ideal' ),
		);

		foreach ( $pages as $key => $label ) {
			$id = sprintf( 'pronamic_pay_%s_page_id', $key );

			$return[ $id ] = $label;
		}

		return $return;
	}

	/**
	 * Settings section
	 */
	public function settings_section( $args ) {
		switch ( $args['id'] ) {
			case 'pronamic_pay_pages' :
				echo '<p>';
				esc_html_e( 'The page an user will get redirected to after payment, based on the payment status.', 'pronamic_ideal' );
				echo '</p>';

				$pages = array( 'completed', 'cancel', 'expired', 'error', 'unknown' );

				foreach ( $pages as $status ) {
					$option_name = sprintf( 'pronamic_pay_%s_page_id', $status );

					$option = get_option( $option_name );

					if ( false !== $option && '' !== $option ) {
						$hide_button = true;
					}
				}

				if ( ! isset( $hide_button ) ) {
					submit_button(
						__( 'Set default pages', 'pronamic_ideal' ),
						'',
						'pronamic_pay_create_pages',
						false
					);
				}

				break;
		}
	}

	/**
	 * Input text
	 *
	 * @param array $args
	 */
	public function input_element( $args ) {
		$defaults = array(
			'type'        => 'text',
			'classes'     => 'regular-text',
			'description' => '',
		);

		$args = wp_parse_args( $args, $defaults );

		$value = sprintf( 'value="%s"', esc_attr( get_option( $args['label_for'] ) ) );

		if ( 'checkbox' === $args['type'] ) {
			$value = checked( 1, get_option( $args['label_for'] ), false );
		}

		printf(
			'<input name="%s" id="%s" type="%s" class="%s" %s />%s',
			esc_attr( $args['label_for'] ),
			esc_attr( $args['label_for'] ),
			esc_attr( $args['type'] ),
			esc_attr( $args['classes'] ),
			$value,
			esc_html( $args['description'] )
		);

	}

	/**
	 * Input license key
	 */
	public function input_license_key( $args ) {
		do_action( 'pronamic_pay_license_check' );

		$this->input_element( $args );

		$status = get_option( 'pronamic_pay_license_status' );

		$icon = 'valid' === $status ? 'yes' : 'no';

		printf( '<span class="dashicons dashicons-%s" style="vertical-align: text-bottom;"></span>', esc_attr( $icon ) );
	}

	/**
	 * Input page
	 *
	 * @param array $args
	 */
	public function input_page( $args ) {
		$name = $args['label_for'];

		wp_dropdown_pages( array(
			'name'             => esc_attr( $name ),
			'post_type'        => esc_attr( isset( $args['post_type'] ) ? $args['post_type'] : 'page' ),
			'selected'         => esc_attr( get_option( $name, '' ) ),
			'show_option_none' => esc_attr( isset( $args['show_option_none'] ) ? $args['show_option_none'] : __( '— Select a page —', 'pronamic_ideal' ) ),
			'class'            => 'regular-text',
		) );
	}
}
