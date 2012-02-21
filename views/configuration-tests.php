<?php 

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);

$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById($id);

?>
<div class="wrap">
	<?php screen_icon(Pronamic_WordPress_IDeal_Plugin::SLUG); ?>

	<h2>
		<?php _e('iDEAL Tests', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
	</h2>

	<?php if($configuration == null): ?>

	<p>
		<?php printf(__('We could not find any feed with the ID "%s".', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN), $id); ?>
	</p>

	<?php else: ?>

	<?php $testsLink = admin_url(Pronamic_WordPress_IDeal_Admin::getConfigurationTestsLink($configuration->getId())); ?>

	<div>
		<h3>
			<?php _e('Info', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
		</h3>

		<table class="form-table">
			<tr>
				<th scope="row">
					<?php _e('ID', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
				</th>
				<td>
					<?php echo $configuration->getId(); ?>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e('Name', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
				</th>
				<td>
					<?php echo $configuration->getName(); ?>
				</td>
			</tr>
		</table>
	</div>

	<?php if($configuration->getVariant() instanceof Pronamic_IDeal_VariantAdvanced): ?>

	<div>
		<h3>
			<?php _e('Retrieve Issuers Lists', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
		</h3>

		<?php 

		$lists = Pronamic_WordPress_IDeal_IDeal::getIssuersLists($configuration);

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
		
		<h3>
			<?php _e('Mandatory Tests', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
		</h3>

		<form method="post" action="" target="_blank">
			<?php 
			
			wp_nonce_field('test', 'pronamic_ideal_nonce');

			echo Pronamic_IDeal_HTML_Helper::issuersSelect('pronamic_ideal_issuer_id', $lists);
			
			foreach(array(1, 2, 3, 4, 5, 7) as $testCase) {
				$name = sprintf(__('Test Case %s', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN), $testCase);
				
				submit_button($name, 'secondary', 'test[' . $testCase . ']', false);
			}
			
			?>
		</form>

		<?php elseif($error = Pronamic_WordPress_IDeal_IDeal::getError()): ?>

		<div class="error inline below-h2">
			<dl>
				<dt><?php _e('Code', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?></dt>
				<dd><?php echo $error->getCode(); ?></dd>

				<dt><?php _e('Message', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?></dt>
				<dd><?php echo $error->getMessage(); ?></dd>

				<dt><?php _e('Detail', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?></dt>
				<dd><?php echo $error->getDetail(); ?></dd>

				<dt><?php _e('Consumer Message', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?></dt>
				<dd><?php echo $error->getConsumerMessage(); ?></dd>
			</dl>
		</div>

		<?php endif; ?>
	</div>
	
	<?php endif; ?>

	<?php if($configuration->getVariant() instanceof Pronamic_IDeal_VariantBasic): ?>
	
	<h3>
		<?php _e('Mandatory Tests', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
	</h3>

	<?php foreach(array(1, 2, 3, 4, 5, 6, 7) as $testCase): ?>
	
	<?php 
				
	$name = sprintf(__('Test Case %s', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN), $testCase);
			
	$iDeal = new Pronamic_IDeal_Basic();
	$iDeal->setPaymentServerUrl($configuration->getPaymentServerUrl());
	$iDeal->setMerchantId($configuration->getMerchantId());
	$iDeal->setSubId($configuration->getSubId());
	$iDeal->setLanguage('nl');
	$iDeal->setHashKey($configuration->hashKey);
	$iDeal->setCurrency('EUR');
	$iDeal->setPurchaseId(uniqid('test-' . $testCase));
	$iDeal->setDescription('Test ' . $testCase);

	// Success URL
	$url = add_query_arg('status', 'success', $testsLink);
	$iDeal->setSuccessUrl($url);

	// Cancel URL
	$url = add_query_arg('status', 'cancel', $testsLink);
	$iDeal->setCancelUrl($url);

	// Error URL
	$url = add_query_arg('status', 'error', $testsLink);
	$iDeal->setErrorUrl($url);

	// Test item
	$items = new Pronamic_IDeal_Items();

	$item = new Pronamic_IDeal_Item();
	$item->setNumber($testCase);
	$item->setDescription($name);
	$item->setPrice($testCase);
	$item->setQuantity(1);
	
	$items->addItem($item);

	$iDeal->setItems($items);
	
	?>
	<form method="post" action="<?php echo esc_attr($iDeal->getPaymentServerUrl()); ?>" target="_blank" style="display: inline">
		<?php 
		
		echo $iDeal->getHtmlFields(); 

		submit_button($name, 'secondary', 'submit', false); 
						
		?>
	</form>

	<?php endforeach; ?>

	<?php endif; ?>

	<?php if($configuration->getVariant() instanceof Pronamic_IDeal_VariantKassa): ?>
	
	<?php 
	
	$user = wp_get_current_user();

	$iDeal = new Pronamic_IDeal_Kassa();
	$iDeal->setPaymentServerUrl($configuration->getPaymentServerUrl());
	$iDeal->setPspId($configuration->getMerchantId());
	$iDeal->setOrderId(time());
	$iDeal->setAmount(1);
	$iDeal->setCurrency('EUR');
	$iDeal->setLanguage('nl');
	$iDeal->setCustomerName($user->user_firstname . ' ' . $user->user_lastname);
	$iDeal->setEMailAddress($user->user_email);

	$file = dirname(Pronamic_WordPress_IDeal_Plugin::$file) . '/other/calculations-parameters-sha-in.txt';
	$iDeal->calculationsParametersShaIn = file($file, FILE_IGNORE_NEW_LINES);
	
	$file = dirname(Pronamic_WordPress_IDeal_Plugin::$file) . '/other/calculations-parameters-sha-out.txt';
	$iDeal->calculationsParametersShaOut = file($file, FILE_IGNORE_NEW_LINES);

	?>
	
	<h3>
		<?php _e('Mandatory Tests', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
	</h3>

	<form method="post" action="<?php echo esc_attr($iDeal->getPaymentServerUrl()); ?>" target="_blank" style="display: inline">
		<?php 
		
		echo $iDeal->getHtmlFields(); 

		submit_button('Test', 'secondary', 'submit', false); 
						
		?>
	</form>

	<?php endif; ?>

	<?php if($configuration->getVariant() instanceof Pronamic_IDeal_VariantOmniKassa): ?>

	<?php 

	$iDeal = new Pronamic_IDeal_OmniKassa();
	$iDeal->setPaymentServerUrl($configuration->getPaymentServerUrl());
	$iDeal->setMerchantId($configuration->getMerchantId());
	$iDeal->setKeyVersion($configuration->getSubId());
	$iDeal->setSecretKey($configuration->hashKey);
	$iDeal->setCurrencyNumericCode(978);
	$iDeal->setNormalReturnUrl(home_url());
	$iDeal->setAmount(1);
	$iDeal->setTransactionReference(uniqid('test'));
	// $iDeal->setOrderId(1);
	$iDeal->setCustomerLanguage(Pronamic_WordPress_IDeal_Plugin::getLanguage());

	?>
	
	<h3>
		<?php _e('Mandatory Tests', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
	</h3>

	<form method="post" action="<?php echo esc_attr($iDeal->getPaymentServerUrl()); ?>" target="_blank" style="display: inline">
		<?php 

		echo $iDeal->getHtmlFields();

		submit_button('Test', 'secondary', 'submit', false); 
						
		?>
	</form>

	<?php endif; ?>

	<?php endif; ?>
</div>