<?php

/**
 * Title: WordPress iDEAL admin
 * Description:
 * Copyright: Copyright (c) 2005 - 2015
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0.0
 * @since 1.0.0
 */
class Pronamic_WP_Pay_Admin {
	/**
	 * Constructs and initalize an admin object
	 */
	public function __construct() {
		$this->settings = new Pronamic_WP_Pay_Settings();

		// Actions
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		add_action( 'load-post.php', array( $this, 'maybe_test_payment' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Reports
		if ( version_compare( PHP_VERSION, '5.3', '>=' ) ) {
			$this->reports = new Pronamic_WP_Pay_Admin_Reports( $this );
		}

		// Dashboard
		$this->dashboard = new Pronamic_WP_Pay_Admin_Dashboard( $this );
	}

	//////////////////////////////////////////////////

	/**
	 * Admin initialize
	 */
	public function admin_init() {
		global $pronamic_ideal_errors;

		$pronamic_ideal_errors = array();

		// Maybe
		$this->maybe_download_private_certificate();
		$this->maybe_download_private_key();
		$this->maybe_create_pages();

		// Actions
		// Show license message if the license is not valid
		if ( ! ( defined( 'PRONAMIC_PAY_DEBUG' ) && PRONAMIC_PAY_DEBUG ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		}

		// Post types
		new Pronamic_WP_Pay_Admin_FormPostType();
		new Pronamic_WP_Pay_Admin_GatewayPostType();
		new Pronamic_WP_Pay_Admin_PaymentPostType();

		// Maybe update
		global $pronamic_pay_version;

		if ( get_option( 'pronamic_pay_version' ) !== $pronamic_pay_version ) {
			$this->upgrade();
		}
	}

	//////////////////////////////////////////////////

	public static function input_checkbox( $args ) {
		$defaults = array(
			'label_for' => '',
			'type'      => 'text',
			'label'     => '',
		);

		$args = wp_parse_args( $args, $defaults );

		$id    = $args['label_for'];
		$value = get_option( $id );

		$legend = sprintf(
			'<legend class="screen-reader-text"><span>%s</span></legend>',
			esc_html( $args['label'] )
		);

		$input = sprintf(
			'<input name="%s" id="%s" type="%s" value="%s" %s />',
			esc_attr( $id ),
			esc_attr( $id ),
			esc_attr( 'checkbox' ),
			esc_attr( '1' ),
			checked( $value, true, false )
		);

		$label = sprintf(
			'<label for="%s">%s %s</label>',
			esc_attr( $id ),
			$input,
			esc_html( $args['label'] )
		);

		printf(
			'<fieldset>%s %s</fieldset>',
			$legend,
			$label
		);
	}

	public static function sanitize_boolean( $value ) {
		return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
	}

	public static function dropdown_configs( $args ) {
		$defaults = array(
			'name'     => 'pronamic_pay_config_id',
			'echo'     => true,
			'selected' => false,
		);

		$args = wp_parse_args( $args, $defaults );

		// Output
		$output = '';

		// Dropdown
		$id       = $args['name'];
		$name     = $args['name'];
		$selected = $args['selected'];

		if ( false === $selected ) {
			$selected = get_option( $id );
		}

		$output .= sprintf(
			'<select id="%s" name="%s">',
			esc_attr( $id ),
			esc_attr( $name )
		);

		$options = Pronamic_WP_Pay_Plugin::get_config_select_options();

		foreach ( $options as $value => $name ) {
			$output .= sprintf(
				'<option value="%s" %s>%s</option>',
				esc_attr( $value ),
				selected( $value, $selected, false ),
				esc_html( $name )
			);
		}

		$output .= sprintf( '</select>' );

		// Return or echo
		if ( $args['echo'] ) {
			echo $output;
		} else {
			return $output;
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Upgrade
	 */
	public function upgrade() {
		global $pronamic_pay_version;

		$updates = array(
			'2.0.0',
			'2.0.1',
			'3.3.0',
			'3.7.0',
		);

		$version_current = get_option( 'pronamic_pay_version' );

		if ( $version_current ) {
			foreach ( $updates as $version ) {
				if ( version_compare( $version_current, $version, '<' ) ) {
					$file = plugin_dir_path( Pronamic_WP_Pay_Plugin::$file ) . 'includes/updates/update-' . $version . '.php';

					if ( is_readable( $file ) ) {
						include $file;

						update_option( 'pronamic_pay_db_version', $version );
					}
				}
			}
		}

		update_option( 'pronamic_pay_version', $pronamic_pay_version );
	}

	//////////////////////////////////////////////////

	/**
	 * Create pages
	 */
	private function create_pages( $pages, $parent = null ) {
		foreach ( $pages as $page ) {
			$post = array(
				'post_title'     => $page['post_title'],
				'post_name'      => $page['post_title'],
				'post_content'   => $page['post_content'],
				'post_status'    => 'publish',
				'post_type'      => 'page',
				'comment_status' => 'closed',
			);

			if ( isset( $parent ) ) {
				$post['post_parent'] = $parent;
			}

			$result = wp_insert_post( $post, true );

			if ( ! is_wp_error( $result ) ) {
				if ( isset( $page['post_meta'] ) ) {
					foreach ( $page['post_meta'] as $key => $value ) {
						update_post_meta( $result, $key, $value );
					}
				}

				if ( isset( $page['children'] ) ) {
					$this->create_pages( $page['children'], $result );
				}
			}
		}
	}

	/**
	 * Maybe create pages
	 */
	public function maybe_create_pages() {
		if ( filter_has_var( INPUT_POST, 'pronamic_pay_create_pages' ) && check_admin_referer( 'pronamic_pay_create_pages', 'pronamic_pay_nonce' ) ) {
			$this->create_pages( $_POST['pronamic_pay_pages'] );

			wp_redirect( add_query_arg( 'message', 1 ) );

			exit;
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Download private certificate
	 */
	public function maybe_download_private_certificate() {
		if ( filter_has_var( INPUT_POST, 'download_private_certificate' ) ) {
			$post_id = filter_input( INPUT_POST, 'post_ID', FILTER_SANITIZE_STRING );

			$filename = sprintf( 'ideal-private-certificate-%s.cer', $post_id );

			header( 'Content-Description: File Transfer' );
			header( 'Content-Disposition: attachment; filename=' . $filename );
			header( 'Content-Type: application/x-x509-ca-cert; charset=' . get_option( 'blog_charset' ), true );

			echo get_post_meta( $post_id, '_pronamic_gateway_ideal_private_certificate', true );

			exit;
		}
	}

	/**
	 * Download private key
	 */
	public function maybe_download_private_key() {
		if ( filter_has_var( INPUT_POST, 'download_private_key' ) ) {
			$post_id = filter_input( INPUT_POST, 'post_ID', FILTER_SANITIZE_STRING );

			$filename = sprintf( 'ideal-private-key-%s.key', $post_id );

			header( 'Content-Description: File Transfer' );
			header( 'Content-Disposition: attachment; filename=' . $filename );
			header( 'Content-Type: application/pgp-keys; charset=' . get_option( 'blog_charset' ), true );

			echo get_post_meta( $post_id, '_pronamic_gateway_ideal_private_key', true );

			exit;
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Maybe show an license message
	 */
	public function admin_notices() {
		if ( 'valid' !== get_option( 'pronamic_pay_license_status' ) ) {
			printf(
				'<div class="%s"><p>%s</p></div>',
				Pronamic_WP_Pay_Plugin::get_number_payments() > 20 ? 'error' : 'updated',
				sprintf(
					__( '<strong>Pronamic iDEAL</strong> &mdash; You have not <a href="%s">entered a (valid) Pronamic iDEAL license key</a>, get your license key from <a href="http://www.pronamic.eu/" target="_blank">Pronamic.eu</a>.', 'pronamic_ideal' ),
					add_query_arg( 'page', 'pronamic_pay_settings', get_admin_url( null, 'admin.php' ) )
				)
			);
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Enqueue admin scripts
	 */
	public function enqueue_scripts( $hook ) {
		$screen = get_current_screen();

		$enqueue  = false;
		$enqueue |= in_array( $screen->post_type, array(
			'pronamic_gateway',
			'pronamic_payment',
			'pronamic_pay_form',
			'pronamic_pay_gf',
		) );
		$enqueue |= 'dashboard' === $screen->id;
		$enqueue |= 'toplevel_page_gf_edit_forms' === $screen->id;
		$enqueue |= strpos( $hook, 'pronamic_pay' ) !== false;
		$enqueue |= strpos( $hook, 'pronamic_ideal' ) !== false;

		if ( $enqueue ) {
			$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			// Styles
			wp_enqueue_style(
				'proanmic-pay-admin',
				plugins_url( 'css/admin' . $min . '.css', Pronamic_WP_Pay_Plugin::$file ),
				array(),
				'3.6.6'
			);

			// Scripts
			wp_enqueue_script(
				'proanmic-pay-admin',
				plugins_url( 'js/admin' . $min . '.js', Pronamic_WP_Pay_Plugin::$file ),
				array( 'jquery' ),
				'3.6.6',
				true
			);
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Maybe test payment
	 */
	public function maybe_test_payment() {
		if ( filter_has_var( INPUT_POST, 'test_pay_gateway' ) && check_admin_referer( 'test_pay_gateway', 'pronamic_pay_test_nonce' ) ) {
			$id = filter_input( INPUT_POST, 'post_ID', FILTER_SANITIZE_NUMBER_INT );

			$gateway = Pronamic_WP_Pay_Plugin::get_gateway( $id );

			if ( $gateway ) {
				$amount = filter_input( INPUT_POST, 'test_amount', FILTER_VALIDATE_FLOAT, array(
					'flags'   => FILTER_FLAG_ALLOW_THOUSAND,
					'options' => array( 'decimal' => pronamic_pay_get_decimal_separator() ),
				) );

				$data = new Pronamic_WP_Pay_PaymentTestData( wp_get_current_user(), $amount );

				$payment = Pronamic_WP_Pay_Plugin::start( $id, $gateway, $data );

				$error = $gateway->get_error();

				if ( is_wp_error( $error ) ) {
					Pronamic_WP_Pay_Plugin::render_errors( $error );
				} else {
					$gateway->redirect( $payment );
				}

				exit;
			}
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Create the admin menu
	 */
	public function admin_menu() {
		add_menu_page(
			__( 'iDEAL', 'pronamic_ideal' ),
			__( 'iDEAL', 'pronamic_ideal' ),
			'manage_options',
			'pronamic_ideal',
			array( $this, 'page_dashboard' ),
			plugins_url( 'images/icon-16x16.png', Pronamic_WP_Pay_Plugin::$file )
		);

		// @see https://github.com/woothemes/woocommerce/blob/2.3.13/includes/admin/class-wc-admin-menus.php#L145
		$menu_title = __( 'Payments', 'pronamic_ideal' );

		$counts = wp_count_posts( 'pronamic_payment' );

		if ( isset( $counts, $counts->payment_pending ) && $counts->payment_pending > 0 ) {
			$menu_title = sprintf(
				__( 'Payments %s', 'pronamic_ideal' ),
				sprintf(
					'<span class="awaiting-mod update-plugins count-%s"><span class="processing-count">%s</span></span>',
					$counts->payment_pending,
					$counts->payment_pending
				)
			);
		}

		add_submenu_page(
			'pronamic_ideal',
			__( 'Payments', 'pronamic_ideal' ),
			$menu_title,
			'manage_options',
			'edit.php?post_type=pronamic_payment'
		);

		add_submenu_page(
			'pronamic_ideal',
			__( 'Configurations', 'pronamic_ideal' ),
			__( 'Configurations', 'pronamic_ideal' ),
			'manage_options',
			'edit.php?post_type=pronamic_gateway'
		);

		add_submenu_page(
			'pronamic_ideal',
			__( 'Payment Forms', 'pronamic_ideal' ),
			__( 'Payment Forms', 'pronamic_ideal' ),
			'manage_options',
			'edit.php?post_type=pronamic_pay_form'
		);

		add_submenu_page(
			'pronamic_ideal',
			__( 'Settings', 'pronamic_ideal' ),
			__( 'Settings', 'pronamic_ideal' ),
			'manage_options',
			'pronamic_pay_settings',
			array( $this, 'page_settings' )
		);

		add_submenu_page(
			'pronamic_ideal',
			__( 'Tools', 'pronamic_ideal' ),
			__( 'Tools', 'pronamic_ideal' ),
			'manage_options',
			'pronamic_pay_tools',
			array( $this, 'page_tools' )
		);

		global $submenu;

		if ( isset( $submenu['pronamic_ideal'] ) ) {
			$submenu['pronamic_ideal'][0][0] = __( 'Dashboard', 'pronamic_ideal' );
		}
	}

	//////////////////////////////////////////////////

	public function page_dashboard() { return $this->render_page( 'dashboard' ); }
	public function page_settings() { return $this->render_page( 'settings' ); }
	public function page_tools() { return $this->render_page( 'tools' ); }

	//////////////////////////////////////////////////

	/**
	 * Render the specified page
	 */
	public function render_page( $name ) {
		$result = false;

		$file = plugin_dir_path( Pronamic_WP_Pay_Plugin::$file ) . 'admin/page-' . $name . '.php';

		if ( is_readable( $file ) ) {
			include $file;

			$result = true;
		}

		return $result;
	}

	//////////////////////////////////////////////////

	/**
	 * Render the specified view
	 */
	public static function render_view( $name ) {
		$result = false;

		$file = plugin_dir_path( Pronamic_WP_Pay_Plugin::$file ) . 'views/' . $name . '.php';

		if ( is_readable( $file ) ) {
			include $file;

			$result = true;
		}

		return $result;
	}
}
