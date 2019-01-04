<?php
/**
 * Meta box gateway config payment methods.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

namespace Pronamic\WordPress\Pay;

?>

<ul class="pronamic-pay-payment-methods">

	<?php

	foreach ( $payment_methods as $payment_method => $details ) {
		$class = $payment_method;

		if ( $details['available'] ) {
			$class .= ' available';
		}

		printf(
			'<li class="%1$s"><span class="pronamic-pay-icon pronamic-pay-icon-completed"></span> %2$s</li>',
			esc_attr( $class ),
			esc_html( $details['name'] )
		);
	}

	?>

</ul>
