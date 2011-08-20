<?php 

use Pronamic\WordPress\IDeal\ConfigurationsRepository;
namespace Pronamic\WordPress\IDeal;

use Pronamic\IDeal\IDeal as IDealCore;

?>
<div class="wrap">
	<?php screen_icon(Plugin::SLUG); ?>

	<h2>
		<?php _e('iDEAL Variants', Plugin::TEXT_DOMAIN); ?>
	</h2>

	<table cellspacing="0" class="widefat fixed">

		<?php foreach(array('thead', 'tfoot') as $tag): ?>

		<<?php echo $tag; ?>>
			<tr>
				<th scope="col" class="manage-column"><?php _e('Name', Plugin::TEXT_DOMAIN) ?></th>
				<th scope="col" class="manage-column"><?php _e('Method', Plugin::TEXT_DOMAIN) ?></th>
				<th scope="col" class="manage-column"><?php _e('Dashboard', Plugin::TEXT_DOMAIN) ?></th>
			</tr>
		</<?php echo $tag; ?>>

		<?php endforeach; ?>

		<tbody>

			<?php foreach(ConfigurationsRepository::getVariants() as $variant): ?>

			<tr>
				<td>
					<?php if($provider = $variant->getProvider()): ?>
					<a href="<?php echo $provider->getUrl(); ?>">
						<?php echo $provider->getName(); ?>
					</a> - 
					<?php endif; ?>

					<?php echo $variant->getName(); ?>
				</td>
				<td>
					<?php 
					
					switch($variant->getMethod()) {
						case IDealCore::METHOD_BASIC:
							_e('Basic', Plugin::TEXT_DOMAIN);
							break;
						case IDealCore::METHOD_ADVANCED:
							_e('Advanced', Plugin::TEXT_DOMAIN);
							break;
						default:
							_e('Unknown', Plugin::TEXT_DOMAIN);
							break;
					}
					
					?>
				</td>
				<td>
					<?php if($variant->testSettings->dashboardUrl): ?>
					<a href="<?php echo $variant->testSettings->dashboardUrl; ?>">
						<?php _e('Test', Plugin::TEXT_DOMAIN); ?>
					</a>
					<?php endif; ?>

					<?php if($variant->liveSettings->dashboardUrl): ?>
					<a href="<?php echo $variant->liveSettings->dashboardUrl; ?>">
						<?php _e('Live', Plugin::TEXT_DOMAIN); ?>
					</a>
					<?php endif; ?>
				</td>
			</tr>

			<?php endforeach; ?>

		</tbody>
	</table>
</div>