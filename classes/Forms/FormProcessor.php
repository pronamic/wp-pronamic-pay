<?php
/**
 * Form Processor
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Forms
 */

namespace Pronamic\WordPress\Pay\Forms;

/**
 * Form Processor
 *
 * @author Remco Tolsma
 * @version 3.7.0
 * @since 3.7.0
 */
class FormProcessor {
	/**
	 * Constructs and initalize an form processor object.
	 *
	 * @param Plugin $plugin Plugin.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;

		// Actions.
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Initialize.
	 */
	public function init() {
		global $pronamic_pay_errors;

		$pronamic_pay_errors = array();

		// Nonce.
		if ( ! filter_has_var( INPUT_POST, 'pronamic_pay_nonce' ) ) {
			return;
		}

		$nonce = filter_input( INPUT_POST, 'pronamic_pay_nonce', FILTER_SANITIZE_STRING );

		if ( ! wp_verify_nonce( $nonce, 'pronamic_pay' ) ) {
			return;
		}

		// Validate.
		$valid = $this->validate();

		if ( ! $valid ) {
			return;
		}

		// Gateway.
		$id = filter_input( INPUT_POST, 'pronamic_pay_form_id', FILTER_VALIDATE_INT );

		$config_id = get_post_meta( $id, '_pronamic_payment_form_config_id', true );

		$gateway = \Pronamic\WordPress\Pay\Plugin::get_gateway( $config_id );

		if ( ! $gateway ) {
			return;
		}

		// Data.
		$data = new PaymentFormData();

		$payment = \Pronamic\WordPress\Pay\Plugin::start( $config_id, $gateway, $data );

		$error = $gateway->get_error();

		if ( is_wp_error( $error ) ) {
			\Pronamic\WordPress\Pay\Plugin::render_errors( $error );

			exit;
		}

		// @see https://github.com/WordImpress/Give/blob/1.1/includes/payments/functions.php#L172-L178.
		// @see https://github.com/woothemes/woocommerce/blob/2.4.3/includes/wc-user-functions.php#L36-L118.
		$first_name = filter_input( INPUT_POST, 'pronamic_pay_first_name', FILTER_SANITIZE_STRING );
		$last_name  = filter_input( INPUT_POST, 'pronamic_pay_last_name', FILTER_SANITIZE_STRING );
		$email      = filter_input( INPUT_POST, 'pronamic_pay_email', FILTER_VALIDATE_EMAIL );

		$user = get_user_by( 'email', $email );

		if ( ! $user ) {
			// Make a random string for password.
			$password = wp_generate_password( 10 );

			// Make a user with the username as the email.
			$user_id = wp_insert_user(
				array(
					'user_login' => $email,
					'user_pass'  => $password,
					'user_email' => $email,
					'role'       => 'payer',
					'first_name' => $first_name,
					'last_name'  => $last_name,
				)
			);

			// User.
			$user = new \WP_User( $user_id );
		}

		wp_update_post(
			array(
				'ID'          => $payment->post->ID,
				'post_author' => $user->ID,
			)
		);

		$gateway->redirect( $payment );

		exit;
	}

	/**
	 * Validate.
	 *
	 * @return boolean True if valid, false otherwise.
	 */
	private function validate() {
		global $pronamic_pay_errors;

		// First Name.
		$first_name = filter_input( INPUT_POST, 'pronamic_pay_first_name', FILTER_SANITIZE_STRING );

		if ( empty( $first_name ) ) {
			$pronamic_pay_errors['first_name'] = __( 'Please enter your first name', 'pronamic_ideal' );
		}

		// E-mail.
		$email = filter_input( INPUT_POST, 'pronamic_pay_email', FILTER_VALIDATE_EMAIL );

		if ( empty( $email ) ) {
			$pronamic_pay_errors['email'] = __( 'Please enter a valid email address', 'pronamic_ideal' );
		}

		return empty( $pronamic_pay_errors );
	}
}
