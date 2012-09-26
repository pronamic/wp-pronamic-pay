<h3>
	<?php _e('Tests', 'pronamic_ideal'); ?>
</h3>

<?php foreach(array(2, 3, 4, 5, 1) as $testCase): ?>

<?php 
			
$name = sprintf(__('Test &euro; %s', 'pronamic_ideal'), $testCase);

$user = wp_get_current_user();

$iDeal = new Pronamic_Gateways_IDealEasy_IDealEasy();

$iDeal->setPaymentServerUrl($configuration->getPaymentServerUrl());
$iDeal->setPspId($configuration->pspId);

$iDeal->setLanguage(Pronamic_WordPress_IDeal_Util::getLanguageIso639AndCountryIso3166Code());
$iDeal->setCurrency('EUR');
$iDeal->setOrderId(uniqid('test'));
$iDeal->setDescription($name);
$iDeal->setAmount($testCase);
$iDeal->setEMailAddress($user->user_email);
$iDeal->setCustomerName($user->user_firstname . ' ' . $user->user_lastname);

?>

<form method="post" action="<?php echo esc_attr($iDeal->getPaymentServerUrl()); ?>" target="_blank" style="display: inline">
	<?php 

	echo $iDeal->getHtmlFields();

	submit_button($name, 'secondary', 'submit', false); 

	?>
</form>

<?php endforeach; ?>