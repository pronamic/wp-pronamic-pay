<?php 

$user = wp_get_current_user();

$iDeal = new Pronamic_Gateways_IDealInternetKassa_IDealInternetKassa();

$iDeal->setPaymentServerUrl( $configuration->getPaymentServerUrl() );

$iDeal->setPspId( $configuration->pspId );
$iDeal->setPassPhraseIn( $configuration->shaInPassPhrase );
$iDeal->setPassPhraseOut( $configuration->shaOutPassPhrase );

$iDeal->setOrderId( time() );
$iDeal->setAmount( 1 );
$iDeal->setCurrency( 'EUR' );
$iDeal->setLanguage( 'nl_NL' );
$iDeal->setCustomerName( $user->user_firstname . ' ' . $user->user_lastname );
$iDeal->setEMailAddress( $user->user_email );

$iDeal->setField( 'PARAMPLUS', 'pid=1234567890' );

$file = dirname( Pronamic_WordPress_IDeal_Plugin::$file ) . '/other/calculations-parameters-sha-in.txt';
$iDeal->setCalculationsParametersIn( file( $file, FILE_IGNORE_NEW_LINES ) );

$file = dirname( Pronamic_WordPress_IDeal_Plugin::$file ) . '/other/calculations-parameters-sha-out.txt';
$iDeal->setCalculationsParametersOut( file( $file, FILE_IGNORE_NEW_LINES ) );

?>

<h3>
	<?php _e( 'Mandatory Tests', 'pronamic_ideal' ); ?>
</h3>

<form method="post" action="<?php echo esc_attr( $iDeal->getPaymentServerUrl() ); ?>" target="_blank" style="display: inline">
	<?php 
	
	echo $iDeal->getHtmlFields(); 

	submit_button( 'Test', 'secondary', 'submit', false ); 
					
	?>
</form>