<?php

$matrix = array();

$components = array(
	'address'                => 'Address',
	'line_1'                 => 'Line 1',
	'line_2'                 => 'Line 2',
	'line_3'                 => 'Line 3',
	'line_4'                 => 'Line 4',
	'street_name'            => 'Street Name',
	'street_number'          => 'Street Number',
	'house_number'           => 'House Number',
	'house_number_extension' => 'House Number Extension',
	'city'                   => 'City',
	'postal_code'            => 'Postal Code',
	'postcode'               => 'Postcode',
	'zip'                    => array(
		'label' => 'ZIP',
		'link'  => 'https://nl.wikipedia.org/wiki/Postcodes_in_de_Verenigde_Staten',
	),
	'zip_code'               => array(
		'label' => 'ZIP Code',
		'link'  => 'https://nl.wikipedia.org/wiki/Postcodes_in_de_Verenigde_Staten',
	),
	'country'                => 'Country',
	'country_code'           => 'Country Code',
	'country_name'           => 'Country Name',
	'county'                 => 'County',
	'region'                 => 'Region',
	'locality'               => 'Locality',
	'state'                  => 'State',
	'state_code'             => 'State Code',
	'state_name'             => 'State Name',
	'province'               => 'Province',
);

$sources = array(
	'adyen'    => array(
		'label'      => 'Adyen',
		'link'       => '',
		'components' => array(

		),
	),
	'ingenico' => array(
		'label'      => 'Ingenico',
		'link'       => '',
		'components' => array(

		),
	),
	'mollie' => array(
		'label'      => 'Mollie',
		'link'       => '',
		'components' => array(

		),
	),
	'paypal' => array(
		'label'      => 'PayPal',
		'link'       => '',
		'components' => array(

		),
	),
	'pay.nl' => array(
		'label'      => 'Pay.nl',
		'link'       => '',
		'components' => array(

		),
	),
	'woocommerce' => array(
		'label'      => 'WooCommerce',
		'link'       => '',
		'components' => array(
			'line_1'       => 'address_1',
			'line_2'       => 'address_2',
			'city'         => 'city',
			'postcode'     => 'postcode',
			'country_code' => 'country',
			'state_code'   => 'state',
		),
	),
	'gravityforms' => array(
		'label'      => 'Gravity Forms',
		'link'       => '',
		'components' => array(
			'line_1'  => 'street',
			'line_2'  => 'street2',
			'city'    => 'city',
			'state'   => 'state',
			'zip'     => 'zip',
			'country' => 'country',
		),
	),
	'salesforce' => array(
		'label'      => 'Salesforce',
		'link'       => '',
		'components' => array(
			'address' => 'Address',
			'street'  => 'Street',
		),
	),
	'suitecrm' => array(
		'label'      => 'SuiteCRM',
		'link'       => '',
		'components' => array(
			'line_1' => 'street',
			'line_2' => 'street_2',
			'line_3' => 'street_3',
			'line_4' => 'street_4',
		),
	),
	'schema.org' => array(
		'label'      => 'Schema.org',
		'link'       => '',
		'components' => array(
			'address' => 'streetAddress',
		),
	),
	'vcard' => array(
		'label'      => 'vCard',
		'link'       => '',
		'components' => array(
			'address' => 'street address',
		),
	),
);


?>
<!doctype html>

<html lang="en">
	<head>
		<meta charset="utf-8">

		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

		<title>Pronamic Pay - Address</title>
	</head>

	<body>
		<h1>Pronamic Pay - Address</h1>

		<table class="table table-sm table-striped table-hover">
			<thead>
				<tr>
					<th scope="col">Component</th>

					<?php foreach ( $sources as $source ) : ?>

						<th scope="col"><?php echo $source['label']; ?></th>

					<?php endforeach; ?>

				</tr>
			</thead>

			<tbody>
				
				<?php foreach ( $components as $key => $data ) : ?>

					<tr>
						<th scope="row">
							<?php

							if ( is_array( $data ) ) {
								echo $data['label'];
								echo ' ';

								printf(
									'<a href="%s"><i class="fas fa-info-circle"></i></a>',
									$data['link']
								);
							} else {
								echo $data;
							}

							?>
						</th>

						<?php foreach ( $sources as $source ) : ?>

							<td>
								<?php

								if ( isset( $source['components'][ $key ] ) ) {
									printf(
										'<code>%s</code>',
										$source['components'][ $key ]
									);
								}

								?>
							</td>

						<?php endforeach; ?>
					</tr>

				<?php endforeach; ?>

			</tbody>
		</table>

		<!-- Optional JavaScript -->
		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	</body>
</html>
