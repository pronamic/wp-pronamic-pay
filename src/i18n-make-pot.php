<?php

namespace Pronamic\WordPress\Pay;

/*
 * The `pronamic i18n make-pot` command requires the `i18n make-pot` command.
 *
 * @link https://make.wordpress.org/cli/2017/05/03/managing-command-dependencies/
 */
\WP_CLI::add_hook( 'after_add_command:i18n make-pot', function () {
	class MakePotCommand extends \WP_CLI\I18n\MakePotCommand {
		public function __construct() {
			parent::__construct();

			// https://github.com/wp-cli/i18n-command/blob/v2.0.1/src/MakePotCommand.php#L36-L44
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

	// https://github.com/wp-cli/i18n-command/blob/v2.0.1/i18n-command.php
	\WP_CLI::add_command( 'pronamic i18n make-pot', '\Pronamic\WordPress\Pay\MakePotCommand' );
	// wp pronamic i18n make-pot . languages/pronamic_ideal.pot --slug="pronamic-ideal"
} );
