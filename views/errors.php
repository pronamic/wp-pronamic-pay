<?php
/**
 * Errors
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2021 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

global $pronamic_ideal_errors;

foreach ( $pronamic_ideal_errors as $pay_error ) {
	include 'error.php';
}
