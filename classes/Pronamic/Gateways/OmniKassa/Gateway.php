<?php

/**
 * Title: OmniKassa
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_OmniKassa_Gateway extends Pronamic_Gateways_Gateway {
	/**
	 * The OmniKassa client object
	 * 
	 * @var Pronamic_Gateways_OmniKassa_OmniKassa
	 */
	private $client;
	
	/////////////////////////////////////////////////

	/**
	 * Constructs and initializes an OmniKassa gateway
	 * 
	 * @param Pronamic_Gateways_OmniKassa_Config $config
	 */
	public function __construct( Pronamic_Gateways_OmniKassa_Config $config ) {
		parent::__construct( $config );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTML_FORM );
		$this->set_has_feedback( true );
		$this->set_amount_minimum( 0.01 );

		// Client
		$this->client = new Pronamic_Gateways_OmniKassa_OmniKassa();
		
		$action_url = Pronamic_Gateways_OmniKassa_OmniKassa::ACTION_URL_PRUDCTION;
		if ( $config->mode == Pronamic_IDeal_IDeal::MODE_TEST ) {
			$action_url = Pronamic_Gateways_OmniKassa_OmniKassa::ACTION_URL_TEST;
		}

		$this->client->set_action_url( $action_url );
		$this->client->set_merchant_id( $config->merchant_id );
		$this->client->set_key_version( $config->key_version );
		$this->client->set_secret_key( $config->secret_key );
	}
	
	/////////////////////////////////////////////////

	/**
	 * Start
	 * 
	 * @see Pronamic_Gateways_Gateway::start()
	 * @param Pronamic_Pay_PaymentDataInterface $data
	 */
	public function start( Pronamic_Pay_PaymentDataInterface $data, Pronamic_Pay_Payment $payment ) {
		$this->set_transaction_id( md5( time() . $data->get_order_id() ) );
		$this->set_action_url( $this->client->get_action_url() );

		$this->client->setCustomerLanguage( $data->getLanguageIso639Code() );
		$this->client->setCurrencyNumericCode( $data->getCurrencyNumericCode() );
		$this->client->set_order_id( $data->get_order_id() );
		$this->client->set_normal_return_url( home_url( '/' ) );
		$this->client->set_automatic_response_url( home_url( '/' ) );
		$this->client->set_amount( $data->getAmount() );
		$this->client->set_transaction_reference( $this->get_transaction_id() );

		update_post_meta( $payment->id, '_pronamic_payment_transaction_id', $this->get_transaction_id() );
	}
	
	/////////////////////////////////////////////////

	/**
	 * Get the output HTML
	 * 
	 * @see Pronamic_Gateways_Gateway::get_output_html()
	 */
	public function get_output_html() {
		return $this->client->getHtmlFields();
	}
	
	/////////////////////////////////////////////////

	/**
	 * Update status of the specified payment
	 * 
	 * @param Pronamic_Pay_Payment $payment
	 */
	public function update_status( Pronamic_Pay_Payment $payment ) {
		$input_data = filter_input( INPUT_POST, 'Data', FILTER_SANITIZE_STRING );
		$input_seal = filter_input( INPUT_POST, 'Seal', FILTER_SANITIZE_STRING );
		
		$data = Pronamic_Gateways_OmniKassa_OmniKassa::parse_piped_string( $input_data );

		$transaction_reference = $data['transactionReference'];

		$seal = Pronamic_Gateways_OmniKassa_OmniKassa::compute_seal( $input_data, $this->config->secret_key );
		
		// Check if the posted seal is equal to our seal
		if ( strcasecmp( $input_seal, $seal ) === 0 ) {
			$response_code = $data['responseCode'];

			$status = Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_OPEN;

			switch ( $response_code ) {
				case Pronamic_Gateways_OmniKassa_OmniKassa::RESPONSE_CODE_TRANSACTION_SUCCES:
					$status = Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_SUCCESS;

					update_post_meta( $payment->id, '_pronamic_payment_status', $status );

					break;
				case Pronamic_Gateways_OmniKassa_OmniKassa::RESPONSE_CODE_CANCELLATION_OF_PAYMENT:
					$status = Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_CANCELLED;
					
					update_post_meta( $payment->id, '_pronamic_payment_status', $status );

					break;
			}
			
			$labels = array(
				'amount' 	           => __( 'Amount', 'pronamic_ideal' ),
				'captureDay'           => __( 'Capture Day', 'pronamic_ideal' ),
				'captureMode'          => __( 'Capture Mode', 'pronamic_ideal' ),
				'currencyCode'         => __( 'Currency Code', 'pronamic_ideal' ),
				'merchantId'           => __( 'Merchant ID', 'pronamic_ideal' ),
				'orderId'              => __( 'Order ID', 'pronamic_ideal' ),
				'transactionDateTime'  => __( 'Transaction Date Time', 'pronamic_ideal' ),
				'transactionReference' => __( 'Transaction Reference', 'pronamic_ideal' ),
				'keyVersion'           => __( 'Key Version', 'pronamic_ideal' ),
				'authorisationId'      => __( 'Authorisation ID', 'pronamic_ideal' ),
				'paymentMeanBrand'     => __( 'Payment Mean Brand', 'pronamic_ideal' ),
				'paymentMeanType'      => __( 'Payment Mean Type', 'pronamic_ideal' ),
				'responseCode'         => __( 'Response Code', 'pronamic_ideal' )
			);
			
			$note = '';
			
			
			$note .= '<p>';
			$note .= __( 'OmniKassa return data:', 'pronamic_ideal' );
			$note .= '</p>';
			
			$note .= '<dl>';
			
			foreach ( $labels as $key => $label ) {
				if ( isset( $data[$key] ) ) {
					$note .= sprintf( '<dt>%s</dt>', esc_html( $label ) );
					$note .= sprintf( '<dd>%s</dd>', esc_html( $data[$key] ) );
				}
			}
			
			$note .= '</dl>';
			
			$payment->add_note( $note ); 

			$payment->status = $status;
		}
	}
}
