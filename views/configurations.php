<div class="wrap">
	<?php screen_icon( 'pronamic_ideal' ); ?>

	<h2>
		<?php _e('iDEAL Configurations', 'pronamic_ideal');

		if(true): ?>

		<a class="button add-new-h2" href="<?php echo Pronamic_WordPress_IDeal_Admin::getConfigurationEditLink(); ?>">
			<?php _e('Add New', 'pronamic_ideal'); ?>
		</a>

		<?php endif; ?>
	</h2>

	<?php include 'configurations-form.php'; ?>

	<?php include 'pronamic.php'; ?>
</div>