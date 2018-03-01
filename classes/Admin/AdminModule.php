<?php
/**
 * Admin Module
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Admin
 */

namespace Pronamic\WordPress\Pay\Admin;

use Pronamic\WordPress\Pay\Plugin;

/**
 * WordPress Pay admin
 *
 * @author Remco Tolsma
 * @version 1.0.0
 * @since 1.0.0
 */
class AdminModule {
	/**
	 * Constructs and initalize an admin object.
	 *
	 * @param Plugin $plugin Plugin.
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		$this->install = new Install( $this );

		// Actions.
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		add_action( 'load-post.php', array( $this, 'maybe_test_payment' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_filter( 'pronamic_pay_gateway_settings', array( $this, 'gateway_settings' ) );

		// Modules.
		$this->settings  = new AdminSettings( $plugin );
		$this->about     = new AdminAboutPage( $plugin, $this );
		$this->dashboard = new AdminDashboard( $plugin );
		$this->notices   = new AdminNotices( $plugin );
		$this->reports   = new AdminReports( $plugin, $this );
		$this->tour      = new AdminTour( $plugin );
	}

	/**
	 * Admin initialize.
	 */
	public function admin_init() {
		global $pronamic_ideal_errors;

		$pronamic_ideal_errors = array();

		// Maybe.
		$this->maybe_create_pages();
		$this->maybe_redirect();

		// Post types.
		new GatewayPostType( $this );
		new PaymentPostType();
		new SubscriptionPostType();

		// Gateway settings.
		$this->gateway_settings = new GatewaySettings();

		if ( ! wp_next_scheduled( 'pronamic_pay_license_check' ) ) {
			wp_schedule_event( time(), 'daily', 'pronamic_pay_license_check' );
		}
	}

	/**
	 * Maybe redirect.
	 *
	 * @see https://github.com/woothemes/woocommerce/blob/2.4.4/includes/admin/class-wc-admin.php#L29
	 * @see https://github.com/woothemes/woocommerce/blob/2.4.4/includes/admin/class-wc-admin.php#L96-L122
	 */
	public function maybe_redirect() {
		$redirect = get_transient( 'pronamic_pay_admin_redirect' );

		// Check.
		if (
			false === $redirect
				||
			defined( 'DOING_AJAX' ) && DOING_AJAX
				||
			defined( 'DOING_CRON' ) && DOING_CRON
				||
			is_network_admin()
				||
			filter_has_var( INPUT_GET, 'activate-multi' )
				||
			! current_user_can( 'manage_options' )
		) {
			return;
		}

		// Update.
		set_transient( 'pronamic_pay_admin_redirect', false );

		// Delete.
		delete_transient( 'pronamic_pay_admin_redirect' );

		// Redirect.
		wp_safe_redirect( $redirect );

		exit;
	}

	/**
	 * Input checkbox.
	 *
	 * @param array $args Arguments.
	 */
	public static function input_checkbox( $args ) {
		$defaults = array(
			'label_for' => '',
			'type'      => 'text',
			'label'     => '',
		);

		$args = wp_parse_args( $args, $defaults );

		$id    = $args['label_for'];
		$value = get_option( $id );

		$legend = sprintf(
			'<legend class="screen-reader-text"><span>%s</span></legend>',
			esc_html( $args['label'] )
		);

		$input = sprintf(
			'<input name="%s" id="%s" type="%s" value="%s" %s />',
			esc_attr( $id ),
			esc_attr( $id ),
			esc_attr( 'checkbox' ),
			esc_attr( '1' ),
			checked( $value, true, false )
		);

		$label = sprintf(
			'<label for="%s">%s %s</label>',
			esc_attr( $id ),
			$input,
			esc_html( $args['label'] )
		);

		printf( // WPCS: XSS ok.
			'<fieldset>%s %s</fieldset>',
			$legend,
			$label
		);
	}

	/**
	 * Sanitize the specified value to a boolean.
	 *
	 * @param mixed $value Value.
	 * @return boolean
	 */
	public static function sanitize_boolean( $value ) {
		return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * Configurations dropdown.
	 *
	 * @param array $args Arguments.
	 * @return string
	 */
	public static function dropdown_configs( $args ) {
		$defaults = array(
			'name'           => 'pronamic_pay_config_id',
			'echo'           => true,
			'selected'       => false,
			'payment_method' => null,
		);

		$args = wp_parse_args( $args, $defaults );

		// Output.
		$output = '';

		// Dropdown.
		$id       = $args['name'];
		$name     = $args['name'];
		$selected = $args['selected'];

		if ( false === $selected ) {
			$selected = get_option( $id );
		}

		$output .= sprintf(
			'<select id="%s" name="%s">',
			esc_attr( $id ),
			esc_attr( $name )
		);

		$options = \Pronamic\WordPress\Pay\Plugin::get_config_select_options( $args['payment_method'] );

		foreach ( $options as $value => $name ) {
			$output .= sprintf(
				'<option value="%s" %s>%s</option>',
				esc_attr( $value ),
				selected( $value, $selected, false ),
				esc_html( $name )
			);
		}

		$output .= sprintf( '</select>' );

		// Return or echo.
		if ( $args['echo'] ) {
			echo $output; // WPCS: XSS ok.
		} else {
			return $output;
		}
	}

	/**
	 * Create pages.
	 *
	 * @param array  $pages   Page.
	 * @param string $parent Parent post ID.
	 */
	private function create_pages( $pages, $parent = null ) {
		foreach ( $pages as $page ) {
			$post = array(
				'post_title'     => $page['post_title'],
				'post_name'      => $page['post_title'],
				'post_content'   => $page['post_content'],
				'post_status'    => 'publish',
				'post_type'      => 'page',
				'comment_status' => 'closed',
			);

			if ( isset( $parent ) ) {
				$post['post_parent'] = $parent;
			}

			$result = wp_insert_post( $post, true );

			if ( ! is_wp_error( $result ) ) {
				if ( isset( $page['post_meta'] ) ) {
					foreach ( $page['post_meta'] as $key => $value ) {
						update_post_meta( $result, $key, $value );
					}
				}

				if ( isset( $page['option_name'] ) ) {
					update_option( $page['option_name'], $result );
				}

				if ( isset( $page['children'] ) ) {
					$this->create_pages( $page['children'], $result );
				}
			}
		}
	}

	/**
	 * Maybe create pages.
	 */
	public function maybe_create_pages() {
		if ( filter_has_var( INPUT_POST, 'pronamic_pay_create_pages' ) && check_admin_referer( 'pronamic_pay_settings', 'pronamic_pay_nonce' ) ) {

			$pages = array(
				'ideal' => array(
					'post_title'   => __( 'iDEAL', 'pronamic_ideal' ),
					'post_name'    => __( 'ideal', 'pronamic_ideal' ),
					'post_content' => '',
					'post_meta'    => array(
						'_yoast_wpseo_meta-robots-noindex' => true,
					),
					'children'     => array(
						'completed' => array(
							'post_title'   => __( 'iDEAL payment completed', 'pronamic_ideal' ),
							'post_name'    => __( 'completed', 'pronamic_ideal' ),
							'post_content' => __( '<p>The payment process is successfully completed.</p>', 'pronamic_ideal' ),
							'post_meta'    => array(
								'_yoast_wpseo_meta-robots-noindex' => true,
							),
							'option_name'  => 'pronamic_pay_completed_page_id',
						),
						'cancel'    => array(
							'post_title'   => __( 'iDEAL payment canceled', 'pronamic_ideal' ),
							'post_name'    => __( 'cancelled', 'pronamic_ideal' ),
							'post_content' => __( '<p>You canceled the iDEAL payment.</p>', 'pronamic_ideal' ),
							'post_meta'    => array(
								'_yoast_wpseo_meta-robots-noindex' => true,
							),
							'option_name'  => 'pronamic_pay_cancel_page_id',
						),
						'expired'   => array(
							'post_title'   => __( 'iDEAL payment expired', 'pronamic_ideal' ),
							'post_name'    => __( 'expired', 'pronamic_ideal' ),
							'post_content' => __( '<p>Unfortunately your iDEAL payment session has expired.</p>', 'pronamic_ideal' ),
							'post_meta'    => array(
								'_yoast_wpseo_meta-robots-noindex' => true,
							),
							'option_name'  => 'pronamic_pay_expired_page_id',
						),
						'error'     => array(
							'post_title'   => __( 'iDEAL payment error', 'pronamic_ideal' ),
							'post_name'    => __( 'error', 'pronamic_ideal' ),
							'post_content' => __( '<p>Unfortunately an error has occurred during your iDEAL payment.</p>', 'pronamic_ideal' ),
							'post_meta'    => array(
								'_yoast_wpseo_meta-robots-noindex' => true,
							),
							'option_name'  => 'pronamic_pay_error_page_id',
						),
						'unknown'   => array(
							'post_title'   => __( 'iDEAL payment unknown', 'pronamic_ideal' ),
							'post_name'    => __( 'unknown', 'pronamic_ideal' ),
							'post_content' => __( '<p>The status of your iDEAL payment is unknown.</p>', 'pronamic_ideal' ),
							'post_meta'    => array(
								'_yoast_wpseo_meta-robots-noindex' => true,
							),
							'option_name'  => 'pronamic_pay_unknown_page_id',
						),
					),
				),
			);

			$this->create_pages( $pages );

			$url = add_query_arg(
				array(
					'page'    => 'pronamic_pay_settings',
					'message' => 'pages-generated',
				), admin_url( 'admin.php' )
			);

			wp_redirect( $url );

			exit;
		}
	}

	/**
	 * Enqueue admin scripts.
	 *
	 * @param string $hook Hook.
	 */
	public function enqueue_scripts( $hook ) {
		$screen = get_current_screen();

		$enqueue  = false;
		$enqueue |= in_array(
			$screen->post_type, array(
				'pronamic_gateway',
				'pronamic_payment',
				'pronamic_pay_form',
				'pronamic_pay_gf',
				'pronamic_pay_subscr',
			), true
		);
		$enqueue |= 'dashboard' === $screen->id;
		$enqueue |= strpos( $hook, 'pronamic_pay' ) !== false;
		$enqueue |= strpos( $hook, 'pronamic_ideal' ) !== false;
		$enqueue |= 'toplevel_page_gf_edit_forms' === $screen->id;

		if ( $enqueue ) {
			$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			// Tippy.js - https://atomiks.github.io/tippyjs/.
			wp_register_script(
				'tippy.js',
				plugins_url( 'assets/tippy.js/tippy.all' . $min . '.js', \Pronamic\WordPress\Pay\Plugin::$file ),
				array(),
				'2.2.3',
				true
			);

			// Pronamic.
			wp_register_style(
				'pronamic-pay-icons',
				plugins_url( 'fonts/pronamic-pay-icons.css', \Pronamic\WordPress\Pay\Plugin::$file ),
				array(),
				$this->plugin->get_version()
			);

			wp_register_style(
				'pronamic-pay-admin',
				plugins_url( 'css/admin' . $min . '.css', \Pronamic\WordPress\Pay\Plugin::$file ),
				array( 'pronamic-pay-icons' ),
				$this->plugin->get_version()
			);

			wp_register_script(
				'pronamic-pay-admin',
				plugins_url( 'js/admin' . $min . '.js', \Pronamic\WordPress\Pay\Plugin::$file ),
				array( 'jquery', 'tippy.js' ),
				$this->plugin->get_version(),
				true
			);

			// Enqueue.
			wp_enqueue_style( 'pronamic-pay-admin' );
			wp_enqueue_script( 'pronamic-pay-admin' );
		}
	}

	/**
	 * Maybe test payment.
	 */
	public function maybe_test_payment() {
		if ( filter_has_var( INPUT_POST, 'test_pay_gateway' ) && check_admin_referer( 'test_pay_gateway', 'pronamic_pay_test_nonce' ) ) {
			$id = filter_input( INPUT_POST, 'post_ID', FILTER_SANITIZE_NUMBER_INT );

			$gateway = \Pronamic\WordPress\Pay\Plugin::get_gateway( $id );

			if ( $gateway ) {
				$amount = filter_input(
					INPUT_POST, 'test_amount', FILTER_VALIDATE_FLOAT, array(
						'flags'   => FILTER_FLAG_ALLOW_THOUSAND,
						'options' => array(
							'decimal' => pronamic_pay_get_decimal_separator(),
						),
					)
				);

				$data = new \Pronamic\WordPress\Pay\Payments\PaymentTestData( wp_get_current_user(), $amount );

				$payment_method = filter_input( INPUT_POST, 'pronamic_pay_test_payment_method', FILTER_SANITIZE_STRING );

				$payment = \Pronamic\WordPress\Pay\Plugin::start( $id, $gateway, $data, $payment_method );

				$error = $gateway->get_error();

				if ( is_wp_error( $error ) ) {
					\Pronamic\WordPress\Pay\Plugin::render_errors( $error );

					exit;
				}

				$gateway->redirect( $payment );
			}
		}
	}

	/**
	 * Create the admin menu.
	 */
	public function admin_menu() {
		// @see https://github.com/woothemes/woocommerce/blob/2.3.13/includes/admin/class-wc-admin-menus.php#L145
		$counts = wp_count_posts( 'pronamic_payment' );

		$badge = '';
		if ( isset( $counts, $counts->payment_pending ) && $counts->payment_pending > 0 ) {
			$badge = sprintf(
				' <span class="awaiting-mod update-plugins count-%s"><span class="processing-count">%s</span></span>',
				$counts->payment_pending,
				$counts->payment_pending
			);
		}

		add_menu_page(
			__( 'Pronamic Pay', 'pronamic_ideal' ),
			__( 'Pay', 'pronamic_ideal' ) . $badge,
			'edit_payments',
			'pronamic_ideal',
			array( $this, 'page_dashboard' ),
			'dashicons-money'
		);

		add_submenu_page(
			'pronamic_ideal',
			__( 'Payments', 'pronamic_ideal' ),
			__( 'Payments', 'pronamic_ideal' ) . $badge,
			'edit_payments',
			'edit.php?post_type=pronamic_payment'
		);

		add_submenu_page(
			'pronamic_ideal',
			__( 'Subscriptions', 'pronamic_ideal' ),
			__( 'Subscriptions', 'pronamic_ideal' ),
			'edit_payments',
			'edit.php?post_type=pronamic_pay_subscr'
		);

		do_action( 'pronamic_pay_admin_menu' );

		add_submenu_page(
			'pronamic_ideal',
			__( 'Payment Forms', 'pronamic_ideal' ),
			__( 'Forms', 'pronamic_ideal' ),
			'edit_forms',
			'edit.php?post_type=pronamic_pay_form'
		);

		add_submenu_page(
			'pronamic_ideal',
			__( 'Configurations', 'pronamic_ideal' ),
			__( 'Configurations', 'pronamic_ideal' ),
			'manage_options',
			'edit.php?post_type=pronamic_gateway'
		);

		add_submenu_page(
			'pronamic_ideal',
			__( 'Settings', 'pronamic_ideal' ),
			__( 'Settings', 'pronamic_ideal' ),
			'manage_options',
			'pronamic_pay_settings',
			array( $this, 'page_settings' )
		);

		add_submenu_page(
			'pronamic_ideal',
			__( 'Tools', 'pronamic_ideal' ),
			__( 'Tools', 'pronamic_ideal' ),
			'manage_options',
			'pronamic_pay_tools',
			array( $this, 'page_tools' )
		);

		global $submenu;

		if ( isset( $submenu['pronamic_ideal'] ) ) {
			$submenu['pronamic_ideal'][0][0] = __( 'Dashboard', 'pronamic_ideal' ); // WPCS: override ok.
		}
	}

	/**
	 * Page dashboard.
	 */
	public function page_dashboard() {
		return $this->render_page( 'dashboard' );
	}

	/**
	 * Page settings.
	 */
	public function page_settings() {
		return $this->render_page( 'settings' );
	}

	/**
	 * Page tools.
	 */
	public function page_tools() {
		return $this->render_page( 'tools' );
	}

	/**
	 * Render the specified page.
	 *
	 * @param string $name Page identifier.
	 * @return boolean True if a page is rendered, false otherwise.
	 */
	public function render_page( $name ) {
		$result = false;

		$file = plugin_dir_path( \Pronamic\WordPress\Pay\Plugin::$file ) . 'admin/page-' . $name . '.php';

		if ( is_readable( $file ) ) {
			include $file;

			$result = true;
		}

		return $result;
	}

	/**
	 * Gateway settings.
	 *
	 * @param array $classes Classes.
	 */
	public function gateway_settings( $classes ) {
		foreach ( $this->plugin->gateway_integrations as $integration ) {
			$class = $integration->get_settings_class();

			if ( null === $class ) {
				continue;
			}

			if ( is_array( $class ) ) {
				foreach ( $class as $c ) {
					$classes[ $c ] = $c;
				}
			} else {
				$classes[ $class ] = $class;
			}
		}

		return $classes;
	}

	/**
	 * Get a CSS class for the specified post status.
	 *
	 * @param string $post_status Post status.
	 * @return string
	 */
	public static function get_post_status_icon_class( $post_status ) {
		switch ( $post_status ) {
			case 'payment_pending':
			case 'subscr_pending':
				return 'pronamic-pay-icon-pending';
			case 'payment_cancelled':
			case 'subscr_cancelled':
				return 'pronamic-pay-icon-cancelled';
			case 'payment_completed':
			case 'subscr_completed':
				return 'pronamic-pay-icon-completed';
			case 'payment_refunded':
				return 'pronamic-pay-icon-refunded';
			case 'payment_failed':
			case 'subscr_failed':
				return 'pronamic-pay-icon-failed';
			case 'payment_on_hold':
			case 'payment_expired':
			case 'subscr_expired':
				return 'pronamic-pay-icon-on-hold';
			case 'payment_processing':
			case 'subscr_active':
			default:
				return 'pronamic-pay-icon-processing';
		}
	}
}
