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
		add_filter( 'woocommerce_payment_gateways',             array( __CLASS__, 'payment_gateways' ) );
		
		add_action( 'pronamic_ideal_status_update',             array( __CLASS__, 'status_update' ), 10, 2 );
		
		add_filter( 'pronamic_ideal_source_column_woocommerce', array( __CLASS__, 'source_column' ), 10, 2 );
	}

	//////////////////////////////////////////////////

	/**
	 * Checks if Shopp is supported
	 * 
	 * @return true if Shopp is supported, false otherwise
	 */
	public static function isWooCommerceSupported() {
		return defined( 'WOOCOMMERCE_VERSION' );
	}

	//////////////////////////////////////////////////

	/**
	 * Add the gateway to WooCommerce
	 */
	function payment_gateways( $gateways ) {
		$gateways[] = 'Pronamic_WooCommerce_IDeal_IDealGateway';

		return $gateways;
	}

	//////////////////////////////////////////////////
	
	/**
	 * Update lead status of the specified payment
	 * 
	 * @param string $payment
	 */
	public static function status_update( Pronamic_WordPress_IDeal_Payment $payment, $can_redirect = false ) {
		if ( $payment->getSource() == self::SLUG && self::isWooCommerceSupported() ) {
			$id = $payment->getSourceId();

			$order = new WC_Order( (int) $id );

			$should_update = ! in_array(
				$order->status,
				array(
					Pronamic_WooCommerce_WooCommerce::ORDER_STATUS_PENDING,
					Pronamic_WooCommerce_WooCommerce::ORDER_STATUS_COMPLETED
				)
			);

			if ( $should_update ) {	
				$data = new Pronamic_WooCommerce_IDeal_IDealDataProxy( $order );

				$url = $data->getNormalReturnUrl();

				switch ( $payment->status ) {
					case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_CANCELLED:
						$order->update_status( Pronamic_WooCommerce_WooCommerce::ORDER_STATUS_CANCELLED, __( 'iDEAL payment cancelled.', 'pronamic_ideal' ) );

						$url = $data->getCancelUrl();

						break;
					case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_EXPIRED:
						// WooCommerce PayPal gateway uses 'failed' order status for an 'expired' payment
						// @see http://plugins.trac.wordpress.org/browser/woocommerce/tags/1.5.4/classes/gateways/class-wc-paypal.php#L557
						$order->update_status( Pronamic_WooCommerce_WooCommerce::ORDER_STATUS_FAILED, __( 'iDEAL payment expired.', 'pronamic_ideal' ) );

						break;
					case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_FAILURE:
						$order->update_status( Pronamic_WooCommerce_WooCommerce::ORDER_STATUS_FAILED, __( 'iDEAL payment failed.', 'pronamic_ideal' ) );

						break;
					case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_SUCCESS:
		            	// Payment completed
		                $order->add_order_note( __( 'iDEAL payment completed.', 'pronamic_ideal' ) );
		                $order->payment_complete();

		                $url = $data->getSuccessUrl();

						break;
					case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_OPEN:
						$order->add_order_note( __( 'iDEAL payment open.', 'pronamic_ideal' ) );

						break;
					default:
						$order->add_order_note( __( 'iDEAL payment unknown.', 'pronamic_ideal' ) );

						break;
				}
				
				if ( $can_redirect ) {
					wp_redirect( $url, 303 );

					exit;
				}
			}
		}
	}

	//////////////////////////////////////////////////
	
	/**
	 * Source column
	 */
	public static function source_column( $text, $payment ) {
		$text  = '';

		$text .= __( 'WooCommerce', 'pronamic_ideal' ) . '<br />';

		$text .= sprintf(
			'<a href="%s">%s</a>', 
			get_edit_post_link( $payment->getSourceId() ),
			sprintf( __( 'Order #%s', 'pronamic_ideal' ), $payment->getSourceId() )
		);

		return $text;
	}
}
