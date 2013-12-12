<?php

/**
 * Title: s2Member bridge order
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Leon Rowland
 * @since 1.2.6
 */
class Pronamic_S2Member_Bridge_Order {
	public static $periods = array();

	public function __construct() {
		$label = __( 'One Time ( for %s access, non-recurring )', 'pronamic_ideal' );

		// Periods
		$periods = array(
			'1 D' => sprintf( $label, __( '1 day', 'pronamic_ideal' ) ),
			'2 D' => sprintf( $label, __( '2 day', 'pronamic_ideal' ) ),
			'3 D' => sprintf( $label, __( '3 day', 'pronamic_ideal' ) ),
			'4 D' => sprintf( $label, __( '4 day', 'pronamic_ideal' ) ),
			'5 D' => sprintf( $label, __( '5 day', 'pronamic_ideal' ) ),
			'6 D' => sprintf( $label, __( '6 day', 'pronamic_ideal' ) ),
			'1 W' => sprintf( $label, __( '1 week', 'pronamic_ideal' ) ),
			'2 W' => sprintf( $label, __( '2 week', 'pronamic_ideal' ) ),
			'3 W' => sprintf( $label, __( '3 week', 'pronamic_ideal' ) ),
			'1 M' => sprintf( $label, __( '1 month', 'pronamic_ideal' ) ),
			'2 M' => sprintf( $label, __( '2 month', 'pronamic_ideal' ) ),
			'3 M' => sprintf( $label, __( '3 month', 'pronamic_ideal' ) ),
			'4 M' => sprintf( $label, __( '4 month', 'pronamic_ideal' ) ),
			'5 M' => sprintf( $label, __( '5 month', 'pronamic_ideal' ) ),
			'6 M' => sprintf( $label, __( '6 month', 'pronamic_ideal' ) ),
			'1 Y' => sprintf( $label, __( '1 year', 'pronamic_ideal' ) ),
			'2 Y' => sprintf( $label, __( '1 year', 'pronamic_ideal' ) ),
			'3 Y' => sprintf( $label, __( '1 year', 'pronamic_ideal' ) ),
			'4 Y' => sprintf( $label, __( '1 year', 'pronamic_ideal' ) ),
			'5 Y' => sprintf( $label, __( '1 year', 'pronamic_ideal' ) ),
			'1 L' => sprintf( $label, __( 'lifetime', 'pronamic_ideal' ) ),
		);

		self::$periods = apply_filters( 'pronamic_ideal_s2member_default_periods', $periods );
	}

	public function update_order( $order_data, $user_id ) {
		update_user_option( $user_id, 'pronamic_ideal_s2member_order', $order_data );
	}

	public function add_order( $order_data, $user_id ) {
		// Get user order
		$existing_user_order = get_user_option( 'pronamic_ideal_s2member_order', $user_id );

		// If the user has some order information, remove it
		if ( $existing_user_order )
			$this->remove_order( $user_id, 'pronamic_ideal_s2member_order' );

		// Store meta information in db
		update_user_option( $user_id, 'pronamic_ideal_s2member_order', $order_data );

		// Find the user option meta id and use that as an order id.  return it!
		return $this->find_user_option_uid( $user_id, 'pronamic_ideal_s2member_order' );
	}

	public function remove_order( $user_id ) {
		delete_user_option( $user_id, 'pronamic_ideal_s2member_order' );
	}

	public function find_user_option_uid( $user_id, $meta_key ) {
		global $wpdb;

		$table = _get_meta_table( 'user' );

		$meta = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE user_id = %d AND meta_key = %s", $user_id, $wpdb->prefix . $meta_key ) );

		if ( empty( $meta ) )
			return false;

		return $meta->umeta_id;
	}

	public static function getUserFromUID( $uid_key ) {
		global $wpdb;

		$table = _get_meta_table( 'user' );

		$meta = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE umeta_id = %d", $uid_key ) );

		if ( empty( $meta ) )
			return false;

		return $meta->user_id;
	}
}
