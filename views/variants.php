<div class="wrap">
	<?php screen_icon(Pronamic_WordPress_IDeal_Plugin::SLUG); ?>

	<h2>
		<?php _e('iDEAL Variants', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
	</h2>

	<table cellspacing="0" class="widefat fixed">

		<?php foreach(array('thead', 'tfoot') as $tag): ?>

		<<?php echo $tag; ?>>
			<tr>
				<th scope="col" class="manage-column"><?php _e('Provider', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) ?></th>
				<th scope="col" class="manage-column"><?php _e('Name', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) ?></th>
				<th scope="col" class="manage-column"><?php _e('Method', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) ?></th>
				<th scope="col" class="manage-column"><?php _e('Dashboard', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) ?></th>
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
							_e('Easy', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN);
							break;
						case Pronamic_IDeal_IDeal::METHOD_BASIC:
							_e('Basic', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN);
							break;
						case Pronamic_IDeal_IDeal::METHOD_ADVANCED:
							_e('Advanced', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN);
							break;
						default:
							_e('Unknown', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN);
							break;
					}
					
					?>
				</td>
				<td>
					<?php if($variant->testSettings->dashboardUrl): ?>
					<a href="<?php echo $variant->testSettings->dashboardUrl; ?>">
						<?php _e('Test', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
					</a>
					<?php endif; ?>

					<?php if($variant->liveSettings->dashboardUrl): ?>
					<a href="<?php echo $variant->liveSettings->dashboardUrl; ?>">
						<?php _e('Live', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
					</a>
					<?php endif; ?>
				</td>
			</tr>

			<?php endforeach; ?>

		</tbody>
	</table>
</div>