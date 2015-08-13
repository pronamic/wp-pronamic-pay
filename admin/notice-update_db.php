<?php
/**
 * Admin View: Notice - Update
 *
 * @see https://github.com/woothemes/woocommerce/blob/2.4.3/includes/admin/views/html-notice-update.php
 */

$update_link = wp_nonce_url( add_query_arg(
	array(
		'page'                   => 'pronamic_ideal',
		'pronamic_pay_update_db' => true,
	),
	admin_url( 'admin.php' )
), 'pronamic_pay_update_db', 'pronamic_pay_nonce' );

?>
<div class="updated">
	<p>
		<strong><?php esc_html_e( 'Pronamic iDEAL Data Update Required', 'pronamic_ideal' ); ?></strong> – 
		<?php esc_html_e( 'We just need to update your install to the latest version', 'pronamic_ideal' ); ?>
	</p>

	<p class="submit">
		<a href="<?php echo esc_attr( $update_link ); ?>" class="pp-update-now button-primary"><?php _e( 'Run the updater', 'pronamic_ideal' ); ?></a>
	</p>
</div>

<script type="text/javascript">
	jQuery( '.pp-update-now' ).click( 'click', function() {
		return window.confirm( '<?php echo esc_js( __( 'It is strongly recommended that you backup your database before proceeding. Are you sure you wish to run the updater now?', 'pronamic_ideal' ) ); ?>' );
	});
</script>
