<?php
/**
 * Install
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Admin
 */

namespace Pronamic\WordPress\Pay\Admin;

use Pronamic\WordPress\Pay\Forms\FormPostType;
use Pronamic\WordPress\Pay\Payments\PaymentPostType;
use Pronamic\WordPress\Pay\Plugin;

/**
 * WordPress admin install
 *
 * @author Remco Tolsma
 * @version 3.7.0
 * @since 3.7.0
 */
class Install {
	/**
	 * Database updates.
	 *
	 * @var array
	 */
	private $db_updates = array(
		'2.0.0',
		'2.0.1',
		'3.3.0',
		'3.7.0',
		'3.7.2',
	);

	/**
	 * Constructs and initializes an install object.
	 *
	 * @see https://github.com/woothemes/woocommerce/blob/2.4.3/includes/class-wc-install.php
	 *
	 * @param Plugin      $plugin Plugin.
	 * @param AdminModule $admin  Admin.
	 */
	public function __construct( Plugin $plugin, AdminModule $admin ) {
		$this->plugin = $plugin;
		$this->admin  = $admin;

		// Actions.
		add_action( 'admin_init', array( $this, 'admin_init' ), 5 );
	}

	/**
	 * Admin intialize.
	 */
	public function admin_init() {
		// Install.
		if ( get_option( 'pronamic_pay_version' ) !== $this->plugin->get_version() ) {
			$this->install();
		}

		// Maybe update database.
		if ( filter_has_var( INPUT_GET, 'pronamic_pay_update_db' ) && wp_verify_nonce( filter_input( INPUT_GET, 'pronamic_pay_nonce', FILTER_SANITIZE_STRING ), 'pronamic_pay_update_db' ) ) {
			$this->update_db();

			$this->admin->notices->remove_notice( 'update_db' );

			$this->redirect_to_about();
		}
	}

	/**
	 * Install.
	 */
	private function install() {
		// Roles.
		$this->create_roles();

		// Rewrite Rules.
		flush_rewrite_rules();

		// Database update.
		$version = $this->plugin->get_version();

		$parts = explode( '.', $version );

		$major_version = implode( '.', array_slice( $parts, 0, 1 ) );
		$minor_version = implode( '.', array_slice( $parts, 0, 2 ) );

		$current_version    = get_option( 'pronamic_pay_version', null );
		$current_db_version = get_option( 'pronamic_pay_db_version', null );

		if (
			$current_db_version
				&&
			(
				// Check for old database version notation without dots, for example `366`.
				false === strpos( $current_db_version, '.' )
					||
				version_compare( $current_db_version, max( $this->db_updates ), '<' )
			)
		) {
			$this->admin->notices->add_notice( 'update_db' );
		}

		// Redirect.
		if ( null === $current_version ) {
			// No version? This is a new install :).
			$url = add_query_arg(
				array(
					'page' => 'pronamic-pay-about',
					'tab'  => 'getting-started',
				), admin_url( 'index.php' )
			);

			set_transient( 'pronamic_pay_admin_redirect', $url, 3600 );
		} elseif ( version_compare( $current_version, $minor_version, '<' ) ) {
			// Show welcome screen for minor updates only.
			$url = add_query_arg(
				array(
					'page' => 'pronamic-pay-about',
					'tab'  => 'new',
				), admin_url( 'index.php' )
			);

			set_transient( 'pronamic_pay_admin_redirect', $url, 3600 );
		}

		// Update version.
		update_option( 'pronamic_pay_version', $version );
	}

	/**
	 * Create roles.
	 *
	 * @see https://codex.wordpress.org/Function_Reference/register_post_type
	 * @see https://github.com/woothemes/woocommerce/blob/v2.2.3/includes/class-wc-install.php#L519-L562
	 * @see https://github.com/woothemes/woocommerce/blob/v2.2.3/includes/class-wc-post-types.php#L245
	 */
	private function create_roles() {
		// Payer role.
		add_role(
			'payer', __( 'Payer', 'pronamic_ideal' ), array(
				'read' => true,
			)
		);

		// @see https://developer.wordpress.org/reference/functions/wp_roles/.
		$roles = wp_roles();

		// Payments.
		$payment_capabilities = PaymentPostType::get_capabilities();

		unset( $payment_capabilities['publish_posts'] );
		unset( $payment_capabilities['create_posts'] );

		foreach ( $payment_capabilities as $capability ) {
			$roles->add_cap( 'administrator', $capability );
		}

		// Forms.
		$form_capabilities = FormPostType::get_capabilities();

		foreach ( $form_capabilities as $capability ) {
			$roles->add_cap( 'administrator', $capability );
		}
	}

	/**
	 * Update database.
	 */
	public function update_db() {
		$current_db_version = get_option( 'pronamic_pay_db_version', null );

		if ( $current_db_version ) {
			foreach ( $this->db_updates as $version ) {
				if ( ! version_compare( $current_db_version, $version, '<' ) ) {
					continue;
				}

				$file = plugin_dir_path( $this->plugin->get_file() ) . 'includes/updates/update-' . $version . '.php';

				if ( is_readable( $file ) ) {
					include $file;

					update_option( 'pronamic_pay_db_version', $version );
				}
			}
		}

		update_option( 'pronamic_pay_db_version', $this->plugin->get_version() );
	}

	/**
	 * Redirect to about.
	 */
	private function redirect_to_about() {
		wp_safe_redirect( admin_url( 'index.php?page=pronamic-pay-about' ) );

		exit;
	}
}
