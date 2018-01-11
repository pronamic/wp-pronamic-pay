<?php

namespace Pronamic\WordPress\Pay\Forms;

/**
 * Title: WordPress iDEAL form processor
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 3.7.0
 * @since 3.7.0
 */
class FormsModule {
	/**
	 * Constructs and initalize a forms module object.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;

		// Processor
		$this->processor = new FormProcessor( $plugin );

		// Scripts
		$this->scripts = new FormScripts( $plugin );

		// Shortcode
		$this->shortcode = new FormShortcode();

		// Actions
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	/**
	 * Admin initialize.
	 */
	public function admin_init() {
		// Form Post Type
		$this->form_post_type = new FormPostType();
	}
}
