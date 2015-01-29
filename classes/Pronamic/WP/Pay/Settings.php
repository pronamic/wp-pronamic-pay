<?php

/**
 * Title: WordPress iDEAL admin
 * Description:
 * Copyright: Copyright (c) 2005 - 2015
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0.0
 */
class Pronamic_WP_Pay_Settings {
	/**
	 * Constructs and initalize an admin object
	 */
	public function __construct() {
		// Actions
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Admin initialize
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
				'type'      => 'password',
				'label_for' => 'pronamic_pay_license_key',
			) // args
		);

		register_setting( 'pronamic_pay', 'pronamic_pay_license_key', 'trim' );

		// Settings - Pages
		add_settings_section(
			'pronamic_pay_pages', // id
			__( 'Payment Status Pages', 'pronamic_ideal' ), // title
			array( $this, 'settings_section' ), // callback
			'pronamic_pay' // page
		);

		$pages = array(
			'error'     => __( 'Error', 'pronamic_ideal' ),
			'cancel'    => __( 'Canceled', 'pronamic_ideal' ),
			'unknown'   => __( 'Unknown', 'pronamic_ideal' ),
			'expired'   => __( 'Expired', 'pronamic_ideal' ),
			'completed' => __( 'Completed', 'pronamic_ideal' ),
		);

		foreach ( $pages as $key => $label ) {
			$id = sprintf( 'pronamic_pay_%s_page_id', $key );

			add_settings_field(
				$id, // id
				$label, // title
				array( $this, 'input_page' ), // callback
				'pronamic_pay', // page
				'pronamic_pay_pages', // section
				array( 'label_for' => $id ) // args
			);

			register_setting( 'pronamic_pay', $id );
		}

		// Settings - Currency
		add_settings_section(
			'pronamic_pay_currency', // id
			__( 'Currency', 'pronamic_ideal' ), // title
			array( $this, 'settings_section' ), // callback
			'pronamic_pay' // page
		);

		register_setting( 'pronamic_pay', 'pronamic_pay_thousands_sep' );

		add_settings_field(
			'pronamic_pay_thousands_sep', // id
			__( 'Thousands Seperator', 'pronamic_ideal' ), // title
			array( $this, 'input_element' ), // callback
			'pronamic_pay', // page
			'pronamic_pay_currency', // section
			array( 'label_for' => 'pronamic_pay_thousands_sep' ) // args
		);

		register_setting( 'pronamic_pay', 'pronamic_pay_decimal_sep' );

		add_settings_field(
			'pronamic_pay_decimal_sep', // id
			__( 'Decimal Seperator', 'pronamic_ideal' ), // title
			array( $this, 'input_element' ), // callback
			'pronamic_pay', // page
			'pronamic_pay_currency', // section
			array( 'label_for' => 'pronamic_pay_decimal_sep' ) // args
		);
	}

	//////////////////////////////////////////////////

	/**
	 * Settings section
	 */
	public function settings_section() {

	}

	/**
	 * Input text
	 *
	 * @param array $args
	 */
	public function input_element( $args ) {
		$defaults = array(
			'type' => 'text',
		);

		$args = wp_parse_args( $args, $defaults );

		printf(
			'<input name="%s" id="%s" type="%s" value="%s" class="%s" />',
			esc_attr( $args['label_for'] ),
			esc_attr( $args['label_for'] ),
			esc_attr( $args['type'] ),
			esc_attr( get_option( $args['label_for'] ) ),
			'regular-text'
		);

	}

	public function input_license_key( $args ) {
		$this->input_element( $args );

		$status = get_option( 'pronamic_pay_license_status' );

		$icon = 'valid' == $status ? 'yes' : 'no';

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
			'name' => $name,
			'selected' => get_option( $name, '' ),
			'show_option_none' => __( '&mdash; Select a page &mdash;', 'pronamic_ideal' )
		) );
	}
}
