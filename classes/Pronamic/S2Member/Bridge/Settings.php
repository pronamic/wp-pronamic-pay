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
    	add_action( 'init',       array( $this, 'save_options_page' ) );

        add_action( 'admin_menu', array( $this, 'menu' ) );
    }

    public function menu() {
        $parent_slug = apply_filters( 'ws_plugin__s2member_during_add_admin_options_menu_slug', 'ws-plugin--s2member-start' );

        if ( apply_filters( 'ws_plugin__s2member_during_add_admin_options_add_divider_6', true, get_defined_vars() ) ) /* Divider. */
			add_submenu_page( $parent_slug, '', '<span style="display:block; margin:1px 0 1px -5px; padding:0; height:1px; line-height:1px; background:#CCCCCC;"></span>', 'create_users', '#' );

		add_submenu_page(
			$parent_slug,
			__( 'Pronamic iDEAL Options', 'pronamic_ideal' ),
			__( 'iDEAL Options', 'pronamic_ideal' ),
			'create_users',
            's2member_pronamic_ideal',
            array( $this, 'view_options_page' )
		);

		add_submenu_page(
			$parent_slug,
			__( 'Pronamic iDEAL Buttons Generator', 'pronamic_ideal' ),
			__( 'iDEAL Buttons', 'pronamic_ideal' ),
			'create_users',
			's2member_pronamic_ideal_buttons',
			array( $this, 'view_buttongen_page' )
		);
	}

    public function view_options_page() {
		return Pronamic_WordPress_IDeal_Admin::renderView( 's2member/settings' );
    }

    public function save_options_page() {
        if ( ! isset( $_POST['pronamic-ideal-s2member-options-nonce'] ) )
            return;

        if ( ! wp_verify_nonce( $_POST['pronamic-ideal-s2member-options-nonce'], 'pronamic-ideal-s2member-options') )
            return;

        // Clean options
        $pronamic_ideal_s2member_enabled = filter_input( INPUT_POST, 'pronamic_ideal_s2member_enabled', FILTER_VALIDATE_INT );
        $pronamic_ideal_s2member_chosen_configuration = filter_input( INPUT_POST, 'pronamic_ideal_s2member_chosen_configuration', FILTER_VALIDATE_INT );

        // Save options
        update_option( 'pronamic_ideal_s2member_enabled', $pronamic_ideal_s2member_enabled );
        update_option( 'pronamic_ideal_s2member_chosen_configuration', $pronamic_ideal_s2member_chosen_configuration );

        return;
    }

	public function view_buttongen_page() {
		return Pronamic_WordPress_IDeal_Admin::renderView( 's2member/buttons-generator' );
	}
}
