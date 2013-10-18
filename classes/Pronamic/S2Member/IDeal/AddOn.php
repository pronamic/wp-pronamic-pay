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
		add_action( 'plugins_loaded', array( __CLASS__, 'load' ), 100 );
	}

	public static function load() {
		if ( defined( 'WS_PLUGIN__S2MEMBER_VERSION' ) ) {
			// Bridge Classes
			new Pronamic_S2Member_Bridge_Order();
			new Pronamic_S2Member_Bridge_Settings();
			new Pronamic_S2Member_Bridge_Shortcodes();

			$slug = 's2member';
	
			add_action( "pronamic_payment_status_update_$slug", array( __CLASS__, 'status_update' ), 10, 2 );
			add_filter( "pronamic_payment_source_text_$slug",   array( __CLASS__, 'source_text' ), 10, 2 );
		}
	}

	public static function status_update( Pronamic_Pay_Payment $payment, $can_redirect = false ) {		
		$data = new Pronamic_WP_Pay_S2Member_PaymentData( array(
			'level'  => get_post_meta( $payment->id, '_pronamic_payment_s2member_level', true ),
			'period' => get_post_meta( $payment->id, '_pronamic_payment_s2member_period', true )
		) );

		$url = $data->get_normal_return_url();

		switch ( $payment->status ) {
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_CANCELLED:
				$url = $data->get_cancel_url();

				break;
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_EXPIRED:
				$url = $data->get_error_url();

				break;
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_FAILURE:
				$url = $data->get_error_url();

				break;
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_SUCCESS:
				$url = $data->get_success_url();
				$email = $payment->get_email();

				// get account from email
				$user = get_user_by( 'email', $email );

				// No valid user?
				if ( ! $user ) {
					// Make a random string for password
					$random_string = wp_generate_password( 10 );

					// Make a user with the username as the email
					$user_id = wp_create_user( $email, $random_string, $email );
											
					$subject = __( 'Account Confirmation', 'pronamic-ideal' ) . ' | ' . get_bloginfo( 'name' );
					$message = sprintf( __( 'Your password is %s . Please change your password when you login', 'pronamic-ideal' ), $random_string );
					wp_mail( $email, $subject, $message );

					$user = new WP_User( $user_id );
				}

				$level      = $data->get_level();
				$period     = $data->get_period();

				$capability = 'access_s2member_level' . $level;
				$role       = 's2member_level' . $level;

				// Update user role
				if ( user_can( $user, $capability ) ) {
					$note = sprintf(
						__( 'User "%s" already has access to "%s".', 'pronamic_ideal' ),
						$email,
						$capability
					);
				} else {
					$user->add_cap( $capability );
					$user->set_role( $role );
					
					$note = sprintf(
						__( 'Update user "%s" to role "%s" and added custom capability "%s".', 'pronamic_ideal' ),
						$email,
						$role,
						$capability
					);
				}

				$payment->add_note( $note );

				// Registration times
				$registration_time = time();

				$registration_times = get_user_option( 's2member_paid_registration_times', $user->ID );
				if ( empty( $registration_times ) )
					$registration_times = array( );
				
				$registration_times[ 'level' . $level ]	 = $registration_time;

				update_user_option( $user->ID, 's2member_paid_registration_times', $registration_times );

				// Auto end of time
				$auto_time = c_ws_plugin__s2member_utils_time::auto_eot_time( $user->ID, $period, false, false, $registration_time );

				update_user_option( $user->ID, 's2member_auto_eot_time', $auto_time );

				break;
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_OPEN:
				$url = $data->get_normal_return_url();

				break;
		}

		if ( $url && $can_redirect ) {
			wp_redirect( $url, 303 );

			exit;
		}
	}
	
	//////////////////////////////////////////////////
	
	/**
	 * Source column
	 */
	public static function source_text( $text, Pronamic_WP_Pay_Payment $payment ) {
		$text  = '';

		$text .= __( 's2Member', 'pronamic_ideal' );

		return $text;
	}
}
