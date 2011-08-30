<div class="wrap">
	<?php screen_icon(Pronamic_WordPress_IDeal_Plugin::SLUG); ?>

	<h2>
		<?php _e('iDEAL Configurations', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN);

		if(true): ?>

		<a class="button add-new-h2" href="<?php echo Pronamic_WordPress_IDeal_Admin::getConfigurationEditLink(); ?>">
			<?php _e('Add New', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
		</a>

		<?php endif; ?>
	</h2>

	<?php include 'configurations-form.php'; ?>
	
	<?php include 'uninstall-form.php'; ?>
</div>