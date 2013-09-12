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
	 * Get configurations select options
	 *
	 * @return array
	 */
	public static function get_configurations_select_options() {
		$gateways = get_posts( array(
			'post_type' => 'pronamic_gateway',
			'nopaging'  => true
		) );

		$options = array( '' => __( '&mdash; Select configuration &mdash;', 'pronamic_ideal' ) );

		foreach ( $gateways as $gateway ) {
			$options[$gateway->ID] = get_the_title( $gateway->ID );
		}

		return $options;
	}

	/**
	 * Get the configuration option name
	 *
	 * @param Pronamic_WordPress_IDeal_Configuration $configuration
	 */
	public static function get_configuration_option_name( Pronamic_WordPress_IDeal_Configuration $configuration ) {
		return sprintf(
			'%s. %s (%s)',
			$configuration->getId(),
			$configuration->getName(),
			$configuration->mode
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

	public static function get_gateway( $configuration_id ) {
		$id = get_post_meta( $configuration_id, '_pronamic_gateway_id', true );
		
		global $pronamic_pay_gateways;
		
		if ( isset( $pronamic_pay_gateways[$id] ) ) {
			$gateway      = $pronamic_pay_gateways[$id];
			$gateway_slug = $gateway['gateway'];
			
			switch ( $gateway_slug ) {
				case Pronamic_IDeal_IDeal::METHOD_EASY:
					return new Pronamic_Gateways_IDealEasy_Gateway( $configuration_id );
				case Pronamic_IDeal_IDeal::METHOD_BASIC:
					return new Pronamic_Gateways_IDealBasic_Gateway( $configuration_id );
				case Pronamic_IDeal_IDeal::METHOD_INTERNETKASSA:
					return new Pronamic_Gateways_IDealInternetKassa_Gateway( $configuration_id );
				case Pronamic_IDeal_IDeal::METHOD_OMNIKASSA:
					return new Pronamic_Gateways_OmniKassa_Gateway( $configuration_id );
				case 'advanced':
					return new Pronamic_Gateways_IDealAdvanced_Gateway( $configuration_id );
				case 'advanced_v3':
					return new Pronamic_Gateways_IDealAdvancedV3_Gateway( $configuration_id );
				case 'mollie':
					return new Pronamic_Gateways_Mollie_Gateway( $configuration_id );
				case 'buckaroo':
					return new Pronamic_Gateways_Buckaroo_Gateway( $configuration_id );
				case 'targetpay':
					return new Pronamic_Gateways_TargetPay_Gateway( $configuration_id );
				case 'icepay':
					return new Pronamic_Gateways_Icepay_Gateway( $configuration_id );
				case 'sisow':
					return new Pronamic_Gateways_Sisow_Gateway( $configuration_id );
				case 'qantani':
					return new Pronamic_Gateways_Qantani_Gateway( $configuration_id );
				case 'ogone_directlink':
					return new Pronamic_Pay_Gateways_Ogone_DirectLink_Gateway( $configuration_id );
			}
		}
	}

	public static function start( $configuration_id, Pronamic_Gateways_Gateway $gateway, Pronamic_Pay_PaymentDataInterface $data ) {
		$result = self::create_payment( $configuration_id, $gateway, $data );

		if ( is_wp_error( $result ) ) {
			
		} else {
			$payment_id = $result;

			$gateway->start( $data, $payment_id );
			$gateway->payment( $payment_id );
		}
	}

	public static function create_payment( $configuration_id, $gateway, $data ) {
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
				$prefix . 'configuration_id'        => $configuration_id,
				$prefix . 'transaction_id'          => $gateway->get_transaction_id(),
				$prefix . 'purchase_id'             => $data->getOrderId(),
				$prefix . 'currency'                => $data->getCurrencyAlphabeticCode(),
				$prefix . 'amount'                  => $data->getAmount(),
				$prefix . 'expiration_period'       => null,
				$prefix . 'language'                => $data->getLanguageIso639Code(),
				$prefix . 'entrance_code'           => $data->get_entrance_code(),
				$prefix . 'description'             => $data->getDescription(),
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
		}

		return $result;
	}
}
