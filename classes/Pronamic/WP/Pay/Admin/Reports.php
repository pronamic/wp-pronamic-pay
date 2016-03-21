<?php

/**
 * Title: WordPress admin reports
 * Description:
 * Copyright: Copyright (c) 2005 - 2016
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 3.7.0
 * @since 3.7.0
 */
class Pronamic_WP_Pay_Admin_Reports {
	private $admin;

	public function __construct( $admin ) {
		$this->admin = $admin;

		// Actions
		add_action( 'pronamic_pay_admin_menu', array( $this, 'admin_menu' ) );
	}

	public function admin_menu() {
		$hook_suffix = add_submenu_page(
			'pronamic_ideal',
			__( 'Reports', 'pronamic_ideal' ),
			__( 'Reports', 'pronamic_ideal' ),
			'manage_options',
			'pronamic_pay_reports',
			array( $this, 'page_reports' )
		);

		// @see https://github.com/WordPress/WordPress/blob/4.2.4/wp-admin/admin-header.php#L82-L87
		add_action( 'admin_print_styles-' . $hook_suffix, array( $this, 'admin_css' ) );
	}

	public function page_reports() {
		return $this->admin->render_page( 'reports' );
	}

	//////////////////////////////////////////////////

	/**
	 * Enqueue admin scripts
	 */
	public function admin_css() {
		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Flot - http://www.flotcharts.org/
		wp_register_script(
			'flot',
			plugins_url( 'assets/flot/jquery.flot' . $min . '.js', Pronamic_WP_Pay_Plugin::$file ),
			array( 'jquery' ),
			'0.8.3',
			true
		);

		wp_register_script(
			'flot-time',
			plugins_url( 'assets/flot/jquery.flot.time' . $min . '.js', Pronamic_WP_Pay_Plugin::$file ),
			array( 'flot' ),
			'0.8.3',
			true
		);

		wp_register_script(
			'flot-resize',
			plugins_url( 'assets/flot/jquery.flot.resize' . $min . '.js', Pronamic_WP_Pay_Plugin::$file ),
			array( 'flot' ),
			'0.8.3',
			true
		);

		// Accounting.js - http://openexchangerates.github.io/accounting.js
		wp_register_script(
			'accounting',
			plugins_url( 'assets/accounting/accounting' . $min . '.js', Pronamic_WP_Pay_Plugin::$file ),
			array( 'jquery' ),
			'0.4.1',
			true
		);

		// Reports
		wp_register_script(
			'proanmic-pay-admin-reports',
			plugins_url( 'js/admin-reports' . $min . '.js', Pronamic_WP_Pay_Plugin::$file ),
			array(
				'jquery',
				'flot',
				'flot-time',
				'flot-resize',
				'accounting',
			),
			'3.7.0',
			true
		);

		global $wp_locale;

		wp_localize_script(
			'proanmic-pay-admin-reports',
			'pronamicPayAdminReports',
			array(
				'data'       => $this->get_reports(),
				'monthNames' => array_values( $wp_locale->month_abbrev ),
			)
		);

		// Enqueue
		wp_enqueue_script( 'proanmic-pay-admin-reports' );
	}

	//////////////////////////////////////////////////

	public function get_reports() {
		$start = new DateTime( 'First day of January' );
		$end   = new DateTime( 'Last day of December' );

		$data = array(
			(object) array(
				'label'      => __( 'Number succesfull payments', 'pronamic_ideal' ),
				'data'       => $this->get_report( 'payment_completed', 'COUNT', $start, $end ),
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
				'data'             => $this->get_report( 'payment_pending', 'SUM', $start, $end ),
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
				'data'             => $this->get_report( 'payment_completed', 'SUM', $start, $end ),
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
				'data'             => $this->get_report( 'payment_cancelled', 'SUM', $start, $end ),
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
				'data'             => $this->get_report( 'payment_expired', 'SUM', $start, $end ),
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
				'data'             => $this->get_report( 'payment_failed', 'SUM', $start, $end ),
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

		return $data;
	}

	//////////////////////////////////////////////////

	/**
	 * Get report
	 *
	 * @see https://github.com/woothemes/woocommerce/blob/2.3.11/assets/js/admin/reports.js
	 * @see https://github.com/woothemes/woocommerce/blob/master/includes/admin/reports/class-wc-report-sales-by-date.php
	 */
	private function get_report( $status, $function, $start, $end ) {
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
				WHERE
					post.post_type = 'pronamic_payment'
						AND
					post.post_date BETWEEN %s AND %s
						AND
					post.post_status = %s
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
}
