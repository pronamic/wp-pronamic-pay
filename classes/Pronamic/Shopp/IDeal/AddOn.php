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
		add_action( 'shopp_init', array( __CLASS__, 'shopp_init' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Initialize the Shopp Add-On
	 */
	public static function shopp_init() {
		self::add_gateway();

		add_action( 'pronamic_payment_status_update_' . self::SLUG, array( __CLASS__, 'status_update' ), 10, 2 );
		add_filter( 'pronamic_payment_source_text_' . self::SLUG,   array( __CLASS__, 'source_text' ), 10, 2 );
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

		if ( Pronamic_Shopp_Shopp::version_compare( '1.3', '<' ) ) {
			// Shop 1.2.9 (or lower)
			// @see https://github.com/ingenesis/shopp/blob/1.2.9/core/model/Modules.php#L123
			$path = dirname( __FILE__ );
			$file = '/GatewayModule.php';

			$module = new ModuleFile( $path, $file );
			if ( $module->addon ) {
				$Shopp->Gateways->modules[ $module->subpackage ] = $module;
			} else {
				$Shopp->Gateways->legacy[] = md5_file( $path . $file );
			}

			if ( isset( $Shopp->Settings ) ) {
				$activeGateways = $Shopp->Settings->get( 'active_gateways' );

				if ( strpos( $activeGateways, 'Pronamic_Shopp_IDeal_GatewayModule' ) !== false ) {
					$Shopp->Gateways->activated[] = 'Pronamic_Shopp_IDeal_GatewayModule';
				}
			}
		} else {
			// Shop 1.3 (or higer)
			// @see https://github.com/ingenesis/shopp/blob/1.3/core/library/Modules.php#L262
			$class = new ReflectionClass( 'GatewayModules' );

			$property = $class->getProperty( 'paths' );
			$property->setAccessible( true );

			$paths = $property->getValue( $Shopp->Gateways );
			// @see https://github.com/ingenesis/shopp/blob/1.3/Shopp.php#L193
			$paths[] = Pronamic_WP_Pay_Plugin::$dirname . '/classes/Pronamic/Shopp/Gateways';

			$property->setValue( $Shopp->Gateways, $paths );
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Update lead status of the specified advanced payment
	 *
	 * @param Pronamic_Pay_Payment $payment
	 */
	public static function status_update( Pronamic_Pay_Payment $payment, $can_redirect = false ) {
		if ( $payment->get_source() == self::SLUG && self::is_shopp_supported() ) {
			global $Shopp;

			$id = $payment->get_source_id();

			$purchase = new Purchase( $id );
			$gateway  = new Pronamic_Shopp_IDeal_GatewayModule();
			$data     = new Pronamic_Shopp_PaymentData( $purchase, $gateway );

			if ( ! Pronamic_Shopp_Shopp::is_purchase_paid( $purchase ) ) {
				$url = $data->get_normal_return_url();

				switch ( $payment->status ) {
					case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_CANCELLED:
						Pronamic_Shopp_Shopp::update_purchase_status( $purchase, Pronamic_Shopp_Shopp::PAYMENT_STATUS_CANCELLED );

						$url = $data->get_cancel_url();

						break;
					case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_EXPIRED:
						Pronamic_Shopp_Shopp::update_purchase_status( $purchase, Pronamic_Shopp_Shopp::PAYMENT_STATUS_EXPIRED );

						break;
					case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_FAILURE:
						Pronamic_Shopp_Shopp::update_purchase_status( $purchase, Pronamic_Shopp_Shopp::PAYMENT_STATUS_FAILURE );

						$url = $data->get_error_url();

						break;
					case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_SUCCESS:
						Pronamic_Shopp_Shopp::update_purchase_status( $purchase, Pronamic_Shopp_Shopp::PAYMENT_STATUS_CAPTURED );

						$url = $data->get_success_url();

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
	 * Source column
	 */
	public static function source_text( $text, Pronamic_Pay_Payment $payment ) {
		$text  = '';

		$text .= __( 'Shopp', 'pronamic_ideal' ) . '<br />';

		$text .= sprintf(
			'<a href="%s">%s</a>',
			add_query_arg( array( 'page' => 'shopp-orders', 'id' => $payment->get_source_id() ), admin_url( 'admin.php' ) ),
			sprintf( __( 'Order #%s', 'pronamic_ideal' ), $payment->get_source_id() )
		);

		return $text;
	}
}
