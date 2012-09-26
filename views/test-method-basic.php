<h3>
	<?php _e('Mandatory Tests', 'pronamic_ideal'); ?>
</h3>

<?php foreach(array(1, 2, 3, 4, 5, 6, 7) as $testCase): ?>

<?php 
			
$name = sprintf(__('Test Case %s', 'pronamic_ideal'), $testCase);
		
$iDeal = new Pronamic_IDeal_Basic();
$iDeal->setPaymentServerUrl($configuration->getPaymentServerUrl());
$iDeal->setMerchantId($configuration->getMerchantId());
$iDeal->setSubId($configuration->getSubId());
$iDeal->setLanguage(Pronamic_WordPress_IDeal_Util::getLanguageIso639Code());
$iDeal->setHashKey($configuration->hashKey);
$iDeal->setCurrency('EUR');
$iDeal->setPurchaseId(uniqid('test-' . $testCase));
$iDeal->setDescription('Test ' . $testCase);

// URL's
$testsLink = admin_url(Pronamic_WordPress_IDeal_Admin::getConfigurationTestsLink($configuration->getId()));

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