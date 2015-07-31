/* global pronamicPayAdminReports */
/* global accounting */
jQuery( document ).ready( function( $ ) {
	var container = $( '#chart1' );

	var drawChart = function( highlight ) {
		// @see http://stackoverflow.com/a/817050
		var data = $.extend( true, [], pronamicPayAdminReports.data );

		if ( typeof highlight !== 'undefined' && data[ highlight ] ) {
			var serie = data[ highlight ];

			serie.color = '#23282F';

			if ( serie.bars ) {
				serie.bars.fillColor = '#23282F';
			}

			if ( serie.lines ) {
				serie.lines.lineWidth = 5;
			}
		}

		$.plot( container, data, {
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
				monthNames: pronamicPayAdminReports.monthNames,
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
					tickFormatter: function( val ) {
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
