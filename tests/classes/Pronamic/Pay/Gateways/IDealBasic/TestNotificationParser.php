<?php

class Pronamic_Pay_Gateways_IDealBasic_TestNotificationParser extends WP_UnitTestCase {
	function test_init() {
		$filename = Pronamic_WordPress_IDeal_Plugin::$dirname . '/tests/data/Pronamic/Pay/Gateways/IDealBasic/notification.xml';

		$simplexml = simplexml_load_file( $filename );

		$this->assertInstanceOf( 'SimpleXMLElement', $simplexml );

		return $simplexml;
	}

	/**
	 * @depends test_init
	 */
	function test_parser( $simplexml ) {
		$notification = Pronamic_Gateways_IDealBasic_XML_NotificationParser::parse( $simplexml );

		$this->assertInstanceOf( 'Pronamic_Gateways_IDealBasic_Notification', $notification );

		return $notification;
	}

	/**
	 * @depends test_parser
	 */
	function test_values( $notification ) {
		$expected = new Pronamic_Gateways_IDealBasic_Notification();
		$expected->set_date( new DateTime( '20131022120742' ) );
		$expected->set_transaction_id( '0020000048638175' );
		$expected->set_purchase_id( '1382436458' );
		$expected->set_status( Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_SUCCESS );

		$this->assertEquals( $expected, $notification );
	}
}
