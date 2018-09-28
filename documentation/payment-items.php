<?php

$matrix = array();

$components = array(
	'id'                    => 'ID',
	'number'                => 'Number',
	'reference'             => 'Reference',
	'sku'                   => array(
		'label' => 'SKU',
		'link'  => 'https://en.wikipedia.org/wiki/Stock_keeping_unit',
	),
	'name'                  => 'Name',
	'label'                 => 'Label',
	'description'           => 'Description',
	'quantity'              => 'Quantity',
	'quantity_unit'         => 'Quantity Unit',
	'price'                 => 'Price',
	'unit_price'            => 'Unit Price',
	'net_price'             => 'Net Price',
	'total'                 => 'Total',
	'total_amount'          => 'Total Amount',
	'total_discount_amount' => 'Total Discount Amount',
	'total_tax_amount'      => 'Total Tax Amount',
	'subtotal'              => 'Subtotal',
	'net_total'             => 'Net Total',
	'amount'                => 'Amount',
	'currency'              => 'Currency',
	'tax'                   => 'Tax',
	'tax_amount'            => 'Tax Amount',
	'tax_class'             => 'Tax Class',
	'tax_percentage'        => 'Tax Percentage',
	'tax_rate'              => 'Tax Rate',
	'vat'                   => array(
		'label' => 'VAT',
		'link'  => 'https://euvatrates.com/rates.json',
	),
	'vat_code'       => array(
		'label' => 'VAT Code',
		'link'  => 'https://euvatrates.com/rates.json',
	),
	'vat_category'   => array(
		'label' => 'VAT Category',
		'link'  => 'https://euvatrates.com/rates.json',
	),
	'shipping'             => 'Shipping',
	'type'                 => 'Type',
	'url'                  => 'URL',
	'image_url'            => 'Image URL',
	'status'               => 'Status',
	'stock'                => 'Stock',
	'discount'             => 'Discount',
	'pending'              => 'Pending',
	'category'             => 'Category',
	'discount'             => 'Discount',
	'product_id'           => 'Product ID',
	'product_url'          => 'Product URL',
);

$sources = array(
	'adyen'    => array(
		'label'      => 'Adyen',
		'link'       => 'https://docs.adyen.com/developers/api-reference/common-api#address',
		'components' => array(

		),
	),
	'ingenico' => array(
		'label'      => 'Ingenico',
		'link'       => 'https://epayments-api.developer-ingenico.com/s2sapi/v1/en_US/java/payments/create.html#payments-create-request-example',
		'components' => array(

		),
	),
	'klarna' => array(
		'label'      => 'Klarna',
		'link'       => 'https://developers.klarna.com/api/#payments-api-create-a-new-order',
		'components' => array(
			'type'  => array(
				'name'        => 'type',
				'description' => 'Order line type. Possible values:
physical
discount
shipping_fee
sales_tax
digital
gift_card
store_credit
surcharge',
			),
			'reference'  => array(
				'name'        => 'reference',
				'description' => 'Article number, SKU or similar. Max length is 64 characters.',
			),
			'name'  => array(
				'name'        => 'name',
				'description' => 'Descriptive item name.',
				'required'    => true,
			),
			'quantity'  => array(
				'name'        => 'quantity',
				'description' => 'Non-negative. The item quantity.',
				'required'    => true,
			),
			'quantity_unit'  => array(
				'name'        => 'quantity_unit',
				'description' => 'Unit used to describe the quantity, e.g. kg, pcs... If defined has to be 1-8 characters.',
			),
			'unit_price'  => array(
				'name'        => 'unit_price',
				'description' => 'Minor units. Includes tax, excludes discount. (max value: 100000000)',
				'required'    => true,
			),
			'tax_rate'  => array(
				'name'        => 'tax_rate',
				'description' => 'Non-negative. In percent, two implicit decimals. I.e 2500 = 25%.',
			),
			'total_amount'  => array(
				'name'        => 'total_amount',
				'description' => 'Includes tax and discount. Must match (quantity unit_price) - total_discount_amount within ±quantity. (max value: 100000000)',
				'required'    => true,
			),
			'total_discount_amount' => array(
				'name'        => 'total_discount_amount',
				'description' => 'Non-negative minor units. Includes tax.',
			),
			'total_tax_amount'  => array(
				'name'        => 'total_tax_amount',
				'description' => 'Must be within ±1 of total_amount - total_amount 10000 / (10000 + tax_rate). Negative when type is discount.',
			),
			'product_url'  => array(
				'name'        => 'product_url',
				'description' => 'URL to an image that can be later embedded in communications between Klarna and the customer. (max 1024 characters)',
			),
			'image_url'  => array(
				'name'        => 'image_url',
				'description' => 'URL to an image that can be later embedded in communications between Klarna and the customer. (max 1024 characters)',
			),
		),
	),
	'mollie' => array(
		'label'      => 'Mollie',
		'link'       => 'https://docs.mollie.com/guides/common-data-types#address-object',
		'components' => array(

		),
	),
	'multisafepay' => array(
		'label'      => 'MultiSafepay',
		'link'       => 'https://github.com/wp-pay-gateways/multisafepay',
		'components' => array(

		),
	),
	'omnikassa-2' => array(
		'label'      => 'OmniKassa 2.0',
		'link'       => 'https://github.com/wp-pay-gateways/omnikassa-2/blob/master/documentation/handleiding-api-koppeling-rabo-smartpin-en_29970886.pdf',
		'components' => array(
			'id'  => array(
				'name'        => 'id',
				'description' => 'Item id',
				'example'     => 'A1000',
			),
			'name' => array(
				'name'        => 'name',
				'description' => 'Item name',
				'required'    => true,
				'example'     => 'Jackie O Round Sunglasses',
			),
			'description'   => array(
				'name'        => 'description',
				'description' => 'Item description',
				'example'     => 'These distinct, feminine frames balance a classic Jackie-O styling with a modern look.',
			),
			'quantity'  => array(
				'name'        => 'quantity',
				'description' => 'number: 1-2147483647',
				'required'    => true,
				'example'     => '1',
			),
			'amount'  => array(
				'name'        => 'amount',
				'description' => 'The amount in cents, including VAT, of the item each, see below for more details.',
				'required'    => true,
				'example'     => 'If the piece price of an order item (excluding VAT) is € 12.98 and a VAT rate of 21% is applied. The piece price including VAT € 12.98 + 21% = € 15.71',
			),
			'tax'  => array(
				'name'        => 'tax',
				'description' => 'The VAT of the item each, see below for more details',
			),
			'category'  => array(
				'name'        => 'category',
				'description' => 'Product category: PHYSICAL or DIGITAL',
				'required'    => true,
				'example'     => 'PHYSICAL',
			),
			'vat_category'  => array(
				'name'        => 'vatCategory',
				'description' => 'The VAT category of the product. The values refer to the different rates that are used in the Netherlands: 1 = High (currently 21%), 2 = Low (currently 6%), 3 = Zero (0%), 4 = None (exempt from VAT)',
				'example'     => '1',
			),
		),
	),
	'paypal' => array(
		'label'      => 'PayPal',
		'link'       => 'https://developer.paypal.com/docs/api/payments/v1/#definition-item',
		'components' => array(

		),
	),
	'pay.nl' => array(
		'label'      => 'Pay.nl',
		'link'       => 'https://www.pay.nl/docs/developers.php',
		'components' => array(
			'id'  => array(
				'name'        => 'productId',
				'type'        => 'integer',
				'description' => 'Het interne product id binnen uw systeem',
			),
			'description' => array(
				'name'        => 'description',
				'type'        => 'string',
				'description' => 'Omschrijving van het bestelde product',
			),
			'price'  => array(
				'name'        => 'price',
				'type'        => 'integer',
				'description' => 'Productprijs in centen, dus €3,50 is 350',
			),
			'quantity'  => array(
				'name'        => 'quantity',
				'type'        => 'integer',
				'description' => 'Aantal bestelde producten met het betreffende product id',
			),
			'vat_code'  => array(
				'name'        => 'vatCode',
				'type'        => 'string',
				'description' => 'BTW code, mogelijke waarden: H: high, N: zero, L: low',
			),
			'vat_percentage'  => array(
				'name'        => 'vatPercentage',
			),
			'discount'        => array(
				'name'        => 'discount',
			),
		),
	),
	'sisow' => array(
		'label'      => 'Sisow',
		'link'       => 'https://www.sisow.nl/implementatie-api',
		'components' => array(
			'id'  => array(
				'name'        => 'product_id_x',
				'description' => 'Product code',
			),
			'description'  => array(
				'name'        => 'product_description_x',
				'description' => 'Omschrijving van het product',
			),
			'quantity'  => array(
				'name'        => 'product_quantity_x',
				'description' => 'Aantal besteld',
			),
			'net_price'  => array(
				'name'        => 'product_netprice_x',
				'description' => 'Netto prijs van het product',
			),
			'total'  => array(
				'name'        => 'product_total_x',
				'description' => 'Totaal bedrag van de orderregel inclusief BTW',
			),
			'net_total'  => array(
				'name'        => 'product_nettotal_x',
				'description' => 'Totaal bedrag van de orderregel exclusief BTW',
			),
			'tax'  => array(
				'name'        => 'product_tax_x',
				'description' => 'BTW bedrag in centen van de orderregel',
			),
			'tax_rate'  => array(
				'name'        => 'product_taxrate_x',
				'description' => 'BTW percentage (21% doorgeven als 2100)',
			),
		),
	),
	'easy-digital-downloads' => array(
		'label'      => 'Easy Digital Downloads',
		'link'       => 'https://github.com/easydigitaldownloads/easy-digital-downloads/blob/2.9.7/includes/admin/reporting/class-export-payments.php',
		'components' => array(

		),
	),
	'gravityforms' => array(
		'label'      => 'Gravity Forms',
		'link'       => 'https://github.com/wp-premium/gravityforms/blob/2.3.2/includes/fields/class-gf-field-address.php#L48-L52',
		'components' => array(

		),
	),
	'memberpress' => array(
		'label'      => 'MemberPress',
		'link'       => 'https://github.com/wp-premium/memberpress-business/blob/1.3.36/app/models/MeprUser.php#L1296-L1317',
		'components' => array(

		),
	),
	'restrict-content-pro' => array(
		'label'      => 'Restrict Content Pro',
		'link'       => 'https://github.com/restrictcontentpro/restrict-content',
		'components' => array(

		),
	),
	'woocommerce' => array(
		'label'      => 'WooCommerce',
		'links'      => array(
			'https://github.com/woocommerce/woocommerce/blob/master/includes/class-wc-order-item.php',
			'https://github.com/woocommerce/woocommerce/blob/3.4.5/includes/api/legacy/v1/class-wc-api-orders.php#L98-L234'
		),
		'components' => array(
			'id'  => array(
				'name'        => 'id',
			),
			'subtotal'  => array(
				'name'        => 'subtotal',
				'description' => array(
					'Get line subtotal - this is the cost before discount.',
					'wc_format_decimal( $order->get_line_subtotal( $item ), 2 )',
					'public function get_line_subtotal( $item, $inc_tax = false, $round = true )',
				),
				'links'       => array(
					'https://github.com/woocommerce/woocommerce/blob/3.4.5/includes/api/legacy/v1/class-wc-api-orders.php#L181',
					'https://github.com/woocommerce/woocommerce/blob/3.4.5/includes/abstracts/abstract-wc-order.php#L1511-L1533',
				),
			),
			'total'  => array(
				'name'        => 'total',
				'description' => array(
					'Calculate line total - useful for gateways.',
					'wc_format_decimal( $order->get_line_total( $item ), 2 )',
					'public function get_line_total( $item, $inc_tax = false, $round = true )',
				),
				'links'       => array(
					'https://github.com/woocommerce/woocommerce/blob/3.4.5/includes/api/legacy/v1/class-wc-api-orders.php#L182',
					'https://github.com/woocommerce/woocommerce/blob/3.4.5/includes/abstracts/abstract-wc-order.php#L1559-L1579',
				),
			),
			'total_tax'  => array(
				'name'        => 'total_tax',
				'description' => array(
					'Get line tax - useful for gateways.',
					'wc_format_decimal( $order->get_line_tax( $item ), 2 )',
					'public function get_line_tax( $item )',
					'public function get_total_tax( $context = \'view\' )',
				),
				'links'       => array(
					'https://github.com/woocommerce/woocommerce/blob/3.4.5/includes/api/legacy/v1/class-wc-api-orders.php#L183',
					'https://github.com/woocommerce/woocommerce/blob/3.4.5/includes/abstracts/abstract-wc-order.php#L1599-L1607',
					'https://github.com/woocommerce/woocommerce/blob/3.4.5/includes/abstracts/abstract-wc-order.php#L363-L371',
				),
			),
			'price'  => array(
				'name'        => 'price',
				'description' => array(
					'Calculate item cost - useful for gateways.',
					'wc_format_decimal( $order->get_item_total( $item ), 2 )',
					'public function get_item_total( $item, $inc_tax = false, $round = true )',
				),
				'links'       => array(
					'https://github.com/woocommerce/woocommerce/blob/3.4.5/includes/api/legacy/v1/class-wc-api-orders.php#L184',
					'https://github.com/woocommerce/woocommerce/blob/3.4.5/includes/abstracts/abstract-wc-order.php#L1535-L1557',
				),
			),
			'quantity'  => array(
				'name'        => 'quantity',
			),
			'tax_class'  => array(
				'name'        => 'tax_class',
			),
			'name'  => array(
				'name'        => 'name',
			),
			'product_id'  => array(
				'name'        => 'product_id',
			),
			'sku'  => array(
				'name'        => 'sku',
			),
		),
	),
	'google-analytics' => array(
		'label'      => 'Google Analytics',
		'link'       => 'https://developers.google.com/analytics/devguides/collection/analyticsjs/ecommerce',
		'components' => array(
			'id'  => array(
				'name'        => 'id',
				'description' => 'The transaction ID. This ID is what links items to the transactions to which they belong. (e.g. 1234)',
				'required'    => true,
			),
			'name'  => array(
				'name'        => 'name',
				'description' => 'The item name. (e.g. Fluffy Pink Bunnies)',
				'required'    => true,
			),
			'sku'  => array(
				'name'        => 'sku',
				'description' => 'Specifies the SKU or item code. (e.g. SKU47)',
			),
			'category'  => array(
				'name'        => 'category',
				'description' => 'The category to which the item belongs (e.g. Party Toys)',
			),
			'price'  => array(
				'name'        => 'price',
				'description' => 'The individual, unit, price for each item. (e.g. 11.99)',
			),
			'quantity'  => array(
				'name'        => 'quantity',
				'description' => 'The number of units purchased in the transaction. If a non-integer value is passed into this field (e.g. 1.5), it will be rounded to the closest integer value.',
			),
		),
	),
	'mailchimp' => array(
		'label'      => 'MailChimp',
		'link'       => 'https://developer.mailchimp.com/documentation/mailchimp/reference/ecommerce/stores/carts/lines/',
		'components' => array(
			'id'  => array(
				'name'        => 'id',
				'description' => 'A unique identifier for the cart line item.',
			),
			'quantity'  => array(
				'name'        => 'quantity',
				'description' => 'The quantity of a cart line item.',
			),
			'price'  => array(
				'name'        => 'price',
				'description' => 'The price of a cart line item.',
			),
		),
	),
	'apple-pay' => array(
		'label'      => 'Apple Pay',
		'link'       => 'https://developer.apple.com/documentation/apple_pay_on_the_web/applepaylineitem',
		'components' => array(
			'label'  => array(
				'name'        => 'label',
				'description' => 'A short, localized description of the line item.',
				'required'    => true,
			),
			'amount' => array(
				'name'        => 'amount',
				'description' => 'The monetary amount of the line item.',
				'required'    => true,
			),
			'type'   => array(
				'name'        => 'type',
				'description' => 'A value that indicates whether the line item is final or pending.',
			),
		),
	),
	'schema.org' => array(
		'label'      => 'Schema.org',
		'link'       => 'https://schema.org/OrderItem',
		'components' => array(
			'number'       => array(
				'name'        => 'orderItemNumber',
				'description' => 'The identifier of the order item',
			),
			'status'       => array(
				'name'        => 'orderItemStatus',
				'description' => 'The current status of the order item.',
			),
			'quantity'     => array(
				'name'        => 'orderQuantity',
				'description' => 'The number of the item ordered. If the property is not set, assume the quantity is one.',
			),
		),
	),
	'payment-request' => array(
		'label'      => 'Payment Request API',
		'link'       => 'https://www.w3.org/TR/payment-request/#dom-paymentitem',
		'components' => array(
			'label'       => array(
				'name'        => 'label',
				'description' => 'A human-readable description of the item. The user agent may display this to the user.',
				'required'    => true,
			),
			'amount'       => array(
				'name'        => 'amount',
				'description' => 'A PaymentCurrencyAmount containing the monetary amount for the item.',
				'required'    => true,
			),
			'pending'       => array(
				'name'        => 'pending',
				'description' => 'A boolean. When set to true it means that the amount member is not final. This is commonly used to show items such as shipping or tax amounts that depend upon selection of shipping address or shipping option. User agents MAY indicate pending fields in the user interface for the payment request.',
				'required'    => true,
			),
		),
	),
);

function print_link( $array ) {
	$links = array();

	if ( array_key_exists( 'links', $array ) ) {
		$links = $array['links'];
	}

	if ( array_key_exists( 'link', $array ) ) {
		$links[] = $array['link'];
	}

	$html = array();

	foreach ( $links as $link ) {
		$html[] = sprintf(
			'<a href="%s"><i class="fas fa-info-circle"></i></a>',
			$link
		);
	}

	echo implode( ' ', $html );
}

?>
<!doctype html>

<html lang="en">
	<head>
		<meta charset="utf-8">

		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

		<title>Pronamic Pay - Payment Items</title>
	</head>

	<body>
		<h1>Pronamic Pay - Payment Items</h1>

		<table class="table table-sm table-striped table-hover">
			<thead>
				<tr>
					<th scope="col">Component</th>

					<?php foreach ( $sources as $source ) : ?>

						<th scope="col">
							<?php

							echo $source['label'];

							echo ' ';

							print_link( $source );

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

								print_link( $data );
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

										if ( array_key_exists( 'description', $component ) ) {
											$description = $component['description'];
											$description = is_array( $description ) ? $description : array( $description );

											$tip = implode( "\r\n", $description );
										}

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

									echo ' ';

									print_link( $component );
								}

								?>
							</td>

						<?php endforeach; ?>
					</tr>

				<?php endforeach; ?>

			</tbody>
		</table>

		<h2>Links</h2>

		<table class="table table-striped">
			<thead>
				<tr>
					<th scope="col">Title</th>
					<th scope="col">URL</th>
					<th scope="col">Description</th>
				</tr>
			</thead>

			<tbody>
				<tr>
					<td>
						Payment Request API - `PaymentItem` dictionary
					</td>
					<td>
						<a href="https://www.w3.org/TR/payment-request/#dom-paymentitem">https://www.w3.org/TR/payment-request/#dom-paymentitem</a>						
					</td>
					<td></td>
				</tr>
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
