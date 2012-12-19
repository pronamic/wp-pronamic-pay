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
	 */
	public static function deleteConfigurationTransient(Pronamic_WordPress_IDeal_Configuration $configuration) {
		delete_transient('pronamic_ideal_issuers_' . $configuration->getId());
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
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_CANCELLED:
				return __( 'Cancelled', 'pronamic_ideal' );
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_EXPIRED:
				return __( 'Expired', 'pronamic_ideal' );
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_FAILURE:
				return __( 'Failure', 'pronamic_ideal' );
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_OPEN:
				return __( 'Open', 'pronamic_ideal' );
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_SUCCESS:
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
		$configurations = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurations();

		$options = array( '' => __( '&mdash; Select configuration &mdash;', 'pronamic_ideal' ) );
		
		foreach ( $configurations as $configuration ) {
			$options[$configuration->getId()] = self::get_configuration_option_name( $configuration );
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
	
	public static function get_gateway( Pronamic_WordPress_IDeal_Configuration $configuration = null ) {
		if ( $configuration !== null ) {
			$variant = $configuration->getVariant();

			if ( $variant !== null ) {
				switch ( $variant->getMethod() ) {
					case Pronamic_IDeal_IDeal::METHOD_EASY:
						return new Pronamic_Gateways_IDealEasy_Gateway( $configuration );
					case Pronamic_IDeal_IDeal::METHOD_BASIC:
						return new Pronamic_Gateways_IDealBasic_Gateway( $configuration );
					case Pronamic_IDeal_IDeal::METHOD_INTERNETKASSA:
						return new Pronamic_Gateways_IDealInternetKassa_Gateway( $configuration );
					case Pronamic_IDeal_IDeal::METHOD_OMNIKASSA:
						return new Pronamic_Gateways_OmniKassa_Gateway( $configuration );
					case 'advanced':
						return new Pronamic_Gateways_IDealAdvanced_Gateway( $configuration );
					case 'advanced_v3':
						return new Pronamic_Gateways_IDealAdvancedV3_Gateway( $configuration );
					case 'mollie':
						return new Pronamic_Gateways_Mollie_Gateway( $configuration );
					case 'targetpay':
						return new Pronamic_Gateways_TargetPay_Gateway( $configuration );
				}
			}
		}				
	}

	public static function start( $configuration, $gateway, $data ) {
		$gateway->start( $data );

		$payment = self::create_payment( $configuration, $gateway, $data );
		
		$gateway->payment( $payment );
	}
	
	public static function create_payment( $configuration, $gateway, $data ) {
		$payment = new Pronamic_WordPress_IDeal_Payment();
		$payment->configuration           = $configuration;
		$payment->transaction_id          = $gateway->get_transaction_id();
		$payment->purchase_id             = $data->getOrderId();
		$payment->description             = $data->getDescription();
		$payment->amount                  = $data->getAmount();
		$payment->currency                = $data->getCurrencyAlphabeticCode();
		$payment->language                = $data->getLanguageIso639Code();
		$payment->entrance_code           = $data->get_entrance_code();
		$payment->source                  = $data->getSource();
		$payment->source_id               = $data->get_source_id();
		$payment->expiration_period       = null;
		$payment->status                  = null;
		$payment->consumer_name           = null;
		$payment->consumer_account_number = null;
		$payment->consumer_city           = null;

		$updated = Pronamic_WordPress_IDeal_PaymentsRepository::updatePayment( $payment );

		return $payment;
	}
}
