<?php

class Pronamic_S2Member_Bridge_Return {

	public function __construct() {

		//add_action( 'init', array( $this, 'listen' ) );
	}

	public function listen() {
		if ( ! isset( $_GET['key'] ) || ! isset( $_GET['uid'] ) )
			return;

		$user_id = Pronamic_S2Member_Bridge_Order::getUserFromUID( $payment->get_source_id() );

		// Get user order meta
		$order = get_user_option( 'pronamic_ideal_s2member_order', $user_id );

		// Add a registration time for their level
		$registration_times = get_user_option( 's2member_paid_registration_times', $user_id );

		$ordered_level = $order['level'];

		$registration_times['level' + $ordered_level] = time();

		update_user_option( $user_id, 's2member_paid_registration_times', $registration_times );

		$auto_time = c_ws_plugin__s2member_utils_time::auto_eot_time($user_id, $order['period']);

		update_user_option( $user_id, 's2member_auto_eot_time', $auto_time );

	}
}