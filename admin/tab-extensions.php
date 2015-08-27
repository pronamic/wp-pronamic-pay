<h3><?php esc_html_e( 'Supported Extensions', 'pronamic_ideal' ); ?></h3>

<?php

$extensions = array(

);

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
				<?php esc_html_e( 'GitHub', 'pronamic_ideal' ); ?>
			</th>
			<th scope="col">
				<?php esc_html_e( 'Requires at least', 'pronamic_ideal' ); ?>
			</th>
			<th scope="col">
				<?php esc_html_e( 'Tested up to', 'pronamic_ideal' ); ?>
			</th>
			<th scope="col">
				<?php esc_html_e( 'Active', 'pronamic_ideal' ); ?>
			</th>
		</tr>
	</thead>

	<tbody>

		<?php foreach ( $extensions as $extension ) : ?>

			<tr>
				<td>
					<a href="<?php echo esc_attr( $extension['url'] ); ?>" target="_blank">
						<?php echo esc_html( $extension['name'] ); ?>
					</a>
				</td>
				<td>
					<?php

					if ( isset( $extension['author'], $extension['author_url'] ) ) {
						printf(
							'<a href="%s" target="_blank">%s</a>',
							esc_attr( $extension['author_url'] ),
							esc_html( $extension['author'] )
						);
					}

					?>
				</td>
				<td>
					<?php

					if ( isset( $extension['wp_org_url'] ) ) {
						printf(
							'<a href="%s" target="_blank">%s</a>',
							esc_attr( $extension['wp_org_url'] ),
							esc_html( $extension['wp_org_url'] )
						);
					}

					?>
				</td>
				<td>
					<?php

					if ( isset( $extension['github_url'] ) ) {
						printf(
							'<a href="%s" target="_blank">%s</a>',
							esc_attr( $extension['github_url'] ),
							esc_html( $extension['github_url'] )
						);
					}

					?>
				</td>
				<td>
					<?php

					if ( isset( $extension['requires_at_least'] ) ) {
						echo esc_html( $extension['requires_at_least'] );
					}

					?>
				</td>
				<td>
					<?php echo esc_html( $extension['tested_up_to'] ); ?>
				</td>
				<td>
					<?php if ( isset( $extension['active'] ) && $extension['active'] ) : ?>
						&#10003;
					<?php endif; ?>
				</td>
			</tr>

		<?php endforeach; ?>

	</tbody>
</table>
