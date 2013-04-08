<?php

/**
 * Title: s2Member iDEAL add-on
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Leon Rowland
 * @since 1.2.6
 */
class Pronamic_S2Member_IDeal_AddOn {
    public static function bootstrap() {
        add_action( 'plugins_loaded', array( __CLASS__, 'load' ) );
    }

	public static function load() {
		if ( defined( 'WS_PLUGIN__S2MEMBER_VERSION' ) ) {
			// Bridge Classes
			new Pronamic_S2Member_Bridge_Order();
			new Pronamic_S2Member_Bridge_Settings();
			new Pronamic_S2Member_Bridge_Shortcodes();

			add_action( 'pronamic_ideal_status_update', array( __CLASS__, 'status_update' ) );
		}
	}

	public static function status_update( Pronamic_WordPress_IDeal_Payment $payment, $can_redirect = false ) {
		if ( $payment->getSource() == 's2member' ) {
			// Get the ID of the payment source ( which will be the meta id )
			$user_id = Pronamic_S2Member_Bridge_Order::getUserFromUID( $payment->getSourceId() );

			// Get user order meta
			$order_data = get_user_option( 'pronamic_ideal_s2member_order', $user_id );

			if ( 'completed' !== $order_data['status'] ) {

				$order_data['orderID'] = $payment->getSourceId();
				$data = new Pronamic_S2Member_IDeal_IDealDataProxy( $order_data );

				$url = $data->getNormalReturnUrl();

				switch( $payment->status ) {
					case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_CANCELLED:
						$order_data['status'] = 'Cancelled';

						$url = $data->getCancelUrl();

						break;
					case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_EXPIRED:
						$order_data['status'] = 'Expired';

						break;
					case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_FAILURE:
						$order_data['status'] = 'Failure';

						break;
					case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_SUCCESS:
						// Add a registration time for their level
						$registration_times = get_user_option( 's2member_paid_registration_times', $user_id );

						$ordered_level = $order_data['level'];

						if ( empty( $registration_times ) ) $registration_times = array();
						$registration_times['level' . $ordered_level] = time();

						$user = new WP_User( $user_id );
						// $user->add_cap( "s2member_level{$ordered_level}" );
						$user->add_cap( "access_s2member_level{$ordered_level}" );
						// $user->set_role( "s2member_level{$ordered_level}" );

						update_user_option( $user_id, 's2member_paid_registration_times', $registration_times );

						$auto_time = c_ws_plugin__s2member_utils_time::auto_eot_time($user_id, $order_data['period'], false, false, $registration_times['level' . $ordered_level ] );

						update_user_option( $user_id, 's2member_auto_eot_time', $auto_time );

						break;
					case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_OPEN:
						$order_data['status'] = 'Open';

						break;
					default:
						$order_data['status'] = 'Unknown';

						break;

					$order = new Pronamic_S2Member_Bridge_Order();
					$order->update_order( $order_data, $user_id);

				}
			}
		}
	}
}
