<?php 

/**
 * Title: ClassiPress
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_ClassiPress_ClassiPress {
	/**
	 * Get order values by ID
	 * 
	 * @see /classipress/includes/gateways/gateway.php#L40
	 * @see /classipress/includes/theme-functions.php#L2619
	 * @param string $id
	 * @return array
	 */
	public static function getOrderValuesById($id) {
		$orderValues['post_id'] = $id;

	    $orderValues = cp_get_order_vals($orderValues);

	    return $orderValues;
	}

	//////////////////////////////////////////////////

	/**
	 * Update ad status
	 * 
	 * @param string $id
	 * @param string $status
	 */
	public static function updateAdStatus($id, $status) {
		$data = array();
		$data['ID'] = $id;
		$data['post_status'] = $status;

		$result = wp_update_post($data);

		// now we need to update the ad expiration date so they get the full length of time
		// sometimes they didn't pay for the ad right away or they are renewing

		// first get the ad duration and first see if ad packs are being used
		// if so, get the length of time in days otherwise use the default
		// prune period defined on the CP settings page

		$duration = get_post_meta($id, 'cp_sys_ad_duration', true);
		if(!isset($ad_length)) {
			$duration = get_option('cp_prun_period');
		}

		// set the ad listing expiration date
		$expireDate = date_i18n('m/d/Y H:i:s', strtotime('+' . $duration . ' days')); // don't localize the word 'days'

		// now update the expiration date on the ad
		update_post_meta($ad_id, 'cp_sys_expire_date', $expireDate);
	}
}
