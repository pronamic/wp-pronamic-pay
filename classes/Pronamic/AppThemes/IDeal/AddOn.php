<?php 

/**
 * Title: AppThemes iDEAL Add-On
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_AppThemes_IDeal_AddOn {
	/**
	 * Slug
	 * 
	 * @var string
	 */
	const SLUG = 'appthemes';

	//////////////////////////////////////////////////

	/**
	 * Bootstrap
	 */
	public static function bootstrap() {
		add_action( 'init', array( __CLASS__, 'load_gateway' ), 100 );
		
		$slug = self::SLUG;

		add_action( "pronamic_payment_status_update_$slug", array( __CLASS__, 'status_update' ), 10, 2 );
		add_filter( "pronamic_payment_source_text_$slug",   array( __CLASS__, 'source_text' ), 10, 2 );
	}

	//////////////////////////////////////////////////

	/**
	 * Initialize
	 */
	public static function load_gateway() {
		if ( function_exists( 'appthemes_register_gateway' ) ) {
			appthemes_register_gateway( 'Pronamic_AppThemes_IDeal_IDealGateway' );
		}
	}

	//////////////////////////////////////////////////
	
	/**
	 * Update lead status of the specified payment
	 * 
	 * @param string $payment
	 */
	public static function status_update( Pronamic_Pay_Payment $payment, $can_redirect = false ) {
		if ( $payment->getSource() == self::SLUG ) {
			$id = $payment->getSourceId();

			$order = appthemes_get_order( $id );

			$data = new Pronamic_WP_Pay_AppThemes_PaymentData( $order );

			$url = $data->get_normal_return_url();

			switch ( $payment->status ) {
				case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_CANCELLED:
					$order->failed();

					$url = $data->get_cancel_url();

					break;
				case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_EXPIRED:
					$order->failed();
					
					$url = $data->get_error_url();

					break;
				case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_FAILURE:
					$order->failed();
					
					$url = $data->get_error_url();

					break;
				case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_SUCCESS:
					$order->complete();

	                $url = $data->get_success_url();

					break;
				case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_OPEN:
					$order->pending();

					break;
				default:
					$order->pending();

					break;
			}
			
			if ( $can_redirect ) {
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

		$text .= __( 'AppThemes', 'pronamic_ideal' ) . '<br />';

		$text .= sprintf( '<a href="%s">%s</a>', 
			get_edit_post_link( $payment->get_source_id() ),
			sprintf( __( 'Order #%s', 'pronamic_ideal' ), $payment->get_source_id() )
		);

		return $text;
	}
}
