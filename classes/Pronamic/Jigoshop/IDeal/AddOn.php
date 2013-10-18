<?php

/**
 * Title: Jigoshop iDEAL Add-On
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Jigoshop_IDeal_AddOn {
	/**
	 * Slug
	 *
	 * @var string
	 */
	const SLUG = 'jigoshop';

	//////////////////////////////////////////////////

	/**
	 * Bootstrap
	 */
	public static function bootstrap() {
		add_action( 'init', array( __CLASS__, 'init' ) );

		// Gateways need to add themselves to this filter -prior- to the 'init' action hook
		// @see https://github.com/jigoshop/jigoshop/blob/1.8/gateways/gateways.class.php#L34
		add_filter( 'jigoshop_payment_gateways', array( __CLASS__, 'payment_gateways' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Initailze
	 */
	public static function init() {
		if ( Pronamic_Jigoshop_Jigoshop::is_active() ) {
			$slug = self::SLUG;
			
			add_action( "pronamic_payment_status_update_$slug", array( __CLASS__, 'update_status' ), 10, 2 );
			add_filter( "pronamic_payment_source_text_$slug",   array( __CLASS__, 'source_text' ), 10, 2 );
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Add the gateway to Jigoshop
	 */
	function payment_gateways( $methods ) {
		$methods[] = 'Pronamic_Jigoshop_IDeal_IDealGateway';

		return $methods;
	}

	//////////////////////////////////////////////////

	/**
	 * Update lead status of the specified payment
	 *
	 * @param string $payment
	 */
	public static function update_status( Pronamic_Pay_Payment $payment, $can_redirect = false ) {
		$id = $payment->get_source_id();

		$order = new jigoshop_order( (int) $id );
		$data  = new Pronamic_WP_Pay_Jigoshop_PaymentData( $order );

		$should_update = ! in_array(
			$order->status,
			array(
				Pronamic_Jigoshop_Jigoshop::ORDER_STATUS_COMPLETED,
				Pronamic_Jigoshop_Jigoshop::ORDER_STATUS_PROCESSING
			)
		);

		if ( $should_update ) {
			$url = $data->get_normal_return_url();

			switch ( $payment->status ) {
				case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_CANCELLED:
					$order->update_status( Pronamic_Jigoshop_Jigoshop::ORDER_STATUS_CANCELLED, __( 'iDEAL payment cancelled.', 'pronamic_ideal' ) );

					$url = $data->get_cancel_url();

					break;
				case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_EXPIRED:
					// Jigoshop PayPal gateway uses 'on-hold' order status for an 'expired' payment
					// @see http://plugins.trac.wordpress.org/browser/jigoshop/tags/1.2.1/gateways/paypal.php#L430
					$order->update_status( Pronamic_Jigoshop_Jigoshop::ORDER_STATUS_ON_HOLD, __( 'iDEAL payment expired.', 'pronamic_ideal' ) );

					break;
				case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_FAILURE:
					// Jigoshop PayPal gateway uses 'on-hold' order status for an 'failure' in the payment
					// @see http://plugins.trac.wordpress.org/browser/jigoshop/tags/1.2.1/gateways/paypal.php#L431
					$order->update_status( 'failed', __( 'iDEAL payment failed.', 'pronamic_ideal' ) );

					break;
				case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_SUCCESS:
	            	// Payment completed
	                $order->add_order_note( __( 'iDEAL payment completed.', 'pronamic_ideal' ) );
	                $order->payment_complete();

	                $url = $data->get_success_url();

					break;
				case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_OPEN:
	                $order->add_order_note( __( 'iDEAL payment open.', 'pronamic_ideal' ) );

					break;
				default:
	                $order->add_order_note( __( 'iDEAL payment unknown.', 'pronamic_ideal' ) );

					break;
			}

			if ( $url && $can_redirect ) {
				wp_redirect( $url, 303 );

				exit;
			}
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Source column
	 */
	public static function source_text( $text, Pronamic_Pay_Payment $payment ) {
		$text  = '';

		$text .= __( 'Jigoshop', 'pronamic_ideal' ) . '<br />';

		$text .= sprintf(
			'<a href="%s">%s</a>',
			get_edit_post_link( $payment->get_source_id() ),
			sprintf( __( 'Order #%s', 'pronamic_ideal' ), $payment->get_source_id() )
		);

		return $text;
	}
}
