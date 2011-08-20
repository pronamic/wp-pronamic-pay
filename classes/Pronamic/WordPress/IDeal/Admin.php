<?php

namespace Pronamic\WordPress\IDeal;

/**
 * Title: WordPress iDEAL plugin
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Admin {
	/**
	 * Bootstrap
	 */
	public static function bootstrap() {
		add_action('admin_menu', array(__CLASS__, 'adminMenu'));
	}

	//////////////////////////////////////////////////

	/**
	 * Create the admin menu
	 * 
	 * @param array $menus
	 */
	public static function adminMenu($menus) {
		add_menu_page(
			$pageTitle = __('iDEAL', Plugin::TEXT_DOMAIN) ,
			$menuTitle = __('iDEAL', Plugin::TEXT_DOMAIN) ,
			$capability = 'manage_options' , 
			$menuSlug = Plugin::SLUG , 
			$function = array(__CLASS__, 'pageIndex') , 
			$iconUrl = plugins_url('images/icon-16x16.png', Plugin::$file)
		);

		add_submenu_page(
			$parentSlug = Plugin::SLUG , 
			$pageTitle = __('Payments', Plugin::TEXT_DOMAIN) , 
			$menuTitle = __('Payments', Plugin::TEXT_DOMAIN) , 
			$capability = 'manage_options' , 
			$menuSlug = Plugin::SLUG . '_payments' , 
			$function = array(__CLASS__, 'pagePayments')
		);

		add_submenu_page(
			$parentSlug = Plugin::SLUG , 
			$pageTitle = __('Settings', Plugin::TEXT_DOMAIN) , 
			$menuTitle = __('Settings', Plugin::TEXT_DOMAIN) , 
			$capability = 'manage_options' , 
			$menuSlug = Plugin::SLUG . '_settings' , 
			$function = array(__CLASS__, 'pageSettings')
		);

		add_submenu_page(
			$parentSlug = Plugin::SLUG , 
			$pageTitle = __('Variants', Plugin::TEXT_DOMAIN) , 
			$menuTitle = __('Variants', Plugin::TEXT_DOMAIN) , 
			$capability = 'manage_options' , 
			$menuSlug = Plugin::SLUG . '_variants' , 
			$function = array(__CLASS__, 'pageVariants')
		);

		add_submenu_page(
			$parentSlug = Plugin::SLUG , 
			$pageTitle = __('Documentation', Plugin::TEXT_DOMAIN) , 
			$menuTitle = __('Documentation', Plugin::TEXT_DOMAIN) , 
			$capability = 'manage_options' , 
			$menuSlug = Plugin::SLUG . '_documentation' , 
			$function = array(__CLASS__, 'pageDocumentation')
		);

		global $submenu;

		if(isset($submenu[Plugin::SLUG])) {
			$submenu[Plugin::SLUG][0][0] = __('Configurations', Plugin::TEXT_DOMAIN);
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
		return self::getLink(Plugin::SLUG);
	}

	public static function getConfigurationEditLink($id = null) {
		return self::getLink(Plugin::SLUG, 'edit', $id);
	}

	public static function getConfigurationTestsLink($id = null) {
		return self::getLink(Plugin::SLUG, 'tests', $id);
	}

	public static function getConfigurationDeleteLink($id = null) {
		return add_query_arg(array(
			'page' => Plugin::SLUG ,
			'action' => 'delete' ,
			'id' => $id   
		), 'admin.php');
	}

	public static function getPaymentsLink($id = null) {
		return self::getLink(Plugin::SLUG . '-payments');
	}

	public static function getPaymentDetailsLink($id = null) {
		return self::getLink(Plugin::SLUG . '-payments', 'details', $id);
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

	/***
	 * Render the specified view
	 */
	public static function renderView($name) {
		$result = false;

		$file = plugin_dir_path(Plugin::$file) . 'views/' . $name . '.php';

		if(is_readable($file)) {
			include $file;
			
			$result = true;
		} 
		
		return $result;
	}
}
