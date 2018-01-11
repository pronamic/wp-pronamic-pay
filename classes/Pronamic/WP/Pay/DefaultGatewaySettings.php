<?php

/**
 * Title: Default gateway settings
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
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
		$sections['general'] = array();

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
			'type'        => 'select',
			'options'     => array(
				array(
					'options' => array(
						'test' => __( 'Test', 'pronamic_ideal' ),
						'live' => __( 'Live', 'pronamic_ideal' ),
					),
				),
			),
		);

		return $fields;
	}
}
