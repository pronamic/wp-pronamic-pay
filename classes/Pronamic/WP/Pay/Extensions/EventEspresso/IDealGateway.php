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
		$this->_gateway_name = 'pronamic_pay_ideal';

		// @see https://github.com/eventespresso/event-espresso-core/blob/4.2.2.reg/core/db_classes/EE_Offsite_Gateway.class.php#L4
		// @see https://github.com/eventespresso/event-espresso-core/blob/4.2.2.reg/core/db_classes/EE_Gateway.class.php#L26
		parent::__construct( $model );
	}

	//////////////////////////////////////////////////
	// Abstract functions
	//////////////////////////////////////////////////

	/**
	 * Default settings
	 *
	 * @see https://github.com/eventespresso/event-espresso-core/blob/4.2.2.reg/core/db_classes/EE_Gateway.class.php#L83
	 * @see https://github.com/eventespresso/event-espresso-core/blob/4.2.2.reg/modules/gateways/Paypal_Standard/EE_Paypal_Standard.class.php#L55
	 */
	protected function _default_settings() {
		$this->_payment_settings['display_name'] = __( 'iDEAL', 'pronamic_ideal' );
		$this->_payment_settings['button_url']   = plugins_url( 'images/icon-24x24.png', Pronamic_WP_Pay_Plugin::$file );
	}

	/**
	 * Update settings
	 *
	 * @see https://github.com/eventespresso/event-espresso-core/blob/4.2.2.reg/core/db_classes/EE_Gateway.class.php#L84
	 * @see https://github.com/eventespresso/event-espresso-core/blob/4.2.2.reg/modules/gateways/Paypal_Standard/EE_Paypal_Standard.class.php#L67-L74
	 */
	protected function _update_settings() {
		$this->_payment_settings['config_id']  = filter_input( INPUT_POST, 'config_id', FILTER_SANITIZE_STRING );
		$this->_payment_settings['button_url'] = filter_input( INPUT_POST, 'button_url', FILTER_SANITIZE_STRING );
	}

	/**
	 * Display settings
	 *
	 * @see https://github.com/eventespresso/event-espresso-core/blob/4.2.2.reg/core/db_classes/EE_Gateway.class.php#L85
	 */
	protected function _display_settings() {
		?>
		<tr>
			<th>
				<label><?php _e( 'Configuration', 'pronamic_ideal' ); ?></label>
			</th>
			<td>
				<?php

				echo Pronamic_WP_Pay_Admin::dropdown_configs( array(
					'name'     => 'config_id',
					'selected' => $this->_payment_settings['config_id'],
				) );

				?>
			</td>
		</tr>
		<?php
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

	//////////////////////////////////////////////////

	/**
	 * Display settings help
	 *
	 * @see https://github.com/eventespresso/event-espresso-core/blob/4.2.2.reg/modules/gateways/Paypal_Standard/EE_Paypal_Standard.class.php#L302-L345
	 */
	protected function _display_settings_help() {

	}
}
