<?php 

if(!empty($_POST) && check_admin_referer('pronamic_ideal_uninstall', 'pronamic_ideal_nonce')) {
	Pronamic_WordPress_IDeal_Plugin::uninstall();
}

?>
<form method="post" action="">
	<?php wp_nonce_field('pronamic_ideal_uninstall', 'pronamic_ideal_nonce'); ?>

	<h3>
		<?php _e('Delete iDEAL plugin', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
	</h3>

	<div class="delete-alert">
		<p>
			<?php _e('Warning! This will delete all iDEAL configurations, payments and options.', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
		</p>

		<?php 
		
		submit_button(
			__('Uninstall', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) , 
			'delete'
		);
		
		?>
	</div>
</form> 