<?php 

/**
 * Title: WooCommerce iDEAL Add-On
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WooCommerce_IDeal_AddOn {
	/**
	 * Slug
	 * 
	 * @var string
	 */
	const SLUG = 'woocommerce';

	//////////////////////////////////////////////////

	/**
	 * Bootstrap
	 */
	public static function bootstrap() {
		add_action( 'init', array( __CLASS__, 'init' ) );

		add_filter( 'woocommerce_payment_gateways', array( __CLASS__, 'payment_gateways' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Initialize
	 */
	public static function init() {
		if ( Pronamic_WooCommerce_WooCommerce::is_active() ) {
			$slug = self::SLUG;
			
			add_action( "pronamic_payment_status_update_$slug", array( __CLASS__, 'status_update' ), 10, 2 );
			add_filter( "pronamic_payment_source_text_$slug",   array( __CLASS__, 'source_text' ), 10, 2 );
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Add the gateway to WooCommerce
	 */
	public static function payment_gateways( $gateways ) {
		$gateways[] = 'Pronamic_WooCommerce_IDeal_IDealGateway';

		return $gateways;
	}

	//////////////////////////////////////////////////
	
	/**
	 * Update lead status of the specified payment
	 * 
	 * @param Pronamic_Pay_Payment $payment
	 */
	public static function status_update( Pronamic_Pay_Payment $payment, $can_redirect = false ) {
		$source_id = $payment->get_source_id();

		$order   = new WC_Order( (int) $source_id );
		$gateway = new Pronamic_WooCommerce_IDeal_IDealGateway();

		$data = new Pronamic_WooCommerce_PaymentData( $order, $gateway );

		// Only update if order is not 'processing' or 'completed'
		// @see https://github.com/woothemes/woocommerce/blob/v2.0.0/classes/class-wc-order.php#L1279
		$should_update = ! in_array(
			$order->status,
			array(
				Pronamic_WooCommerce_WooCommerce::ORDER_STATUS_COMPLETED,
				Pronamic_WooCommerce_WooCommerce::ORDER_STATUS_PROCESSING,
			)
		);

		// Defaults
		$status = null;
		$note   = null;
		$url    = $data->get_normal_return_url();
		
		$status = $payment->get_status();

		switch ( $status ) {
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_CANCELLED:
				$url = $data->get_cancel_url();

				break;
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_EXPIRED:
				if ( $should_update ) {
					// WooCommerce PayPal gateway uses 'failed' order status for an 'expired' payment
					// @see http://plugins.trac.wordpress.org/browser/woocommerce/tags/1.5.4/classes/gateways/class-wc-paypal.php#L557
					$order->update_status( Pronamic_WooCommerce_WooCommerce::ORDER_STATUS_FAILED, __( 'iDEAL payment expired.', 'pronamic_ideal' ) );
				}
				
				$url = $data->get_error_url();

				break;
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_FAILURE:
				if ( $should_update ) {
					$order->update_status( Pronamic_WooCommerce_WooCommerce::ORDER_STATUS_FAILED, __( 'iDEAL payment failed.', 'pronamic_ideal' ) );
				}
				
				$url = $data->get_error_url();

				break;
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_SUCCESS:
				if ( $should_update ) {
	            	// Payment completed
	                $order->add_order_note( __( 'iDEAL payment completed.', 'pronamic_ideal' ) );
	                
	                // Mark order complete
    	            $order->payment_complete();
				}

                $url = $data->get_success_url();

				break;
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_OPEN:
				if ( $should_update ) {
					$order->add_order_note( __( 'iDEAL payment open.', 'pronamic_ideal' ) );
				}

				break;
			default:
				if ( $should_update ) {
					$order->add_order_note( __( 'iDEAL payment unknown.', 'pronamic_ideal' ) );
				}

				break;
		}
		
		if ( $can_redirect ) {
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

		$text .= __( 'WooCommerce', 'pronamic_ideal' ) . '<br />';

		// Check order post meta for order number
		$order_number = '#' . $payment->source_id;

		$value = get_post_meta( $payment->source_id, '_order_number', true );
		
		if ( ! empty( $value ) ) {
			$order_number = $value;
		}

		$text .= sprintf(
			'<a href="%s">%s</a>', 
			get_edit_post_link( $payment->source_id ),
			sprintf( __( 'Order %s', 'pronamic_ideal' ), $order_number )
		);

		return $text;
	}
}
