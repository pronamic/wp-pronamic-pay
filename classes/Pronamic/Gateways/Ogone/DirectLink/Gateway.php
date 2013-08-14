<?php

/**
 * Title: Ogone
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Ogone_DirectLink_Gateway extends Pronamic_Gateways_Gateway {
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
	 * @param Pronamic_WordPress_IDeal_Configuration $configuration
	 */
	public function __construct( Pronamic_WordPress_IDeal_Configuration $configuration ) {
		parent::__construct( $configuration );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTTP_REDIRECT );
		$this->set_has_feedback( true );
		$this->set_amount_minimum( 1.20 );
		$this->set_slug( self::SLUG );

		$this->client = new Pronamic_Gateways_Ogone_DirectLink_Client();
		$this->client->psp_id   = $configuration->pspId;
		$this->client->sha_in   = $configuration->shaInPassPhrase;
		$this->client->user_id  = $configuration->ogone_user_id;
		$this->client->password = $configuration->ogone_password;
	}
	
	/////////////////////////////////////////////////

	public function start( Pronamic_IDeal_IDealDataProxy $data ) {
		$kassa = new Pronamic_Gateways_IDealInternetKassa_IDealInternetKassa();
		$kassa->setPspId( $this->client->psp_id );
		$kassa->setPassPhraseIn( $this->client->sha_in );
		$kassa->setOrderId( $data->getOrderId() );
		$kassa->set_field( 'USERID', $this->client->user_id );
		$kassa->set_field( 'PSWD', $this->client->password );
		$kassa->setAmount( $data->getAmount() );
		$kassa->setCurrency( 'EUR' );
		$kassa->set_field( 'CARDNO', '5555555555554444' );
		// $kassa->set_field( 'CARDNO', '4111111111111111' );
		$kassa->set_field( 'ED', '01/15' );
		$kassa->setOrderDescription( $data->getDescription() );
		$kassa->setCustomerName( $data->getCustomerName() );
		$kassa->setEMailAddress( $data->getEMailAddress() );
		$kassa->set_field( 'CVC', '000' );
		$kassa->set_field( 'OPERATION', 'SAL' );
		
		$data = $kassa->get_fields();
		$data['SHASIGN'] = $kassa->getSignatureIn();
		
		$result = $this->client->order_direct( $data );
		
		if ( is_wp_error( $result ) ) {
			$this->error = $result;
		} else {
			$this->set_action_url( add_query_arg( 'status', $result->status ) );
		}
	}
}
