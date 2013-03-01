<?php

class Pronamic_S2Member_IDeal_IDealGateway {

	/**
	 * Holds the configuration ID from the settings
	 *
	 * The setting key is 'pronamic_ideal_s2member_chosen_configuration'
	 *
	 * @var int
	 */
	public $configuarationID = 0;

	public function __construct() {

		$this->configuarationID = get_option( 'pronamic_ideal_s2member_chosen_configuration' );

	}

	public function process_gateway( $order_id ) {

		// Gets the configuration class
		$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $this->configuarationID );

		// Which is passed into retrieving the gateway
		$gateway = Pronamic_WordPress_IDeal_IDeal::get_gateway( $configuration );

		if ( $gateway ) {
			return $gateway->get_form_html();
		}
	}

}