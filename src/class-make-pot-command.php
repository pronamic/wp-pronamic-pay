<?php
/**
 * WP-CLI `pronamic i18n make-pot` command.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2023 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

namespace Pronamic\WordPress\Pay;

/**
 * Make POT command class
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
			[
				'vendor',
			]
		);

		$this->exclude = array_merge(
			$this->exclude,
			[
				'build',
				'deploy',
				'documentation',
				'etc',
				'repositories',
				'vendor/wp-phpunit',
				'vendor-bin',
				'wordpress',
				'wp-content',
			]
		);

		$this->include = [
			'admin',
			'includes',
			'templates',
			'vendor',
			'views',
			'*.php',
		];
	}
}
