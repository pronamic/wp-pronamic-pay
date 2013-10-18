<?php

/**
 * Title: WordPress iDEAL admin
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WordPress_IDeal_Admin {
	/**
	 * Bootstrap
	 */
	public static function bootstrap() {
		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
		
		add_action( 'load-post.php', array( __CLASS__, 'maybe_test_payment' ) );

		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
		
		add_action( 'pronamic_pay_upgrade', array( __CLASS__, 'upgrade' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Admin initialize
	 */
	public static function admin_init() {
		global $pronamic_ideal_errors;

		$pronamic_ideal_errors = array();
		
		self::maybe_download_private_certificate();
		self::maybe_download_private_key();

		self::settings_init();

		// Maybe update
		global $pronamic_ideal_db_version;

		if ( get_option( 'pronamic_ideal_db_version' ) != $pronamic_ideal_db_version ) {
			do_action( 'pronamic_pay_upgrade', $pronamic_ideal_db_version );

			update_option( 'pronamic_ideal_db_version', $pronamic_ideal_db_version );
		}
	}

	//////////////////////////////////////////////////

	public static function pre_update_option_license_key( $newvalue, $oldvalue ) {
		if ( $newvalue != $oldvalue ) {
			$newvalue = md5( trim( $newvalue ) );
		}

		return $newvalue;
	}

	public static function settings_init() {
		add_filter( sprintf( 'pre_update_option_%s', 'pronamic_pay_license_key' ), array( __CLASS__, 'pre_update_option_license_key' ) );
		
		// Settings - General
		add_settings_section(
			'pronamic_pay_general', // id
			__( 'General', 'pronamic_ideal' ), // title
			array( __CLASS__, 'settings_section' ), // callback
			'pronamic_pay' // page
		);
		
		add_settings_field(
			'pronamic_pay_license_key', // id
			__( 'Support License Key', 'pronamic_ideal' ), // title
			array( __CLASS__, 'input_element' ), // callback
			'pronamic_pay', // page
			'pronamic_pay_general', // section
			array( 'type' => 'password', 'label_for' => 'pronamic_pay_license_key' ) // args
		);
		
		register_setting( 'pronamic_pay', 'pronamic_pay_license_key' );

		// Settings - Pages
		add_settings_section(
			'pronamic_pay_pages', // id
			__( 'Payment Status Pages', 'pronamic_ideal' ), // title
			array( __CLASS__, 'settings_section' ), // callback
			'pronamic_pay' // page
		);
		
		$pages = array(
			'error'     => __( 'Error', 'pronamic_ideal' ),
			'cancel'    => __( 'Canceled', 'pronamic_ideal' ),
			'unknown'   => __( 'Unknown', 'pronamic_ideal' ),
			'expired'   => __( 'Expired', 'pronamic_ideal' ),
			'completed' => __( 'Completed', 'pronamic_ideal' )
		);

		foreach ( $pages as $key => $label ) {
			$id = sprintf( 'pronamic_pay_%s_page_id', $key );

			add_settings_field(
				$id, // id
				$label, // title
				array( __CLASS__, 'input_page' ), // callback
				'pronamic_pay', // page
				'pronamic_pay_pages', // section
				array( 'label_for' => $id ) // args
			);

			register_setting( 'pronamic_pay', $id );
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Settings section
	 */
	public static function settings_section() {

	}
	
	public static function input_checkbox( $args ) {
		$defaults = array (
			'label_for' => '',
			'type'      => 'text',
			'label'     => ''
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
		$defaults = array (
			'name'     => 'pronamic_pay_config_id',
			'echo'     => true,
			'selected' => false
		);

		$args = wp_parse_args( $args, $defaults );

		// Output
		$output = '';

		// Dropdown
		$id       = $args['name'];
		$name     = $args['name'];
		$selected = $args['selected'];
		
		if ( $selected === false ) {
			$selected = get_option( $id );
		}

		$output .= sprintf(
			'<select id="%s" name="%s">',
			esc_attr( $id ),
			esc_attr( $name )
		);

		$options = Pronamic_WordPress_IDeal_IDeal::get_config_select_options();
		
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

	/**
	 * Input text
	 *
	 * @param array $args
	 */
	public static function input_element( $args ) {
		$defaults = array (
			'type' => 'text'
		);
		
		$args = wp_parse_args( $args, $defaults );

		printf(
			'<input name="%s" id="%s" type="%s" value="%s" class="%s" />',
			esc_attr( $args['label_for'] ),
			esc_attr( $args['label_for'] ),
			esc_attr( $args['type'] ),
			esc_attr( get_option( $args['label_for'] ) ),
			'regular-text'
		);
	}

	/**
	 * Input page
	 *
	 * @param array $args
	 */
	public static function input_page( $args ) {
		$name = $args['label_for'];

		wp_dropdown_pages( array(
			'name' => $name,
			'selected' => get_option( $name, '' ),
			'show_option_none' => __( '&mdash; Select a page &mdash;', 'pronamic_companies' )
		) );
	}

	//////////////////////////////////////////////////

	/**
	 * Upgrade
	 */
	public static function upgrade() {
		require_once Pronamic_WordPress_IDeal_Plugin::$dirname . '/admin/includes/upgrade.php';

		$db_version = get_option( 'pronamic_ideal_db_version' );

		if ( $db_version < 140 ) {
			orbis_ideal_upgrade_140();
		}
	}

	/**
	 * Download private certificate
	 */
	public static function maybe_download_private_certificate() {
		if ( isset( $_POST['download_private_certificate'] ) ) {
			$id = filter_input( INPUT_POST, 'pronamic_ideal_configuration_id', FILTER_SANITIZE_STRING );

			$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $id );

			if ( ! empty( $configuration ) ) {
				$filename = 'ideal-private-certificate-' . $id . '.cer';

				header( 'Content-Description: File Transfer' );
				header( 'Content-Disposition: attachment; filename=' . $filename );
				header( 'Content-Type: application/x-x509-ca-cert; charset=' . get_option( 'blog_charset' ), true );

				echo $configuration->privateCertificate;

				exit;
			}
		}
	}

	/**
	 * Download private key
	 */
	public static function maybe_download_private_key() {
		if ( isset( $_POST['download_private_key'] ) ) {
			$id = filter_input( INPUT_POST, 'pronamic_ideal_configuration_id', FILTER_SANITIZE_STRING );

			$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $id );

			if ( ! empty( $configuration ) ) {
				$filename = 'ideal-private-key-' . $id . '.key';

				header( 'Content-Description: File Transfer' );
				header( 'Content-Disposition: attachment; filename=' . $filename );
				header( 'Content-Type: application/pgp-keys; charset=' . get_option( 'blog_charset' ), true );

				echo $configuration->privateKey;

				exit;
			}
		}
	}
	
	//////////////////////////////////////////////////

	/**
	 * Enqueue admin scripts
	 */
	public static function enqueue_scripts( $hook ) {
		$screen = get_current_screen();

		$enqueue  = false;
		$enqueue |= $screen->post_type == 'pronamic_gateway';
		$enqueue |= $screen->post_type == 'pronamic_payment';
		$enqueue |= $screen->post_type == 'pronamic_pay_gf';
		$enqueue |= strpos( $hook, 'pronamic_ideal' ) !== false;

		if ( $enqueue ) {
			// Styles
			wp_enqueue_style(
				'proanmic_ideal_admin',
				plugins_url( 'admin/css/admin.css', Pronamic_WordPress_IDeal_Plugin::$file )
			);
	
			// Scripts
			wp_enqueue_script(
				'proanmic_ideal_admin',
				plugins_url( 'admin/js/admin.js', Pronamic_WordPress_IDeal_Plugin::$file ),
				array( 'jquery' )
			);
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Maybe test payment
	 */
	public static function maybe_test_payment() {
		if ( filter_has_var( INPUT_POST, 'test_pay_gateway' ) && check_admin_referer( 'test_pay_gateway', 'pronamic_pay_test_nonce' ) ) {
			$id = filter_input( INPUT_POST, 'post_ID', FILTER_SANITIZE_NUMBER_INT );

			$gateway = Pronamic_WordPress_IDeal_IDeal::get_gateway( $id );

			if ( $gateway ) {
				$amount = filter_input( INPUT_POST, 'test_amount', FILTER_VALIDATE_FLOAT );

				$data = new Pronamic_WP_Pay_PaymentTestData( wp_get_current_user(), $amount );
	
				Pronamic_WordPress_IDeal_IDeal::start( $id, $gateway, $data);
	
				$error = $gateway->get_error();
				
				if ( is_wp_error( $error ) ) {
					 Pronamic_WordPress_IDeal_IDeal::render_errors( $error );
				} else {
					$gateway->redirect();
				}

				exit;
			}
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Create the admin menu
	 */
	public static function admin_menu() {
		add_menu_page(
			__( 'iDEAL', 'pronamic_ideal' ),
			__( 'iDEAL', 'pronamic_ideal' ),
			'pronamic_ideal', 
			'pronamic_ideal', 
			array( __CLASS__, 'page_dashboard' ), 
			plugins_url( 'images/icon-16x16.png', Pronamic_WordPress_IDeal_Plugin::$file )
		);

		add_submenu_page(
			'pronamic_ideal', 
			__( 'Payments', 'pronamic_ideal' ), 
			__( 'Payments', 'pronamic_ideal' ), 
			'pronamic_ideal_payments',
			'edit.php?post_type=pronamic_payment'
		);

		add_submenu_page(
			'pronamic_ideal', 
			__( 'Configurations', 'pronamic_ideal' ), 
			__( 'Configurations', 'pronamic_ideal' ), 
			'pronamic_ideal',
			'edit.php?post_type=pronamic_gateway'
		);

		add_submenu_page(
			'pronamic_ideal', 
			__( 'Settings', 'pronamic_ideal' ), 
			__( 'Settings', 'pronamic_ideal' ), 
			'pronamic_ideal_settings', 
			'pronamic_ideal_settings', 
			array( __CLASS__, 'pageSettings' )
		);

		add_submenu_page(
			'pronamic_ideal', 
			__( 'System Status', 'pronamic_ideal' ), 
			__( 'System Status', 'pronamic_ideal' ), 
			'pronamic_ideal_status', 
			'pronamic_pay_system_status', 
			array( __CLASS__, 'page_system_status' )
		);

		add_submenu_page(
			'pronamic_ideal', 
			__( 'iDEAL Status', 'pronamic_ideal' ), 
			__( 'iDEAL Status', 'pronamic_ideal' ), 
			'pronamic_ideal_status', 
			'pronamic_ideal_status', 
			array( __CLASS__, 'page_ideal_status' )
		);

		add_submenu_page(
			'pronamic_ideal', 
			__( 'Gateway Providers', 'pronamic_ideal' ), 
			__( 'Providers', 'pronamic_ideal' ), 
			'pronamic_ideal_providers', 
			'pronamic_ideal_providers', 
			array( __CLASS__, 'page_providers' )
		);

		add_submenu_page(
			'pronamic_ideal', 
			__( 'Variants', 'pronamic_ideal' ), 
			__( 'Variants', 'pronamic_ideal' ), 
			'pronamic_ideal_variants', 
			'pronamic_ideal_variants', 
			array( __CLASS__, 'pageVariants' )
		);

		add_submenu_page(
			'pronamic_ideal', 
			__( 'Documentation', 'pronamic_ideal' ), 
			__( 'Documentation', 'pronamic_ideal' ), 
			'pronamic_ideal_documentation', 
			'pronamic_ideal_documentation', 
			array( __CLASS__, 'pageDocumentation' )
		);

		add_submenu_page(
			'pronamic_ideal', 
			__( 'Branding', 'pronamic_ideal' ), 
			__( 'Branding', 'pronamic_ideal' ), 
			'pronamic_ideal_branding', 
			'pronamic_ideal_branding', 
			array( __CLASS__, 'page_branding' )
		);

		add_submenu_page(
			'pronamic_ideal', 
			__( 'Pages Generator', 'pronamic_ideal' ), 
			__( 'Pages Generator', 'pronamic_ideal' ), 
			'pronamic_ideal_pages_generator', 
			'pronamic_ideal_pages_generator', 
			array( __CLASS__, 'pagePagesGenerator' )
		);

		global $submenu;

		if ( isset( $submenu['pronamic_ideal'] ) ) {
			$submenu['pronamic_ideal'][0][0] = __( 'Dashboard', 'pronamic_ideal' );
		}
	}

	//////////////////////////////////////////////////

	public static function page_dashboard() {
		return self::renderView( 'dashboard' );
	}

	public static function pageSettings() {
		self::renderView( 'settings' );
	}

	public static function pagePagesGenerator() {
		self::renderView( 'pages-generator' );
	}

	public static function page_system_status() {
		self::renderView( 'status-system' );
	}

	public static function page_ideal_status() {
		self::renderView( 'status-ideal' );
	}

	public static function page_providers() {
		self::renderView( 'providers' );
	}

	public static function pageVariants() {
		self::renderView( 'variants' );
	}

	public static function pageDocumentation() {
		self::renderView( 'documentation' );
	}

	public static function page_branding() {
		self::renderView( 'branding' );
	}

	//////////////////////////////////////////////////

	/**
	 * Render the specified view
	 */
	public static function renderView($name) {
		$result = false;

		$file = plugin_dir_path( Pronamic_WordPress_IDeal_Plugin::$file ) . 'views/' . $name . '.php';

		if ( is_readable( $file ) ) {
			include $file;
			
			$result = true;
		} 
		
		return $result;
	}
}
