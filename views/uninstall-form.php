<?php 

namespace Pronamic\WordPress\IDeal;

if(!empty($_POST) && check_admin_referer('pronamic_ideal_uninstall', 'pronamic_ideal_nonce')) {
	Plugin::uninstall();
}

?>
<form method="post" action="">
	<?php wp_nonce_field('pronamic_ideal_uninstall', 'pronamic_ideal_nonce'); ?>

	<h3>
		<?php _e('Delete iDEAL plugin', Plugin::TEXT_DOMAIN); ?>
	</h3>

	<div class="delete-alert">
		<p>
			<?php _e('Warning! This will delete all iDEAL configurations, payments and options.', Plugin::TEXT_DOMAIN); ?>
		</p>

		<?php 
		
		submit_button(
			__('Uninstall', Plugin::TEXT_DOMAIN) , 
			'delete'
		);
		
		?>
	</div>
</form> 