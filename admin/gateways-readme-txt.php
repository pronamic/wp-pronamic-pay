<?php

foreach ( $pronamic_pay_providers as $provider ) {
	if ( isset( $provider['url'] ) ) {
		printf( "*	[%s](%s)\n", esc_html( $provider['name'] ), esc_html( $provider['url'] ) );
	} else {
		printf( "*	%s\n", esc_html( $provider['name'] ) );
	}

	foreach ( $provider['gateways'] as $id => $gateway ) {
		printf( "	*	%s\n", esc_html( $gateway['name'] ) );
	}
}
