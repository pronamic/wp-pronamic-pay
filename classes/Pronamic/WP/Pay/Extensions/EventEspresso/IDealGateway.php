<?php

/**
 * Title: WordPress pay Event Espresso iDEAL gateway
 * Description:
 * Copyright: Copyright (c) 2005 - 2014
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0.0
 */
class Pronamic_WP_Pay_Extensions_EventEspresso_IDealGateway extends EE_Offsite_Gateway {
	/**
	 * Constructs and initialize iDEAL gateway
	 *
	 * @param EEM_Gateways $model
	 */
	public function __construct( EEM_Gateways $model ) {
		$this->_gateway_name = 'iDEAL';

		// @see https://github.com/eventespresso/event-espresso-core/blob/4.2.2.reg/core/db_classes/EE_Offsite_Gateway.class.php#L4
		// @see https://github.com/eventespresso/event-espresso-core/blob/4.2.2.reg/core/db_classes/EE_Gateway.class.php#L26
		parent::__construct( $model );
	}

	//////////////////////////////////////////////////

	/**
	 * Default settings
	 *
	 * @see https://github.com/eventespresso/event-espresso-core/blob/4.2.2.reg/core/db_classes/EE_Gateway.class.php#L83
	 * @see https://github.com/eventespresso/event-espresso-core/blob/4.2.2.reg/modules/gateways/Paypal_Standard/EE_Paypal_Standard.class.php#L55
	 */
	protected function _default_settings() {
		$this->_payment_settings['display_name'] = __( 'iDEAL', 'pronamic_ideal' );
	}

	/**
	 * Update settings
	 *
	 * @see https://github.com/eventespresso/event-espresso-core/blob/4.2.2.reg/core/db_classes/EE_Gateway.class.php#L84
	 */
	protected function _update_settings() {

	}

	/**
	 * Display settings
	 *
	 * @see https://github.com/eventespresso/event-espresso-core/blob/4.2.2.reg/core/db_classes/EE_Gateway.class.php#L85
	 */
	protected function _display_settings() {

	}

	/**
	 * Display payment gateways
	 *
	 * @see https://github.com/eventespresso/event-espresso-core/blob/4.2.2.reg/core/db_classes/EE_Gateway.class.php#L86
	 *
	 * @param string $selected_gateway
	 */
	public function espresso_display_payment_gateways( $selected_gateway = '' ) {

	}
}
