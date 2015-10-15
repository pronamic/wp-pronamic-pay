<?php

/**
 * Title: WordPress iDEAL form processor
 * Description:
 * Copyright: Copyright (c) 2005 - 2015
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 3.7.0
 * @since 3.7.0
 */
class Pronamic_WP_Pay_FormProcessor {
	/**
	 * Constructs and initalize an form processor object
	 */
	public function __construct() {
		// Actions
		add_action( 'init', array( $this, 'init' ) );

		add_filter( 'pronamic_payment_source_text_payment_form', array( $this, 'source_text' ), 10, 2 );
	}

	//////////////////////////////////////////////////

	/**
	 * Initialize
	 */
	public function init() {
		global $pronamic_pay_errors;

		$pronamic_pay_errors = array();

		if ( filter_has_var( INPUT_POST, 'pronamic_pay_nonce' ) ) {
			$nonce = filter_input( INPUT_POST, 'pronamic_pay_nonce', FILTER_SANITIZE_STRING );

			if ( wp_verify_nonce( $nonce, 'pronamic_pay' ) ) {
				$id = filter_input( INPUT_POST, 'pronamic_pay_form_id', FILTER_VALIDATE_INT );

				$config_id = get_post_meta( $id, '_pronamic_payment_form_config_id', true );

				$valid = $this->validate();

				if ( $valid ) {
					$gateway = Pronamic_WP_Pay_Plugin::get_gateway( $config_id );

					if ( $gateway ) {
						$data = new Pronamic_WP_Pay_PaymentFormData();

						$payment = Pronamic_WP_Pay_Plugin::start( $config_id, $gateway, $data );

						$error = $gateway->get_error();

						if ( is_wp_error( $error ) ) {
							Pronamic_WP_Pay_Plugin::render_errors( $error );
						} else {
							// @see https://github.com/WordImpress/Give/blob/1.1/includes/payments/functions.php#L172-L178
							// @see https://github.com/woothemes/woocommerce/blob/2.4.3/includes/wc-user-functions.php#L36-L118
							$first_name = filter_input( INPUT_POST, 'pronamic_pay_first_name', FILTER_SANITIZE_STRING );
							$last_name  = filter_input( INPUT_POST, 'pronamic_pay_last_name', FILTER_SANITIZE_STRING );
							$email      = filter_input( INPUT_POST, 'pronamic_pay_email', FILTER_VALIDATE_EMAIL );

							$user = get_user_by( 'email', $email );

							if ( ! $user ) {
								// Make a random string for password
								$password = wp_generate_password( 10 );

								// Make a user with the username as the email
								$user_id = wp_insert_user( array(
									'user_login' => $email,
									'user_pass'  => $password,
									'user_email' => $email,
									'role'       => 'payer',
									'first_name' => $first_name,
									'last_name'  => $last_name,
								) );

								// User
								$user = new WP_User( $user_id );
							}

							wp_update_post( array(
								'ID' => $payment->post->ID,
								'post_author' => $user->ID,
							) );

							$gateway->redirect( $payment );
						}

						exit;
					}
				}
			}
		}
	}

	private function validate() {
		global $pronamic_pay_errors;

		// First Name
		$first_name = filter_input( INPUT_POST, 'pronamic_pay_first_name', FILTER_SANITIZE_STRING );

		if ( empty( $first_name ) ) {
			$pronamic_pay_errors['first_name'] = __( 'Please enter your first name', 'pronamic_ideal' );
		}

		// E-mail
		$email = filter_input( INPUT_POST, 'pronamic_pay_email', FILTER_VALIDATE_EMAIL );

		if ( empty( $email ) ) {
			$pronamic_pay_errors['email'] = __( 'Please enter a valid email address', 'pronamic_ideal' );
		}

		return empty( $pronamic_pay_errors );
	}

	//////////////////////////////////////////////////

	/**
	 * Source text
	 */
	public function source_text( $text, Pronamic_WP_Pay_Payment $payment ) {
		$text  = '';

		$text .= __( 'Payment Form', 'pronamic_ideal' ) . '<br />';

		$text .= sprintf(
			'<a href="%s">%s</a>',
			get_edit_post_link( $payment->source_id ),
			$payment->source_id
		);

		return $text;
	}
}
