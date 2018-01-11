<?php

/**
 * Title: iDEAL
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_IDeal_IDeal {
	/**
	 * The date format (yyyy-MMddTHH:mm:ss.SSSZ)
	 * The Z stands for the time zone (CET).
	 *
	 * @var string
	 */
	const DATE_FORMAT = 'Y-m-d\TH:i:s.000\Z';

	/**
	 * The timezone
	 *
	 * @var string
	 */
	const TIMEZONE = 'UTC';

	//////////////////////////////////////////////////

	/**
	 * Indicator for test mode
	 *
	 * @var int
	 */
	const MODE_TEST = 'test';

	/**
	 * Indicator for live mode
	 *
	 * @var int
	 */
	const MODE_LIVE = 'live';

	//////////////////////////////////////////////////

	public static function htmlHiddenFields( $data ) {
		$html = '';

		foreach ( $data as $name => $value ) {
			$html .= sprintf( '<input type="hidden" name="%s" value="%s" />', esc_attr( $name ), esc_attr( $value ) );
		}

		return $html;
	}

	//////////////////////////////////////////////////

	/**
	 * Sanitize iDEAL value
	 *
	 * @see page 30 http://pronamic.nl/wp-content/uploads/2012/09/iDEAL-Merchant-Integratie-Gids-NL.pdf
	 *
	 * The use of characters that are not listed above will not lead to a refusal of a batch or post, but the
	 * character will be changed by Equens (formerly Interpay) to a space, question mark or asterisk. The
	 * same goes for diacritical characters (à, ç, ô, ü, ý etcetera).
	 *
	 * @param string $value
	 * @return string
	 */
	public static function sanitize_ideal_value( $value ) {
		$value = remove_accents( $value );

		return $value;
	}
}
