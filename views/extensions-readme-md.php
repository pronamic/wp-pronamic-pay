<table>
	<thead>
		<tr>
			<th scope="col"><?php _ex( 'Name', 'readme.md', 'pronamic_ideal' ); ?></th>
			<th scope="col"><?php _ex( 'Author', 'readme.md', 'pronamic_ideal' ); ?></th>
			<th scope="col"><?php _ex( 'WordPress.org', 'readme.md', 'pronamic_ideal' ); ?></th>
			<th scope="col"><?php _ex( 'GitHub', 'readme.md', 'pronamic_ideal' ); ?></th>
			<th scope="col"><?php _ex( 'Requires at least', 'readme.md', 'pronamic_ideal' ); ?></th>
			<th scope="col"><?php _ex( 'Tested up to', 'readme.md', 'pronamic_ideal' ); ?></th>
		</tr>
	</thead>
	
	<tbody>
<?php foreach ( $extensions as $extension ) : ?>
		<tr>
			<td><?php 

				printf(
					'<a href="%s" target="_blank">%s</a>',
					esc_attr( $extension['url'] ),
					esc_html( $extension['name'] )
				);
				
			?></td>
			<td><?php
				
				if ( isset( $extension['author'], $extension['author_url'] ) ) {
					printf(
						'<a href="%s" target="_blank">%s</a>',
						esc_attr( $extension['author_url'] ),
						esc_html( $extension['author'] )
					);
				}
				
			?></td>
			<td><?php
				
				if ( isset( $extension['wp_org_url'] ) ) {
					printf(
						'<a href="%s" target="_blank">%s</a>',
						esc_attr( $extension['wp_org_url'] ),
						esc_html__( 'WordPress.org', 'pronamic_ideal' )
					);
				}
				
			?></td>
			<td><?php
				
				if ( isset( $extension['github_url'] ) ) {
					printf(
						'<a href="%s" target="_blank">%s</a>',
						esc_attr( $extension['github_url'] ),
						esc_html__( 'GitHub', 'pronamic_ideal' )
					);
				}
				
			?></td>
			<td><?php
				
				if ( isset( $extension['requires_at_least'] ) ) {
					echo $extension['requires_at_least'];
				}

			?></td>
			<td><?php echo esc_html( $extension['tested_up_to'] ); ?></td>
		</tr>
<?php endforeach; ?>
	</tbody>
</table>