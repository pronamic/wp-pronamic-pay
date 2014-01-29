<?php

/**
 * Title: Exchange iDEAL Add-On
 * Description:
 * Copyright Copyright (c) 2005 - 2014
 * Company: Pronamic
 * @author Stefan Boonstra
 * @version 1.0
 */
class Pronamic_Exchange_IDeal_AddOn {

	/**
	 * Bootstrap
	 */
	public static function bootstrap() {

		add_action( 'it_exchange_register_addons', array( __CLASS__, 'init' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Initialize
	 */
	public static function init() {

		$options = array(
			'name'              => __( 'iDEAL', 'pronamic_ideal' ),
			'description'       => __( 'Adds the ability for users to checkout with iDEAL.', 'pronamic_ideal' ),
			'author'            => 'Pronamic',
			'author_url'        => 'http://www.pronamic.eu/wordpress-plugins/pronamic-ideal/',
			'icon'              => plugins_url( 'images/icon-50x50.png', Pronamic_WordPress_IDeal_Plugin::$file ),
			'file'              => Pronamic_WordPress_IDeal_Plugin::$dirname . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'exchange.php',
			'category'          => 'transaction-methods',
			'supports'          => array( 'transaction_status' => true ),
			'settings-callback' => array( __CLASS__, 'settings' ),
		);
		it_exchange_register_addon( 'ideal', $options );
	}

	//////////////////////////////////////////////////

	/**
	 * Gateway settings
	 */
	public static function settings() {

	}
}