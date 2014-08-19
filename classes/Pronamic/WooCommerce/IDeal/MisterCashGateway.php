<?php

/**
 * Title: WooCommerce Mister Cash gateway
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WooCommerce_IDeal_MisterCashGateway extends Pronamic_WooCommerce_Pay_Gateway {
	/**
	 * The unique ID of this payment gateway
	 *
	 * @var string
	 */
	const ID = 'pronamic_pay_mister_cash';

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize an iDEAL gateway
	 */
	public function __construct() {
		$this->id           = self::ID;
		$this->method_title = __( 'Mister Cash', 'pronamic_ideal' );

		parent::__construct( );
	}

	/**
     * Initialise form fields
     */
	function init_form_fields() {
		parent::init_form_fields();
		
		$this->form_fields['enabled']['label'] = __( 'Enable Mister Cash', 'pronamic_ideal' );
		$this->form_fields['description']['default'] = '';
		$this->form_fields['icon']['default'] = plugins_url( 'images/mistercash/icon-24x24.png', Pronamic_WP_Pay_Plugin::$file );
	}
}
