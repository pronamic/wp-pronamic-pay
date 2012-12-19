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
		add_filter( 'jigoshop_payment_gateways',             array( __CLASS__, 'payment_gateways' ) );

		add_action( 'pronamic_ideal_status_update',          array( __CLASS__, 'update_status' ), 10, 2 );

		add_filter( 'pronamic_ideal_source_column_jigoshop', array( __CLASS__, 'source_column' ), 10, 2 );
	}

	//////////////////////////////////////////////////

	/**
	 * Checks if Shopp is supported
	 * 
	 * @return true if Shopp is supported, false otherwise
	 */
	public static function isJigoshopSupported() {
		return defined( 'JIGOSHOP_VERSION' );
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
	public static function update_status( Pronamic_WordPress_IDeal_Payment $payment, $can_redirect = false ) {
		if ( $payment->getSource() == self::SLUG && self::isJigoshopSupported() ) {
			$id = $payment->getSourceId();

			$order = &new jigoshop_order( (int) $id );
			$data_proxy = new Pronamic_Jigoshop_IDeal_IDealDataProxy( $order );

			if ( $order->status !== Pronamic_Jigoshop_Jigoshop::ORDER_STATUS_COMPLETED ) {				
				$url = $data_proxy->getNormalReturnUrl();

				switch ( $payment->status ) {
					case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_CANCELLED:
						$order->update_status( Pronamic_Jigoshop_Jigoshop::ORDER_STATUS_CANCELLED, __( 'iDEAL payment cancelled.', 'pronamic_ideal' ) );

						$url = $data_proxy->getCancelUrl();

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
		                
		                $url = $data_proxy->getSuccessUrl();

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
	}

	//////////////////////////////////////////////////
	
	/**
	 * Source column
	 */
	public static function source_column( $text, $payment ) {
		$text  = '';

		$text .= __( 'Jigoshop', 'pronamic_ideal' ) . '<br />';

		$text .= sprintf( 
			'<a href="%s">%s</a>', 
			get_edit_post_link( $payment->getSourceId() ),
			sprintf( __( 'Order #%s', 'pronamic_ideal' ), $payment->getSourceId() )
		);

		return $text;
	}
}
