<?php

/**
 * Title: WordPress currency class
 * Description:
 * Copyright: Copyright (c) 2005 - 2017
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WP_Currency {
	/**
	 * Transform currency code to number
	 *
	 * @param string $code
	 * @return string
	 */
	public static function transform_code_to_number( $code ) {
		$currencies = array();

		$file = dirname( Pronamic_WP_Pay_Plugin::$file ) . '/other/dl_iso_table_a1.xml';

		$xml = simplexml_load_file( $file );

		foreach ( $xml->ISO_CURRENCY as $currency ) {
			$alphabetic_code = Pronamic_WP_Pay_XML_Security::filter( $currency->ALPHABETIC_CODE );
			$numeric_code    = Pronamic_WP_Pay_XML_Security::filter( $currency->NUMERIC_CODE );

			$currencies[ $alphabetic_code ] = $numeric_code;
		}

		$number = null;

		if ( isset( $currencies[ $code ] ) ) {
			$number = $currencies[ $code ];
		}

		return $number;
	}
}
