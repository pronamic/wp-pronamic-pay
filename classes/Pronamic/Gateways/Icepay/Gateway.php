<?php

/**
 * Icepay Gateway Class
 *
 * @copyright (c) 2013, Pronamic
 * @author Leon Rowland <leon@rowland.nl>
 * @version 1.0
 */
class Pronamic_Gateways_Icepay_Gateway extends Pronamic_Gateways_Gateway {


	//////////////////////////////////////////////////

	/**
	 * Constructs and intializes an Icepay gateway
	 *
	 * @param Pronamic_Gateways_Icepay_Config $config
	 */
	public function __construct( Pronamic_Gateways_Icepay_Config $config ) {
		parent::__construct( $config );

		// Default properties for this gateway
		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTTP_REDIRECT );
		$this->set_has_feedback( true );
		$this->set_amount_minimum( 1.20 );
		$this->set_slug( 'icepay' );

		if ( ! class_exists( 'Icepay_Paymentmethod_Ideal' ) ) {
			require_once Pronamic_WP_Pay_Plugin::$dirname . '/includes/icepay/api/icepay_api_basic.php';
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Start an transaction
	 *
	 * @see Pronamic_Gateways_Gateway::start()
	 */
	public function start( Pronamic_Pay_PaymentDataInterface $data, Pronamic_Pay_Payment $payment ) {
		try {
			/*
			 * Order ID
			 * Your unique order number.
			 * This can be auto incremental number from your payments table
			 *
			 * Data type  = String
			 * Max length = 10
			 * Required   = Yes
			 */

			$payment_object = new Icepay_PaymentObject();
			$payment_object
				->setAmount( Pronamic_WP_Util::amount_to_cents( $data->get_amount() ) )
				->setCountry( 'NL' )
				->setLanguage( 'NL' )
				->setReference( $data->get_order_id() )
				->setDescription( $data->get_description() )
				->setCurrency( $data->get_currency() )
				->setIssuer( $data->get_issuer_id() )
				->setOrderID( $payment->get_id() );

			$basicmode = Icepay_Basicmode::getInstance();
			$basicmode
				->setMerchantID( $this->config->merchant_id )
				->setSecretCode( $this->config->secret_code )
				->setProtocol( 'http' )
				->validatePayment( $payment_object );

			$payment->set_action_url( $basicmode->getURL() );
		} catch ( Exception $exception ) {
			$this->error = new WP_Error( 'icepay_error', $exception->getMessage(), $exception );
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Update the status of the specified payment
	 *
	 * @param Pronamic_Pay_Payment $payment
	 * @throws Exception
	 */
	public function update_status( Pronamic_Pay_Payment $payment ) {
		// Get the Icepay Result and set the required fields
		$result = new Icepay_Result();
		$result
			->setMerchantID( $this->config->merchant_id )
			->setSecretCode( $this->config->secret_code );

		try {
			// Determine if the result can be validated
			if ( $result->validate() ) {
				// What was the status response
				switch ( $result->getStatus() ) {
					case Icepay_StatusCode::SUCCESS:
						$payment->set_status( Pronamic_Pay_Gateways_IDeal_Statuses::SUCCESS );

						break;
					case Icepay_StatusCode::OPEN:
						$payment->set_status( Pronamic_Pay_Gateways_IDeal_Statuses::OPEN );

						break;
					case Icepay_StatusCode::ERROR:
						$payment->set_status( Pronamic_Pay_Gateways_IDeal_Statuses::FAILURE );

						break;
				}
			}
		} catch ( Exception $exception ) {
			$this->error = new WP_Error( 'icepay_error', $exception->getMessage(), $exception );
		}
	}
}
