<?php 

namespace Pronamic\WordPress\IDeal;

use Pronamic\IDeal\VariantAdvanced;
use Pronamic\IDeal\HTML\Helper;

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);

$configuration = ConfigurationsRepository::getConfigurationById($id);

?>
<div class="wrap">
	<?php screen_icon(Plugin::SLUG); ?>

	<h2>
		<?php _e('iDEAL Tests', Plugin::TEXT_DOMAIN); ?>
	</h2>

	<?php if($configuration == null): ?>

	<p>
		<?php printf(__('We could not find any feed with the ID "%s".', Plugin::TEXT_DOMAIN), $id); ?>
	</p>

	<?php else: ?>

	<div>
		<h3>
			<?php _e('Info', Plugin::TEXT_DOMAIN); ?>
		</h3>

		<table class="form-table">
			<tr>
				<th scope="row">
					<?php _e('ID', Plugin::TEXT_DOMAIN); ?>
				</th>
				<td>
					<?php echo $configuration->getId(); ?>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e('Name', Plugin::TEXT_DOMAIN); ?>
				</th>
				<td>
					<?php echo $configuration->getName(); ?>
				</td>
			</tr>
		</table>
	</div>

	<?php if($configuration->variant instanceof VariantAdvanced): ?>

	<div>
		<h3>
			<?php _e('Retrieve Issuers Lists', Plugin::TEXT_DOMAIN); ?>
		</h3>

		<?php 

		$lists = IDeal::getIssuersLists($configuration);

		if($lists): ?>

		<ul>
			<?php foreach($lists as $name => $list): ?>
			<li>
				<strong><?php echo $name; ?></strong>

				<?php if($list): ?>
				<ul>
					<?php foreach($list as $issuer): ?>
					<li>
						<?php echo $issuer->getName(); ?>
					</li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>
			</li>
			<?php endforeach; ?>
		</ul>

		<?php elseif($error = IDeal::getError()): ?>

		<div class="error inline below-h2">
			<dl>
				<dt><?php _e('Code', Plugin::TEXT_DOMAIN); ?></dt>
				<dd><?php echo $error->getCode(); ?></dd>

				<dt><?php _e('Message', Plugin::TEXT_DOMAIN); ?></dt>
				<dd><?php echo $error->getMessage(); ?></dd>

				<dt><?php _e('Detail', Plugin::TEXT_DOMAIN); ?></dt>
				<dd><?php echo $error->getDetail(); ?></dd>

				<dt><?php _e('Consumer Message', Plugin::TEXT_DOMAIN); ?></dt>
				<dd><?php echo $error->getConsumerMessage(); ?></dd>
			</dl>
		</div>

		<?php endif; ?>
	</div>
	
	<?php endif; ?>
	
	<h3>
		<?php _e('Mandatory Tests', Plugin::TEXT_DOMAIN); ?>
	</h3>

	<table cellspacing="0" class="widefat fixed">

		<?php foreach(array('thead', 'tfoot') as $tag): ?>

		<<?php echo $tag; ?>>
			<tr>
				<th scope="col" class="manage-column"><?php _e('Test', Plugin::TEXT_DOMAIN) ?></th>
				<th scope="col" class="manage-column"><?php _e('Action', Plugin::TEXT_DOMAIN) ?></th>
			</tr>
		</<?php echo $tag; ?>>

		<?php endforeach; ?>

		<tbody>
		
			<?php foreach(array(1, 2, 3, 4, 5, 6, 7) as $testCase): ?>
	
			<tr>
				<?php 
				
				$name = sprintf(__('Test Case %s', Plugin::TEXT_DOMAIN), $testCase);
			
				/*

				$iDeal = Gravity_Forms_IDeal::getIDealForFeed($feed);
			
				$item = new Pronamic_IDealBasic_Item();
				$item->setNumber($testCase);
				$item->setDescription($name);
				$item->setPrice($testCase);
				$item->setNumber(1);
			
				$iDeal->addItem($item);
				
				*/
				
				?>
				<th scope="row">
					<?php echo $name; ?>
				</th>
				<td>
					<form method="post" action="<?php // echo esc_attr($iDeal->getPaymentServerUrl()); ?>" target="_blank">
						<?php // echo $iDeal->getHtmlFields(); ?>
						
						<?php 
						
						$text = __('Execute', Plugin::TEXT_DOMAIN);
				
						submit_button($text, 'secondary', 'submit', false); 
						
						?>
					</form>
				</td>
			</tr>
		
			<?php endforeach; ?>

		</tbody>
	</table>

	<?php endif; ?>
</div>