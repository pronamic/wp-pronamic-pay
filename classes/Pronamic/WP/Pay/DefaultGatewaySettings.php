<?php

/**
 * Title: Default gateway settings
 * Description:
 * Copyright: Copyright (c) 2005 - 2015
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 3.8.0
 * @since 3.8.0
 */
class Pronamic_WP_Pay_DefaultGatewaySettings extends Pronamic_WP_Pay_GatewaySettings {
	public function __construct() {
		add_filter( 'pronamic_pay_gateway_sections', array( $this, 'sections' ) );
		add_filter( 'pronamic_pay_gateway_fields', array( $this, 'fields' ) );
	}

	public function sections( array $sections ) {
		$sections['general'] = array(
			'title' => __( 'General', 'pronamic_ideal' ),
		);

		return $sections;
	}

	public function fields( array $fields ) {
		$fields[] = array(
			'filter'      => FILTER_SANITIZE_STRING,
			'section'     => 'general',
			'meta_key'    => '_pronamic_gateway_mode',
			'name'        => 'mode',
			'id'          => 'pronamic_ideal_mode',
			'title'       => __( 'Mode', 'pronamic_ideal' ),
			'type'        => 'optgroup',
			'options'     => array(
				Pronamic_IDeal_IDeal::MODE_LIVE => __( 'Live', 'pronamic_ideal' ),
				Pronamic_IDeal_IDeal::MODE_TEST => __( 'Test', 'pronamic_ideal' ),
			),
		);

		return $fields;
	}
}
