<?php

/**
 * Title: WordPress pay Event Espresso extension
 * Description:
 * Copyright: Copyright (c) 2005 - 2014
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0.0
 */
class Pronamic_WP_Pay_Extensions_EventEspresso_Extension {
	/**
	 * Bootstrap
	 */
	public static function bootstrap() {
		// Priority 5 is needed to make sure the gateways are loaded on time
		add_action( 'init', array( __CLASS__, 'init' ), 5 );
	}

	/**
	 * Initialize
	 */
	public static function init() {
		/**
		 * Check if Event Espresso gateways class and instance method exists
		 *
		 * @see https://github.com/eventespresso/event-espresso-core/blob/4.2.2.reg/core/db_models/EEM_Gateways.model.php#L25
		 * @see https://github.com/eventespresso/event-espresso-core/blob/4.2.2.reg/core/db_models/EEM_Gateways.model.php#L47-L58
		 */
		if ( method_exists( 'EEM_Gateways', 'instance' ) ) {
			/**
			 * Add the iDEAL gateway instance to the private gateway instances
			 * var. There is some voodoo involved to make the private var
			 * public.
			 *
			 * @see https://github.com/eventespresso/event-espresso-core/blob/4.2.2.reg/core/db_models/EEM_Gateways.model.php#L184-L251
			 */
			$gateways = EEM_Gateways::instance();

			$class = new ReflectionClass( 'EEM_Gateways' );

			// All gateways
			// @see https://github.com/eventespresso/event-espresso-core/blob/4.2.2.reg/core/db_models/EEM_Gateways.model.php#L28
			$property = $class->getProperty( '_all_gateways' );
			$property->setAccessible( true );

			$all_gateways = $property->getValue( $gateways );
			$all_gateways['pronamic_pay_ideal'] = false;

			$property->setValue( $gateways, $all_gateways );

			// Gateway instances
			// @see https://github.com/eventespresso/event-espresso-core/blob/4.2.2.reg/core/db_models/EEM_Gateways.model.php#L29
			$property = $class->getProperty( '_gateway_instances' );
			$property->setAccessible( true );

			$instances = $property->getValue( $gateways );
			$instances['pronamic_pay_ideal'] = new Pronamic_WP_Pay_Extensions_EventEspresso_IDealGateway( $gateways );

			$property->setValue( $gateways, $instances );
		}
	}
}
