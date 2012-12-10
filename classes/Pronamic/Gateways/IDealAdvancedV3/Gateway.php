<?php

/**
 * Title: iDEAL Advanced v3+
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvancedV3_Gateway extends Pronamic_Gateways_Gateway {
	public function __construct( $configuration ) {
		parent::__construct();

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTTP_REDIRECT );
		$this->set_require_issue_select( true );
		$this->set_amount_minimum( 0.01 );
		
		$client = new Pronamic_Gateways_IDealAdvancedV3_Client();
		$client->set_acquirer_url( $configuration->getPaymentServerUrl() );
		$client->merchant_id          = $configuration->getMerchantId();
		$client->sub_id               = $configuration->getSubId();
		$client->private_key          = $configuration->privateKey;
		$client->private_key_password = $configuration->privateKeyPassword;
		$client->private_certificate  = $configuration->privateCertificate;
		
		$this->client = $client;
		
	}
	
	/////////////////////////////////////////////////

	public function get_issuers() {
		$directory = $this->client->get_directory();
		
		$groups = array();

		foreach ( $directory->get_countries() as $country ) {
			$issuers = array();

			foreach ( $country->get_issuers() as $issuer ) {
				$issuers[$issuer->get_id()] = $issuer->get_name();
			}

			$groups[] = array(
				'name'    => $country->get_name(),
				'options' => $issuers
			);
		}
		
		return $groups;
	}
	
	/////////////////////////////////////////////////

	public function get_html_fields() {
		$html  = '';
		
		$groups = $this->get_issuers();

		$value = '';

		$html .= '<select id="pronamic_ideal_issuer_id" name="pronamic_ideal_issuer_id">';
		$html .= Pronamic_IDeal_HTML_Helper::select_options_grouped( $groups, $value );
		$html .= '</select>';
		
		return $html;
	}
	
	/////////////////////////////////////////////////

	public function start( $data ) {
		$transaction = new Pronamic_Gateways_IDealAdvancedV3_Transaction();
		$transaction->setPurchaseId( $data->getOrderId() );
		$transaction->setAmount( $data->getAmount() );
		$transaction->setCurrency( $data->getCurrencyAlphabeticCode() );
		$transaction->setExpirationPeriod( 'PT3M30S' );
		$transaction->setLanguage( $data->getLanguageIso639Code() );
		$transaction->setDescription( $data->getDescription() );
		$transaction->setEntranceCode( $data->get_entrance_code() );

		$result = $this->client->create_transaction( $transaction, $data->get_issuer_id() );

		$error = $this->client->get_error();
		
		if ( $error !== null ) {
			$error = $this->client->get_error();
		
			var_dump( $error );
		} else {
			$issuer = $result->issuer;

			$this->action_url     = $result->issuer->get_authentication_url();
			$this->transaction_id = $result->transaction->get_id();
		}
		?>
		<pre><?php var_dump( $this ); ?></pre>
		<?php
	}
}
