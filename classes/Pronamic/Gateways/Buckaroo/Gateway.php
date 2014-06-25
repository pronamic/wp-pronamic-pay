<?php

/**
 * Title: Buckaroo gateway
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Buckaroo_Gateway extends Pronamic_WP_Pay_Gateway {
	/**
	 * Slug of this gateway
	 *
	 * @var string
	 */
	const SLUG = 'buckaroo';

	/////////////////////////////////////////////////

	/**
	 * Constructs and initializes an Buckaroo gateway
	 *
	 * @param Pronamic_Gateways_Buckaroo_Config $config
	 */
	public function __construct( Pronamic_Gateways_Buckaroo_Config $config ) {
		parent::__construct( $config );

		$this->set_method( Pronamic_WP_Pay_Gateway::METHOD_HTML_FORM );
		$this->set_has_feedback( true );
		$this->set_amount_minimum( 0.01 );
		$this->set_slug( self::SLUG );

		$this->client = new Pronamic_Gateways_Buckaroo_Buckaroo();
		$this->client->set_website_key( $config->website_key );
		$this->client->set_secret_key( $config->secret_key );

		if ( $config->mode == Pronamic_IDeal_IDeal::MODE_TEST ) {
			$this->client->set_payment_server_url( Pronamic_Gateways_Buckaroo_Buckaroo::GATEWAY_TEST_URL );
		}
	}

	/////////////////////////////////////////////////

	/**
	 * Start
	 *
	 * @param Pronamic_Pay_PaymentDataInterface $data
	 * @param Pronamic_Pay_Payment              $payment
	 *
	 * @see Pronamic_WP_Pay_Gateway::start()
	 */
	public function start( Pronamic_Pay_PaymentDataInterface $data, Pronamic_Pay_Payment $payment ) {
		$payment->set_action_url( $this->client->get_payment_server_url() );

		// Buckaroo uses 'nl-NL' instead of 'nl_NL'
		$culture = str_replace( '_', '-', $data->get_language_and_country() );

		$this->client->set_culture( $culture );
		$this->client->set_currency( $data->get_currency() );
		$this->client->set_description( $data->get_description() );
		$this->client->set_amount( $data->get_amount() );

		$order_id = $data->get_order_id();

		if ( empty( $order_id ) ) {
			$this->client->set_invoice_number( $payment->get_id() );
		} else {
			$this->client->set_invoice_number( $order_id );
		}

		$return_url = add_query_arg( 'payment', $payment->get_id(), home_url( '/' ) );

		$this->client->set_return_url( $return_url );
		$this->client->set_return_cancel_url( $return_url );
		$this->client->set_return_error_url( $return_url );
		$this->client->set_return_reject_url( $return_url );
	}

	/////////////////////////////////////////////////

	/**
	 * Get output HTML
	 *
	 * @see Pronamic_WP_Pay_Gateway::get_output_html()
	 */
	public function get_output_html() {
		return $this->client->get_html_fields();
	}

	/////////////////////////////////////////////////

	/**
	 * Update status of the specified payment
	 *
	 * @param Pronamic_Pay_Payment $payment
	 */
	public function update_status( Pronamic_Pay_Payment $payment ) {
		$data = Pronamic_Gateways_Buckaroo_Util::urldecode( $_POST );

		$data = $this->client->verify_request( $data );

		if ( $data ) {
			$payment->set_transaction_id( $data[ Pronamic_Gateways_Buckaroo_Parameters::PAYMENT ] );
			$payment->set_status( Pronamic_Gateways_Buckaroo_Statuses::transform( $data[ Pronamic_Gateways_Buckaroo_Parameters::STATUS_CODE ] ) );
			$payment->set_consumer_iban( $data[ Pronamic_Gateways_Buckaroo_Parameters::SERVICE_IDEAL_CONSUMER_IBAN ] );
			$payment->set_consumer_bic( $data[ Pronamic_Gateways_Buckaroo_Parameters::SERVICE_IDEAL_CONSUMER_BIC ] );
			$payment->set_consumer_name( $data[ Pronamic_Gateways_Buckaroo_Parameters::SERVICE_IDEAL_CONSUMER_NAME ] );

			$labels = array(
				Pronamic_Gateways_Buckaroo_Parameters::PAYMENT                       => __( 'Payment', 'pronamic_ideal' ),
				Pronamic_Gateways_Buckaroo_Parameters::PAYMENT_METHOD                => __( 'Payment Method', 'pronamic_ideal' ),
				Pronamic_Gateways_Buckaroo_Parameters::STATUS_CODE                   => __( 'Status Code', 'pronamic_ideal' ),
				Pronamic_Gateways_Buckaroo_Parameters::STATUS_CODE_DETAIL            => __( 'Status Code Detail', 'pronamic_ideal' ),
				Pronamic_Gateways_Buckaroo_Parameters::STATUS_MESSAGE                => __( 'Status Message', 'pronamic_ideal' ),
				Pronamic_Gateways_Buckaroo_Parameters::INVOICE_NUMBER                => __( 'Invoice Number', 'pronamic_ideal' ),
				Pronamic_Gateways_Buckaroo_Parameters::AMOUNT                        => __( 'Amount', 'pronamic_ideal' ),
				Pronamic_Gateways_Buckaroo_Parameters::CURRENCY                      => __( 'Currency', 'pronamic_ideal' ),
				Pronamic_Gateways_Buckaroo_Parameters::TIMESTAMP                     => __( 'Timestamp', 'pronamic_ideal' ),
				Pronamic_Gateways_Buckaroo_Parameters::SERVICE_IDEAL_CONSUMER_ISSUER => __( 'Service iDEAL Consumer Issuer', 'pronamic_ideal' ),
				Pronamic_Gateways_Buckaroo_Parameters::SERVICE_IDEAL_CONSUMER_NAME   => __( 'Service iDEAL Consumer Name', 'pronamic_ideal' ),
				Pronamic_Gateways_Buckaroo_Parameters::SERVICE_IDEAL_CONSUMER_IBAN   => __( 'Service iDEAL Consumer IBAN', 'pronamic_ideal' ),
				Pronamic_Gateways_Buckaroo_Parameters::SERVICE_IDEAL_CONSUMER_BIC    => __( 'Service iDEAL Consumer BIC', 'pronamic_ideal' ),
				Pronamic_Gateways_Buckaroo_Parameters::TRANSACTIONS                  => __( 'Transactions', 'pronamic_ideal' ),
			);

			$note = '';

			$note .= '<p>';
			$note .= __( 'Buckaroo data:', 'pronamic_ideal' );
			$note .= '</p>';

			$note .= '<dl>';

			foreach ( $labels as $key => $label ) {
				if ( isset( $data[ $key ] ) ) {
					$note .= sprintf( '<dt>%s</dt>', esc_html( $label ) );
					$note .= sprintf( '<dd>%s</dd>', esc_html( $data[ $key ] ) );
				}
			}

			$note .= '</dl>';

			$payment->add_note( $note );
		}
	}
}
