<?php
/**
 * Util
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

namespace Pronamic\WordPress\Pay;

use DateTime;
use SimpleXMLElement;
use WP_Error;

/**
 * Title: WordPress utility class
 *
 * @author Remco Tolsma
 * @version 1.0
 */
class Util {
	/**
	 * Remote get body.
	 *
	 * @param string $url                    The URL to use for the remote request.
	 * @param int    $required_response_code The required response code.
	 * @param array  $args                   The WordPress HTTP API request arguments.
	 *
	 * @return string|WP_Error
	 */
	public static function remote_get_body( $url, $required_response_code = 200, array $args = array() ) {
		$result = wp_remote_request( $url, $args );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		$response_code = wp_remote_retrieve_response_code( $result );

		if ( $response_code === $required_response_code ) {
			return wp_remote_retrieve_body( $result );
		}

		return new WP_Error(
			'wrong_response_code',
			sprintf(
				__( 'The response code (<code>%1$s<code>) was incorrect, required response code <code>%2$s</code>.', 'pronamic_ideal' ),
				$response_code,
				$required_response_code
			)
		);
	}

	/**
	 * SimpleXML load string.
	 *
	 * @param string $string The XML string to convert to a SimpleXMLElement object.
	 *
	 * @return SimpleXMLElement|WP_Error
	 */
	public static function simplexml_load_string( $string ) {
		$result = false;

		// Suppress all XML errors.
		$use_errors = libxml_use_internal_errors( true );

		// Load.
		$xml = simplexml_load_string( $string );

		if ( false !== $xml ) {
			$result = $xml;
		} else {
			$error = new WP_Error( 'simplexml_load_error', __( 'Could not load the XML string.', 'pronamic_ideal' ) );

			foreach ( libxml_get_errors() as $e ) {
				$error->add( 'libxml_error', $e->message, $e );
			}

			libxml_clear_errors();

			$result = $error;
		}

		// Set back to previous value.
		libxml_use_internal_errors( $use_errors );

		return $result;
	}

	/**
	 * Amount to cents.
	 *
	 * @param float $price The amount to convert to cents.
	 *
	 * @return int
	 */
	public static function amount_to_cents( $price ) {
		return round( $price * 100 );
	}

	/**
	 * Cents to amount.
	 *
	 * @param int $cents The numberof cents to convert to an amount.
	 *
	 * @return float
	 */
	public static function cents_to_amount( $cents ) {
		return $cents / 100;
	}

	/**
	 * Convert boolean to an numceric boolean.
	 *
	 * @see https://github.com/eet-nu/buckaroo-ideal/blob/master/lib/buckaroo-ideal/request.rb#L136
	 *
	 * @param boolean $boolean The boolean to convert to 1 or 0.
	 *
	 * @return int
	 */
	public static function boolean_to_numeric( $boolean ) {
		return $boolean ? 1 : 0;
	}

	/**
	 * Convert boolean to an string boolean.
	 *
	 * @see https://github.com/eet-nu/buckaroo-ideal/blob/master/lib/buckaroo-ideal/request.rb#L136
	 *
	 * @param boolean $boolean The boolean to convert to the string 'true' or 'false'.
	 *
	 * @return int
	 */
	public static function boolean_to_string( $boolean ) {
		return $boolean ? 'true' : 'false';
	}

	/**
	 * Format date.
	 *
	 * @param string   $format The desired date form.
	 * @param DateTime $date   The date to format.
	 * @return string
	 */
	public static function format_date( $format, DateTime $date = null ) {
		$result = null;

		if ( null !== $date ) {
			$result = $date->format( $format );
		}

		return $result;
	}

	/**
	 * Format price.
	 *
	 * @see https://github.com/woothemes/woocommerce/blob/v2.2.3/includes/wc-formatting-functions.php#L306-L347
	 * @see https://github.com/woothemes/woocommerce/blob/v2.2.3/includes/wc-core-functions.php#L299-L376
	 *
	 * @param float  $amount   The amount to format.
	 * @param string $currency The currency code for the currency symbol.
	 *
	 * @return string
	 */
	public static function format_price( $amount, $currency = null ) {
		$float = filter_var( $amount, FILTER_VALIDATE_FLOAT );

		if ( false === $float ) {
			return;
		}

		$currency = ( null === $currency ) ? 'EUR' : $currency;

		$currency_symbol = $currency;

		switch ( $currency ) {
			case 'EUR':
				$currency_symbol = '€';
				break;
			case 'USD':
				$currency_symbol = '$';
				break;
		}

		// @see https://en.wikipedia.org/wiki/Non-breaking_space#Keyboard_entry_methods
		$non_breaking_space = ' ';

		return '' . $currency_symbol . $non_breaking_space . number_format_i18n( $float, 2 );
	}

	/**
	 * Format interval.
	 *
	 * @param int    $interval The interval number.
	 * @param string $period   The period indicator.
	 *
	 * @return string
	 */
	public static function format_interval( $interval, $period ) {
		switch ( $period ) {
			case 'D':
			case 'day':
			case 'days':
				return sprintf( _n( 'Every %s day', 'Every %s days', $interval, 'pronamic_ideal' ), $interval );
			case 'W':
			case 'week':
			case 'weeks':
				return sprintf( _n( 'Every %s week', 'Every %s weeks', $interval, 'pronamic_ideal' ), $interval );
			case 'M':
			case 'month':
			case 'months':
				return sprintf( _n( 'Every %s month', 'Every %s months', $interval, 'pronamic_ideal' ), $interval );
			case 'Y':
			case 'year':
			case 'years':
				return sprintf( _n( 'Every %s year', 'Every %s years', $interval, 'pronamic_ideal' ), $interval );
		}
	}

	/**
	 * Convert single interval period character to full name.
	 *
	 * @param string $interval_period string Short interval period (D, W, M or Y).
	 *
	 * @return string
	 */
	public static function to_interval_name( $interval_period ) {
		switch ( $interval_period ) {
			case 'D':
				return 'days';
			case 'W':
				return 'weeks';
			case 'M':
				return 'months';
			case 'Y':
				return 'years';
		}

		return $interval_period;
	}

	/**
	 * Format frequency.
	 *
	 * @param int $frequency The number of times.
	 *
	 * @return string
	 */
	public static function format_frequency( $frequency ) {
		if ( '' === $frequency ) {
			return _x( 'Unlimited', 'Recurring payment', 'pronamic_ideal' );
		}

		return sprintf( _n( '%s time', '%s times', $frequency, 'pronamic_ideal' ), $frequency );
	}

	/**
	 * Build URL with the specified parameters
	 *
	 * @param string $url        The URL to extend with specified parameters.
	 * @param array  $parameters The parameters to add to the specified URL.
	 *
	 * @return string
	 */
	public static function build_url( $url, array $parameters ) {
		return $url . '?' . _http_build_query( $parameters, null, '&' );
	}

	/**
	 * Get hidden inputs HTML for data.
	 *
	 * @param array $data Array with name and value pairs to convert to hidden HTML input eleemnts.
	 *
	 * @return string
	 */
	public static function html_hidden_fields( $data ) {
		$html = '';

		foreach ( $data as $name => $value ) {
			$html .= sprintf( '<input type="hidden" name="%s" value="%s" />', esc_attr( $name ), esc_attr( $value ) );
		}

		return $html;
	}

	/**
	 * Array to HTML attributes.
	 *
	 * @param array $attributes The key and value pairs to convert to HTML attributes.
	 *
	 * @return string
	 */
	public static function array_to_html_attributes( array $attributes ) {
		$html = '';

		foreach ( $attributes as $key => $value ) {
			$html .= sprintf( '%s="%s"', $key, esc_attr( $value ) );
		}

		$html = trim( $html );

		return $html;
	}

	/**
	 * Select options grouped.
	 *
	 * @param array  $groups         The grouped select options.
	 * @param string $selected_value The selected value.
	 *
	 * @return string
	 */
	public static function select_options_grouped( $groups, $selected_value = null ) {
		$html = '';

		if ( is_array( $groups ) ) {
			foreach ( $groups as $group ) {
				$optgroup = isset( $group['name'] ) && ! empty( $group['name'] );

				if ( $optgroup ) {
					$html .= '<optgroup label="' . $group['name'] . '">';
				}

				foreach ( $group['options'] as $value => $label ) {
					$html .= '<option value="' . $value . '" ' . selected( $selected_value, $value, false ) . '>' . $label . '</option>';
				}

				if ( $optgroup ) {
					$html .= '</optgroup>';
				}
			}
		}

		return $html;
	}
}
