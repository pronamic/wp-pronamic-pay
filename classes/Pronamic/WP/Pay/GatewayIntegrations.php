<?php

/**
 * Title: WordPress gateway integrations
 * Description:
 * Copyright: Copyright (c) 2005 - 2015
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 3.8.0
 * @since 3.8.0
 */
class Pronamic_WP_Pay_GatewayIntegrations implements IteratorAggregate {
	/**
	 * Integrations
	 */
	private $integrations;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an admin gateway integrations object
	 */
	public function __construct() {
		$this->integrations = array();

		// Classes
		$classes = apply_filters( 'pronamic_pay_gateway_integrations', array() );

		foreach ( $classes as $class ) {
			$object = new $class();

			$this->integrations[ $object->id ] = $object;
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
