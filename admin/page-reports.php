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

// @see https://github.com/woothemes/woocommerce/blob/master/includes/admin/reports/class-wc-report-sales-by-date.php

global $wpdb;

$payments = $wpdb->get_results( "
	SELECT
		DATE_FORMAT( post.post_date, '%Y-%m' ),
		SUM( meta.meta_value ) AS amount
	FROM
		$wpdb->posts AS post
			LEFT JOIN
		$wpdb->postmeta AS meta
				ON post.ID = meta.post_id AND meta_key = '_pronamic_payment_amount'
	WHERE
		post.post_type = 'pronamic_payment'
	GROUP BY
		YEAR( post.post_date ), MONTH( post.post_date )
	ORDER BY
		post_date
	;
", OBJECT_K );

$begin = new DateTime( 'first day of January' );
$end   = new DateTime( 'last day of December' );

$interval = new DateInterval( 'P1M' );

$period = new DatePeriod( $begin, $interval, $end );

$labels = array();
$data   = array();

foreach ( $period as $date ) {
	$key = $date->format( 'Y-m' );

	$labels[ $key ]  = $date->format( 'M' );

	$amount = 0;
	if ( isset( $payments[ $key ] ) ) {
		$amount = (float) $payments[ $key ]->amount;
	}

	$data[] = array( $date->getTimestamp() * 1000, $amount );
}

$labels  = array_values( $labels );

global $wp_locale;

?>
<script class="code" type="text/javascript">
	var data = jQuery.parseJSON( '<?php echo json_encode( $data ); ?>' );

	series = [
		{
			label: "Average sales amount",
			data: data,
			yaxis: 2,
			color: '#b1d4ea',
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
				color: '#aaa',
				borderColor: 'transparent',
				borderWidth: 0,
				hoverable: true
			},
			xaxes: [ {
				color: '#aaa',
				position: "bottom",
				tickColor: 'transparent',
				mode: "time",
				timeformat: "%b",
				monthNames: <?php echo json_encode( array_values( $wp_locale->month_abbrev ) ) ?>,
				tickLength: 1,
				minTickSize: [1, "month"],
				font: {
					color: "#aaa"
				}
			} ],
			yaxes: [
				{
					min: 0,
					minTickSize: 1,
					tickDecimals: 0,
					color: '#d4d9dc',
					font: { color: "#aaa" }
				},
				{
					position: "right",
					min: 0,
					tickDecimals: 2,
					alignTicksWithAxis: 1,
					color: 'transparent',
					font: { color: "#aaa" }
				}
			]
		} );

		$( '<div id="flotr-tooltip"></div>' ).css( {
			position: 'absolute',
			display: 'none',
			border: '1px solid #fdd',
			padding: '2px',
			'background-color': '#fee',
			opacity: 0.9
		} ).appendTo("body");

		container.bind( 'plothover', function(event, pos, item) {
			if (item) {
				$( '#flotr-tooltip' ).html( item.datapoint[1].toFixed(2) )
					.css( {
						top: item.pageY + 10,
						left: item.pageX + 10
					} )
					.fadeIn( 200 );
			} else {
				$( '#flotr-tooltip' ).fadeOut( 100 );
			}
		});
	} );
</script>

	<?php include 'pronamic.php'; ?>
</div>
