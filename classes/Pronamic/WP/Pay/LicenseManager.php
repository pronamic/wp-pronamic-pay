<?php

/**
 * Title: WordPress iDEAL admin
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WP_Pay_LicenseManager {
	/**
	 * Constructs and initalize an license manager object
	 */
	public function __construct() {
		// Actions
		add_action( 'pronamic_pay_license_check', array( $this, 'license_check_event' ) );

		// Filters
		add_filter( sprintf( 'pre_update_option_%s', 'pronamic_pay_license_key' ), array( $this, 'pre_update_option_license_key' ), 10, 2 );
	}

	//////////////////////////////////////////////////

	/**
	 * Pre update option 'pronamic_pay_license_key'
	 *
	 * @param string $newvalue
	 * @param string $oldvalue
	 * @return string
	 */
	public function pre_update_option_license_key( $newvalue, $oldvalue ) {
		if ( $newvalue != $oldvalue ) {
			delete_option( 'pronamic_pay_license_status' );

			$this->deactivate_license( $oldvalue );
			$this->activate_license( $newvalue );
		}

		return $newvalue;
	}

	//////////////////////////////////////////////////

	/**
	 * License check event
	 */
	public function license_check_event() {
		$license = get_option( 'pronamic_pay_license_key' );

		$this->check_license( $license );
	}

	//////////////////////////////////////////////////

	/**
	 * Activate license
	 *
	 * @param string $license
	 * @return boolean
	 */
	public function check_license( $license ) {
		$args = array(
			'license' => $license,
			'name'    => 'Pronamic iDEAL',
			'url'     => home_url(),
		);

		$args = urlencode_deep( $args );

		$response = wp_remote_get( add_query_arg( $args, 'http://api.pronamic.eu/licenses/check/1.0/' ) );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		if ( $license_data ) {
			update_option( 'pronamic_pay_license_status', $license_data->license );
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Deactivate license
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

		$url = add_query_arg( $args, 'http://api.pronamic.eu/licenses/deactivate/1.0/' );

		$response = wp_remote_get( $url );
	}

	//////////////////////////////////////////////////

	/**
	 * Activate license
	 *
	 * @param string $license
	 * @return boolean
	 */
	public function activate_license( $license ) {
		$args = array(
			'license' => $license,
			'name'    => 'Pronamic iDEAL',
			'url'     => home_url(),
		);

		$args = urlencode_deep( $args );

		$response = wp_remote_get( add_query_arg( $args, 'http://api.pronamic.eu/licenses/activate/1.0/' ) );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		if ( $license_data ) {
			update_option( 'pronamic_pay_license_status', $license_data->license );
		}
	}
}
