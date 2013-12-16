<?php

/**
 * Title: Ogone DirectLink gateway
 * Description: 
 * Copyright: Copyright (c) 2005 - 2013
 * Company: Pronamic
 * @author Remco Tolsma
 * @since 1.4.0
 */
class Pronamic_Pay_Gateways_Ogone_DirectLink_Gateway extends Pronamic_Gateways_Gateway {
	/**
	 * Slug of this gateway
	 * 
	 * @var string
	 */
	const SLUG = 'ogone-directlink';	
	
	/////////////////////////////////////////////////

	/**
	 * Constructs and initializes an Ogone DirectLink gateway
	 * 
	 * @param Pronamic_Pay_Gateways_Ogone_DirectLink_Config $config
	 */
	public function __construct( Pronamic_Pay_Gateways_Ogone_DirectLink_Config $config ) {
		parent::__construct( $config );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTTP_REDIRECT );
		$this->set_has_feedback( true );
		$this->set_amount_minimum( 1.20 );
		$this->set_slug( self::SLUG );

		$this->client = new Pronamic_Pay_Gateways_Ogone_DirectLink_Client();
		$this->client->psp_id   = $config->psp_id;
		$this->client->sha_in   = $config->sha_in_pass_phrase;
		$this->client->user_id  = $config->user_id;
		$this->client->password = $config->password;
		$this->client->api_url  = $config->api_url;
	}

	/////////////////////////////////////////////////

	public function start( Pronamic_Pay_PaymentDataInterface $data, Pronamic_Pay_Payment $payment ) {
		$ogone_data = new Pronamic_Pay_Gateways_Ogone_Data();
		
		// Default
		$ogone_data_general = new Pronamic_Pay_Gateways_Ogone_DataGeneralHelper( $ogone_data );

		$ogone_data_general
			->set_psp_id( $this->client->psp_id )
			->set_order_id( $data->get_order_id() )
			->set_order_description( $data->get_description() )
			->set_currency( $data->get_currency() )
			->set_amount( $data->get_amount() )
			->set_customer_name( $data->getCustomerName() )
			->set_email_address( $data->get_email() )
		;

		// DirectLink
		$ogone_data_directlink = new Pronamic_Pay_Gateways_Ogone_DirectLink_DataHelper( $ogone_data );

		$ogone_data_directlink
			->set_user_id( $this->client->user_id )
			->set_password( $this->client->password )
		;

		// Credit card
		$ogone_data_credit_card = new Pronamic_Pay_Gateways_Ogone_DataCreditCardHelper( $ogone_data );
		
		$credit_card = $data->get_credit_card();

		$ogone_data_credit_card
			->set_number( $credit_card->get_number() )
			->set_expiration_date( $credit_card->get_expiration_date() )
			->set_security_code( $credit_card->get_security_code() )
		;
		
		$ogone_data->set_field( 'OPERATION', 'SAL' );
		
		// 3-D Secure
		if ( $this->config->enabled_3d_secure ) {
			$secure_data_helper = new Pronamic_Pay_Gateways_Ogone_3DSecure_DataHelper( $ogone_data );
	
			$secure_data_helper
				->set_3d_secure_flag( true )
				->set_http_accept( filter_input( INPUT_SERVER, 'HTTP_ACCEPT' ) )
				->set_http_user_agent( filter_input( INPUT_SERVER, 'HTTP_USER_AGENT' ) )
				->set_window( 'MAINW' )
			;

			$ogone_data->set_field( 'ACCEPTURL', home_url( '/' ) );
			$ogone_data->set_field( 'DECLINEURL', home_url( '/' ) );
			$ogone_data->set_field( 'EXCEPTIONURL', home_url( '/' ) );
			$ogone_data->set_field( 'PARAMPLUS', '' );
			$ogone_data->set_field( 'COMPLUS', '' );
			$ogone_data->set_field( 'LANGUAGE', 'en_US' );
		}

		// Signature
		$calculation_fields = Pronamic_Gateways_Ogone_Security::get_calculations_parameters_in();
		
		$fields = Pronamic_Pay_Gateways_Ogone_Security::get_calculation_fields( $calculation_fields, $ogone_data->get_fields() );

		$signature = Pronamic_Pay_Gateways_Ogone_Security::get_signature( $fields, $this->config->sha_in_pass_phrase, $this->config->hash_algorithm );
		
		$ogone_data->set_field( 'SHASIGN', $signature );
		
		// Order
		$result = $this->client->order_direct( $ogone_data->get_fields() );

		$error = $this->client->get_error();

		if ( is_wp_error( $error ) ) {
			$this->error = $error;
		} else {
			$payment->set_transaction_id( $result->pay_id );
			$payment->set_action_url( add_query_arg( 'payment', $payment->get_id(), home_url( '/' ) ) );
			$payment->set_status( Pronamic_Pay_Gateways_Ogone_Statuses::transform( $result->status ) );
			
			if ( ! empty( $result->html_answer ) ) {
				$payment->set_meta( 'ogone_directlink_html_answer', $result->html_answer );
				$payment->set_action_url( add_query_arg( 'payment_redirect', $payment->get_id(), home_url( '/' ) ) );
			}
		}
	}

	/////////////////////////////////////////////////

	/**
	 * Update status of the specified payment
	 *
	 * @param Pronamic_Pay_Payment $payment
	 */
	public function update_status( Pronamic_Pay_Payment $payment ) {
		$inputs = array(
			INPUT_GET  => $_GET,
			INPUT_POST => $_POST
		);
			
		foreach ( $inputs as $input => $data ) {
			$data = $this->client->verifyRequest( $data );

			if ( $data !== false ) {
				$status = Pronamic_Pay_Gateways_Ogone_Statuses::transform( $data[ Pronamic_Pay_Gateways_Ogone_Parameters::STATUS ] );

				$payment->set_status( $status );
			}
		}
	}
}
