<?php

$matrix = array();

$components = array(
	'address'                => 'Address',
	'line_1'                 => 'Line 1',
	'line_2'                 => 'Line 2',
	'line_3'                 => 'Line 3',
	'line_4'                 => 'Line 4',
	'street'                 => 'Street',
	'street_name'            => 'Street Name',
	'street_number'          => 'Street Number',
	'street_and_number'      => 'Street and Number',
	'street_additional'      => 'Street Additional',
	'house_number'           => 'House Number',
	'house_number_addition'  => 'House Number Addition',
	'house_number_extension' => 'House Number Extension',
	'house_extension'        => 'House Extension',
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
	'additional_info'        => 'Additional Info',
);

$sources = array(
	'adyen'    => array(
		'label'      => 'Adyen',
		'link'       => 'https://docs.adyen.com/developers/api-reference/common-api#address',
		'components' => array(
			'city'         => array(
				'name'        => 'city',
				'description' => 'The city name.',
				'required'    => true,
			),
			'country_code' => array(
				'name'        => 'country',
				'description' => 'A valid value is an ISO 2-character country code.',
				'required'    => true,
			),
			'house_number' => array(
				'name'        => 'houseNumberOrName',
				'description' => 'The house number or name.',
				'required'    => true,
			),
			'postal_code'  => array(
				'name'        => 'postalCode',
				'description' => 'The postal code with a maximum of 5 characters for USA and maximum of 10 characters for any other country.',
				'required'    => false,
			),
			'state'        => array(
				'name'        => 'stateOrProvince',
				'description' => 'For USA or Canada, a valid 2-character abbreviation for the state or province respectively. For other countries any abbreviation with maximum 3 characters for the state or province.',
				'required'    => false,
			),
			'street'       => array(
				'name'        => 'street',
				'description' => 'The street name.',
				'required'    => true,
			),
		),
	),
	'ingenico' => array(
		'label'      => 'Ingenico',
		'link'       => 'https://epayments-api.developer-ingenico.com/s2sapi/v1/en_US/java/payments/create.html#payments-create-request-example',
		'components' => array(
			'additional_info'       => array(
				'name'        => 'additionalInfo',
				'description' => 'Additional address information',
				'required'    => false,
			),
			'city'       => array(
				'name'        => 'city',
				'description' => 'City',
				'required'    => 'depends',
			),
			'country_code'       => array(
				'name'        => 'countryCode',
				'description' => 'ISO 3166-1 alpha-2 country code',
				'required'    => 'depends',
			),
			'house_number'       => array(
				'name'        => 'houseNumber',
				'description' => 'House number',
				'required'    => 'depends',
			),
			'state'       => array(
				'name'        => 'state',
				'description' => 'Full name of the state or province',
				'required'    => false,
			),
			'state_code'       => array(
				'name'        => 'stateCode',
				'description' => 'State code',
				'required'    => false,
			),
			'street'       => array(
				'name'        => 'street',
				'description' => 'Streetname',
				'required'    => 'depends',
			),
			'zip_code'       => array(
				'name'        => 'zip',
				'description' => 'Zip code',
				'required'    => 'depends',
			),
		),
	),
	'klarna' => array(
		'label'      => 'Klarna',
		'link'       => 'https://developers.klarna.com/en/nl/kpm/checkout-api#address-structure',
		'components' => array(
			'street'       => array(
				'name'        => 'street',
				'type'        => 'string',
				'description' => 'Street address',
				'required'    => true,
			),
			'house_number' => array(
				'name'        => 'house_number',
				'type'        => 'string',
				'description' => 'House number. Used in Germany and Netherlands. For all other countries you can send in an empty string.',
			),
			'house_extension' => array(
				'name'        => 'house_extension',
				'type'        => 'string',
				'description' => 'House extension. Only used in Netherlands, if the customer has one. For all other countries you can send in an empty string.',
			),
			'zip'       => array(
				'name'        => 'zip',
				'type'        => 'string',
				'description' => 'Zip Code',
				'required'    => true,
			),
			'city'       => array(
				'name'        => 'city',
				'type'        => 'string',
				'description' => 'City',
				'required'    => true,
			),
			'country'       => array(
				'name'        => 'country',
				'type'        => 'integer',
				'description' => 'Code for the country where the consumer lives:
15: Austria
59: Denmark
73: Finland
81: Germany
154: Netherlands
164: Norway
209: Sweden',
				'required'    => true,
			),
		),
	),
	'mollie' => array(
		'label'      => 'Mollie',
		'link'       => 'https://docs.mollie.com/guides/common-data-types#address-object',
		'components' => array(
			'street_and_number'       => array(
				'name'        => 'streetAndNumber',
				'description' => 'The card holder’s street and street number.',
			),
			'street_additional' => array(
				'name'        => 'streetAdditional',
				'description' => 'Any additional addressing details, for example an apartment number.',
				'optional'    => true,
			),
			'postal_code'       => array(
				'name'        => 'postalCode',
				'description' => 'The card holder’s postal code.',
			),
			'city'       => array(
				'name'        => 'city',
				'description' => 'The card holder’s city.',
			),
			'region'       => array(
				'name'        => 'region',
				'description' => 'The card holder’s region.',
				'optional'    => true,
			),
			'country_code'       => array(
				'name'        => 'country',
				'description' => 'The card holder’s country in ISO 3166-1 alpha-2 format.',
			),
		),
	),
	'omnikassa-2' => array(
		'label'      => 'OmniKassa 2.0',
		'link'       => 'https://github.com/wp-pay-gateways/omnikassa-2/blob/master/documentation/handleiding-api-koppeling-rabo-smartpin-en_29970886.pdf',
		'components' => array(
			'street'                => array(
				'name'        => 'street',
				'description' => 'Street.',
				'required'    => true,
			),
			'house_number'          => array(
				'name'        => 'houseNumber',
				'description' => 'House number.',
				'required'    => false,
			),
			'house_number_addition' => array(
				'name'        => 'houseNumberAddition',
				'description' => 'House number additions',
				'required'    => false,
			),
			'postal_code'           => array(
				'name'        => 'postalCode',
				'description' => 'Postal code',
				'required'    => true,
			),
			'city'                  => array(
				'name'        => 'city',
				'description' => 'City',
				'required'    => true,
			),
			'country_code'          => array(
				'name'        => 'countryCode',
				'description' => 'Country code, ISO 3166-1 alpha-2',
				'required'    => true,
			),
		),
	),
	'paypal' => array(
		'label'      => 'PayPal',
		'link'       => 'https://developer.paypal.com/docs/api/payments/v1/#definitions',
		'components' => array(
			'line_1'          => array(
				'name'        => 'line1',
				'description' => 'The first line of the address. For example, number, street, and so on.',
			),
			'line_2'          => array(
				'name'        => 'line2',
				'description' => 'The second line of the address. For example, suite or apartment number.',
			),
			'city'          => array(
				'name'        => 'city',
				'description' => 'The city name.',
			),
			'country_code'   => array(
				'name'        => 'country_code',
				'description' => 'The two-character ISO 3166-1 code that identifies the country or region.',
			),
			'postal_code'   => array(
				'name'        => 'postal_code',
				'description' => 'The postal code, which is the zip code or equivalent. Typically required for countries with a postal code or an equivalent. See postal code.',
			),
			'state'   => array(
				'name'        => 'state',
				'description' => 'The code for a US state or the equivalent for other countries. Required for transactions if the address is in one of these countries: Argentina, Brazil, Canada, India, Italy, Japan, Mexico, Thailand, or United States. Maximum length is 40 single-byte characters.',
			),

		),
	),
	'pay.nl' => array(
		'label'      => 'Pay.nl',
		'link'       => 'https://www.pay.nl/docs/developers.php',
		'components' => array(
			'street_name'          => array(
				'name'        => 'streetName',
				'description' => 'Straatnaam van de eindgebruiker',
			),
			'street_number'          => array(
				'name'        => 'streetNumber',
				'description' => 'Huisnummer van de eindgebruiker',
				'warning'     => 'Street Number = House Number?',
			),
			'zip_code'          => array(
				'name'        => 'zipCode',
				'description' => 'Postcode van de eindgebruiker',
			),
			'city'          => array(
				'name'        => 'city',
				'description' => 'Woonplaats van de eindgebruiker',
			),
			'country_code'          => array(
				'name'        => 'countryCode',
				'description' => 'Landcode volgens ISO 3166 (tweelettercode). U vindt een overzicht op https://admin.pay.nl/data/countries',
			),
		),
	),
	'sisow' => array(
		'label'      => 'Sisow',
		'link'       => 'https://www.sisow.nl/implementatie-api',
		'components' => array(
			'line_1'          => array(
				'name'        => 'billing_address1',
				'description' => 'Factuuradres – Adresregel 1',
				'optional'    => true,
			),
			'line_2'          => array(
				'name'        => 'billing_address2',
				'description' => 'Factuuradres – Adresregel 2',
				'optional'    => true,
			),
			'zip'             => array(
				'name'        => 'billing_zip',
				'description' => 'Factuuradres – Postcode',
				'optional'    => true,
			),
			'city'             => array(
				'name'        => 'billing_city',
				'description' => 'Factuuradres – Stad',
				'optional'    => true,
			),
			'country'             => array(
				'name'        => 'billing_country',
				'description' => 'Factuuradres – Land',
				'optional'    => true,
			),
			'country_code'             => array(
				'name'        => 'billing_countrycode',
				'description' => 'Factuuradres – Lancode (ISO code (2 letterig), standaard NL)',
				'optional'    => true,
			),
		),
	),
	'easy-digital-downloads' => array(
		'label'      => 'Easy Digital Downloads',
		'link'       => 'https://github.com/easydigitaldownloads/easy-digital-downloads/blob/2.9.7/includes/admin/reporting/class-export-payments.php',
		'components' => array(
			'line_1'  => 'address1',
			'line_2'  => 'address2',
			'city'    => 'city',
			'state'   => 'state',
			'zip'     => 'zip',
			'country' => 'country',
		),
	),
	'gravityforms' => array(
		'label'      => 'Gravity Forms',
		'link'       => 'https://github.com/wp-premium/gravityforms/blob/2.3.2/includes/fields/class-gf-field-address.php#L48-L52',
		'components' => array(
			'line_1'  => 'street',
			'line_2'  => 'street2',
			'city'    => 'city',
			'state'   => 'state',
			'zip'     => 'zip',
			'country' => 'country',
		),
	),
	'memberpress' => array(
		'label'      => 'MemberPress',
		'link'       => 'https://github.com/wp-premium/memberpress-business/blob/1.3.36/app/models/MeprUser.php#L1296-L1317',
		'components' => array(
			'line_1'  => 'mepr-address-one',
			'line_2'  => 'mepr-address-two',
			'city'    => 'mepr-address-city',
			'state'   => 'mepr-address-state',
			'zip'     => 'mepr-address-zip',
			'country' => 'mepr-address-country',
		),
	),
	'restrict-content-pro' => array(
		'label'      => 'Restrict Content Pro',
		'link'       => 'https://github.com/restrictcontentpro/restrict-content',
		'components' => array(
			'address' => 'rcp_card_address',
			'city'    => 'rcp_card_city',
			'state'   => 'rcp_card_state',
			'zip'     => 'rcp_card_zip',
			'country' => 'rcp_card_country',
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

						<th scope="col">
							<?php

							echo $source['label'];

							if ( $source['link'] ) {
								echo ' ';

								printf(
									'<a href="%s"><i class="fas fa-info-circle"></i></a>',
									$source['link']
								);
							}

							?>
						</th>

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
									$component = $source['components'][ $key ];

									$name     = null;
									$tip      = null;
									$optional = null;
									$warning  = null;

									if ( is_array( $component ) ) {
										$name     = $component['name'];
										$tip      = $component['description'];

										if ( array_key_exists( 'optional', $component ) ) {
											$optional = $component['optional'];
										}

										if ( array_key_exists( 'required', $component ) ) {
											$optional = ! $component['required'];
										}

										if ( array_key_exists( 'warning', $component ) ) {
											$warning = $component['warning'];
										}
									} else {
										$name = $component;
									}

									printf(
										'<code>%s</code>',
										$name
									);

									if ( ! empty( $tip ) ) {
										echo ' ';

										printf(
											'<i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="%s"></i>',
											$tip
										);
									}

									if ( null !== $optional ) {
										echo ' ';

										printf(
											'<i class="fas %s" data-toggle="tooltip" data-placement="top" title="%s"></i>',
											$optional ? 'fa-dot-circle' : 'fa-circle',
											$optional ? 'Optional' : 'Required'
										);
									}

									if ( null !== $warning ) {
										echo ' ';

										printf(
											'<i class="fas fa-exclamation-triangle" data-toggle="tooltip" data-placement="top" title="%s"></i>',
											$warning
										);
									}
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

		<script type="text/javascript">
			jQuery( document ).ready( function( $ ) {
				$( '[data-toggle="tooltip"]' ).tooltip();
			} );
		</script>
	</body>
</html>
