<?php

/**
 * Title: ClassiPress
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_ClassiPress_ClassiPress {
	/**
	 * Check if ClassiPress is active (Automattic/developer style)
	 *
	 * @see https://bitbucket.org/Pronamic/classipress/src/bc1334736c6eace2a6801625a9d38c5490f4540a/functions.php?at=3.2.1#cl-28
	 * @see https://github.com/Automattic/developer/blob/1.1.2/developer.php#L73
	 *
	 * @return boolean
	 */
	public static function is_active() {
		return defined( 'APP_TD' ) && 'classipress' === APP_TD;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the current ClassPress version number
	 *
	 * @return string
	 */
	public static function get_version() {
		// @see https://bitbucket.org/Pronamic/classipress/src/bc1334736c6eace2a6801625a9d38c5490f4540a/functions.php?at=3.2.1#cl-15
		global $app_version;

		return $app_version;
	}

	//////////////////////////////////////////////////

	/**
	 * Add an transaction entry for the specified order
	 *
	 * @param array $order
	 * @return transaction ID
	 */
	public static function add_transaction_entry( array $order ) {
		/*
		 * We have copied this from the "Bank transfer" gateway:
		 * @see https://bitbucket.org/Pronamic/classipress/src/bc1334736c6e/includes/gateways/banktransfer/banktransfer.php?at=3.2.1#cl-39
		 */
		$transaction_id = false;

		// Require ClassiPress gateway process file
		$file = get_template_directory() . '/includes/gateways/process.php';

		if ( is_readable( $file ) ) {
			require_once $file;

			// @see https://bitbucket.org/Pronamic/classipress/src/bc1334736c6e/includes/gateways/process.php?at=3.2.1#cl-106
			$transaction_entry = cp_prepare_transaction_entry( $order );

			if ( $transaction_entry ) {
				// @see https://bitbucket.org/Pronamic/classipress/src/bc1334736c6e/includes/gateways/process.php?at=3.2.1#cl-152
				$transaction_id = cp_add_transaction_entry( $transaction_entry );
			}
		}

		return $transaction_id;
	}

	//////////////////////////////////////////////////

	/**
	 * Get order by id
	 *
	 * @param string $id
	 * @return mixed order array or null
	 */
	public static function get_order_by_id( $order_id ) {
		global $wpdb;

		/*
		 * The table structure of the 'cp_order_info' table can be found here:
		 * @see https://bitbucket.org/Pronamic/classipress/src/bc1334736c6e/includes/admin/install-script.php?at=3.2.1#cl-166
		 */
		$sql = $wpdb->prepare( "
			SELECT
				*
			FROM
				$wpdb->cp_order_info
			WHERE
				txn_id = %s
			", $order_id
		);

		return $wpdb->get_row( $sql, ARRAY_A );
	}

	//////////////////////////////////////////////////

	/**
	 * Process membership order
	 *
	 * @param array
	 */
	public static function process_membership_order( $order_info ) {
		$file = get_template_directory() . '/includes/forms/step-functions.php';

		if ( is_readable( $file ) ) {
			include_once $file;

			/*
			 * Abracadabra
			 */
			$txn_id = $order_info['txn_id'];

			/*
			 * First we retrieve user orders by the transaction id
			 * @see https://bitbucket.org/Pronamic/classipress/src/bc1334736c6e/includes/theme-functions.php?at=3.2.1#cl-2488
			 */
			$orders = get_user_orders( '', $txn_id );
			$order  = get_option( $orders );

			/*
			 * Get the user ID from the orders
			 * @see https://bitbucket.org/Pronamic/classipress/src/bc1334736c6e/includes/theme-functions.php?at=3.2.1#cl-2476
			 */
			$user_id = get_order_userid( $orders );

			/*
			 * Get user data
			 * @see http://codex.wordpress.org/Function_Reference/get_userdata
			 */
			$userdata = get_userdata( $user_id );

			// @see https://bitbucket.org/Pronamic/classipress/src/bc1334736c6e/includes/forms/step-functions.php?at=3.2.1#cl-895
			$order_processed = appthemes_process_membership_order( $userdata, $order );

			if ( $order_processed ) {
				// @see https://bitbucket.org/Pronamic/classipress/src/bc1334736c6e/includes/theme-emails.php?at=3.2.1#cl-563
				cp_owner_activated_membership_email( $userdata, $order_processed );
			}
		}
	}

	/**
	 * Process ad order
	 *
	 * @param array
	 */
	public static function process_ad_order( $order_id ) {
		$post = self::get_post_ad_by_id( $order_id );

		if ( ! empty( $post ) ) {
			self::update_ad_status( $post->ID, 'publish' );
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Get ad by id
	 *
	 * @param string $order_id
	 */
	public static function get_post_ad_by_id( $order_id ) {
		global $wpdb;

		/*
		 * The post order ID is stored in the 'cp' meta key:
		 * @see https://bitbucket.org/Pronamic/classipress/src/bc1334736c6e/includes/forms/step-functions.php?at=3.2.1#cl-822
		 *
		 * We have copied this from the PayPal gateway:
		 * @see https://bitbucket.org/Pronamic/classipress/src/bc1334736c6e/includes/gateways/paypal/ipn.php?at=3.2.1#cl-178
		 */
		$sql = $wpdb->prepare( "
			SELECT
				post.ID,
				post.post_status
			FROM
				$wpdb->posts AS post,
				$wpdb->postmeta AS meta
			WHERE
				post.ID = meta.post_id
					AND
				post.post_status != 'publish'
					AND
				meta.meta_key = 'cp_sys_ad_conf_id'
					AND
				meta.meta_value = %s
			", $order_id
		);

		return $wpdb->get_row( $sql );
	}

	//////////////////////////////////////////////////

	/**
	 * Update ad status
	 *
	 * @param string $id
	 * @param string $status
	 */
	public static function update_ad_status( $id, $status ) {
		$data = array();
		$data['ID']          = $id;
		$data['post_status'] = $status;

		$result = wp_update_post( $data );

		/*
		 * We have copied this from the PayPal gateway:
		 * @see https://bitbucket.org/Pronamic/classipress/src/bc1334736c6e/includes/gateways/paypal/ipn.php?at=3.2.1#cl-190
		 *
		 * now we need to update the ad expiration date so they get the full length of time
		 * sometimes they didn't pay for the ad right away or they are renewing
		 *
		 * first get the ad duration and first see if ad packs are being used
		 * if so, get the length of time in days otherwise use the default
		 * prune period defined on the CP settings page
		 */
		$duration = get_post_meta( $id, 'cp_sys_ad_duration', true );
		if ( ! isset( $duration ) ) {
			$duration = get_option( 'cp_prun_period' );
		}

		// set the ad listing expiration date
		$expire_date = date_i18n( 'm/d/Y H:i:s', strtotime( '+' . $duration . ' days' ) ); // don't localize the word 'days'

		// now update the expiration date on the ad
		update_post_meta( $id, 'cp_sys_expire_date', $expire_date );
	}

	//////////////////////////////////////////////////

	/**
	 * Update payment status
	 *
	 * @param string $id
	 * @param string $status
	 * @return int
	 */
	public static function update_payment_status_by_txn_id( $txn_id, $status ) {
		global $wpdb;

		/*
		 * The table structure of the 'cp_order_info' table can be found here:
		 * @see https://bitbucket.org/Pronamic/classipress/src/bc1334736c6e/includes/admin/install-script.php?at=3.2.1#cl-166
		 */
		$result = $wpdb->update(
			$wpdb->cp_order_info,
			array( 'payment_status' => $status ),
			array( 'txn_id' => $txn_id )
		);

		return $result;
	}
}
