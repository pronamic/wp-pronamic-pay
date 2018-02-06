<?php

namespace Pronamic\WordPress\Pay\Admin;

use Pronamic\WordPress\Pay\Plugin;

/**
 * Title: WordPress admin about
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 3.7.0
 * @since 3.7.0
 */
class AdminAboutPage {
	private $plugin;

	private $admin;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes admin about page object.
	 *
	 * @see https://github.com/WordImpress/Give/blob/1.1/includes/admin/dashboard-widgets.php
	 * @see https://github.com/woothemes/woocommerce/blob/2.3.13/includes/admin/class-wc-admin.php
	 * @see https://github.com/woothemes/woocommerce/blob/2.3.13/includes/admin/class-wc-admin-dashboard.php
	 *
	 * @param Plugin      $plugin
	 * @param AdminModule $admin
	 */
	public function __construct( Plugin $plugin, AdminModule $admin ) {
		$this->plugin = $plugin;
		$this->admin  = $admin;

		// Actions
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_head', array( $this, 'admin_head' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Add admin menus/screens
	 */
	public function admin_menu() {
		$page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );

		if ( 'pronamic-pay-about' === $page ) {
			$hook_suffix = add_dashboard_page(
				__( 'About Pronamic Pay', 'pronamic_ideal' ),
				__( 'Welcome to Pronamic Pay', 'pronamic_ideal' ),
				'manage_options',
				$page,
				array( $this, 'page_about' )
			);

			add_action( 'admin_print_styles-' . $hook_suffix, array( $this, 'admin_css' ) );
		}
	}

	public function admin_head() {
		remove_submenu_page( 'index.php', 'pronamic-pay-about' );
	}

	public function admin_css() {
		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_style(
			'proanmic-pay-admin-about',
			plugins_url( 'css/admin-about' . $min . '.css', Plugin::$file ),
			array(),
			$this->plugin->get_version()
		);
	}

	/**
	 * Page about
	 */
	public function page_about() {
		$this->admin->render_page( 'about' );
	}
}
