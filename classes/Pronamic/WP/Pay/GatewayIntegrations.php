<?php

/**
 * Title: WordPress gateway integrations
 * Description:
 * Copyright: Copyright (c) 2005 - 2017
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 3.8.0
 * @since 3.8.0
 */
class Pronamic_WP_Pay_GatewayIntegrations implements IteratorAggregate {
	/**
	 * Integrations
	 */
	public $integrations;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an admin gateway integrations object
	 */
	public function __construct() {
		$this->integrations = array();

		// Classes
		$classes = apply_filters( 'pronamic_pay_gateway_integrations', array() );

		foreach ( $classes as $value ) {
			if ( is_object( $value ) ) {
				$object = $value;
			} else {
				$object = new $value();
			}

			$this->integrations[ $object->get_id() ] = $object;

			Pronamic_WP_Pay_ConfigProvider::register( $object->get_id(), $object->get_config_factory_class() );
		}
	}

	/**
	 * Get an integration by the specified ID.
	 *
	 * @param string $id
	 * @return mixed
	 */
	public function get_integration( $id ) {
		if ( isset( $this->integrations[ $id ] ) ) {
			return $this->integrations[ $id ];
		}
	}

	/**
	 * Get iterator
	 *
	 * @return ArrayIterator
	 */
	public function getIterator() {
		return new ArrayIterator( $this->integrations );
	}
}
