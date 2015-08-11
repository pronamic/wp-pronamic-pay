<?php

/**
 * Title: WordPress admin tour
 * Description:
 * Copyright: Copyright (c) 2005 - 2015
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 3.7.0
 * @since 3.7.0
 */
class Pronamic_WP_Pay_Admin_Tour {
	/**
	 * Constructs and initializes an pointers object
	 *
	 * @see https://github.com/WordPress/WordPress/blob/4.2.4/wp-includes/js/wp-pointer.js
	 * @see https://github.com/WordPress/WordPress/blob/4.2.4/wp-admin/includes/template.php#L1955-L2016
	 * @see https://github.com/Yoast/wordpress-seo/blob/2.3.4/admin/class-pointers.php
	 */
	public function __construct( $admin ) {
		$this->admin = $admin;

		// Actions
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	public function admin_init() {
		if ( filter_has_var( INPUT_GET, 'pronamic_pay_ignore_tour' ) && wp_verify_nonce( filter_input( INPUT_GET, 'pronamic_pay_nonce' ), 'pronamic_pay_ignore_tour' ) ) {
			$ignore = filter_input( INPUT_GET, 'pronamic_pay_ignore_tour', FILTER_VALIDATE_BOOLEAN );

			update_user_meta( get_current_user_id(), 'pronamic_pay_ignore_tour', $ignore );
		}

		if ( ! get_user_meta( get_current_user_id(), 'pronamic_pay_ignore_tour', true ) ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		}
	}

	public function admin_enqueue_scripts() {
		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Pointers
		wp_register_style(
			'proanmic-pay-admin-tour',
			plugins_url( 'css/admin-tour' . $min . '.css', Pronamic_WP_Pay_Plugin::$file ),
			array(
				'wp-pointer',
			),
			'3.7.0'
		);

		wp_register_script(
			'proanmic-pay-admin-tour',
			plugins_url( 'js/admin-tour' . $min . '.js', Pronamic_WP_Pay_Plugin::$file ),
			array(
				'jquery',
				'wp-pointer',
			),
			'3.7.0',
			true
		);

		wp_localize_script(
			'proanmic-pay-admin-tour',
			'pronamicPayAdminTour',
			array(
				'pointers' => $this->get_pointers(),
			)
		);

		// Enqueue
		wp_enqueue_style( 'proanmic-pay-admin-tour' );
		wp_enqueue_script( 'proanmic-pay-admin-tour' );
	}

	public function get_content( $file ) {
		$path = plugin_dir_path( Pronamic_WP_Pay_Plugin::$file ) . 'admin/' . $file . '.php';

		if ( is_readable( $path ) ) {
			ob_start();

			include $path;

			$content = ob_get_contents();

			ob_end_clean();
		}

		return $content;
	}

	public function get_pointers() {
		$pointers = array();

		$page   = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
		$screen = get_current_screen();

		switch ( $screen->id ) {
			case 'toplevel_page_pronamic_ideal' :
				$pointers = array(
					array(
						'selector' => '.wrap h2',
						'options'  => (object) array(
							'content'   => $this->get_content( 'pointer-dashboard' ),
							'position'  => (object) array(
								'edge'      => 'top',
								'align'     => ( is_rtl() ) ? 'left' : 'right',	
							),
							'pointerWidth' => 450,
						),
					),
				);

				break;
			case 'edit-pronamic_payment' : 
				$pointers = array(
					array(
						'selector' => '.wrap h2',
						'options'  => (object) array(
							'content'   => $this->get_content( 'pointer-payments' ),
							'position'  => (object) array(
								'edge'      => 'top',
								'align'     => ( is_rtl() ) ? 'left' : 'right',	
							),
							'pointerWidth' => 450,
						),
					),
				);

				break;
			case 'edit-pronamic_gateway' :
				$pointers = array(
					array(
						'selector' => '.wrap h2',
						'options'  => (object) array(
							'content'   => $this->get_content( 'pointer-gateways' ),
							'position'  => (object) array(
								'edge'      => 'top',
								'align'     => ( is_rtl() ) ? 'left' : 'right',	
							),
							'pointerWidth' => 450,
						),
					),
				);

				break;
			case 'edit-pronamic_pay_form' :
				$pointers = array(
					array(
						'selector' => '.wrap h2',
						'options'  => (object) array(
							'content'   => $this->get_content( 'pointer-forms' ),
							'position'  => (object) array(
								'edge'      => 'top',
								'align'     => ( is_rtl() ) ? 'left' : 'right',	
							),
							'pointerWidth' => 450,
						),
					),
				);

				break;
		}

		switch ( $page ) {
			case 'pronamic_pay_settings' :
				$pointers = array(
					array(
						'selector' => '.wrap h2',
						'options'  => (object) array(
							'content'   => $this->get_content( 'pointer-settings' ),
							'position'  => (object) array(
								'edge'      => 'top',
								'align'     => ( is_rtl() ) ? 'left' : 'right',	
							),
							'pointerWidth' => 450,
						),
					),
				);

				break;
			case 'pronamic_pay_tools' :
				$pointers = array(
					array(
						'selector' => '.wrap h2',
						'options'  => (object) array(
							'content'   => $this->get_content( 'pointer-tools' ),
							'position'  => (object) array(
								'edge'      => 'top',
								'align'     => ( is_rtl() ) ? 'left' : 'right',	
							),
							'pointerWidth' => 450,
						),
					),
				);

				break;
			case 'pronamic_pay_reports' :
				$pointers = array(
					array(
						'selector' => '.wrap h2',
						'options'  => (object) array(
							'content'   => $this->get_content( 'pointer-reports' ),
							'position'  => (object) array(
								'edge'      => 'top',
								'align'     => ( is_rtl() ) ? 'left' : 'right',	
							),
							'pointerWidth' => 450,
						),
					),
				);

				break;
		}

		if ( empty( $pointers ) ) {
			$pointers = array(
				array(
					'selector'  => 'li.toplevel_page_pronamic_ideal',
					'options'  => (object) array(
						'content'   => $this->get_content( 'pointer-start' ),
						'position'  => (object) array(
							'edge'      => 'bottom',
							'align'     => 'center',
						),
					),
				),
			);
		}

		return $pointers;
	}

	public function get_close_url() {
		return wp_nonce_url( add_query_arg( array(
			'pronamic_pay_ignore_tour'  => true,
		) ), 'pronamic_pay_ignore_tour', 'pronamic_pay_nonce' );
	}
}
