<?php

/**
 * Title: WordPress admin pointers
 * Description:
 * Copyright: Copyright (c) 2005 - 2015
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 3.7.0
 * @since 3.7.0
 */
class Pronamic_WP_Pay_Admin_Pointers {
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
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) ); 
		add_action( 'admin_print_footer_scripts', array( $this, 'admin_print_footer_scripts' ) );
	}

	public function admin_enqueue_scripts() {
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'wp-pointer' );
	}

	public function admin_print_footer_scripts() {
		$admin_pointers = array(
			'pronamic_ideal' => array(
				'selector'  => 'li.toplevel_page_pronamic_ideal',
				'content'   => '<h3>Pronamic iDEAL</h3><p>Test</p>',
				'edge'      => 'bottom',
				'align'     => 'center',
				'buttons'   => '<div><a class="button-secondary">Close</a><a href="/wp-admin/admin.php?page=pronamic_ideal" class="button-primary">Start Tour</a></div>',
			),
		);

		?>
<script type="text/javascript">
	/* <![CDATA[ */
	( function( $ ) {
		var setup = function() {
			<?php foreach ( $admin_pointers as $pointer_id => $array ) : ?>

				$( '<?php echo $array['selector']; ?>' ).first().pointer( {
					content: '<?php echo $array['content']; ?>',
					position: {
						edge: '<?php echo $array['edge']; ?>',
						align: '<?php echo $array['align']; ?>'
					},
					close: function() {
						$.post( ajaxurl, {
							pointer: '<?php echo $pointer_id; ?>',
							action: 'dismiss-wp-pointer'
						} );
					},
					buttons: function( event, t ) {
						return $( '<div>' ).html( '<?php echo $array['buttons']; ?>' );
					}
				} ).pointer( 'open' );

			<?php endforeach; ?>
		}

		// $( window ).bind( 'load.wp-pointers', setup );
		$( document ).ready( setup );
	} )(jQuery);
	/* ]]> */
</script>
		<?php
	}
}
