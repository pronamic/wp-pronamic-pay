<?php
/**
 * Tab Gateways
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

?>
<h2><?php esc_html_e( 'Supported Payment Gateways', 'pronamic_ideal' ); ?></h2>

<?php

global $pronamic_pay_providers;

bind_providers_and_gateways();

?>

<table class="wp-list-table widefat" cellspacing="0">
	<thead>
		<tr>
			<th scope="col"><?php esc_html_e( 'Payment provider', 'pronamic_ideal' ); ?></th>
			<th scope="col"><?php esc_html_e( 'Gateway', 'pronamic_ideal' ); ?></th>
			<th scope="col"><?php esc_html_e( 'Site', 'pronamic_ideal' ); ?></th>
		</tr>
	</thead>

	<tbody>

		<?php

		$current_provider = '';
		$alternate        = false;

		foreach ( $pronamic_pay_providers as $provider ) :

			if ( isset( $provider['integrations'] ) && is_array( $provider['integrations'] ) ) :

				foreach ( $provider['integrations'] as $integration ) :
					$name = $integration->get_name();

					$name = explode( ' - ', $name );

					// Provider.
					if ( count( $name ) > 1 ) :
						$provider = array_shift( $name );
					else :
						$provider_name = explode( '(', $name[0] );

						$provider = array_shift( $provider_name );
					endif;

					if ( $current_provider === $integration->provider ) :
						$provider = '';
					else :
						$current_provider = $integration->provider;

						$alternate = ! $alternate;
					endif;

					$name = implode( '', $name );

					// Deprecated notice.
					if ( isset( $integration->deprecated ) && $integration->deprecated ) {
						/* translators: %s: Integration name */
						$name = sprintf( __( '%s (obsoleted)', 'pronamic_ideal' ), $name );
					}

					// Product link.
					$site = '';

					if ( $integration->get_product_url() ) {
						$site = sprintf(
							'<a href="%s" target="_blank" title="%s">%2$s</a>',
							$integration->get_product_url(),
							__( 'Site', 'pronamic_ideal' )
						);
					}

					printf( // WPCS: XSS ok.
						'<tr%s>
							<td>%s</td>
							<td>%s</td>
							<td>%s</td>
						</tr>',
						( $alternate ? ' class="alternate"' : null ),
						esc_html( $provider ),
						esc_html( $name ),
						$site
					);

				endforeach;

			endif;

		endforeach;

		?>

	</tbody>
</table>
