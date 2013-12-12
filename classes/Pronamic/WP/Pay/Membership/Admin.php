<?php

/**
 * Title: WordPress iDEAL admin
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WP_Pay_Membership_Admin {
	/**
	 * Bootstrap
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		add_action( 'membership_add_menu_items_after_gateways', array( $this, 'add_menu_items' ) );

		add_action( 'add_option_'    . Pronamic_Membership_IDeal_AddOn::OPTION_CONFIG_ID, array( $this, 'add_option_config_id'    ), 11, 2 );
		add_action( 'update_option_' . Pronamic_Membership_IDeal_AddOn::OPTION_CONFIG_ID, array( $this, 'update_option_config_id' ), 11, 2 );
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
    		array( 'Pronamic_WordPress_IDeal_Admin', 'settings_section' ), // callback
    		'pronamic_pay_membership' // page
    	);

    	add_settings_field(
    		Pronamic_Membership_IDeal_AddOn::OPTION_CONFIG_ID, // id
    		__( 'Configuration', 'pronamic_ideal' ), // title
    		array(  'Pronamic_WordPress_IDeal_Admin', 'dropdown_configs' ), // callback
    		'pronamic_pay_membership', // page
    		'pronamic_pay_membership_general', // section
    		array( // args 
    			'name'      => Pronamic_Membership_IDeal_AddOn::OPTION_CONFIG_ID,
    			'label_for' => Pronamic_Membership_IDeal_AddOn::OPTION_CONFIG_ID
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
			'membershipadmin',
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
		return Pronamic_WordPress_IDeal_Admin::render_view( 'membership/settings' );
    }

	//////////////////////////////////////////////////

    /**
     * Update config id option
     * 
     * @param string $old_value
     * @param string $value
     */
    public function update_option_config_id( $old_value, $value ) {
    	// We add or remove the 'ideal' gateway to the activated gateways array
    	// when the config id option is updated
    	$activated_gateways = get_option( 'membership_activated_gateways', array() );

    	if ( is_array( $activated_gateways ) ) {
    		if ( empty( $value ) ) {
    			$key = array_search( 'ideal', $activated_gateways );

    			if ( $key !== false ) {
    				unset( $activated_gateways[$key] );
    			}
    		} else {    		
    			$activated_gateways[] = 'ideal';
    		}
    		
    		update_option( 'membership_activated_gateways', array_unique( $activated_gateways ) );
    	}
    }
    
    //////////////////////////////////////////////////
    
    /**
     * Add config id option
     *
     * @param string $option
     * @param string $value
     */
    public function add_option_config_id( $option, $value ) {
    	$this->update_option_config_id( null, $value );
    }
}
