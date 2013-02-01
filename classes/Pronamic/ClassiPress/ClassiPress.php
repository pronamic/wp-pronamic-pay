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
	 * Get the current ClassPress version number
	 * 
	 * @return string
	 */	
	public static function get_version() {
		global $app_version;

		return $app_version;
	}

	//////////////////////////////////////////////////

	/**
	 * Get order by id
	 * 
	 * @param string $id
	 */
	public static function get_order_by_id_backup( $order_id ) {
		$order = null;

		$orders = get_user_orders( '', $order_id );

		if ( ! empty( $orders ) ) {
			$order = get_option( $orders );
			
			if ( ! empty( $order ) ) {
				$order = cp_get_order_vals( $order );
			}
		}

		return $order;
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
	public static function process_membership_order( $order ) {
		$file = get_template_directory() . '/includes/forms/step-functions.php';
		
		if ( is_readable( $file ) ) {
			include_once $file;
		}

		$user_id = $order['user_id'];

		if ( $user_id ) {
			$userdata = get_userdata( $user_id );

			$order_processed = appthemes_process_membership_order( $userdata, $order );

			if ( $order_processed ) {
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

		// now we need to update the ad expiration date so they get the full length of time
		// sometimes they didn't pay for the ad right away or they are renewing

		// first get the ad duration and first see if ad packs are being used
		// if so, get the length of time in days otherwise use the default
		// prune period defined on the CP settings page
		$duration = get_post_meta( $id, 'cp_sys_ad_duration', true );
		if ( ! isset( $duration ) ) {
			$duration = get_option( 'cp_prun_period' );
		}

		// set the ad listing expiration date
		$expire_date = date_i18n( 'm/d/Y H:i:s', strtotime( '+' . $duration . ' days' ) ); // don't localize the word 'days'

		// now update the expiration date on the ad
		update_post_meta( $id, 'cp_sys_expire_date', $expire_date );
	}
}
