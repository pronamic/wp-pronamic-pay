<?php

/**
 * Title: Mollie
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Mollie_Gateway extends Pronamic_WP_Pay_Gateway {
	/**
	 * Slug of this gateway
	 *
	 * @var string
	 */
	const SLUG = 'mollie';

	/////////////////////////////////////////////////

	/**
	 * Constructs and initializes an Mollie gateway
	 *
	 * @param Pronamic_Gateways_Mollie_IDeal_Config $config
	 */
	public function __construct( Pronamic_Gateways_Mollie_Config $config ) {
		parent::__construct( $config );

		$this->set_method( Pronamic_WP_Pay_Gateway::METHOD_HTTP_REDIRECT );
		$this->set_has_feedback( true );
		$this->set_amount_minimum( 1.20 );
		$this->set_slug( self::SLUG );

		$this->client = new Pronamic_Gateways_Mollie_Client( $config->api_key );
	}

	/////////////////////////////////////////////////

	/**
	 * Start
	 *
	 * @param Pronamic_Pay_PaymentDataInterface $data
	 * @see Pronamic_WP_Pay_Gateway::start()
	 */
	public function start( Pronamic_Pay_PaymentDataInterface $data, Pronamic_Pay_Payment $payment, $payment_method = null ) {
		$request = new Pronamic_Gateways_Mollie_PaymentRequest();

		$request->amount       = $data->get_amount();
		$request->description  = $data->get_description();
		$request->redirect_url = add_query_arg( 'payment', $payment->get_id(), home_url( '/' ) );
		
		if ( isset( $payment_method ) ) {
			if ( 'mister_cash' == $payment_method ) {
				$request->method = 'mistercash';
			}
		}

		$result = $this->client->create_payment( $request );

		if ( $result ) {
			$payment->set_transaction_id( $result->id );
			$payment->set_action_url( $result->links->paymentUrl );
		} else {
			$this->error = $this->client->get_error();
		}
	}

	/////////////////////////////////////////////////

	/**
	 * Update status of the specified payment
	 *
	 * @param Pronamic_Pay_Payment $payment
	 */
	public function update_status( Pronamic_Pay_Payment $payment ) {
		$mollie_payment = $this->client->get_payment( $payment->get_transaction_id() );

		if ( $mollie_payment ) {
			$payment->set_status( Pronamic_WP_Pay_Mollie_Statuses::transform( $mollie_payment->status ) );
			
			if ( isset( $mollie_payment->details ) ) {
				$details = $mollie_payment->details;
				
				if ( isset( $details->consumerName ) ) { 
					$payment->set_consumer_name( $details->consumerName );
				}
				
				if ( isset( $details->consumerAccount ) ) {
					$payment->set_consumer_iban( $details->consumerAccount );
				}
			}
		} else {
			$this->error = $this->client->get_error();
		}
	}
}
