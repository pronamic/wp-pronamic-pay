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
		add_action( 'admin_init',                              array( __CLASS__, 'admin_init' ) );
		add_action( 'admin_menu',                              array( __CLASS__, 'admin_menu' ) );

		add_action( 'load-ideal_page_pronamic_ideal_payments', array( __CLASS__, 'load_payments_page' ) );
		
		add_action( 'load-toplevel_page_pronamic_ideal',       array( __CLASS__, 'maybe_test_payment' ) );

		add_action( 'admin_enqueue_scripts',                   array( __CLASS__, 'enqueue_scripts' ) );
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
		$is_pronamic_ideal = strpos( $hook, 'pronamic_ideal' ) !== false;
		$edit_gravity_forms = ( strpos( $hook, 'page_gf_new_form' ) ) !== false || ( strpos( $hook, 'page_gf_edit_forms' ) !== false );

		if ( $is_pronamic_ideal || $edit_gravity_forms ) {
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
	 * Load payments page
	 */
	public static function load_payments_page() {
		global $wp_list_table;
		
		$wp_list_table = new Pronamic_WordPress_IDeal_PaymentsListTable();
	}

	//////////////////////////////////////////////////

	/**
	 * Maybe test payment
	 */
	public static function maybe_test_payment() {
		global $pronamic_ideal_errors;

		if ( isset( $_POST['test_ideal_advanced_v3'] ) && check_admin_referer( 'test_ideal_advanced_v3', 'pronamic_ideal_nonce' ) ) {
			$id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_STRING );

			$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $id );
			
			$test = filter_input( INPUT_POST, 'test_ideal_advanced_v3', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY );
			$test = key( $test );
			
			$data = new Pronamic_WordPress_IDeal_IDealTestDataProxy( wp_get_current_user(), $test );
			
			$gateway = new Pronamic_Gateways_IDealAdvancedV3_Gateway( $configuration );
			
			$gateway->start( $data );

			$error = $gateway->get_error();
			
			if ( is_wp_error( $error ) ) {
				 $pronamic_ideal_errors[] = $error;
			} else {
				Pronamic_WordPress_IDeal_IDeal::create_payment( $configuration, $gateway, $data );

				$gateway->redirect();
			}
		}

		if ( isset( $_POST['test_ideal_advanced'] ) && check_admin_referer( 'test_ideal_advanced', 'pronamic_ideal_nonce' ) ) {
			$id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_STRING );

			$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $id );
			
			$test = filter_input( INPUT_POST, 'test_ideal_advanced', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY );
			$test = key( $test );
			
			$data = new Pronamic_WordPress_IDeal_IDealTestDataProxy( wp_get_current_user(), $test );
			
			$gateway = new Pronamic_Gateways_IDealAdvanced_Gateway( $configuration );

			$gateway->start( $data );

			$error = $gateway->get_error();
			
			if ( is_wp_error( $error ) ) {
				 $pronamic_ideal_errors[] = $error;
			} else {
				Pronamic_WordPress_IDeal_IDeal::create_payment( $configuration, $gateway, $data );

				$gateway->redirect();
			}
    	}

		if ( isset( $_POST['test_ideal_mollie'] ) && check_admin_referer( 'test_ideal_mollie', 'pronamic_ideal_nonce' ) ) {
			$id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_STRING );

			$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $id );
			
			$test = filter_input( INPUT_POST, 'test_amount', FILTER_VALIDATE_FLOAT );

			$data = new Pronamic_WordPress_IDeal_IDealTestDataProxy( wp_get_current_user(), $test );
			
			$gateway = new Pronamic_Gateways_Mollie_Gateway( $configuration );
			
			$gateway->start( $data );

			$error = $gateway->get_error();
			
			if ( is_wp_error( $error ) ) {
				$pronamic_ideal_errors[] = $error;
			} else {
				Pronamic_WordPress_IDeal_IDeal::create_payment( $configuration, $gateway, $data );

				$gateway->redirect();
			}
    	}

		if ( isset( $_POST['test_ideal_targetpay'] ) && check_admin_referer( 'test_ideal_targetpay', 'pronamic_ideal_nonce' ) ) {
			$id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_STRING );

			$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $id );
			
			$test = filter_input( INPUT_POST, 'test_amount', FILTER_VALIDATE_FLOAT );

			$data = new Pronamic_WordPress_IDeal_IDealTestDataProxy( wp_get_current_user(), $test );
			
			$gateway = new Pronamic_Gateways_TargetPay_Gateway( $configuration );
			
			$gateway->start( $data );

			$error = $gateway->get_error();
			
			if ( is_wp_error( $error ) ) {
				$pronamic_ideal_errors[] = $error;
			} else {
				Pronamic_WordPress_IDeal_IDeal::create_payment( $configuration, $gateway, $data );

				$gateway->redirect();
			}
    	}

    	if ( isset( $_POST['test_ideal_buckaroo'] ) && check_admin_referer( 'test_ideal_buckaroo', 'pronamic_ideal_nonce' ) ) {
			$id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_STRING );

			$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $id );
			
			$test = filter_input( INPUT_POST, 'test_amount', FILTER_VALIDATE_FLOAT );

			$data = new Pronamic_WordPress_IDeal_IDealTestDataProxy( wp_get_current_user(), $test );
			
			$gateway = new Pronamic_Gateways_Buckaroo_Gateway( $configuration );
			
			$gateway->start( $data );

			$error = $gateway->get_error();
			
			if ( is_wp_error( $error ) ) {
				$pronamic_ideal_errors[] = $error;
			} else {
				Pronamic_WordPress_IDeal_IDeal::create_payment( $configuration, $gateway, $data );

				$gateway->redirect();
			}
    	}

		if ( isset( $_POST['test_ideal_sisow'] ) && check_admin_referer( 'test_ideal_sisow', 'pronamic_ideal_nonce' ) ) {
			$id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_STRING );

			$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $id );
			
			$test = filter_input( INPUT_POST, 'test_amount', FILTER_VALIDATE_FLOAT );

			$data = new Pronamic_WordPress_IDeal_IDealTestDataProxy( wp_get_current_user(), $test );
			
			$gateway = new Pronamic_Gateways_Sisow_Gateway( $configuration );
			
			$gateway->start( $data );

			$error = $gateway->get_error();
			
			if ( is_wp_error( $error ) ) {
				$pronamic_ideal_errors[] = $error;
			} else {
				Pronamic_WordPress_IDeal_IDeal::create_payment( $configuration, $gateway, $data );

				$gateway->redirect();
			}
    	}

		if ( isset( $_POST['test_ideal_qantani'] ) && check_admin_referer( 'test_ideal_qantani', 'pronamic_ideal_nonce' ) ) {
			$id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_STRING );

			$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $id );
			
			$test = filter_input( INPUT_POST, 'test_amount', FILTER_VALIDATE_FLOAT );

			$data = new Pronamic_WordPress_IDeal_IDealTestDataProxy( wp_get_current_user(), $test );
			
			$gateway = new Pronamic_Gateways_Qantani_Gateway( $configuration );
			
			$gateway->start( $data );

			$error = $gateway->get_error();
			
			if ( is_wp_error( $error ) ) {
				$pronamic_ideal_errors[] = $error;
			} else {
				Pronamic_WordPress_IDeal_IDeal::create_payment( $configuration, $gateway, $data );

				$gateway->redirect();
			}
    	}

		if ( isset( $_POST['test_ogone_directlink'] ) && check_admin_referer( 'test_ogone_directlink', 'pronamic_ideal_nonce' ) ) {
			$id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_STRING );

			$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $id );
			
			$test = filter_input( INPUT_POST, 'test_amount', FILTER_VALIDATE_FLOAT );

			$data = new Pronamic_WordPress_IDeal_IDealTestDataProxy( wp_get_current_user(), $test );
			
			$gateway = new Pronamic_Gateways_Ogone_DirectLink_Gateway( $configuration );
			
			$gateway->start( $data );

			$error = $gateway->get_error();
			
			if ( is_wp_error( $error ) ) {
				$pronamic_ideal_errors[] = $error;
			} else {
				Pronamic_WordPress_IDeal_IDeal::create_payment( $configuration, $gateway, $data );

				$gateway->redirect();
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
			array( __CLASS__, 'pageIndex' ), 
			plugins_url( 'images/icon-16x16.png', Pronamic_WordPress_IDeal_Plugin::$file )
		);

		if ( false ) {
			add_submenu_page(
				'pronamic_ideal', 
				__( 'Gateways', 'pronamic_ideal' ), 
				__( 'Gateways', 'pronamic_ideal' ), 
				'pronamic_ideal',
				'edit.php?post_type=pronamic_gateway'
			);
		}

		add_submenu_page(
			'pronamic_ideal', 
			__( 'Payments', 'pronamic_ideal' ), 
			__( 'Payments', 'pronamic_ideal' ), 
			'pronamic_ideal_payments', 
			'pronamic_ideal_payments', 
			array( __CLASS__, 'pagePayments' )
		);

		if ( false ) {
			add_submenu_page(
				'pronamic_ideal', 
				__( 'Payments', 'pronamic_ideal' ), 
				__( 'Payments', 'pronamic_ideal' ), 
				'pronamic_ideal_payments',
				'edit.php?post_type=pronamic_payment'
			);
		}

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
			__( 'Pages Generator', 'pronamic_ideal' ), 
			__( 'Pages Generator', 'pronamic_ideal' ), 
			'pronamic_ideal_pages_generator', 
			'pronamic_ideal_pages_generator', 
			array( __CLASS__, 'pagePagesGenerator' )
		);

		add_submenu_page(
			'pronamic_ideal', 
			__( 'Status', 'pronamic_ideal' ), 
			__( 'Status', 'pronamic_ideal' ), 
			'pronamic_ideal_status', 
			'pronamic_ideal_status', 
			array( __CLASS__, 'page_status' )
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

		global $submenu;

		if ( isset( $submenu['pronamic_ideal'] ) ) {
			$submenu['pronamic_ideal'][0][0] = __( 'Configurations', 'pronamic_ideal' );
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Get the edit link for the specfied feed id
	 * 
	 * @param string $id
	 * @return string url
	 */
	public static function getLink( $page = null, $view = null, $id = null ) {
		$link = 'admin.php';

		if ( $page != null ) {
			$link = add_query_arg( 'page', $page, $link );
		}

		if ( $page != null ) {
			$link = add_query_arg( 'view', $view, $link );
		}

		if ( $id != null ) {
			$link = add_query_arg( 'id', $id, $link );
		}

		return $link;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the edit link for the specfied feed id
	 * 
	 * @param string $id
	 * @return string url
	 */
	public static function getConfigurationsLink() {
		return add_query_arg( array(
			'page' => 'pronamic_ideal' 
		), 'admin.php');
	}

	public static function getConfigurationEditLink($id = null) {
		return add_query_arg( array(
			'page' => 'pronamic_ideal',
			'view' => 'edit' ,
			'id'   => $id   
		), 'admin.php');
	}

	public static function getConfigurationTestsLink($id = null) {
		return add_query_arg( array(
			'page' => 'pronamic_ideal',
			'view' => 'tests',
			'id'   => $id
		), 'admin.php');
	}

	public static function getConfigurationDeleteLink($id = null) {
		return add_query_arg( array(
			'page'   => 'pronamic_ideal',
			'action' => 'delete',
			'id'     => $id
		), 'admin.php');
	}

	public static function getPaymentsLink($id = null) {
		return self::getLink( 'pronamic_ideal_payments' );
	}

	public static function getPaymentDetailsLink($id = null) {
		return self::getLink( 'pronamic_ideal_payments', 'details', $id );
	}

	//////////////////////////////////////////////////

	public static function pageIndex() {
		$view = filter_input( INPUT_GET, 'view', FILTER_SANITIZE_STRING );

		switch($view) {
			case 'edit':
				return self::renderView( 'configuration-edit' );
			case 'tests':
				return self::renderView( 'configuration-tests' );
			default:
				return self::pageConfigurations();
		}
	}

	public static function pageConfigurations() {
		return self::renderView( 'configurations' );
	}

	public static function pageSettings() {
		self::renderView( 'settings' );
	}

	public static function pagePagesGenerator() {
		self::renderView( 'pages-generator' );
	}

	public static function page_status() {
		self::renderView( 'status' );
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

	public static function pagePayments() {
		$view = filter_input( INPUT_GET, 'view', FILTER_SANITIZE_STRING );

		switch($view) {
			case 'details':
				return self::renderView( 'payment' );
			case 'edit':
				return self::renderView( 'payment-edit' );
			default:
				return self::renderView( 'payments' );
		}
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
