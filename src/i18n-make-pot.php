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

/*
 * The `pronamic i18n make-pot` command requires the `i18n make-pot` command.
 *
 * @link https://make.wordpress.org/cli/2017/05/03/managing-command-dependencies/
 */
\WP_CLI::add_hook(
	'after_add_command:i18n make-pot',
	function () {

		/**
		 * Title: Make pot command.
		 * Description:
		 * Copyright: 2005-2022 Pronamic
		 * Company: Pronamic
		 *
		 * @author  Remco Tolsma
		 * @version 5.5.5
		 * @since   5.5.0
		 */
		class MakePotCommand extends \WP_CLI\I18n\MakePotCommand {
			/**
			 * Command constructor.
			 */
			public function __construct() {
				parent::__construct();

				// @link https://github.com/wp-cli/i18n-command/blob/v2.0.1/src/MakePotCommand.php#L36-L44
				$this->exclude = array_diff(
					$this->exclude,
					array(
						'vendor',
					)
				);

				$this->exclude = array_merge(
					$this->exclude,
					array(
						'build',
						'deploy',
						'documentation',
						'etc',
						'repositories',
						'vendor/wp-phpunit',
						'wordpress',
						'wp-content',
					)
				);

				$this->include = array(
					'admin',
					'includes',
					'templates',
					'vendor',
					'views',
					'*.php',
				);
			}
		}

		// @link https://github.com/wp-cli/i18n-command/blob/v2.0.1/i18n-command.php
		\WP_CLI::add_command( 'pronamic i18n make-pot', '\Pronamic\WordPress\Pay\MakePotCommand' );

		/*
		 * Usage example:
		 *
		 * wp pronamic i18n make-pot . languages/pronamic_ideal.pot --slug="pronamic-ideal"
		 */
	}
);
