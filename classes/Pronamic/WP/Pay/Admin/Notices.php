<?php

/**
 * Title: WordPress admin notices
 * Description:
 * Copyright: Copyright (c) 2005 - 2015
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 3.7.0
 * @since 3.7.0
 */
class Pronamic_WP_Pay_Admin_Notices {
	/**
	 * Constructs and initializes an notices object
	 *
	 * @see https://github.com/woothemes/woocommerce/blob/2.4.3/includes/admin/class-wc-admin-notices.php
	 */
	public function __construct( $admin ) {
		$this->admin = $admin;

		// Actions
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ), 11 );
	}

	//////////////////////////////////////////////////

	/**
	 * Admin intialize
	 */
	public function admin_init() {

	}

	//////////////////////////////////////////////////

	/**
	 * Admin notices
	 *
	 * @see https://github.com/WordPress/WordPress/blob/4.3.1/wp-admin/admin-header.php#L245-L250
	 */
	public function admin_notices() {
		$screen = get_current_screen();

		// Jetpack
		if ( 'jetpack' === $screen->parent_base ) {
			return;
		}

		// License notice
		if ( 'valid' !== get_option( 'pronamic_pay_license_status' ) ) {
			$class = Pronamic_WP_Pay_Plugin::get_number_payments() > 20 ? 'error' : 'updated';

			printf( //xss ok
				'<div class="%s"><p>%s</p></div>',
				esc_attr( $class ),
				sprintf(
					__( '<strong>Pronamic iDEAL</strong> &mdash; You have not <a href="%s">entered a (valid) Pronamic iDEAL support license key</a>, please get your license key at <a href="%s" target="_blank">pronamic.eu</a>.', 'pronamic_ideal' ),
					add_query_arg( 'page', 'pronamic_pay_settings', get_admin_url( null, 'admin.php' ) ),
					'http://www.pronamic.eu/plugins/pronamic-ideal/'
				)
			);
		}

		// Stored notices
		$notices = get_option( 'pronamic_pay_admin_notices', array() );

		foreach ( $notices as $name ) {
			$file = plugin_dir_path( Pronamic_WP_Pay_Plugin::$file ) . 'admin/notice-' . $name . '.php';

			if ( is_readable( $file ) ) {
				include $file;
			}
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Add a notice to show
	 *
	 * @param string $name
	 */
	public function add_notice( $name ) {
		$notices = array_unique( array_merge( get_option( 'pronamic_pay_admin_notices', array() ), array( $name ) ) );

		update_option( 'pronamic_pay_admin_notices', $notices );
	}

	/**
	 * Remove a notice from being displayed
	 *
	 * @param string $name
	 */
	public static function remove_notice( $name ) {
		$notices = array_diff( get_option( 'pronamic_pay_admin_notices', array() ), array( $name ) );

		update_option( 'pronamic_pay_admin_notices', $notices );
	}
}
