<h3>
	<?php _e( 'Tests', 'pronamic_ideal' ); ?>
</h3>

<?php foreach ( array( 2, 3, 4, 5, 1 ) as $test_case ): ?>
	
	<?php 
				
	$name = sprintf( __( 'Test &euro; %s', 'pronamic_ideal' ), $test_case );
	
	$iDeal = new Pronamic_IDeal_OmniKassa();
	
	$iDeal->setPaymentServerUrl( $configuration->getPaymentServerUrl() );
	$iDeal->setMerchantId( $configuration->getMerchantId() );
	$iDeal->setKeyVersion( $configuration->getSubId() );
	$iDeal->setSecretKey( $configuration->hashKey );
	$iDeal->setCurrencyNumericCode( 978 );
	$iDeal->setNormalReturnUrl( site_url( '/' ) );
	$iDeal->setAmount( $test_case );
	$iDeal->setTransactionReference( uniqid( 'test' ) );
	// $iDeal->setOrderId(1);
	$iDeal->setCustomerLanguage( Pronamic_WordPress_IDeal_Util::getLanguageIso639Code() );
	
	?>
	
	<form method="post" action="<?php echo esc_attr( $iDeal->getPaymentServerUrl() ); ?>" target="_blank" style="display: inline">
		<?php 
	
		echo $iDeal->getHtmlFields();
	
		submit_button( $name, 'secondary', 'submit', false ); 
	
		?>
	</form>

<?php endforeach; ?>