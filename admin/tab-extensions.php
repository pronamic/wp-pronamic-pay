<?php
/**
 * Tab Extensions
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

?><h2><?php esc_html_e( 'Supported extensions', 'pronamic_ideal' ); ?></h2>

<?php

$data       = file_get_contents( __DIR__ . '/../other/extensions.json' );
$extensions = json_decode( $data );

?>

<table class="wp-list-table widefat">
	<thead>
		<tr>
			<th scope="col">
				<?php esc_html_e( 'Name', 'pronamic_ideal' ); ?>
			</th>
			<th scope="col">
				<?php esc_html_e( 'Author', 'pronamic_ideal' ); ?>
			</th>
			<th scope="col">
				<?php esc_html_e( 'WordPress.org', 'pronamic_ideal' ); ?>
			</th>
			<th scope="col">
				<?php esc_html_e( 'Requires at least', 'pronamic_ideal' ); ?>
			</th>
		</tr>
	</thead>

	<tbody>

		<?php

		$alternate = false;

		foreach ( $extensions as $extension ) :

		?>

			<?php $alternate = ! $alternate; ?>

			<tr<?php if ( $alternate ) : ?> class="alternate"<?php endif; ?>>
				<td>
					<a href="<?php echo esc_attr( $extension->url ); ?>" target="_blank">
						<?php echo esc_html( $extension->name ); ?>
					</a>
				</td>
				<td>
					<?php

					if ( isset( $extension->author, $extension->author_url ) ) {
						printf(
							'<a href="%s" target="_blank">%s</a>',
							esc_attr( $extension->author_url ),
							esc_html( $extension->author )
						);
					}

					?>
				</td>
				<td>
					<?php

					if ( isset( $extension->wp_org_url ) ) {
						printf(
							'<a href="%s" target="_blank">%s</a>',
							esc_attr( $extension->wp_org_url ),
							esc_html( $extension->wp_org_url )
						);
					}

					?>
				</td>
				<td>
					<?php

					if ( isset( $extension->requires_at_least ) ) {
						echo esc_html( $extension->requires_at_least );
					}

					?>
				</td>
			</tr>

		<?php endforeach; ?>

	</tbody>
</table>
