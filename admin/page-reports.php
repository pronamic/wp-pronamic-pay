<?php

wp_enqueue_script( 'flot' );
wp_enqueue_script( 'flot-time' );
wp_enqueue_script( 'flot-resize' );
wp_enqueue_script( 'accounting' );

// @see https://github.com/woothemes/woocommerce/blob/2.3.11/assets/js/admin/reports.js
// @see https://github.com/woothemes/woocommerce/blob/master/includes/admin/reports/class-wc-report-sales-by-date.php

function pronamic_pay_get_report( $status, $function, $start, $end ) {
	global $wpdb;
	
	$interval = new DateInterval( 'P1M' );
	$period   = new DatePeriod( $start, $interval, $end );

	$date_format = '%Y-%m';

	$query = $wpdb->prepare( "
			SELECT
				DATE_FORMAT( post.post_date, %s ) AS month,
				$function( meta_amount.meta_value ) AS value
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

		$value = 0;
		if ( isset( $data[ $key ] ) ) {
			$value = (float) $data[ $key ]->value;
		}

		$report[] = array(
			// Flot requires milliseconds so multiply with 1000
			$date->getTimestamp() * 1000,
			$value,
		);
	}

	return $report;
}

$start = new DateTime( 'First day of January' );
$end   = new DateTime( 'Last day of December' );

$data = array(
	(object) array(
		'label'      => __( 'Number succesfull payments', 'pronamic_ideal' ),
		'data'       => pronamic_pay_get_report( 'Success', 'COUNT', $start, $end ),
		'color'      => '#dbe1e3',
		'bars'       => (object) array(
			'fillColor' => '#dbe1e3',
			'fill'      => true,
			'show'      => true,
			'lineWidth' => 0,
			'barWidth'  => 2419200000 * 0.5,
			'align'     => 'center',
		),
		'shadowSize' => 0,
		'hoverable'  => false,
	),
	(object) array(
		'label'            => __( 'Open payments', 'pronamic_ideal' ),
		'data'             => pronamic_pay_get_report( 'Open', 'SUM', $start, $end ),
		'yaxis'            => 2,
		'color'            => '#b1d4ea',
		'points'           => (object) array(
			'show'      => true,
			'radius'    => 5,
			'lineWidth' => 2,
			'fillColor' => '#FFF',
			'fill'      => true,
		),
		'lines'            => (object) array(
			'show'      => true,
			'lineWidth' => 2,
			'fill'      => false,
		),
		'shadowSize'       => 0,
		'tooltipFormatter' => 'money',
	),
	(object) array(
		'label'            => __( 'Succesfull payments', 'pronamic_ideal' ),
		'data'             => pronamic_pay_get_report( 'Success', 'SUM', $start, $end ),
		'yaxis'            => 2,
		'color'            => '#3498db',
		'points'           => (object) array(
			'show'      => true,
			'radius'    => 6,
			'lineWidth' => 4,
			'fillColor' => '#FFF',
			'fill'      => true,
		),
		'lines'            => (object) array(
			'show'      => true,
			'lineWidth' => 5,
			'fill'      => false,
		),
		'shadowSize'       => 0,
		'prepend_tooltip'  => '&euro;&nbsp;',
		'tooltipFormatter' => 'money',
	),
	(object) array(
		'label'            => __( 'Cancelled payments', 'pronamic_ideal' ),
		'data'             => pronamic_pay_get_report( 'Cancelled', 'SUM', $start, $end ),
		'yaxis'            => 2,
		'color'            => '#F1C40F',
		'points'           => (object) array(
			'show'      => true,
			'radius'    => 5,
			'lineWidth' => 2,
			'fillColor' => '#FFF',
			'fill'      => true,
		),
		'lines'            => (object) array(
			'show'      => true,
			'lineWidth' => 2,
			'fill'      => false,
		),
		'shadowSize'       => 0,
		'prepend_tooltip'  => '&euro;&nbsp;',
		'tooltipFormatter' => 'money',
	),
	(object) array(
		'label'            => __( 'Expired payments', 'pronamic_ideal' ),
		'data'             => pronamic_pay_get_report( 'Expired', 'SUM', $start, $end ),
		'yaxis'            => 2,
		'color'            => '#DBE1E3',
		'points'           => (object) array(
			'show'      => true,
			'radius'    => 5,
			'lineWidth' => 2,
			'fillColor' => '#FFF',
			'fill'      => true,
		),
		'lines'            => (object) array(
			'show'      => true,
			'lineWidth' => 2,
			'fill'      => false,
		),
		'shadowSize'       => 0,
		'prepend_tooltip'  => '&euro;&nbsp;',
		'tooltipFormatter' => 'money',
	),
	(object) array(
		'label'            => __( 'Failed payments', 'pronamic_ideal' ),
		'data'             => pronamic_pay_get_report( 'Failure', 'SUM', $start, $end ),
		'yaxis'            => 2,
		'color'            => '#E74C3C',
		'points'           => (object) array(
			'show'      => true,
			'radius'    => 5,
			'lineWidth' => 2,
			'fillColor' => '#FFF',
			'fill'      => true,
		),
		'lines'            => (object) array(
			'show'      => true,
			'lineWidth' => 2,
			'fill'      => false,
		),
		'shadowSize'       => 0,
		'prepend_tooltip'  => '&euro;&nbsp;',
		'tooltipFormatter' => 'money',
	),
);

foreach ( $data as $serie ) {
	$serie->legendValue = array_sum( wp_list_pluck( $serie->data, 1 ) );
}

global $wp_locale;

?>
<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<div id="poststuff">
		<div class="postbox">
			<div class="pronamic-pay-chart-filter">

			</div>

			<div class="inside pronamic-pay-chart-with-sidebar">
				<div class="pronamic-pay-chart-sidebar">
					<ul class="pronamci-pay-chart-legend">

						<?php foreach ( $data as $i => $serie ) : ?>

							<li style="border-right-color: <?php echo esc_attr( $serie->color ); ?>;" data-pronamic-pay-highlight-serie="<?php echo esc_attr( $i ); ?>">
								<strong><?php

								if ( isset( $serie->tooltipFormatter ) && 'money' == $serie->tooltipFormatter ) {
									echo '&euro;&nbsp;' . number_format_i18n( $serie->legendValue, 2 );
								} else {
									echo $serie->legendValue;
								}

								?></strong>
								<?php echo esc_html( $serie->label ); ?>
							</li>

						<?php endforeach; ?>

					</ul>
				</div>

				<div id="chart1" style="height: 500px; width: 100%;"></div>
			</div>
		</div>
	</div>

<script class="code" type="text/javascript">
	jQuery( document ).ready( function( $ ) {
		var container = $( '#chart1' );

		var drawChart = function( highlight ) {
			var data = jQuery.parseJSON( '<?php echo json_encode( $data ); ?>' );

			if ( typeof highlight !== 'undefined' && data[ highlight ] ) {
				serie = data[ highlight ];

				serie.color = '#23282F';

				if ( serie.bars ) {
					serie.bars.fillColor = '#23282F';
				}

				if ( serie.lines ) {
					serie.lines.lineWidth = 5;
				}
			}

			var chart = $.plot( container, data, {
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
		};

		drawChart();

		jQuery( '[data-pronamic-pay-highlight-serie]' ).hover(
			function() {
				drawChart( jQuery( this ).data( 'pronamic-pay-highlight-serie' ) );
			},
			function() {
				drawChart();
			}
		);

		var tooltip = $( '<div class="pronamic-pay-chart-tooltip"></div>' ).appendTo( 'body' );

		container.bind( 'plothover', function( event, pos, item ) {
			if ( item ) {
				var text = item.datapoint[1].toFixed( 2 );

				if ( item.series.tooltipFormatter && 'money' === item.series.tooltipFormatter ) {
					text = accounting.formatMoney( item.datapoint[1], '€ ', 2, '.', ',' );
				}

				tooltip.html( text ).css( {
					top: item.pageY - 16,
					left: item.pageX + 20
				} ).fadeIn( 200 );
			} else {
				tooltip.fadeOut( 100 );
			}
		});

	} );
</script>

	<?php include 'pronamic.php'; ?>
</div>
