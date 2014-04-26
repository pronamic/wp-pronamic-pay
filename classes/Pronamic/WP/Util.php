<?php

/**
 * Title: WordPress utility class
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WP_Util {
	/**
	 * Remote get body
	 *
	 * @param string $url
	 * @param int $required_response_code
	 */
	public static function remote_get_body( $url, $required_response_code = 200, array $args = array() ) {
		$return = false;

		$result = wp_remote_request( $url, $args );

		if ( is_wp_error( $result ) ) {
			$return = $result;
		} else {
			if ( wp_remote_retrieve_response_code( $result ) == $required_response_code ) {
				$return = wp_remote_retrieve_body( $result );
			} else {
				$return = new WP_Error(
					'wrong_response_code',
					sprintf(
						__( 'The response code (<code>%s<code>) was incorrect, required response code <code>%s</code>.', 'pronamic_ideal' ),
						wp_remote_retrieve_response_code( $result ),
						$required_response_code
					)
				);
			}
		}

		return $return;
	}

	//////////////////////////////////////////////////

	/**
	 * SimpleXML load string
	 *
	 * @param string $string
	 * @return SimpleXMLElement || WP_Error
	 */
	public static function simplexml_load_string( $string ) {
		$result = false;

		// Suppress all XML errors
		$use_errors = libxml_use_internal_errors( true );

		// Load
		$xml = simplexml_load_string( $string );

		if ( $xml !== false ) {
			$result = $xml;
		} else {
			$error = new WP_Error( 'simplexml_load_error', __( 'Could not load the XML string.', 'pronamic_ideal' ) );

			foreach ( libxml_get_errors() as $e ) {
				$error->add( 'libxml_error', $e->message, $e );
			}

			libxml_clear_errors();

			$result = $error;
		}

		// Set back to previous value
		libxml_use_internal_errors( $use_errors );

		return $result;
	}

	//////////////////////////////////////////////////

	/**
	 * Amount to cents
	 *
	 * @param float $price
	 * @return int
	 */
	public static function amount_to_cents( $price ) {
		return round( $price * 100 );
	}

	/**
	 * Cents to amount
	 *
	 * @param int $cents
	 * @return float
	 */
	public static function cents_to_amount( $cents ) {
		return $cents / 100;
	}

	//////////////////////////////////////////////////

	/**
	 * Convert boolean to an numceric boolean
	 *
	 * @see https://github.com/eet-nu/buckaroo-ideal/blob/master/lib/buckaroo-ideal/request.rb#L136
	 * @param boolean $boolean
	 * @return int
	 */
	public static function to_numeric_boolean( $boolean ) {
		return $boolean ? 1 : 0;
	}

	//////////////////////////////////////////////////

	/**
	 * Convert boolean to an string boolean
	 *
	 * @see https://github.com/eet-nu/buckaroo-ideal/blob/master/lib/buckaroo-ideal/request.rb#L136
	 * @param boolean $boolean
	 * @return int
	 */
	public static function to_string_boolean( $boolean ) {
		return $boolean ? 'true' : 'false';
	}

	//////////////////////////////////////////////////

	public static function format_date( $format, DateTime $date = null ) {
		$result = null;

		if ( $date !== null ) {
			$result = $date->format( $format );
		}

		return $result;
	}

	//////////////////////////////////////////////////

	/**
	 * Build URL with the specified parameters
	 *
	 * @param string $url
	 * @param array $parameters
	 * @return string
	 */
	public static function build_url( $url, array $parameters ) {
		return $url . '?' . _http_build_query( $parameters, null, '&' );
	}
}
