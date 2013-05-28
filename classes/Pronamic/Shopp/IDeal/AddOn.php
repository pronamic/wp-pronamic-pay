<?php 
/**
 * Title: Shopp iDEAL Add-On
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Shopp_IDeal_AddOn {
	/**
	 * Slug
	 */
	const SLUG = 'shopp';

	//////////////////////////////////////////////////
	
	/**
	 * Bootstrap
	 */
	public static function bootstrap() {
		// Actions
		add_action( 'shopp_init', array( __CLASS__, 'intialize' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Initialize the Shopp Add-On
	 */
	public static function intialize() {
		self::add_gateway();

		// Actions
		add_action( 'pronamic_ideal_status_update',       array( __CLASS__, 'update_status' ), 10, 2 );
		
		// Filters
		add_filter( 'pronamic_ideal_source_column_shopp', array( __CLASS__, 'source_column' ), 10, 2 );
	}

	//////////////////////////////////////////////////

	/**
	 * Checks if Shopp is supported
	 * 
	 * @return true if Shopp is supported, false otherwise
	 */
	public static function is_shopp_supported() {
		return defined( 'SHOPP_VERSION' );
	}

	//////////////////////////////////////////////////

	/**
	 * Add the Shopp gateway
	 */
	public static function add_gateway() {
		global $Shopp;
		
		$path = dirname( __FILE__ );
		$file = '/GatewayModule.php';
		
		$module = new ModuleFile( $path, $file );
		if ( $module->addon ) {
			$Shopp->Gateways->modules[$module->subpackage] = $module;
		} else {
			$Shopp->Gateways->legacy[] = md5_file( $path . $file );
		}

		if ( isset( $Shopp->Settings ) ) {
			$activeGateways = $Shopp->Settings->get( 'active_gateways' );

			if ( strpos( $activeGateways, 'Pronamic_Shopp_IDeal_GatewayModule' ) !== false ) {
				$Shopp->Gateways->activated[] = 'Pronamic_Shopp_IDeal_GatewayModule';
			}
		}
	}
	
	//////////////////////////////////////////////////
	
	/**
	 * Update lead status of the specified advanced payment
	 * 
	 * @param Pronamic_WordPress_IDeal_Payment $payment
	 */
	public static function update_status( Pronamic_WordPress_IDeal_Payment $payment, $can_redirect = false ) {
		if ( $payment->getSource() == self::SLUG && self::is_shopp_supported() ) {
			global $Shopp;

			$id = $payment->getSourceId();
			
			$order = $Shopp->Order;

			$url = shoppurl(array('rmtpay'=>'process'),'confirm');

			if ( $url && $can_redirect ) {
				wp_redirect( $url, 303 );
			
				exit;
			}

			/*
var_dump($payment);
exit;
			$gateway = new Pronamic_Shopp_IDeal_GatewayModule();
			$data = new Pronamic_Shopp_IDeal_IDealDataProxy( $order, $gateway );
			
			$url = $data->getNormalReturnUrl();

			switch ( $payment->status ) {
				case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_CANCELLED:
					$order->transaction( $payment->transaction_id, Pronamic_Shopp_Shopp::PAYMENT_STATUS_VOID );

					$url = $data->getCancelUrl();

					break;
				case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_EXPIRED:
					$order->transaction( $payment->transaction_id, Pronamic_Shopp_Shopp::PAYMENT_STATUS_VOID );

					break;
				case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_FAILURE:
					$order->transaction( $payment->transaction_id, Pronamic_Shopp_Shopp::PAYMENT_STATUS_VOID );

					$url = $data->getErrorUrl();

					break;
				case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_SUCCESS:
					$order->transaction( $payment->transaction_id, Pronamic_Shopp_Shopp::PAYMENT_STATUS_CHARGED );

					$url = $data->getSuccessUrl();

					$Shopp->resession();

					break;
				case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_OPEN:
					$order->transaction( $payment->transaction_id, Pronamic_Shopp_Shopp::PAYMENT_STATUS_PE );

					break;
			}

			if ( $url && $can_redirect ) {
				wp_redirect( $url, 303 );

				exit;
			}
			*/
		}
	}
	
	//////////////////////////////////////////////////
	
	/**
	 * Source column
	 */
	public static function source_column( $text, $payment ) {
		$text  = '';

		$text .= __( 'Shopp', 'pronamic_ideal' ) . '<br />';

		$text .= sprintf(
			'<a href="%s">%s</a>', 
			add_query_arg( array( 'page' => 'shopp-orders', 'id' => $payment->getSourceId() ), admin_url( 'admin.php' ) ),
			sprintf( __( 'Order #%s', 'pronamic_ideal' ), $payment->getSourceId() )
		);

		return $text;
	}
}
