<?php

/**
 * Title: Ogone DirectLink gateway
 * Description: 
 * Copyright: Copyright (c) 2005 - 2013
 * Company: Pronamic
 * @author Remco Tolsma
 * @since 1.4.0
 */
class Pronamic_Pay_Gateways_MultiSafepay_Connect_Gateway extends Pronamic_Gateways_Gateway {
	/**
	 * Slug of this gateway
	 * 
	 * @var string
	 */
	const SLUG = 'multisafepay-connect';	
	
	/////////////////////////////////////////////////

	/**
	 * Constructs and initializes an MultiSafepay Connect gateway
	 * 
	 * @param Pronamic_Pay_Gateways_MultiSafepay_Config $config
	 */
	public function __construct( Pronamic_Pay_Gateways_MultiSafepay_Config $config ) {
		parent::__construct( $config );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTTP_REDIRECT );
		$this->set_has_feedback( true );
		$this->set_amount_minimum( 0 );
		$this->set_slug( self::SLUG );
		
		$this->client = new Pronamic_Pay_Gateways_MultiSafepay_Connect_Client();
		$this->client->api_url  = $config->api_url;
	}

	/////////////////////////////////////////////////

	public function start( Pronamic_Pay_PaymentDataInterface $data, Pronamic_Pay_Payment $payment ) {
		$url = add_query_arg( 'payment', $payment->get_id(), home_url( '/' ) );

		$merchant = new Pronamic_Pay_Gateways_MultiSafepay_Connect_Merchant();
		$merchant->account = $this->config->account_id;
		$merchant->site_id = $this->config->site_id;
		$merchant->site_secure_code = $this->config->site_code;
		$merchant->notification_url = $url;
		$merchant->redirect_url = $url;
		$merchant->cancel_url = $url;
		$merchant->close_window = 'false';
		
		$customer = new Pronamic_Pay_Gateways_MultiSafepay_Connect_Customer();
		$customer->locale = $data->get_language_and_country();
		$customer->ip_address = filter_input( INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP );
		$customer->forwarded_ip = filter_input( INPUT_SERVER, 'HTTP_X_FORWARDED_FOR', FILTER_VALIDATE_IP );
		$customer->first_name = $data->getCustomerName();
		$customer->last_name = '';
		$customer->address_1 = 'Test';
		$customer->address_2 = '';
		$customer->house_number = '1';
		$customer->zip_code = '1234 AB';
		$customer->city = 'Test';
		$customer->country = 'Test';
		$customer->phone = '';
		$customer->email = $data->get_email();
		
		$transaction = new Pronamic_Pay_Gateways_MultiSafepay_Connect_Transaction();
		$transaction->id = uniqid();
		$transaction->currency = $data->get_currency();
		$transaction->amount = $data->get_amount();
		$transaction->description = $data->get_description();
		$transaction->var1 = '';
		$transaction->var2 = '';
		$transaction->var3 = '';
		$transaction->items = '';
		$transaction->manual = 'false';
		$transaction->gateway = '';
		$transaction->days_active = '';
		
		$message = new Pronamic_Pay_Gateways_MultiSafepay_Connect_XML_RedirectTransactionRequestMessage( $merchant, $customer, $transaction );
		
		global $pronamic_pay_version;

		$message->set_user_agent( 'Pronamic Pay' . ' ' . $pronamic_pay_version );

		$signature = Pronamic_Pay_Gateways_MultiSafepay_Connect_Signature::generate( $transaction->amount, $transaction->currency, $merchant->account, $merchant->site_id, $transaction->id );

		$message->signature = $signature;
		
		$result = $this->client->start_transaction( $message );

		if ( $result ) {
			$payment->set_transaction_id( $result->id );
			$payment->set_action_url( $result->payment_url );
		} else {
			$this->error = $this->client->get_error();
		}
	}
	
	public function update_status( Pronamic_Pay_Payment $payment ) {
		$merchant = new Pronamic_Pay_Gateways_MultiSafepay_Connect_Merchant();
		$merchant->account = $this->config->account_id;
		$merchant->site_id = $this->config->site_id;
		$merchant->site_secure_code = $this->config->site_code;
	
		$message = new Pronamic_Pay_Gateways_MultiSafepay_Connect_XML_StatusRequestMessage( $merchant, $payment->get_transaction_id() );

		$result = $this->client->get_status( $message );

		if ( $result ) {
			$status = Pronamic_Pay_Gateways_MultiSafepay_Connect_Statuses::transform( $result->ewallet->status );

			$payment->set_status( $status );
			$payment->set_consumer_name( $result->payment_details->account_holder_name );
			$payment->set_consumer_iban( $result->payment_details->account_iban );
			$payment->set_consumer_bic( $result->payment_details->account_bic );
			$payment->set_consumer_account_number( $result->payment_details->account_id );
		} else {
			$this->error = $this->client->get_error();
		}
	}
}
