<div class="wrap">
	<?php screen_icon(Pronamic_WordPress_IDeal_Plugin::SLUG); ?>

	<h2>
		<?php _e('iDEAL Variants', 'pronamic_ideal'); ?>
	</h2>

	<table cellspacing="0" class="widefat fixed">

		<?php foreach(array('thead', 'tfoot') as $tag): ?>

		<<?php echo $tag; ?>>
			<tr>
				<th scope="col" class="manage-column"><?php _e( 'Provider', 'pronamic_ideal' ); ?></th>
				<th scope="col" class="manage-column"><?php _e( 'Name', 'pronamic_ideal' ); ?></th>
				<th scope="col" class="manage-column"><?php _e( 'Method', 'pronamic_ideal' );  ?></th>
				<th scope="col" class="manage-column"><?php _e( 'Dashboard', 'pronamic_ideal' ); ?></th>
			</tr>
		</<?php echo $tag; ?>>

		<?php endforeach; ?>

		<tbody>

			<?php foreach(Pronamic_WordPress_IDeal_ConfigurationsRepository::getVariants() as $variant): ?>

			<tr>
				<td>
					<?php if($provider = $variant->getProvider()): ?>
					<a href="<?php echo $provider->getUrl(); ?>">
						<?php echo $provider->getName(); ?>
					</a>
					<?php endif; ?>
				</td>
				<td>
					<?php echo $variant->getName(); ?>
				</td>
				<td>
					<?php 
					
					switch($variant->getMethod()) {
						case Pronamic_IDeal_IDeal::METHOD_EASY:
							_e('Easy', 'pronamic_ideal');
							break;
						case Pronamic_IDeal_IDeal::METHOD_BASIC:
							_e('Basic', 'pronamic_ideal');
							break;
						case Pronamic_IDeal_IDeal::METHOD_OMNIKASSA:
							_e('OmniKassa', 'pronamic_ideal');
							break;
						case Pronamic_IDeal_IDeal::METHOD_ADVANCED:
							_e('Advanced', 'pronamic_ideal');
							break;
						default:
							_e('Unknown', 'pronamic_ideal');
							break;
					}
					
					?>
				</td>
				<td>
					<?php if($variant->testSettings->dashboardUrl): ?>
					<a href="<?php echo $variant->testSettings->dashboardUrl; ?>">
						<?php _e('Test', 'pronamic_ideal'); ?>
					</a>
					<?php endif; ?>

					<?php if($variant->liveSettings->dashboardUrl): ?>
					<a href="<?php echo $variant->liveSettings->dashboardUrl; ?>">
						<?php _e('Live', 'pronamic_ideal'); ?>
					</a>
					<?php endif; ?>
				</td>
			</tr>

			<?php endforeach; ?>

		</tbody>
	</table>

	<?php include 'pronamic.php'; ?>
</div>