<div class="wrap">
	<?php screen_icon( 'pronamic_ideal' ); ?>

	<h2><?php echo get_admin_page_title(); ?></h2>

	<h3>
		<?php _e( 'Supported Gateways', 'pronamic_ideal' ); ?>
	</h3>
	
	<?php 
	
	global $pronamic_pay_providers;
	global $pronamic_pay_gateways;

	$gateways = $pronamic_pay_gateways;
	
	include 'gateways-wp-admin.php';

	if ( filter_has_var( INPUT_GET, 'markdown' ) ) : ?>
	
		<h4><?php _e( 'Markdown', 'pronamic_ideal' ); ?></h4>
		
		<?php 
		
		ob_start();
		
		include 'gateways-readme-md.php';
		
		$markdown = ob_get_clean();

		?>
		
		<textarea cols="60" rows="25"><?php echo esc_textarea( $markdown ); ?></textarea>
	
	<?php endif; ?>

	<?php include 'pronamic.php'; ?>
</div>