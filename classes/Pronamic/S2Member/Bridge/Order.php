<?php

class Pronamic_S2Member_Bridge_Order {

	public static $periods = array();

	public function __construct() {
		// Periods
		$periods = array(
			'1 D' => __( 'One Time ( for 1 day access, non-recurring )', 'pronamic_ideal' ),
			'2 D' => __( 'One Time ( for 2 day access, non-recurring )', 'pronamic_ideal' ),
			'3 D' => __( 'One Time ( for 3 day access, non-recurring )', 'pronamic_ideal' ),
			'4 D' => __( 'One Time ( for 4 day access, non-recurring )', 'pronamic_ideal' ),
			'5 D' => __( 'One Time ( for 5 day access, non-recurring )', 'pronamic_ideal' ),
			'6 D' => __( 'One Time ( for 6 day access, non-recurring )', 'pronamic_ideal' ),
			'1 W' => __( 'One Time ( for 1 week access, non-recurring )', 'pronamic_ideal' ),
			'2 W' => __( 'One Time ( for 2 week access, non-recurring )', 'pronamic_ideal' ),
			'3 W' => __( 'One Time ( for 3 week access, non-recurring )', 'pronamic_ideal' ),
			'1 M' => __( 'One Time ( for 1 month access, non-recurring )', 'pronamic_ideal' ),
			'2 M' => __( 'One Time ( for 2 month access, non-recurring )', 'pronamic_ideal' ),
			'3 M' => __( 'One Time ( for 3 month access, non-recurring )', 'pronamic_ideal' ),
			'4 M' => __( 'One Time ( for 4 month access, non-recurring )', 'pronamic_ideal' ),
			'5 M' => __( 'One Time ( for 5 month access, non-recurring )', 'pronamic_ideal' ),
			'6 M' => __( 'One Time ( for 6 month access, non-recurring )', 'pronamic_ideal' ),
			'1 Y' => __( 'One Time ( for 1 year access, non-recurring )', 'pronamic_ideal' ),
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