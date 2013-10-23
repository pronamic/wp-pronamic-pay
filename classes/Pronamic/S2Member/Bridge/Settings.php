<?php

/**
 * Title: s2Member bridge settings
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Leon Rowland
 * @since 1.2.6
 */
class Pronamic_S2Member_Bridge_Settings {
    public function __construct() {
    	add_action( 'admin_init', array( $this, 'admin_init' ) );
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
    }
    
    public function admin_init() {
    	// Settings - General
    	add_settings_section(
    		'pronamic_pay_s2member_general', // id
    		__( 'General', 'pronamic_ideal' ), // title
    		array( 'Pronamic_WordPress_IDeal_Admin', 'settings_section' ), // callback
    		'pronamic_pay_s2member' // page
    	);

    	add_settings_field(
    		'pronamic_pay_s2member_config_id', // id
    		__( 'Configuration', 'pronamic_ideal' ), // title
    		array(  'Pronamic_WordPress_IDeal_Admin', 'dropdown_configs' ), // callback
    		'pronamic_pay_s2member', // page
    		'pronamic_pay_s2member_general', // section
    		array( // args 
    			'name'      => 'pronamic_pay_s2member_config_id',
    			'label_for' => 'pronamic_pay_s2member_config_id'
    		)
    	);

    	register_setting( 'pronamic_pay_s2member', 'pronamic_pay_s2member_config_id' );
    }

    public function admin_menu() {
        $parent_slug = apply_filters( 'ws_plugin__s2member_during_add_admin_options_menu_slug', 'ws-plugin--s2member-start' );

        if ( apply_filters( 'ws_plugin__s2member_during_add_admin_options_add_divider_6', true, get_defined_vars() ) ) /* Divider. */
			add_submenu_page( $parent_slug, '', '<span style="display:block; margin:1px 0 1px -5px; padding:0; height:1px; line-height:1px; background:#CCCCCC;"></span>', 'create_users', '#' );

		add_submenu_page(
			$parent_slug,
			__( 'Pronamic iDEAL Options', 'pronamic_ideal' ),
			__( 'iDEAL Options', 'pronamic_ideal' ),
			'create_users',
            'pronamic_pay_s2member_settings',
            array( $this, 'view_options_page' )
		);

		add_submenu_page(
			$parent_slug,
			__( 'Pronamic iDEAL Buttons Generator', 'pronamic_ideal' ),
			__( 'iDEAL Buttons', 'pronamic_ideal' ),
			'create_users',
			'pronamic_pay_s2member_buttons',
			array( $this, 'view_buttongen_page' )
		);
	}

    public function view_options_page() {
		return Pronamic_WordPress_IDeal_Admin::render_view( 's2member/settings' );
    }

	public function view_buttongen_page() {
		return Pronamic_WordPress_IDeal_Admin::render_view( 's2member/buttons-generator' );
	}
}
