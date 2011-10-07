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
		add_action('shopp_init', array(__CLASS__, 'init'));
	}

	//////////////////////////////////////////////////

	/**
	 * Checks if Shopp is supported
	 * 
	 * @return true if Shopp is supported, false otherwise
	 */
	public static function isShoppSupported() {
		global $Shopp;

		return isset($Shopp);
	}

	//////////////////////////////////////////////////

	/**
	 * Shopp init
	 */
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
