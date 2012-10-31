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
	 * Array to HTML attributes
	 * 
	 * @param array $pieces
	 */
	public static function array_to_html_attributes( array $attributes ) {
		$html = '';
		$space = '';

		foreach ( $attributes as $key => $value ) {
			if ( !empty( $value ) ) {
				$html .= $space . $key . '=' . '"' . esc_attr( $value ) . '"';

				$space = ' ';
			} 
		}

		return $html;
	}
	
	/**
	 * Create an issuer HTML select element
	 * 
	 * @param string $name
	 * @param array $lists
	 * @param string $emptyOption
	 * @param array $groups
	 */
	public static function issuersSelect( $name, array $lists, $empty_option = null, array $groups = array(), $id = '' ) {
		$attributes = array(
			'id'   => $id,
			'name' => $name, 
		);
		
		$html = sprintf(
			'<select %s>%s</select>',
			self::array_to_html_attributes( $attributes ),
			self::issuersSelectOptions( $lists, $empty_option, $groups )
		);
		
		return $html;
	}

	/**
	 * Create isseur HTML select options
	 * 
	 * @param array $lists
	 * @param string $emptyOption
	 * @param array $groups
	 */
	public static function issuersSelectOptions( array $lists, $empty_option = null, $value, array $groups = array() ) {
		$html  = '';

		if ( $empty_option !== null ) {
			$html .= '<option value="">' . $empty_option . '</option>';
		}
		
		foreach ( $lists as $name => $list ) {
			if ( isset( $groups[$name] ) ) {
				$html .= '<optgroup label="' . $groups[$name] . '">';
			}

			foreach ( $list as $issuer ) {
				$html .= '<option value="' . $issuer->getId() . '" ' . selected( $value, $issuer->getId(), false ) . '>' . $issuer->getName() . '</option>';
			}

			if ( isset( $groups[$name] ) ) {
				$html .= '</optgroup>';
			}
		}
		
		return $html;
	}
}
