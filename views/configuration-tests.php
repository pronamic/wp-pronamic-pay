<?php 

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);

$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById($id);

?>
<div class="wrap">
	<?php screen_icon(Pronamic_WordPress_IDeal_Plugin::SLUG); ?>

	<h2>
		<?php _e('iDEAL Tests', 'pronamic_ideal'); ?>
	</h2>

	<?php if($configuration == null): ?>

		<p>
			<?php printf(__('We could not find any feed with the ID "%s".', 'pronamic_ideal'), $id); ?>
		</p>

	<?php else: ?>

	<?php $testsLink = admin_url(Pronamic_WordPress_IDeal_Admin::getConfigurationTestsLink($configuration->getId())); ?>

		<div>
			<h3>
				<?php _e('Info', 'pronamic_ideal'); ?>
			</h3>
	
			<table class="form-table">
				<tr>
					<th scope="row">
						<?php _e('ID', 'pronamic_ideal'); ?>
					</th>
					<td>
						<?php echo $configuration->getId(); ?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php _e('Name', 'pronamic_ideal'); ?>
					</th>
					<td>
						<?php echo $configuration->getName(); ?>
					</td>
				</tr>
			</table>
		</div>
	
		<?php 
		
		if ( $configuration->getVariant() instanceof Pronamic_IDeal_VariantEasy ) {
			include 'test-method-easy.php';
		} elseif ( $configuration->getVariant() instanceof Pronamic_IDeal_VariantBasic ) {
			include 'test-method-basic.php';
		} elseif ( $configuration->getVariant() instanceof Pronamic_IDeal_VariantInternetKassa ) {
			include 'test-method-internetkassa.php';
		} elseif ( $configuration->getVariant() instanceof Pronamic_IDeal_VariantOmniKassa ) {
			include 'test-method-omnikassa.php';
		} elseif ( $configuration->getVariant() instanceof Pronamic_IDeal_VariantAdvanced ) {
			include 'test-method-advanced.php';
		}
	
		?>
	
	<?php endif; ?>
</div>