<?php
/**
 * Date time
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 * @see       https://github.com/woocommerce/woocommerce/blob/3.3.4/includes/class-wc-datetime.php
 * @see       https://github.com/Rarst/wpdatetime/
 */

namespace Pronamic\WordPress\Pay;

/**
 * Date time
 *
 * @author Remco Tolsma
 * @version 1.0
 */
class DateTime extends \DateTime {
	/**
	 * MySQL datetime foramt.
	 *
	 * @see https://dev.mysql.com/doc/en/datetime.html
	 * @see https://github.com/Rarst/wpdatetime/blob/0.3/src/WpDateTime.php#L10
	 *
	 * @var string
	 */
	const MYSQL = 'Y-m-d H:i:s';

	/**
	 * Date I18N.
	 *
	 * @see https://github.com/Rarst/wpdatetime/blob/0.3/src/WpDateTimeTrait.php#L79-L104
	 * @see https://github.com/WordPress/WordPress/blob/4.9.4/wp-includes/functions.php#L72-L151
	 *
	 * @param string $format Format.
	 *
	 * @return string
	 */
	public function date_i18n( $format ) {
		$date = clone $this;

		$date->setTimezone( DateTimeZone::get_default() );

		$result = date_i18n( $format, $date->getTimestamp() + $date->getOffset(), true );

		return $result;
	}
}
