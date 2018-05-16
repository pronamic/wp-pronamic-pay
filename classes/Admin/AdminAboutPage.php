<?php
/**
 * Admin About Page
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Admin
 */

namespace Pronamic\WordPress\Pay\Admin;

use Pronamic\WordPress\Pay\Plugin;

/**
 * WordPress admin about
 *
 * @author Remco Tolsma
 * @version 3.7.0
 * @since 3.7.0
 */
class AdminAboutPage {
	/**
	 * Plugin.
	 *
	 * @var Plugin
	 */
	private $plugin;

	/**
	 * Admin.
	 *
	 * @var AdminModule
	 */
	private $admin;

	/**
	 * Constructs and initializes admin about page object.
	 *
	 * @see https://github.com/WordImpress/Give/blob/1.1/includes/admin/dashboard-widgets.php
	 * @see https://github.com/woothemes/woocommerce/blob/2.3.13/includes/admin/class-wc-admin.php
	 * @see https://github.com/woothemes/woocommerce/blob/2.3.13/includes/admin/class-wc-admin-dashboard.php
	 *
	 * @param Plugin      $plugin Plugin.
	 * @param AdminModule $admin  Admin.
	 */
	public function __construct( Plugin $plugin, AdminModule $admin ) {
		$this->plugin = $plugin;
		$this->admin  = $admin;

		// Actions.
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_head', array( $this, 'admin_head' ) );
	}

	/**
	 * Add admin menus/screens.
	 */
	public function admin_menu() {
		$page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );

		if ( 'pronamic-pay-about' !== $page ) {
			return;
		}

		$hook_suffix = add_dashboard_page(
			__( 'About Pronamic Pay', 'pronamic_ideal' ),
			__( 'Welcome to Pronamic Pay', 'pronamic_ideal' ),
			'manage_options',
			$page,
			array( $this, 'page_about' )
		);

		if ( false === $hook_suffix ) {
			return;
		}

		add_action( 'admin_print_styles-' . $hook_suffix, array( $this, 'admin_css' ) );
	}

	/**
	 * Admin head.
	 */
	public function admin_head() {
		remove_submenu_page( 'index.php', 'pronamic-pay-about' );
	}

	/**
	 * Admin CSS.
	 */
	public function admin_css() {
		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_style(
			'proanmic-pay-admin-about',
			plugins_url( 'css/admin-about' . $min . '.css', $this->plugin->get_file() ),
			array(),
			$this->plugin->get_version()
		);
	}

	/**
	 * Page about.
	 */
	public function page_about() {
		$this->admin->render_page( 'about' );
	}
}
