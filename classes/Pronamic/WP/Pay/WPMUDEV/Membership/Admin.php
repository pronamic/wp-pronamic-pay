<?php

/**
 * Title: WordPress iDEAL admin
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WP_Pay_WPMUDEV_Membership_Admin {
	/**
	 * Bootstrap
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		add_action( 'membership_add_menu_items_after_gateways', array( $this, 'add_menu_items' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Admin initialize
	 */
	public function admin_init() {
		$this->settings_init();
	}

	//////////////////////////////////////////////////

	/**
	 * Settings initialize
	 */
	public function settings_init() {
		// Settings - General
		add_settings_section(
			'pronamic_pay_membership_general', // id
			__( 'General', 'pronamic_ideal' ), // title
			array( 'Pronamic_WP_Pay_Admin', 'settings_section' ), // callback
			'pronamic_pay_membership' // page
		);

		add_settings_field(
			Pronamic_WPMUDEV_Membership_IDeal_AddOn::OPTION_CONFIG_ID, // id
			__( 'Configuration', 'pronamic_ideal' ), // title
			array(  'Pronamic_WP_Pay_Admin', 'dropdown_configs' ), // callback
			'pronamic_pay_membership', // page
			'pronamic_pay_membership_general', // section
			array( // args
				'name'      => Pronamic_WPMUDEV_Membership_IDeal_AddOn::OPTION_CONFIG_ID,
				'label_for' => Pronamic_WPMUDEV_Membership_IDeal_AddOn::OPTION_CONFIG_ID,
			)
		);

		register_setting( 'pronamic_pay_membership', 'pronamic_pay_membership_config_id' );
	}

	//////////////////////////////////////////////////

	/**
	 * Add menu items
	 */
	public function add_menu_items() {
		add_submenu_page(
			'membership',
			__( 'Pronamic iDEAL Options', 'pronamic_ideal' ),
			__( 'iDEAL Options', 'pronamic_ideal' ),
			'pronamic_ideal',
			'pronamic_pay_membership_settings',
			array( $this, 'page_settings' )
		);
	}

	//////////////////////////////////////////////////

	/**
	 * Page settings
	 *
	 * @return boolean
	 */
	public function page_settings() {
		return Pronamic_WP_Pay_Admin::render_view( 'membership/settings' );
	}
}
