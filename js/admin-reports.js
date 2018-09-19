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

		// @see https://github.com/flot/flot/blob/master/API.md
		var font = {
			color: '#AAA',
			size: 13
		};

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
				font: font
			} ],
			yaxes: [
				{
					min: 0,
					minTickSize: 1,
					tickDecimals: 0,
					color: '#D4D9DC',
					font: font
				},
				{
					position: 'right',
					min: 0,
					tickDecimals: 2,
					tickFormatter: function( val ) {
						return accounting.formatMoney( val, '€' + '&nbsp;', 2, '.', ',' );
					},
					alignTicksWithAxis: 1,
					color: 'transparent',
					font: font
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

	var tippyInstance = false;
	var dataIndex     = false;
	var seriesIndex   = false;

	container.bind( 'plothover', function( event, pos, item ) {
		if ( ! item || item.dataIndex != dataIndex || item.seriesIndex != seriesIndex ) {
			if ( tippyInstance ) {
				tippyInstance.destroy();
			}

			tippyInstance = false;
		}

		if ( ! item ) {
			return;
		}

		if ( tippyInstance ) {
			return;
		}

		var text = item.datapoint[1].toFixed( 2 );

		if ( item.series.tooltipFormatter && 'money' === item.series.tooltipFormatter ) {
			text = accounting.formatMoney( item.datapoint[1], '€ ', 2, '.', ',' );
		}

		tippyInstance = tippy.one( {
			attributes: {
				title: text
			},
			getBoundingClientRect: function() {
				return {
					width: 0,
					height: 0,
					top: item.pageY - window.pageYOffset,
					left: item.pageX - window.pageXOffset,
					right: item.pageX - window.pageXOffset,
					bottom: item.pageY - window.pageYOffset
				};
			},
			clientHeight: 0,
			clientWidth: 0
		}, {
			placement: 'right'
		} );

		dataIndex   = item.dataIndex;
		seriesIndex = item.seriesIndex;

		tippyInstance.show();
	} );
} );
