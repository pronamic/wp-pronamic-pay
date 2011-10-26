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
		add_action('admin_menu', array(__CLASS__, 'adminMenu'));

		add_action('load-ideal_page_pronamic_ideal_payments', array(__CLASS__, 'loadPaymentsPage'));

		// Styles
		wp_register_style(
			'proanmic_ideal_admin' , 
			plugins_url('css/admin.css', Pronamic_WordPress_IDeal_Plugin::$file)
		);

		// Scripts
		wp_register_script(
			'proanmic_ideal_admin' , 
			plugins_url('js/admin.js', Pronamic_WordPress_IDeal_Plugin::$file) ,
			array('jquery')
		);

		add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueueAdminScripts'));
	}

	//////////////////////////////////////////////////

	/**
	 * Enqueue admin scripts
	 */
	public static function enqueueAdminScripts($hook) {
		$isPronamicIDeal = strpos($hook, 'pronamic_ideal') !== false;
		$editGravityForms = (strpos($hook, 'page_gf_new_form')) !== false || (strpos($hook, 'page_gf_edit_forms') !== false);

		if($isPronamicIDeal || $editGravityForms) {
			wp_enqueue_style('proanmic_ideal_admin');
			wp_enqueue_script('proanmic_ideal_admin');
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
	 * Create the admin menu
	 * 
	 * @param array $menus
	 */
	public static function adminMenu($menus) {
		add_menu_page(
			$pageTitle = __('iDEAL', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) ,
			$menuTitle = __('iDEAL', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) ,
			$capability = 'manage_options' , 
			$menuSlug = Pronamic_WordPress_IDeal_Plugin::SLUG , 
			$function = array(__CLASS__, 'pageIndex') , 
			$iconUrl = plugins_url('images/icon-16x16.png', Pronamic_WordPress_IDeal_Plugin::$file)
		);

		add_submenu_page(
			$parentSlug = Pronamic_WordPress_IDeal_Plugin::SLUG , 
			$pageTitle = __('Payments', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) , 
			$menuTitle = __('Payments', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) , 
			$capability = 'manage_options' , 
			$menuSlug = Pronamic_WordPress_IDeal_Plugin::SLUG . '_payments' , 
			$function = array(__CLASS__, 'pagePayments')
		);

		add_submenu_page(
			$parentSlug = Pronamic_WordPress_IDeal_Plugin::SLUG , 
			$pageTitle = __('Settings', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) , 
			$menuTitle = __('Settings', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) , 
			$capability = 'manage_options' , 
			$menuSlug = Pronamic_WordPress_IDeal_Plugin::SLUG . '_settings' , 
			$function = array(__CLASS__, 'pageSettings')
		);

		add_submenu_page(
			$parentSlug = Pronamic_WordPress_IDeal_Plugin::SLUG , 
			$pageTitle = __('Variants', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) , 
			$menuTitle = __('Variants', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) , 
			$capability = 'manage_options' , 
			$menuSlug = Pronamic_WordPress_IDeal_Plugin::SLUG . '_variants' , 
			$function = array(__CLASS__, 'pageVariants')
		);

		add_submenu_page(
			$parentSlug = Pronamic_WordPress_IDeal_Plugin::SLUG , 
			$pageTitle = __('Documentation', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) , 
			$menuTitle = __('Documentation', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) , 
			$capability = 'manage_options' , 
			$menuSlug = Pronamic_WordPress_IDeal_Plugin::SLUG . '_documentation' , 
			$function = array(__CLASS__, 'pageDocumentation')
		);

		global $submenu;

		if(isset($submenu[Pronamic_WordPress_IDeal_Plugin::SLUG])) {
			$submenu[Pronamic_WordPress_IDeal_Plugin::SLUG][0][0] = __('Configurations', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN);
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Get the edit link for the specfied feed id
	 * 
	 * @param string $id
	 * @return string url
	 */
	public static function getLink($page = null, $view = null, $id = null) {
		$link = 'admin.php';

		if($page != null) {
			$link = add_query_arg('page', $page, $link);
		}

		if($page != null) {
			$link = add_query_arg('view', $view, $link);
		}

		if($id != null) {
			$link = add_query_arg('id', $id, $link);
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
		return add_query_arg(array(
			'page' => Pronamic_WordPress_IDeal_Plugin::SLUG 
		), 'admin.php');
	}

	public static function getConfigurationEditLink($id = null) {
		return add_query_arg(array(
			'page' => Pronamic_WordPress_IDeal_Plugin::SLUG ,
			'view' => 'edit' ,
			'id' => $id   
		), 'admin.php');
	}

	public static function getConfigurationTestsLink($id = null) {
		return add_query_arg(array(
			'page' => Pronamic_WordPress_IDeal_Plugin::SLUG ,
			'view' => 'tests' ,
			'id' => $id   
		), 'admin.php');
	}

	public static function getConfigurationDeleteLink($id = null) {
		return add_query_arg(array(
			'page' => Pronamic_WordPress_IDeal_Plugin::SLUG ,
			'action' => 'delete' ,
			'id' => $id   
		), 'admin.php');
	}

	public static function getPaymentsLink($id = null) {
		return self::getLink(Pronamic_WordPress_IDeal_Plugin::SLUG . '_payments');
	}

	public static function getPaymentDetailsLink($id = null) {
		return self::getLink(Pronamic_WordPress_IDeal_Plugin::SLUG . '_payments', 'details', $id);
	}

	//////////////////////////////////////////////////

	public static function pageIndex() {
		$view = filter_input(INPUT_GET, 'view', FILTER_SANITIZE_STRING);

		switch($view) {
			case 'edit':
		return self::renderView('configuration-edit');
			case 'tests':
				return self::renderView('configuration-tests');
			default:
				return self::pageConfigurations();
		}
	}

	public static function pageConfigurations() {
		return self::renderView('configurations');
	}

	public static function pageSettings() {
		self::renderView('settings');
	}

	public static function pageVariants() {
		self::renderView('variants');
	}

	public static function pageDocumentation() {
		self::renderView('documentation');
	}

	public static function pagePayments() {
		$view = filter_input(INPUT_GET, 'view', FILTER_SANITIZE_STRING);

		switch($view) {
			case 'details':
				return self::renderView('payment');
			case 'edit':
				return self::renderView('payment-edit');
			default:
				return self::renderView('payments');
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Render the specified view
	 */
	public static function renderView($name) {
		$result = false;

		$file = plugin_dir_path(Pronamic_WordPress_IDeal_Plugin::$file) . 'views/' . $name . '.php';

		if(is_readable($file)) {
			include $file;
			
			$result = true;
		} 
		
		return $result;
	}
}
