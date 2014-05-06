<?php

/**
 * Title: Membership iDEAL gateway
 * Copyright: Pronamic (c) 2005 - 2013
 * Company: Pronamic
 * @see http://plugins.trac.wordpress.org/browser/membership/tags/3.4.4.1/membershipincludes/gateways/gateway.freesubscriptions.php
 * @author Leon Rowland <leon@rowland.nl>
 * @version 1.0
 */
class Pronamic_WPMUDEV_Membership_IDeal_IDealGateway extends Membership_Gateway {
	/**
	 * Gateway name/slug
	 *
	 * @see http://plugins.trac.wordpress.org/browser/membership/tags/3.4.4.1/membershipincludes/classes/class.gateway.php#L10
	 * @var string
	 */
	public $gateway = 'ideal';

	/**
	 * Gateway title
	 *
	 * @see http://plugins.trac.wordpress.org/browser/membership/tags/3.4.4.1/membershipincludes/classes/class.gateway.php#L11
	 * @var string
	 */
	public $title = 'iDEAL';

	//////////////////////////////////////////////////

	/**
	 * Constructs and initliaze an Membership iDEAL gateway
	 */
	public function __construct() {
		parent::__construct();

		// @see http://plugins.trac.wordpress.org/browser/membership/tags/3.4.4.1/membershipincludes/gateways/gateway.freesubscriptions.php#L30
		// @see http://plugins.trac.wordpress.org/browser/membership/tags/3.4.4.1/membershipincludes/classes/class.gateway.php#L97
		if ( $this->is_active() ) {
			add_action( 'init', array( $this, 'maybe_pay' ) );

			// @see http://plugins.trac.wordpress.org/browser/membership/tags/3.4.4.1/membershipincludes/includes/payment.form.php#L78
			add_action( 'membership_purchase_button', array( $this, 'purchase_button' ), 1, 3 );

			// Status update
			$slug = Pronamic_WPMUDEV_Membership_IDeal_AddOn::SLUG;

			add_action( "pronamic_payment_status_update_$slug", array( $this, 'status_update' ), 10, 2 );
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Record transaction helper function
	 *
	 * @see https://plugins.trac.wordpress.org/browser/membership/tags/3.4.4.1/membershipincludes/classes/class.gateway.php#L176
	 */
	public function pronamic_record_transaction( $user_id, $sub_id, $amount, $currency, $timestamp, $paypal_ID, $status, $note ) {
		// Membership <= 3.4
		// @see https://plugins.trac.wordpress.org/browser/membership/tags/3.4.4.1/membershipincludes/classes/class.gateway.php#L176
		if ( method_exists( $this, 'record_transaction' ) ) {
			$this->record_transaction( $user_id, $sub_id, $amount, $currency, $timestamp, $paypal_ID, $status, $note );
		}

		// Membership >= 3.5
		// @see https://github.com/pronamic-wpmudev/membership-premium/blob/3.5.1.2/classes/Membership/Gateway.php#L256
		if ( method_exists( $this, '_record_transaction' ) ) {
			$this->_record_transaction( $user_id, $sub_id, $amount, $currency, $timestamp, $paypal_ID, $status, $note );
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Maybe pay
	 */
	public function maybe_pay() {
		if ( filter_has_var( INPUT_POST, 'pronamic_pay_membership' ) ) {
			// Data
			$subscription_id = filter_input( INPUT_POST, 'subscription_id', FILTER_SANITIZE_STRING );
			$user_id         = filter_input( INPUT_POST, 'user_id', FILTER_SANITIZE_STRING );

			$subscription = Pronamic_WPMUDEV_Membership_Membership::get_subscription( $subscription_id );
			$membership   = Pronamic_WPMUDEV_Membership_Membership::get_membership( $user_id );

			if ( isset( $subscription, $membership ) ) {
				$config_id = get_option( Pronamic_WPMUDEV_Membership_IDeal_AddOn::OPTION_CONFIG_ID );

				$gateway = Pronamic_WP_Pay_Plugin::get_gateway( $config_id );

				$data = new Pronamic_WP_Pay_WPMUDEV_Membership_PaymentData( $subscription, $membership );

				// Start
				$payment = Pronamic_WP_Pay_Plugin::start( $config_id, $gateway, $data );

				update_post_meta( $payment->get_id(), '_pronamic_payment_membership_user_id', $user_id );
				update_post_meta( $payment->get_id(), '_pronamic_payment_membership_subscription_id', $data->get_subscription_id() );

				// Membership record transaction
				// @see http://plugins.trac.wordpress.org/browser/membership/tags/3.4.4.1/membershipincludes/classes/class.gateway.php#L176
				$this->pronamic_record_transaction(
					$user_id, // User ID
					$data->get_subscription_id(), // Sub ID
					$data->get_amount(), // Amount
					$data->get_currency(), // Currency
					time(), // Timestamp
					$payment->get_id(), // PayPal ID
					'', // Status
					'' // Note
				);

				// Redirect
				$gateway->redirect( $payment );
			}
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Purchase button
	 *
	 * @see http://plugins.trac.wordpress.org/browser/membership/tags/3.4.4.1/membershipincludes/includes/payment.form.php#L78
	 *
	 * @param M_Subscription $subscription
	 *     @see http://plugins.trac.wordpress.org/browser/membership/tags/3.4.4.1/membershipincludes/classes/class.subscription.php
	 *
	 * @param array $pricing
	 *     @see http://plugins.trac.wordpress.org/browser/membership/tags/3.4.4.1/membershipincludes/classes/class.subscription.php#L110
	 *
	 *     array(
	 *         array(
	 *             'period' => '1',
	 *             'amount' => '50.00',
	 *             'type'   => 'indefinite',
	 *             'unit'   => 'm'
	 *         )
	 *     )
	 *
	 * @param int $user_id WordPress user/member ID
	 */
	public function purchase_button( $subscription, $pricing, $user_id ) {
		if ( Pronamic_WPMUDEV_Membership_Membership::is_pricing_free( $pricing ) ) {
			// @todo what todo?
		} else {
			$membership = Pronamic_WPMUDEV_Membership_Membership::get_membership( $user_id );

			$config_id = get_option( Pronamic_WPMUDEV_Membership_IDeal_AddOn::OPTION_CONFIG_ID );

			$data = new Pronamic_WP_Pay_WPMUDEV_Membership_PaymentData( $subscription, $membership );

			$gateway = Pronamic_WP_Pay_Plugin::get_gateway( $config_id );

			if ( $gateway ) {
				global $M_options;

				// @see http://plugins.trac.wordpress.org/browser/membership/tags/3.4.4.1/membershipincludes/classes/membershipadmin.php#K2908
				if ( isset( $M_options['formtype'] ) && 'new' == strtolower( $M_options['formtype'] ) ) {
					$action = add_query_arg( array(
						'action'       => 'buynow',
						'subscription' => $data->get_subscription_id(),
					), admin_url( 'admin-ajax.php' ) );
				} else {
					$action = '#pronamic-pay-form';
				}

				printf(
					'<form id="pronamic-pay-form" method="post" action="%s">',
					$action
				);

				printf(
					'<img src="%s" alt="%s" />',
					esc_attr( plugins_url( 'images/ideal-logo-pay-off-2-lines.png', Pronamic_WP_Pay_Plugin::$file ) ),
					esc_attr__( 'iDEAL - Online payment through your own bank', 'pronamic_ideal' )
				);

				echo '<div style="margin-top: 1em;">';

				echo $gateway->get_input_html();

				// Data
				$fields = array(
					'subscription_id' => $data->get_subscription_id(),
					'user_id'         => $user_id,
				);

				// Coupon
				$coupon = membership_get_current_coupon();

				if ( $coupon ) {
					$fields['coupon_code'] = $coupon->get_coupon_code();
				}

				echo Pronamic_IDeal_IDeal::htmlHiddenFields( $fields );

				// Submit button
				printf(
					'<input type="submit" name="pronamic_pay_membership" value="%s" />',
					esc_attr__( 'Pay', 'pronamic_ideal' )
				);

				echo '</div>';

				printf( '</form>' );
			}
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Status update
	 */
	public function status_update( Pronamic_Pay_Payment $payment, $can_redirect = false ) {
		$user_id  = get_post_meta( $payment->get_id(), '_pronamic_payment_membership_user_id', true );
		$sub_id   = get_post_meta( $payment->get_id(), '_pronamic_payment_membership_subscription_id', true );
		$amount   = $payment->get_amount();
		$currency = $payment->get_currency();
		$status   = $payment->get_status();
		$note     = '';

		// Membership record transaction
		// @see http://plugins.trac.wordpress.org/browser/membership/tags/3.4.4.1/membershipincludes/classes/class.gateway.php#L176
		$this->pronamic_record_transaction( $user_id, $sub_id, $amount, $currency, time(), $payment->get_id(), $status, $note );

		switch ( $status ) {
			case Pronamic_Pay_Gateways_IDeal_Statuses::CANCELLED:

				break;
			case Pronamic_Pay_Gateways_IDeal_Statuses::EXPIRED:

				break;
			case Pronamic_Pay_Gateways_IDeal_Statuses::FAILURE:

				break;
			case Pronamic_Pay_Gateways_IDeal_Statuses::OPEN:
				// @see http://plugins.trac.wordpress.org/browser/membership/tags/3.4.4.1/membershipincludes/gateways/gateway.paypalexpress.php#L871
				do_action( 'membership_payment_pending', $user_id, $sub_id, $amount, $currency, $payment->get_id() );

				break;
			case Pronamic_Pay_Gateways_IDeal_Statuses::SUCCESS:
				$member = new M_Membership( $user_id );
				if ( $member ) {
					$member->create_subscription( $sub_id, $this->gateway );
				}

				// Added for affiliate system link
				// @see http://plugins.trac.wordpress.org/browser/membership/tags/3.4.4.1/membershipincludes/gateways/gateway.paypalexpress.php#L790
				do_action( 'membership_payment_processed', $user_id, $sub_id, $amount, $currency, $payment->get_id() );

				// @see http://plugins.trac.wordpress.org/browser/membership/tags/3.4.4.1/membershipincludes/gateways/gateway.paypalexpress.php#L901
				do_action( 'membership_payment_subscr_signup', $user_id, $sub_id );

				break;
		}
	}

	/**
	 * Update
	 *
	 * @return boolean
	 */
	public function update() {
		// Default action is to return true
		return true;
	}
}
