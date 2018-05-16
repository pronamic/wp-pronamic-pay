<?php
/**
 * Admin Settings
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Admin
 */

namespace Pronamic\WordPress\Pay\Admin;

use Pronamic\WordPress\Pay\Plugin;
use Pronamic\WordPress\Pay\Util;

/**
 * WordPress iDEAL admin
 *
 * @author Remco Tolsma
 * @version 1.0.0
 */
class AdminSettings {
	/**
	 * Plugin.
	 *
	 * @var Plugin
	 */
	private $plugin;

	/**
	 * Constructs and initalize an admin object.
	 *
	 * @param Plugin $plugin Plugin.
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		// Actions.
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	/**
	 * Admin initialize.
	 */
	public function admin_init() {
		// Settings - General.
		add_settings_section(
			'pronamic_pay_general',
			__( 'General', 'pronamic_ideal' ),
			array( $this, 'settings_section' ),
			'pronamic_pay'
		);

		add_settings_field(
			'pronamic_pay_license_key',
			__( 'Support License Key', 'pronamic_ideal' ),
			array( $this, 'input_license_key' ),
			'pronamic_pay',
			'pronamic_pay_general',
			array(
				'label_for' => 'pronamic_pay_license_key',
				'classes'   => 'regular-text code',
			)
		);

		// Default Config.
		add_settings_field(
			'pronamic_pay_config_id',
			__( 'Default Gateway', 'pronamic_ideal' ),
			array( $this, 'input_page' ),
			'pronamic_pay',
			'pronamic_pay_general',
			array(
				'post_type'        => 'pronamic_gateway',
				'show_option_none' => __( '— Select a gateway —', 'pronamic_ideal' ),
				'label_for'        => 'pronamic_pay_config_id',
			)
		);

		// Google Analytics property UA code.
		add_settings_field(
			'pronamic_pay_google_analytics_property',
			__( 'Google Analytics Property ID', 'pronamic_ideal' ),
			array( $this, 'input_element' ),
			'pronamic_pay',
			'pronamic_pay_general',
			array(
				'description' => __( 'Set a Google Analytics Property UA code to track ecommerce revenue.', 'pronamic_ideal' ),
				'label_for'   => 'pronamic_pay_google_analytics_property',
				'classes'     => 'regular-text code',
			)
		);

		// Remove data on uninstall.
		add_settings_field(
			'pronamic_pay_uninstall_clear_data',
			__( 'Remove Data', 'pronamic_ideal' ),
			array( $this, 'input_checkbox' ),
			'pronamic_pay',
			'pronamic_pay_general',
			array(
				'legend'      => __( 'Remove Data', 'pronamic_ideal' ),
				'description' => __( 'Remove all plugin data on uninstall', 'pronamic_ideal' ),
				'label_for'   => 'pronamic_pay_uninstall_clear_data',
				'classes'     => 'regular-text',
				'type'        => 'checkbox',
			)
		);

		// Settings - Pages.
		add_settings_section(
			'pronamic_pay_pages',
			__( 'Payment Status Pages', 'pronamic_ideal' ),
			array( $this, 'settings_section' ),
			'pronamic_pay'
		);

		foreach ( $this->plugin->get_pages() as $id => $label ) {
			add_settings_field(
				$id,
				$label,
				array( $this, 'input_page' ),
				'pronamic_pay',
				'pronamic_pay_pages',
				array(
					'label_for' => $id,
				)
			);
		}
	}

	/**
	 * Settings section.
	 *
	 * @param array $args Arguments.
	 */
	public function settings_section( $args ) {
		switch ( $args['id'] ) {
			case 'pronamic_pay_pages':
				echo '<p>';
				esc_html_e( 'The page an user will get redirected to after payment, based on the payment status.', 'pronamic_ideal' );
				echo '</p>';

				$pages = array( 'completed', 'cancel', 'expired', 'error', 'unknown' );

				foreach ( $pages as $status ) {
					$option_name = sprintf( 'pronamic_pay_%s_page_id', $status );

					$option = get_option( $option_name );

					if ( ! empty( $option ) ) {
						$hide_button = true;
					}
				}

				if ( ! isset( $hide_button ) ) {
					submit_button(
						__( 'Set default pages', 'pronamic_ideal' ),
						'',
						'pronamic_pay_create_pages',
						false
					);
				}

				break;
		}
	}

	/**
	 * Input text.
	 *
	 * @param array $args Arguments.
	 */
	public function input_element( $args ) {
		$defaults = array(
			'type'        => 'text',
			'classes'     => 'regular-text',
			'description' => '',
		);

		$args = wp_parse_args( $args, $defaults );

		$name  = $args['label_for'];
		$value = get_option( $name );

		$atts = array(
			'name'  => $name,
			'id'    => $name,
			'type'  => $args['type'],
			'class' => $args['classes'],
			'value' => $value,
		);

		printf(
			'<input %s />',
			// @codingStandardsIgnoreStart
			Util::array_to_html_attributes( $atts )
			// @codingStandardsIgnoreEn
		);

		if ( ! empty( $args['description'] ) ) {
			printf(
				'<p class="description">%s</p>',
				esc_html( $args['description'] )
			);
		}
	}

	/**
	 * Input checkbox.
	 *
	 * @see https://github.com/WordPress/WordPress/blob/4.9.1/wp-admin/options-writing.php#L60-L68
	 * @see https://github.com/WordPress/WordPress/blob/4.9.1/wp-admin/options-reading.php#L110-L141
	 * @param array $args Arguments.
	 */
	public function input_checkbox( $args ) {
		$id     = $args['label_for'];
		$name   = $args['label_for'];
		$value  = get_option( $name );
		$legend = $args['legend'];

		echo '<fieldset>';

		printf(
			'<legend class="screen-reader-text"><span>%s</span></legend>',
			esc_html( $legend )
		);

		printf(
			'<label for="%s">',
			esc_attr( $id )
		);

		printf(
			'<input name="%s" id="%s" type="checkbox" value="1" %s />',
			esc_attr( $name ),
			esc_attr( $id ),
			checked( $value, 1, false )
		);

		echo esc_html( $args['description'] );

		echo '</label>';

		echo '</fieldset>';
	}

	/**
	 * Input license key.
	 *
	 * @param array $args Arguments.
	 */
	public function input_license_key( $args ) {
		do_action( 'pronamic_pay_license_check' );

		$this->input_element( $args );

		$status = get_option( 'pronamic_pay_license_status' );

		$icon = 'valid' === $status ? 'yes' : 'no';

		printf( '<span class="dashicons dashicons-%s" style="vertical-align: text-bottom;"></span>', esc_attr( $icon ) );
	}

	/**
	 * Input page.
	 *
	 * @param array $args Arguments.
	 */
	public function input_page( $args ) {
		$name = $args['label_for'];

		wp_dropdown_pages( array(
			'name'             => esc_attr( $name ),
			'post_type'        => esc_attr( isset( $args['post_type'] ) ? $args['post_type'] : 'page' ),
			'selected'         => esc_attr( get_option( $name, '' ) ),
			'show_option_none' => esc_attr( isset( $args['show_option_none'] ) ? $args['show_option_none'] : __( '— Select a page —', 'pronamic_ideal' ) ),
			'class'            => 'regular-text',
		) );
	}
}
