<?php

foreach ( $pronamic_pay_providers as $provider ) {
	if ( isset( $provider['url'] ) ) {
		printf( "*	[%s](%s)\n", $provider['name'], $provider['url'] );
	} else {
		printf( "*	%s\n", $provider['name'] );
	}
	
	foreach ( $provider['gateways']  as $id => $gateway ) {
		printf( "	*	%s\n", $gateway['name'] );
	}
}
