<?php
/**
 * WP-CLI `pronamic i18n make-pot` command.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2022 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

namespace Pronamic\WordPress\Pay;

if ( ! \class_exists( 'WP_CLI' ) ) {
	return;
}

/**
 * The `pronamic i18n make-pot` command requires the `i18n make-pot` command.
 *
 * @link https://make.wordpress.org/cli/2017/05/03/managing-command-dependencies/
 */
\WP_CLI::add_hook(
	'after_add_command:i18n make-pot',
	function () {
		require_once __DIR__ . '/class-make-pot-command.php';

		/**
		 * Add command `pronamic i18n make-pot`.
		 *
		 * Usage example:
		 *
		 * wp pronamic i18n make-pot . languages/pronamic_ideal.pot --slug="pronamic-ideal"
		 * 
		 * @link https://github.com/wp-cli/i18n-command/blob/v2.0.1/i18n-command.php
		 */
		\WP_CLI::add_command( 'pronamic i18n make-pot', '\Pronamic\WordPress\Pay\MakePotCommand' );
	}
);
