<?php

$data       = file_get_contents( __DIR__ . '/../extensions.json' );
$extensions = json_decode( $data );

?>
<table>
	<thead>
		<tr>
			<th scope="col">Name</th>
			<th scope="col">Author</th>
			<th scope="col">WordPress.org</th>
			<th scope="col">GitHub</th>
			<th scope="col">Requires at least</th>
			<th scope="col">Tested up to</th>
		</tr>
	</thead>

	<tbody>
<?php foreach ( $extensions as $extension ) : ?>
		<tr>
			<td><?php

			printf(
				'<a href="%s" target="_blank">%s</a>',
				$extension->url,
				$extension->name
			);

			?></td>
			<td><?php

			if ( isset( $extension->author, $extension->author_url ) ) {
				printf(
					'<a href="%s" target="_blank">%s</a>',
					$extension->author_url,
					$extension->author
				);
			}

			?></td>
			<td><?php

			if ( isset( $extension->wp_org_url ) ) {
				printf(
					'<a href="%s" target="_blank">%s</a>',
					$extension->wp_org_url,
					'WordPress.org'
				);
			}

			?></td>
			<td><?php

			if ( isset( $extension->github_url ) ) {
				printf(
					'<a href="%s" target="_blank">%s</a>',
					$extension->github_url,
					'GitHub'
				);
			}

			?></td>
			<td><?php

			if ( isset( $extension->requires_at_least ) ) {
				echo $extension->requires_at_least;
			}

			?></td>
			<td><?php echo $extension->tested_up_to; ?></td>
		</tr>
<?php endforeach; ?>
	</tbody>
</table>
