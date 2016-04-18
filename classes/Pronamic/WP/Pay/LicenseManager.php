<?php

/**
 * Title: WordPress iDEAL admin
 * Description:
 * Copyright: Copyright (c) 2005 - 2016
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WP_Pay_LicenseManager {
	/**
	 * Constructs and initalize an license manager object.
	 */
	public function __construct() {
		// Actions
		add_action( 'pronamic_pay_license_check', array( $this, 'license_check_event' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );

		// Filters
		add_filter( sprintf( 'pre_update_option_%s', 'pronamic_pay_license_key' ), array( $this, 'pre_update_option_license_key' ), 10, 2 );
	}

	/**
	 * Admin notices.
	 *
	 * @see https://github.com/WordPress/WordPress/blob/4.2.4/wp-admin/options.php#L205-L218
	 * @see https://github.com/easydigitaldownloads/Easy-Digital-Downloads/blob/2.4.2/includes/class-edd-license-handler.php#L309-L369
	 */
	public function admin_notices() {
		$data = get_transient( 'pronamic_pay_license_data' );

		if ( $data ) {
			include plugin_dir_path( Pronamic_WP_Pay_Plugin::$file ) . 'admin/notice-license.php';

			delete_transient( 'pronamic_pay_license_data' );
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Pre update option 'pronamic_pay_license_key'.
	 *
	 * @param string $newvalue
	 * @param string $oldvalue
	 * @return string
	 */
	public function pre_update_option_license_key( $newvalue, $oldvalue ) {
		$newvalue = trim( $newvalue );

		if ( $newvalue !== $oldvalue ) {
			delete_option( 'pronamic_pay_license_status' );

			if ( ! empty( $oldvalue ) ) {
				$this->deactivate_license( $oldvalue );
			}
		}

		delete_transient( 'pronamic_pay_license_data' );

		if ( ! empty( $newvalue ) ) {
			// Always try to activate the new license, it could be deactivated.
			$this->activate_license( $newvalue );
		}

		// Shedule weekly license check
		$time = time() + DAY_IN_SECONDS;

		wp_clear_scheduled_hook( 'pronamic_pay_license_check' );

		wp_schedule_event( $time, 'daily', 'pronamic_pay_license_check' );

		// Get and update license status
		$this->check_license( $newvalue );

		return $newvalue;
	}

	//////////////////////////////////////////////////

	/**
	 * License check event.
	 */
	public function license_check_event() {
		$license = get_option( 'pronamic_pay_license_key' );

		$this->check_license( $license );
	}

	//////////////////////////////////////////////////

	/**
	 * Check license.
	 *
	 * @param string $license
	 * @return boolean
	 */
	public function check_license( $license ) {
		$status = null;

		if ( empty( $license ) ) {
			$status = 'invalid';
		} else {
			// Request
			$args = array(
				'license' => $license,
				'name'    => 'Pronamic iDEAL',
				'url'     => home_url(),
			);

			$args = urlencode_deep( $args );

			$response = wp_remote_get(
				add_query_arg( $args, 'https://api.pronamic.eu/licenses/check/1.0/' ),
				array(
					'timeout' => 20,
				)
			);

			if ( is_wp_error( $response ) ) {
				// On errors we give benefit of the doubt
				$status = 'valid';
			}

			$data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( $data ) {
				$status = $data->license;
			}
		}

		// Update
		update_option( 'pronamic_pay_license_status', $status );
	}

	//////////////////////////////////////////////////

	/**
	 * Deactivate license.
	 *
	 * @param string $license
	 */
	public function deactivate_license( $license ) {
		$args = array(
			'license' => $license,
			'name'    => 'Pronamic iDEAL',
			'url'     => home_url(),
		);

		$args = urlencode_deep( $args );

		$response = wp_remote_get(
			add_query_arg( $args, 'https://api.pronamic.eu/licenses/deactivate/1.0/' ),
			array(
				'timeout' => 20,
			)
		);
	}

	//////////////////////////////////////////////////

	/**
	 * Activate license.
	 *
	 * @param string $license
	 * @return boolean
	 */
	public function activate_license( $license ) {
		// Request
		$args = array(
			'license' => $license,
			'name'    => 'Pronamic iDEAL',
			'url'     => home_url(),
		);

		$args = urlencode_deep( $args );

		$response = wp_remote_get(
			add_query_arg( $args, 'https://api.pronamic.eu/licenses/activate/1.0/' ),
			array(
				'timeout' => 20,
			)
		);

		if ( is_wp_error( $response ) ) {
			// On errors we give benefit of the doubt.
			$status = 'valid';
		}

		$data = json_decode( wp_remote_retrieve_body( $response ) );

		if ( $data ) {
			set_transient( 'pronamic_pay_license_data', $data, 30 );
		}
	}
}
