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

		add_filter( 'shopp_checkout_form',                array( __CLASS__, 'show_message' ) );
		add_filter( 'shopp_cart_template',                array( __CLASS__, 'show_message' ) );
		add_filter( 'shopp_catalog',                      array( __CLASS__, 'show_message' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Checks if Shopp is supported
	 * 
	 * @return true if Shopp is supported, false otherwise
	 */
	public static function isShoppSupported() {
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
		if ( $payment->getSource() == self::SLUG && self::isShoppSupported() ) {
			global $Shopp;

			$id = $payment->getSourceId();
			
			$purchase = new Purchase( $id );
			$gateway = new Pronamic_Shopp_IDeal_GatewayModule();
			$data = new Pronamic_Shopp_IDeal_IDealDataProxy( $purchase, $gateway );
			
			if ( ! Pronamic_Shopp_Shopp::is_purchase_paid( $purchase ) ) {
				$url = $data->getNormalReturnUrl();

				$status = $transaction->getStatus();

				switch ( $payment->status ) {
					case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_CANCELLED:
						Pronamic_Shopp_Shopp::update_purchase_status( $purchase, Pronamic_Shopp_Shopp::PAYMENT_STATUS_CANCELLED );

						$url = $data->getCancelUrl();

						break;
					case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_EXPIRED:
						Pronamic_Shopp_Shopp::update_purchase_status( $purchase, Pronamic_Shopp_Shopp::PAYMENT_STATUS_EXPIRED );

						break;
					case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_FAILURE:
						Pronamic_Shopp_Shopp::update_purchase_status( $purchase, Pronamic_Shopp_Shopp::PAYMENT_STATUS_FAILURE );

						$url = $data->getErrorUrl();

						break;
					case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_SUCCESS:
						Pronamic_Shopp_Shopp::update_purchase_status( $purchase, Pronamic_Shopp_Shopp::PAYMENT_STATUS_CAPTURED );

						$url = $data->getSuccessUrl();

						$Shopp->resession();

						break;
					case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_OPEN:
						Pronamic_Shopp_Shopp::update_purchase_status( $purchase, Pronamic_Shopp_Shopp::PAYMENT_STATUS_OPEN );

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
	 * Gets the $_GET['mesagetype'] from the url and returns
	 * the queued page with the message to Shopp.
	 */
	public static function show_message( $output ) {
		$message = '';

		// Pick something to display
		switch ( strtolower( $_GET['messagetype'] ) ) {
			case 'cancelled':
				$message .= __( 'Payment of the order has been cancelled.', 'pronamic_ideal' );
				break;
			case 'error':
				$message .= __( 'An unexpected error occured during transaction.', 'pronamic_ideal' );
				break;
			case 'expired':
				$message .= __( 'The maximum transaction time expired.', 'pronamic_ideal' );
				break;
			case 'failure':
				$message .= __( 'The transaction failed for an unknown reason.', 'pronamic_ideal' );
				break;
			case 'open':
				$message .= __( 'Transaction was not finished and was left open.', 'pronamic_ideal' );
				break;
		}
		
		// Display an error message when message has a value
		$messageoutput = '';
		$error = new ShoppError( $message );
		if ( ! empty( $message ) ) {
			$messageoutput .= '<div id="errors" class="shopp">
				<h3>' . __( 'Errors', 'pronamic_ideal' ) . '</h3>
				<p>
					' . $error->message() . '
				</p>
			</div>';
		}
		
		return $messageoutput . $output;
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
