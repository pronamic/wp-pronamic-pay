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
	
	public static function select_options_grouped( $groups, $selected_value = null ) {
		$html = '';

		foreach( $groups as $group ) {
			if ( isset( $group['name'] ) ) {
				$html .= '<optgroup label="' . $group['name'] . '">';
			}

			foreach( $group['options'] as $value => $label ) {
				$html .= '<option value="' . $value . '" ' . selected( $selected_value, $value, false ) . '>' . $label . '</option>';
			}

			if ( isset( $group['name'] ) ) {
				$html .= '</optgroup>';
			}
		}
		
		return $html;
	}
}
