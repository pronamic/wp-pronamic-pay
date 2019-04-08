<?php
/**
 * Admin View: Notice - Update webhook URL
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

use Pronamic\WordPress\Pay\Admin\AdminGatewayPostType;
use Pronamic\WordPress\Pay\WebhookManager;

if ( ! defined( 'WPINC' ) ) {
	die;
}

$gateways = array();

$config_ids = get_transient( WebhookManager::OUTDATED_WEBHOOK_URLS_OPTION );

if ( ! is_array( $config_ids ) ) {
	return;
}

foreach ( $config_ids as $config_id ) :

	if ( AdminGatewayPostType::POST_TYPE !== get_post_type( $config_id ) ) {
		continue;
	}

	$gateways[] = sprintf(
		'<a href="%1$s" title="%2$s">%2$s</a>',
		get_edit_post_link( $config_id ),
		get_the_title( $config_id )
	);

endforeach;

// Don't show notice if non of the gateways exists.
if ( empty( $gateways ) ) {
	// Delete transient.
	delete_transient( WebhookManager::OUTDATED_WEBHOOK_URLS_OPTION );

	return;
}

$gateways = implode( ', ', $gateways );

?>
<div class="error">
	<p>
		<strong><?php esc_html_e( 'Pronamic Pay', 'pronamic_ideal' ); ?></strong> —
		<?php

		if ( 1 === count( $config_ids ) ) :

			$message = sprintf(
				/* translators: 1: configuration link, 2: configuration post edit link */
				__(
					'The webhook URL to receive automatic payment status updates seems to have changed for the %1$s configuration. Please check your settings.',
					'pronamic_ideal'
				),
				$gateways // WPCS: xss ok.
			);

		else :

			$url = add_query_arg(
				array(
					'post_type' => 'pronamic_gateway',
				),
				admin_url( 'edit.php' )
			);

			$message = sprintf(
				/* translators: 1: configuration titles, 2: gateway admin url */
				__(
					'The webhook URL to receive automatic payment status updates seems to have changed for the %1$s configurations. <a href="%2$s" title="Payment gateway configurations">Please check your settings</a>.',
					'pronamic_ideal'
				),
				$gateways, // WPCS: xss ok.
				esc_url( $url )
			);

		endif;

		echo wp_kses(
			$message,
			array(
				'a' => array(
					'href'  => true,
					'title' => true,
				),
			)
		);

		?>
	</p>
</div>
