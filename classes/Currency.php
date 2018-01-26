<?php

namespace Pronamic\WordPress\Pay;

use Pronamic\WordPress\Pay\Core\XML\Security;

/**
 * Title: WordPress currency class
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.0
 */
class Currency {
	/**
	 * Transform currency code to number
	 *
	 * @param string $code
	 *
	 * @return string
	 */
	public static function transform_code_to_number( $code ) {
		$currencies = array();

		$file = dirname( Plugin::$file ) . '/other/dl_iso_table_a1.xml';

		$xml = simplexml_load_file( $file );

		foreach ( $xml->ISO_CURRENCY as $currency ) {
			$alphabetic_code = Security::filter( $currency->ALPHABETIC_CODE );
			$numeric_code    = Security::filter( $currency->NUMERIC_CODE );

			$currencies[ $alphabetic_code ] = $numeric_code;
		}

		$number = null;

		if ( isset( $currencies[ $code ] ) ) {
			$number = $currencies[ $code ];
		}

		return $number;
	}
}
