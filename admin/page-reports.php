<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<div id="poststuff">
		<div class="postbox">
			<div class="inside">
				<div id="chart1" style="height:500px; width: 100%;"></div>
			</div>
		</div>
	</div>

<?php

wp_enqueue_script( 'flot' );
wp_enqueue_script( 'flot-time' );
wp_enqueue_script( 'accounting' );

// @see https://github.com/woothemes/woocommerce/blob/master/includes/admin/reports/class-wc-report-sales-by-date.php

function pronamic_pay_get_report( $status, $start, $end ) {
	global $wpdb;
	
	$interval = new DateInterval( 'P1M' );
	$period   = new DatePeriod( $start, $interval, $end );

	$date_format = '%Y-%m';

	$query = $wpdb->prepare( "
			SELECT
				DATE_FORMAT( post.post_date, %s ) AS month,
				SUM( meta_amount.meta_value ) AS amount
			FROM
				$wpdb->posts AS post
					LEFT JOIN
				$wpdb->postmeta AS meta_amount
						ON post.ID = meta_amount.post_id AND meta_amount.meta_key = '_pronamic_payment_amount'
					LEFT JOIN
				$wpdb->postmeta AS meta_status
						ON post.ID = meta_status.post_id AND meta_status.meta_key = '_pronamic_payment_status'
			WHERE
				post.post_type = 'pronamic_payment'
					AND
				post.post_date BETWEEN %s AND %s
					AND
				meta_status.meta_value = %s
			GROUP BY
				YEAR( post.post_date ), MONTH( post.post_date )
			ORDER BY
				post_date
			;
		", 
		$date_format,
		$start->format( 'Y-m-d' ),
		$end->format( 'Y-m-d' ),
		$status 
	);

	$data = $wpdb->get_results( $query, OBJECT_K );

	$report = array();

	foreach ( $period as $date ) {
		$key = $date->format( 'Y-m' );

		$amount = 0;
		if ( isset( $data[ $key ] ) ) {
			$amount = (float) $data[ $key ]->amount;
		}

		$report[] = array(
			// Flot requires milliseconds so multiply with 1000
			$date->getTimestamp() * 1000,
			$amount,
		);
	}

	return $report;
}

$start = new DateTime( 'First day of January' );
$end   = new DateTime( 'Last day of December' );
 
$data = array(
	'open'      => pronamic_pay_get_report( 'Open', $start, $end ),
	'success'   => pronamic_pay_get_report( 'Success', $start, $end ),
	'cancelled' => pronamic_pay_get_report( 'Cancelled', $start, $end ),
	'expired'   => pronamic_pay_get_report( 'Expired', $start, $end ),
	'failure'   => pronamic_pay_get_report( 'Failure', $start, $end ),
);

global $wp_locale;

?>
<script class="code" type="text/javascript">
	var data = jQuery.parseJSON( '<?php echo json_encode( $data ); ?>' );

	series = [
		{
			label: 'Open',
			data: data.unknown,
			yaxis: 2,
			color: '#b1d4ea',
			points: { show: true, radius: 5, lineWidth: 2, fillColor: '#fff', fill: true },
			lines: { show: true, lineWidth: 2, fill: false },
			shadowSize: 0,
			prepend_tooltip: "&euro;&nbsp;"	
		},
		{
			label: 'Success',
			data: data.success,
			yaxis: 2,
			color: '#3498db',
			points: { show: true, radius: 6, lineWidth: 4, fillColor: '#fff', fill: true },
			lines: { show: true, lineWidth: 5, fill: false },
			shadowSize: 0,
			prepend_tooltip: "&euro;&nbsp;"
		},
		{
			label: 'Cancelled',
			data: data.cancelled,
			yaxis: 2,
			color: '#f1c40f',
			points: { show: true, radius: 5, lineWidth: 2, fillColor: '#fff', fill: true },
			lines: { show: true, lineWidth: 2, fill: false },
			shadowSize: 0,
			prepend_tooltip: "&euro;&nbsp;"
		},
		{
			label: 'Failure',
			data: data.failure,
			yaxis: 2,
			color: '#e74c3c',
			points: { show: true, radius: 5, lineWidth: 2, fillColor: '#fff', fill: true },
			lines: { show: true, lineWidth: 2, fill: false },
			shadowSize: 0,
			prepend_tooltip: "&euro;&nbsp;"
		},
		{
			label: 'Expired',
			data: data.expired,
			yaxis: 2,
			color: '#dbe1e3',
			points: { show: true, radius: 5, lineWidth: 2, fillColor: '#fff', fill: true },
			lines: { show: true, lineWidth: 2, fill: false },
			shadowSize: 0,
			prepend_tooltip: "&euro;&nbsp;"
		},
	];

	jQuery( document ).ready( function( $ ) {
		var container = $( '#chart1' );

		var plot1 = $.plot( container, series, {
			legend: {
				show: false
			},
			grid: {
				color: '#AAA',
				borderColor: 'transparent',
				borderWidth: 0,
				hoverable: true
			},
			xaxes: [ {
				color: '#AAA',
				position: 'bottom',
				tickColor: 'transparent',
				mode: 'time',
				timeformat: '%b',
				monthNames: <?php echo json_encode( array_values( $wp_locale->month_abbrev ) ) ?>,
				tickLength: 1,
				minTickSize: [ 1, 'month' ],
				font: {
					color: '#AAA'
				}
			} ],
			yaxes: [
				{
					min: 0,
					minTickSize: 1,
					tickDecimals: 0,
					color: '#D4D9DC',
					font: { color: '#AAA' }
				},
				{
					position: 'right',
					min: 0,
					tickDecimals: 2,
					tickFormatter: function( val, axis ) {
						return accounting.formatMoney( val, '€ ', 2, '.', ',' );
					},
					alignTicksWithAxis: 1,
					color: 'transparent',
					font: { color: '#AAA' }
				}
			]
		} );
	} );
</script>

	<?php include 'pronamic.php'; ?>
</div>
