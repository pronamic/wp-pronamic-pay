<?php

/**
 * Title: iDEAL HTML helper
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_IDeal_HTML_Helper {
	/**
	 * Create an issuer HTML select element
	 * 
	 * @param string $name
	 * @param array $lists
	 * @param string $emptyOption
	 * @param array $groups
	 */
	public static function issuersSelect($name, array $lists, $emptyOption = null, array $groups = array()) {
		$html  = '';

		$html .= '<select name="' . $name . '">';
		$html .= self::issuersSelectOptions($lists, $emptyOption, $groups);
		$html .= '</select>';
		
		return $html;
	}

	/**
	 * Create isseur HTML select options
	 * 
	 * @param array $lists
	 * @param string $emptyOption
	 * @param array $groups
	 */
	public static function issuersSelectOptions(array $lists, $emptyOption = null, $value, array $groups = array()) {
		$html  = '';

		if($emptyOption !== null) {
			$html .= '	<option value="">' . $emptyOption . '</option>';
		}
		
		foreach($lists as $name => $list) {
			if(isset($groups[$name])) {
				$html .= '	<optgroup label="' . $groups[$name] . '">';
			}

			foreach($list as $issuer) {
				$html .= '	<option value="' . $issuer->getId() . '" ' . selected($value, $issuer->getId(), false) . '>' . $issuer->getName() . '</option>';
			}

			if(isset($groups[$name])) {
				$html .= '	</optgroup>';
			}
		}
		
		return $html;
	}
}
