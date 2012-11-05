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
		add_action( 'admin_menu',                              array( __CLASS__, 'admin_menu' ) );

		add_action( 'load-ideal_page_pronamic_ideal_payments', array( __CLASS__, 'loadPaymentsPage' ) );
		
		add_action( 'load-toplevel_page_pronamic_ideal',       array( __CLASS__, 'maybeTestPayment' ) );

		add_action( 'admin_enqueue_scripts',                   array( __CLASS__, 'enqueueAdminScripts' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Enqueue admin scripts
	 */
	public static function enqueueAdminScripts( $hook ) {
		$is_pronamic_ideal = strpos( $hook, 'pronamic_ideal' ) !== false;
		$edit_gravity_forms = ( strpos( $hook, 'page_gf_new_form' ) ) !== false || ( strpos( $hook, 'page_gf_edit_forms' ) !== false );

		if ( $is_pronamic_ideal || $edit_gravity_forms ) {
			// Styles
			wp_enqueue_style(
				'proanmic_ideal_admin',
				plugins_url( 'css/admin.css', Pronamic_WordPress_IDeal_Plugin::$file )
			);
	
			// Scripts
			wp_enqueue_script(
				'proanmic_ideal_admin',
				plugins_url( 'js/admin.js', Pronamic_WordPress_IDeal_Plugin::$file ),
				array( 'jquery' )
			);
		}
	}

	//////////////////////////////////////////////////
	
	/**
	 * Load payments page
	 */
	public static function loadPaymentsPage() {
		global $wp_list_table;
		
		$wp_list_table = new Pronamic_WordPress_IDeal_PaymentsListTable();
	}

	//////////////////////////////////////////////////

	/**
	 * Maybe test payment
	 */
	public static function maybeTestPayment() {
		if(isset($_POST['test']) && check_admin_referer('test', 'pronamic_ideal_nonce')) {
			$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);

			$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById($id);

			if($configuration != null) {
				$variant = $configuration->getVariant();
		
				if($variant !== null) {
					$data = filter_input(INPUT_POST, 'test', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
					$testCase = key($data);
					
					$name = sprintf(__('Test Case %s', 'pronamic_ideal'), $testCase);

					$entranceCode = uniqid();
					$purchaseId = $name;

					$transaction = new Pronamic_Gateways_IDealAdvanced_Transaction();
					$transaction->setAmount($testCase); 
					$transaction->setCurrency('EUR');
					$transaction->setExpirationPeriod('PT1H');
					$transaction->setLanguage('nl');
					$transaction->setEntranceCode($entranceCode);
					$transaction->setDescription($name);
					$transaction->setPurchaseId($purchaseId);
		
					$payment = new Pronamic_WordPress_IDeal_Payment();
					$payment->configuration = $configuration;
					$payment->transaction = $transaction;
					// $payment->setSource('pronamic_ideal', uniqid());
		
					$updated = Pronamic_WordPress_IDeal_PaymentsRepository::updatePayment($payment);
					
					$issuerId = filter_input(INPUT_POST, 'pronamic_ideal_issuer_id', FILTER_SANITIZE_STRING);

					$url = Pronamic_WordPress_IDeal_IDeal::handleTransaction($issuerId, $payment, $variant);

					wp_redirect($url, 303);

					exit;
				}
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

		add_submenu_page(
			'pronamic_ideal', 
			__( 'Payments', 'pronamic_ideal' ), 
			__( 'Payments', 'pronamic_ideal' ), 
			'pronamic_ideal_payments', 
			'pronamic_ideal_payments', 
			array( __CLASS__, 'pagePayments' )
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
			__( 'Pages Generator', 'pronamic_ideal' ), 
			__( 'Pages Generator', 'pronamic_ideal' ), 
			'pronamic_ideal_pages_generator', 
			'pronamic_ideal_pages_generator', 
			array( __CLASS__, 'pagePagesGenerator' )
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
			'page' => Pronamic_WordPress_IDeal_Plugin::SLUG 
		), 'admin.php');
	}

	public static function getConfigurationEditLink($id = null) {
		return add_query_arg( array(
			'page' => Pronamic_WordPress_IDeal_Plugin::SLUG ,
			'view' => 'edit' ,
			'id'   => $id   
		), 'admin.php');
	}

	public static function getConfigurationTestsLink($id = null) {
		return add_query_arg( array(
			'page' => Pronamic_WordPress_IDeal_Plugin::SLUG,
			'view' => 'tests',
			'id'   => $id
		), 'admin.php');
	}

	public static function getConfigurationDeleteLink($id = null) {
		return add_query_arg( array(
			'page'   => Pronamic_WordPress_IDeal_Plugin::SLUG,
			'action' => 'delete',
			'id'     => $id
		), 'admin.php');
	}

	public static function getPaymentsLink($id = null) {
		return self::getLink( Pronamic_WordPress_IDeal_Plugin::SLUG . '_payments' );
	}

	public static function getPaymentDetailsLink($id = null) {
		return self::getLink( Pronamic_WordPress_IDeal_Plugin::SLUG . '_payments', 'details', $id );
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
