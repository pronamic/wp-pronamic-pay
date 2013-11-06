<?php

/**
 * Icepay Gateway Class
 * 
 * @copyright (c) 2013, Pronamic
 * @author Leon Rowland <leon@rowland.nl>
 * @version 1.0
 */
class Pronamic_Gateways_Icepay_Gateway extends Pronamic_Gateways_Gateway {
	/**
	 * Holds the Icepay iDEAL object
	 * 
	 * @access public
	 * @var Icepay_Paymentmethod_Ideal()
	 */
	public $client;

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
			require_once Pronamic_Wordpress_Ideal_Plugin::$dirname . '/includes/icepay/icepay_api_basic.php';
			
			// Get the IDeal Payment Method Class
			require_once Pronamic_WordPress_IDeal_Plugin::$dirname . '/includes/icepay/paymentmethods/ideal.php';
		}

		$this->client = new Icepay_Paymentmethod_Ideal();
	}

	//////////////////////////////////////////////////
	
	/**
	 * Get issuers
	 * 
	 * @see Pronamic_Gateways_Gateway::get_issuers()
	 */
	public function get_issuers() {
		$groups = array();
		
		$results = $this->client->getSupportedIssuers();
		
		$prepped_results = array();
		foreach ( $results as $result ) {
			$prepped_results[$result] = $result;
		}
		
		if ( $prepped_results ) {
			$groups[] = array(
				'options' => $prepped_results
			);
		}
		
		return $groups;
	}

	//////////////////////////////////////////////////
	
	/**
	 * Get issuer field
	 * 
	 * @see Pronamic_Gateways_Gateway::get_issuer_field()
	 */
	public function get_issuer_field() {
		return array(
			'id'       => 'pronamic_ideal_issuer_id',
			'name'     => 'pronamic_ideal_issuer_id',
			'label'    => __( 'Choose your bank', 'pronamic_ideal' ),
			'required' => true,
			'type'     => 'select',
			'choices'  => $this->get_transient_issuers()
		);
	}

	//////////////////////////////////////////////////

	/**
	 * Start an transaction
	 * 
	 * @see Pronamic_Gateways_Gateway::start()
	 */
	public function start( Pronamic_Pay_PaymentDataInterface $data, Pronamic_Pay_Payment $payment ) {
		try {
			$payment_object = new Icepay_PaymentObject();
			$payment_object
				->setPaymentMethod( $this->client->getCode() )
				->setAmount( Pronamic_WP_Util::amount_to_cents( $data->get_amount() ) )
				->setCountry( 'NL' )
				->setLanguage( 'NL' )
				->setReference( $payment->get_id() )
				->setDescription( $data->get_description() )
				->setCurrency( $data->get_currency() )
				->setIssuer( $data->get_issuer_id() )
				->setOrderID( $data->get_order_id() );

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
