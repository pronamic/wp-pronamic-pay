<?php

/**
 * Title: TargetPay
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_TargetPay_Gateway extends Pronamic_Gateways_Gateway {
	/**
	 * Slug of this gateway
	 * 
	 * @var string
	 */
	const SLUG = 'targetpay';	
	
	/////////////////////////////////////////////////

	/**
	 * Constructs and initializes an TargetPay gateway
	 * 
	 * @param Pronamic_WordPress_IDeal_Configuration $configuration
	 */
	public function __construct( Pronamic_Gateways_TargetPay_Config $config ) {
		parent::__construct( $config );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTTP_REDIRECT );
		$this->set_has_feedback( true );
		$this->set_amount_minimum( 0.84 );
		$this->set_slug( self::SLUG );

		$this->client = new Pronamic_Gateways_TargetPay_TargetPay();
	}
	
	/////////////////////////////////////////////////

	/**
	 * Get issuers
	 * 
	 * @see Pronamic_Gateways_Gateway::get_issuers()
	 */
	public function get_issuers() {
		$groups = array();

		$result = $this->client->get_issuers();
		
		if ( $result ) {
			$groups[] = array(
				'options' => $result
			);
		}

		return $groups;
	}
	
	/////////////////////////////////////////////////

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
	
	/////////////////////////////////////////////////

	/**
	 * Start
	 * 
	 * @see Pronamic_Gateways_Gateway::start()
	 */
	public function start( $data ) {
		$result = $this->client->start_transaction(
			$this->config->layoutcode,
			$data->get_issuer_id(),
			$data->getDescription(),
			$data->getAmount(),
			add_query_arg( 'gateway', 'targetpay', home_url( '/' ) ),
			add_query_arg( 'gateway', 'targetpay', home_url( '/' ) )
		);
		
		if ( $result ) {
			$this->set_action_url( $result->url );
			$this->set_transaction_id( $result->transaction_id );
		} else {
			$this->set_error( $this->client->get_error() );
		}
	}
	
	/////////////////////////////////////////////////

	/**
	 * Update status of the specified payment
	 * 
	 * @param Pronamic_Pay_Payment $payment
	 */
	public function update_status( Pronamic_Pay_Payment $payment ) {
		$status = $this->client->check_status(
			$this->config->layoutcode,
			$payment->transaction_id,
			false,
			$this->configuration->getMode() == Pronamic_IDeal_IDeal::MODE_TEST
		);

		if ( $status ) {
			switch ( $status->code ) {
				case Pronamic_Gateways_TargetPay_ResponseCodes::OK:
					$payment->status                  = Pronamic_Gateways_IDealAdvancedV3_Status::SUCCESS;
					$payment->consumer_name           = $status->account_name;
					$payment->consumer_account_number = $status->account_number;
					$payment->consumer_city           = $status->account_city;

					break;
				case Pronamic_Gateways_TargetPay_ResponseCodes::TRANSACTION_NOT_COMPLETED:
					$payment->status = Pronamic_Gateways_IDealAdvancedV3_Status::OPEN;

					break;
				case Pronamic_Gateways_TargetPay_ResponseCodes::TRANSACTION_CANCLLED:
					$payment->status = Pronamic_Gateways_IDealAdvancedV3_Status::CANCELLED;

					break;
				case Pronamic_Gateways_TargetPay_ResponseCodes::TRANSACTION_EXPIRED:
					$payment->status = Pronamic_Gateways_IDealAdvancedV3_Status::EXPIRED;

					break;
				case Pronamic_Gateways_TargetPay_ResponseCodes::TRANSACTION_NOT_PROCESSED:

					break;
				case Pronamic_Gateways_TargetPay_ResponseCodes::ALREADY_USED:

					break;
				case Pronamic_Gateways_TargetPay_ResponseCodes::LAYOUTCODE_NOT_ENTERED:

					break;
				case Pronamic_Gateways_TargetPay_ResponseCodes::TRANSACTION_ID_NOT_ENTERED:

					break;
				case Pronamic_Gateways_TargetPay_ResponseCodes::TRANSACTION_NOT_FOUND:

					break;
				case Pronamic_Gateways_TargetPay_ResponseCodes::LAYOUCODE_NOT_MATCH_TRANSACTION:

					break;
			}
		}
	}
}
