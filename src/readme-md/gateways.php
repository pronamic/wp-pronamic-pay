<?php

$data  = file_get_contents( __DIR__ . '/../providers.json' );
$data = json_decode( $data );

$providers = array();
foreach ( $data as $provider ) {
	$providers[ $provider->slug ] = $provider;
}

$data     = file_get_contents( __DIR__ . '/../gateways.json' );
$gateways = json_decode( $data );

foreach ( $gateways as $gateway ) {
	if ( isset( $providers[ $gateway->provider ] ) ) {
		$provider = $providers[ $gateway->provider ];

		if ( ! isset( $provider->gateways ) ) {
			$provider->gateways = array();
		}

		$provider->gateways[ $gateway->slug ] = $gateway;
	}
}

?>
<table>
	<thead>
		<tr>
			<th scope="col">Provider</th>
			<th scope="col">Name</th>
		</tr>
	</thead>

	<tbody>
<?php foreach ( $gateways as $gateway ) : ?>
		<tr>
			<td><?php

			if ( isset( $gateway->provider, $providers[ $gateway->provider ] ) ) {
				$provider = $providers[ $gateway->provider ];

				if ( isset( $provider->url ) ) {
					printf(
						'<a href="%s">%s</a>',
						$provider->url,
						$provider->name
					);
				} else {
					echo $provider->name;
				}
			}

			?></td>
			<td><?php echo $gateway->name; ?></td>
		</tr>
<?php endforeach; ?>
	</tbody>
</table>
