<?php

/**
 * Title: iDEAL
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WordPress_IDeal_IDeal {
	/**
	 * Delete the transient for the specified configuration
	 *
	 * @param Configuration $configuration
	 * @return boolean true if successful, false otherwise.
	 */
	public static function deleteConfigurationTransient(Pronamic_WordPress_IDeal_Configuration $configuration) {
		return delete_transient('pronamic_ideal_issuers_' . $configuration->getId());
	}

	//////////////////////////////////////////////////

	/**
	 * Get the translaction of the specified status notifier
	 *
	 * @param string $status
	 * @return string
	 */
	public static function translate_status($status) {
		switch ( $status ) {
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_CANCELLED :
				return __( 'Cancelled', 'pronamic_ideal' );
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_EXPIRED :
				return __( 'Expired', 'pronamic_ideal' );
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_FAILURE :
				return __( 'Failure', 'pronamic_ideal' );
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_OPEN :
				return __( 'Open', 'pronamic_ideal' );
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_SUCCESS :
				return __( 'Success', 'pronamic_ideal' );
			default:
				return __( 'Unknown', 'pronamic_ideal' );
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Get default error message
	 *
	 * @return string
	 */
	public static function get_default_error_message() {
		return __( 'Paying with iDEAL is not possible. Please try again later or pay another way.', 'pronamic_ideal' );
	}

	//////////////////////////////////////////////////

	/**
	 * Get config select options
	 *
	 * @return array
	 */
	public static function get_config_select_options() {
		$gateways = get_posts( array(
			'post_type' => 'pronamic_gateway',
			'nopaging'  => true
		) );

		$options = array( '' => __( '&mdash; Select config &mdash;', 'pronamic_ideal' ) );

		foreach ( $gateways as $gateway ) {
			$options[$gateway->ID] = get_the_title( $gateway->ID );
		}

		return $options;
	}

	/**
	 * Get the config option name
	 *
	 * @param Pronamic_WordPress_IDeal_Configuration $config
	 */
	public static function get_configuration_option_name( Pronamic_WordPress_IDeal_Configuration $config ) {
		return sprintf(
			'%s. %s (%s)',
			$config->getId(),
			$config->getName(),
			$config->mode
		);
	}

	//////////////////////////////////////////////////

	/**
	 * Render errors
	 *
	 * @param array $errors
	 */
	public static function render_errors( $errors = array() ) {
		if ( ! is_array( $errors ) ) {
			$errors = array( $errors );
		}

		foreach ( $errors as $error ) {
			include Pronamic_WordPress_IDeal_Plugin::$dirname . '/views/error.php';
		}
	}

	//////////////////////////////////////////////////

	public static function get_gateway( $config_id ) {
		$config = get_pronamic_pay_gateway_config( $config_id );

		$gateway_id = $config->gateway_id;

		Pronamic_WP_Pay_Gateways_ConfigProvider::register( 'buckaroo', 'Pronamic_WP_Pay_Gateways_Buckaroo_ConfigFactory' );
		Pronamic_WP_Pay_Gateways_ConfigProvider::register( 'icepay', 'Pronamic_WP_Pay_Gateways_Icepay_ConfigFactory' );
		Pronamic_WP_Pay_Gateways_ConfigProvider::register( 'ideal_advanced', 'Pronamic_WP_Pay_Gateways_IDealAdvanced_ConfigFactory' );
		Pronamic_WP_Pay_Gateways_ConfigProvider::register( 'ideal_advanced_v3', 'Pronamic_WP_Pay_Gateways_IDealAdvancedV3_ConfigFactory' );
		Pronamic_WP_Pay_Gateways_ConfigProvider::register( 'ideal_basic', 'Pronamic_WP_Pay_Gateways_IDealBasic_ConfigFactory' );
		Pronamic_WP_Pay_Gateways_ConfigProvider::register( 'mollie', 'Pronamic_WP_Pay_Gateways_Mollie_ConfigFactory' );
		Pronamic_WP_Pay_Gateways_ConfigProvider::register( 'ogone_directlink', 'Pronamic_WP_Pay_Gateways_Ogone_DirectLink_ConfigFactory' );
		Pronamic_WP_Pay_Gateways_ConfigProvider::register( 'ogone_orderstandard', 'Pronamic_WP_Pay_Gateways_Ogone_OrderStandard_ConfigFactory' );
		Pronamic_WP_Pay_Gateways_ConfigProvider::register( 'ogone_orderstandard_easy', 'Pronamic_WP_Pay_Gateways_Ogone_OrderStandardEasy_ConfigFactory' );
		Pronamic_WP_Pay_Gateways_ConfigProvider::register( 'omnikassa', 'Pronamic_WP_Pay_Gateways_OmniKassa_ConfigFactory' );
		Pronamic_WP_Pay_Gateways_ConfigProvider::register( 'paydutch', 'Pronamic_WP_Pay_Gateways_PayDutch_ConfigFactory' );
		Pronamic_WP_Pay_Gateways_ConfigProvider::register( 'qantani', 'Pronamic_WP_Pay_Gateways_Qantani_ConfigFactory' );
		Pronamic_WP_Pay_Gateways_ConfigProvider::register( 'sisow', 'Pronamic_WP_Pay_Gateways_Sisow_ConfigFactory' );
		Pronamic_WP_Pay_Gateways_ConfigProvider::register( 'targetpay', 'Pronamic_WP_Pay_Gateways_TargetPay_ConfigFactory' );

		Pronamic_Pay_GatewayFactory::register( 'Pronamic_Gateways_Buckaroo_Config', 'Pronamic_Gateways_Buckaroo_Gateway' );
		Pronamic_Pay_GatewayFactory::register( 'Pronamic_Gateways_Icepay_Config', 'Pronamic_Gateways_Icepay_Gateway' );
		Pronamic_Pay_GatewayFactory::register( 'Pronamic_Gateways_IDealAdvanced_Config', 'Pronamic_Gateways_IDealAdvanced_Gateway' );
		Pronamic_Pay_GatewayFactory::register( 'Pronamic_Gateways_IDealAdvancedV3_Config', 'Pronamic_Gateways_IDealAdvancedV3_Gateway' );
		Pronamic_Pay_GatewayFactory::register( 'Pronamic_Gateways_IDealBasic_Config', 'Pronamic_Gateways_IDealBasic_Gateway' );
		Pronamic_Pay_GatewayFactory::register( 'Pronamic_Pay_Gateways_Ogone_DirectLink_Config', 'Pronamic_Pay_Gateways_Ogone_DirectLink_Gateway' );
		Pronamic_Pay_GatewayFactory::register( 'Pronamic_Pay_Gateways_Ogone_OrderStandard_Config', 'Pronamic_Pay_Gateways_Ogone_OrderStandard_Gateway' );
		Pronamic_Pay_GatewayFactory::register( 'Pronamic_Pay_Gateways_Ogone_OrderStandardEasy_Config', 'Pronamic_Pay_Gateways_Ogone_OrderStandardEasy_Gateway' );
		Pronamic_Pay_GatewayFactory::register( 'Pronamic_Gateways_Mollie_Config', 'Pronamic_Gateways_Mollie_Gateway' );
		Pronamic_Pay_GatewayFactory::register( 'Pronamic_Gateways_OmniKassa_Config', 'Pronamic_Gateways_OmniKassa_Gateway' );
		Pronamic_Pay_GatewayFactory::register( 'Pronamic_Gateways_PayDutch_Config', 'Pronamic_Gateways_PayDutch_Gateway' );
		Pronamic_Pay_GatewayFactory::register( 'Pronamic_Gateways_Qantani_Config', 'Pronamic_Gateways_Qantani_Gateway' );
		Pronamic_Pay_GatewayFactory::register( 'Pronamic_Gateways_Sisow_Config', 'Pronamic_Gateways_Sisow_Gateway' );
		Pronamic_Pay_GatewayFactory::register( 'Pronamic_Gateways_TargetPay_Config', 'Pronamic_Gateways_TargetPay_Gateway' );

		global $pronamic_pay_gateways;
		
		if ( isset( $pronamic_pay_gateways[$gateway_id] ) ) {
			$gateway      = $pronamic_pay_gateways[$gateway_id];
			$gateway_slug = $gateway['gateway'];

			$config = Pronamic_WP_Pay_Gateways_ConfigProvider::get_config( $gateway_slug, $config_id );

			$gateway = Pronamic_Pay_GatewayFactory::create( $config );

			return $gateway;
		}
	}

	public static function start( $configuration_id, Pronamic_Gateways_Gateway $gateway, Pronamic_Pay_PaymentDataInterface $data ) {
		$payment = self::create_payment( $configuration_id, $gateway, $data );

		if ( $payment ) {
			$gateway->start( $data, $payment );

			$gateway->payment( $payment );
		}
		
		return $payment;
	}

	public static function create_payment( $configuration_id, $gateway, $data ) {
		$payment = null;

		$result = wp_insert_post( array(
			'post_type'   => 'pronamic_payment',
			'post_title'  => sprintf( __( 'Payment for %s', 'pronamic_ideal' ), $data->get_title() ),
			'post_status' => 'publish'
		), true );

		if ( is_wp_error( $result ) ) {
			// @todo what todo?
		} else {
			$post_id = $result;

			// Meta 
			$prefix = '_pronamic_payment_';

			$meta = array(
				$prefix . 'config_id'               => $configuration_id,
				$prefix . 'transaction_id'          => $gateway->get_transaction_id(),
				$prefix . 'purchase_id'             => $data->getOrderId(),
				$prefix . 'currency'                => $data->getCurrencyAlphabeticCode(),
				$prefix . 'amount'                  => $data->getAmount(),
				$prefix . 'expiration_period'       => null,
				$prefix . 'language'                => $data->getLanguageIso639Code(),
				$prefix . 'entrance_code'           => $data->get_entrance_code(),
				$prefix . 'description'             => $data->get_description(),
				$prefix . 'consumer_name'           => null,
				$prefix . 'consumer_account_number' => null,
				$prefix . 'consumer_iban'           => null,
				$prefix . 'consumer_bic'            => null,
				$prefix . 'consumer_city'           => null,
				$prefix . 'status'                  => null,
				$prefix . 'source'                  => $data->getSource(),
				$prefix . 'source_id'               => $data->get_source_id(),
				$prefix . 'email'                   => $data->get_email()
			);

			foreach ( $meta as $key => $value ) {
				if ( ! empty( $value ) ) {
					update_post_meta( $post_id, $key, $value );
				}
			}
			
			$payment = new Pronamic_WP_Pay_Payment( $post_id );
		}

		return $payment;
	}
}
