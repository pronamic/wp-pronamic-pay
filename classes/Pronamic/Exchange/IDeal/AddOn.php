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
	 * Options group.
	 *
	 * @const string
	 */
	const OPTION_GROUP = 'pronamic_exchange_ideal_addon';

	/**
	 * The option key that stores the configuration ID.
	 *
	 * @const string
	 */
	const CONFIGURATION_OPTION_KEY = 'pronamic_exchange_ideal_addon_configuration';

	/**
	 * The option key that stores the iDEAL payment button text.
	 *
	 * @const string
	 */
	const TITLE_OPTION_KEY = 'pronamic_exchange_ideal_addon_title';

	//////////////////////////////////////////////////

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
			'file'              => Pronamic_WordPress_IDeal_Plugin::$dirname . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'exchange' . DIRECTORY_SEPARATOR . 'add-on.php',
			'category'          => 'transaction-methods',
			'supports'          => array( 'transaction_status' => true ),
			'settings-callback' => array( __CLASS__, 'settings' ),
		);

		it_exchange_register_addon( 'ideal', $options );

		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Register settings
	 */
	public static function register_settings() {

		register_setting( self::OPTION_GROUP, self::TITLE_OPTION_KEY );
		register_setting( self::OPTION_GROUP, self::CONFIGURATION_OPTION_KEY );
	}

	/**
	 * Gateway settings
	 */
	public static function settings() {

		$data = new stdClass();

		$data->title                 = get_option( self::TITLE_OPTION_KEY, __( 'Pay with iDEAL', 'pronamic_ideal' ) );
		$data->current_configuration = get_option( self::CONFIGURATION_OPTION_KEY, 0 );
		$data->configurations        = Pronamic_WordPress_IDeal_IDeal::get_config_select_options();

		include Pronamic_WordPress_IDeal_Plugin::$dirname . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'exchange' . DIRECTORY_SEPARATOR . 'settings.php';
	}
}