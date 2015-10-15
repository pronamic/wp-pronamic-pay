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
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
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
	 */
	public function admin_notices() {
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
