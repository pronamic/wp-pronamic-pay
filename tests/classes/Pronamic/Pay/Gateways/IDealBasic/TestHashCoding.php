<?php

class Pronamic_Pay_Gateways_IDealBasic_TestHashCoding extends WP_UnitTestCase {
	function test_hashcoding() {
		// http://pronamic.nl/wp-content/uploads/2011/12/iDEAL_Basic_EN_v2.3.pdf #page 23
		$ideal_basic = new Pronamic_Gateways_IDealBasic_IDealBasic();

		$ideal_basic->setHashKey( '41e3hHbYhmxxxxxx' );
		$ideal_basic->setMerchantId( '0050xxxxx' );
		$ideal_basic->setSubId( '0' );
		$ideal_basic->setPurchaseId( '10' );
		$ideal_basic->setPaymentType( 'ideal' );
		$ideal_basic->set_expire_date( new DateTime( '2009-01-01 12:34:56' ) );

		$item = new Pronamic_IDeal_Item();
		$item->setNumber( '1' );
		$item->setDescription( 'omschrijving' );
		$item->setQuantity( 1 );
		$item->setPrice( 1 );

		$items = $ideal_basic->get_items();
		$items->addItem( $item );

		// Other variables (not in hash)
		$ideal_basic->setLanguage( 'nl' );
		$ideal_basic->setCurrency( 'EUR' );
		$ideal_basic->setDescription( 'Example hashcode' );

		$baseurl = 'http://www.uwwebwinkel.nl';

		$ideal_basic->setSuccessUrl( "$baseurl/Success.html" );
		$ideal_basic->setCancelUrl( "$baseurl/Cancel.html" );
		$ideal_basic->setErrorUrl( "$baseurl/Error.html" );

		// Create hash
		$shasign = $ideal_basic->createHash();

		// Assert
		$this->assertEquals( '7615604527e1edd65521e2180e445d3a89abc794', $shasign );
	}
}
