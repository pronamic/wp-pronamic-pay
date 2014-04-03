<?php

foreach ( $gateways as $gateway ) {
	printf( "*	" );
	
	if ( isset( $gateway['provider'], $pronamic_pay_providers[ $gateway['provider'] ] ) ) {
		$provider = $pronamic_pay_providers[ $gateway['provider'] ];
		
		if ( isset( $provider['url'] ) ) {
			printf( '[%s][%s] - ', $provider['name'], $gateway['provider'] );
		} else {
			printf( '%s - ', $provider['name'] );
		}
	}
	
	printf( "%s\n", $gateway['name'] );
}

printf( "\n" );

foreach ( $pronamic_pay_providers as $id => $provider ) {
	if ( isset( $provider['url'] ) ) {
		printf( "[%s]: %s\n", $id, $provider['url'] );
	}
}
