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
	 * Holds the Icepay iDeal class
	 * 
	 * @access public
	 * @var Icepay_Paymentmethod_Ideal()
	 */
	public $client;
	
	public function __construct( \Pronamic_WordPress_IDeal_Configuration $configuration ) {
		parent::__construct( $configuration );
		
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
	
	public function get_issuer_field() {
		return array(
			'id'       => 'pronamic_ideal_issuer_id',
			'name'     => 'pronamic_ideal_issuer_id',
			'label'    => __( 'Choose your bank', 'pronamic_ideal' ),
			'required' => true,
			'type'     => 'select',
			'choices'  => $this->get_issuers()
		);
	}
	
	public function start( Pronamic_IDeal_IDealDataProxy $data ) {
		
		
		try {
			
			$payment = new Icepay_PaymentObject();
			$payment
					->setPaymentMethod( $this->client->getCode() )
					->setAmount( Pronamic_WordPress_Util::amount_to_cents( $data->getAmount() ) )
					->setCountry( "NL" )
					->setLanguage( "NL" )
					->setReference( site_url( "/" ) )
					->setDescription( $data->getDescription() )
					->setCurrency( $data->getCurrencyAlphabeticCode() )
					->setIssuer( $data->get_issuer_id() )
					->setOrderID( $data->getOrderId() );
		
			$basicmode = Icepay_Basicmode::getInstance();
			$basicmode
					->setMerchantID( $this->configuration->icepayMerchantId )
					->setSecretCode( $this->configuration->icepaySecretCode )
					->setProtocol( 'http' )
					->validatePayment( $payment );
			
			
			$this->set_action_url( $basicmode->getURL() );
			
		} catch ( Exception $e ) {
			$this->error = new WP_Error( $e->getMessage() );
		}
		
	}
	
	public function update_status( Pronamic_WordPress_IDeal_Payment $payment ) {
		
		// Get the Icepay Result and set the required fields
		$result = new Icepay_Result();
		$result
			->setMerchantID( $this->configuration->icepayMerchantId )
			->setSecretCode( $this->configuration->icepaySecretCode );
		
		try {
			
			// Determine if the result can be validated
			if ( $result->validate() ) {
				
				// What was the status response
				switch ( $result->getStatus() ) {
					
					case Icepay_StatusCode::SUCCESS:
						$payment->status = 'Success';
						break;
					
					case Icepay_StatusCode::OPEN:
						$payment->status = 'Open';
						break;
					
					case Icepay_StatusCode::ERROR:
						$payment->status = 'Error';
						break;
				}
			} else {
				throw new Exception( "Didn't validate" );
			}

		} catch ( Exception $exc ) {
			$this->error = new WP_Error( $exc->getMessage() );
		}
				
	}
}