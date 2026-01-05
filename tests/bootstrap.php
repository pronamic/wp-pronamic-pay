<?php
/**
 * Bootstrap tests
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2026 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

/**
 * Composer.
 */
require_once __DIR__ . '/../vendor/autoload.php';

/**
 * WorDBless.
 */
define( 'ABSPATH', __DIR__ . '/../wordpress/' );

\WorDBless\Load::load();

/**
 * Psalm.
 */
if ( defined( 'PSALM_VERSION' ) ) {
	return;
}

/**
 * Plugin.
 */
\Pronamic\WordPress\Pay\Plugin::instance(
	[
		'file'             => __DIR__ . '/../pronamic-ideal.php',
		'action_scheduler' => __DIR__ . '/../index.php',
	]
);
