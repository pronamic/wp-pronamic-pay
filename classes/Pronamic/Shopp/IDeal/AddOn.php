<?php 

/**
 * Title: Shopp iDEAL Add-On
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Shopp_IDeal_AddOn {
	/**
	 * Bootstrap
	 */
	public static function bootstrap() {
		add_action('shopp_loaded', array(__CLASS__, 'loaded'));
		add_action('shopp_init', array(__CLASS__, 'init'));
	}
	
	public static function loaded() {

	}
	
	public static function init() {
		global $Shopp;
		
		$path = dirname(__FILE__);
		$file = '/IDealGatewayModule.php';

		$module = new ModuleFile($path, $file);
		if($module->addon) {
			$Shopp->Gateways->modules[$module->subpackage] = $module;
		} else {
			$Shopp->Gateways->legacy[] = md5_file($path . $file);
		}
	}
}
