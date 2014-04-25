<?php

/**
 * Pronamic_S2Member_Bridge_Shortcodes
 *
 * Handles the generation and interpretation of
 * shortcodes for use in S2 Member
 *
 * @author Leon Rowland <leon@rowland.nl>
 * @since 1.2.6
 */
class Pronamic_S2Member_Bridge_Shortcodes {
	/**
	 * Constructs and initializes s2Member pay shortcodes
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'handle_payment' ) );

		add_shortcode( 'pronamic_ideal_s2member', array( $this, 'shortcode_pay' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Create an hash
	 *
	 * @param array $data
	 * @return string
	 */
	public function create_hash( $data ) {
		ksort( $data );

		return sha1( implode( '', $data ) . AUTH_SALT );
	}

	//////////////////////////////////////////////////

	/**
	 * Handles the generation of the form from shortcode arguments.
	 *
	 * Expected shortcode example (made by generator)
	 *
	 * [pronamic_ideal_s2member cost="10" period="1 Y" level="1" description="asdfasdfasdfas asdf asdf asdfa" ]
	 *
	 * period represents one of the predetermined durations they can
	 * selected from the dropdown.
	 *
	 * cost is set by the shortcode generator.  Must be ISO standard format ( . as decimal seperator )
	 *
	 * level is the level access upon payment will be granted.
	 *
	 * description is text shown at payment.
	 *
	 * @param array $atts All arguments inside the shortcode
	 */
	public function shortcode_pay( $atts ) {
		$defaults = array(
			'period'      => null,
			'cost'        => null,
			'level'       => null,
			'description' => __( 'iDEAL s2Member Payment || {{order_id}}', 'pronamic_ideal' ),
		);

		// Combine the passed options
		$atts = shortcode_atts( $defaults, $atts );
		$atts['order_id'] = uniqid();

		// Output
		$output = '';

		// Get the config ID
		$config_id = get_option( 'pronamic_pay_s2member_config_id' );

		// Get the gateway from the configuration
		$gateway = Pronamic_WordPress_IDeal_IDeal::get_gateway( $config_id );

		// Data
		$data = new Pronamic_WP_Pay_S2Member_PaymentData( $atts );

		if ( $gateway ) {
			$output .= '<form method="post" action="">';

			if ( ! is_user_logged_in() ) {
				$output .= sprintf(
					'<label for="%s">%s</label>',
					esc_attr( 'pronamic_pay_s2member_email' ),
					esc_html__( 'Email', 'pronamic_ideal' )
				);
				$output .= ' ';
				$output .= sprintf(
					'<input id="%s" name="%s" value="%s" type="text" />',
					esc_attr( 'pronamic_pay_s2member_email' ),
					esc_attr( 'pronamic_pay_s2member_email' ),
					$data->get_email()
				);
				$output .= ' ';
			}

			$output .= $gateway->get_input_html();

			$output .= ' ';

			$output .= Pronamic_IDeal_IDeal::htmlHiddenFields( array(
				'pronamic_pay_s2member_hash'              => $this->create_hash( $atts ),
				'pronamic_pay_s2member_data[order_id]'    => $atts['order_id'],
				'pronamic_pay_s2member_data[period]'      => $atts['period'],
				'pronamic_pay_s2member_data[cost]'        => $atts['cost'],
				'pronamic_pay_s2member_data[level]'       => $atts['level'],
				'pronamic_pay_s2member_data[description]' => $atts['description']
			) );

			$output .= sprintf(
				'<input name="%s" value="%s" type="submit" />',
				esc_attr( 'pronamic_pay_s2member' ),
				esc_attr__( 'Pay with iDEAL', 'pronamic_ideal' )
			);

			$output .= '</form>';
		}

		return $output;
	}

	//////////////////////////////////////////////////

	/**
	 * Handle payment
	 */
	public function handle_payment() {
		if ( filter_has_var( INPUT_POST, 'pronamic_pay_s2member' ) ) {
			$hash = filter_input( INPUT_POST, 'pronamic_pay_s2member_hash', FILTER_SANITIZE_STRING );
			$data = filter_input( INPUT_POST, 'pronamic_pay_s2member_data', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY );

			if ( $this->create_hash( $data ) == $hash ) {
				// Config
				$config_id = get_option( 'pronamic_pay_s2member_config_id' );

				// Gateway
				$gateway = Pronamic_WordPress_IDeal_IDeal::get_gateway( $config_id );

				// Data
				$data = new Pronamic_WP_Pay_S2Member_PaymentData( $data );

				$email = $data->get_email();

				if ( ! empty( $email ) ) {
					// Start
					$payment = Pronamic_WordPress_IDeal_IDeal::start( $config_id, $gateway, $data );

					update_post_meta( $payment->get_id(), '_pronamic_payment_s2member_period', $data->get_period() );
					update_post_meta( $payment->get_id(), '_pronamic_payment_s2member_level', $data->get_level() );

					// Redirect
					$gateway->redirect( $payment );
				}
			}
		}
	}
}
