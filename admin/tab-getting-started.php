<?php
/**
 * Tab getting started
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>
<div class="feature-section two-col">
	<div class="col">
		<h3><?php esc_html_e( 'Step 1 - Account Payment Provider', 'pronamic_ideal' ); ?></h3>
		
		<p>
			<?php

esc_html_e( 'A payment provider is responsible for the actual processing of payment transactions.
We are not a payment provider, you will need to close an account with a payment provider or bank.
We support most popular payment providers and banks. 
The Pronamic iDEAL plugin makes the link between the selected payment provider and the e-commerce plugin.'
, 'pronamic_ideal' );

			?>
		</p>
	</div>

	<div class="col">
		<img src="//s.w.org/images/core/4.2/press-this.jpg">
	</div>
</div>

<div class="feature-section two-col">
	<div class="col">
		<img src="//s.w.org/images/core/4.2/press-this.jpg">
	</div>

	<div class="col">
		<h3><?php esc_html_e( 'Step 2 - Add Gateway Configuration', 'pronamic_ideal' ); ?></h3>
		
		<p>
			<?php

esc_html_e( 'Create a new configuration via iDEAL » Configurations » Add new.
The settings depend on the chosen payment provider.
Follow the instructions of the most popular payment providers to use the correct settings.'
, 'pronamic_ideal' );

			?>
		</p>
	</div>
</div>

<div class="feature-section two-col">
	<div class="col">
		<h3><?php esc_html_e( 'Step 3 - Test Gateway Configuration', 'pronamic_ideal' ); ?></h3>
		
		<p>
			Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer accumsan lacus sed ante rhoncus maximus. Aliquam pulvinar arcu et felis viverra mollis posuere.
		</p>
	</div>

	<div class="col">
		<img src="//s.w.org/images/core/4.2/press-this.jpg">
	</div>
</div>

<div class="feature-section two-col">
	<div class="col">
		<img src="//s.w.org/images/core/4.2/press-this.jpg">
	</div>

	<div class="col">
		<h3><?php esc_html_e( 'Step 4 - Configure E-commerce Plugin', 'pronamic_ideal' ); ?></h3>
		
		<p>
			Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer accumsan lacus sed ante rhoncus maximus. Aliquam pulvinar arcu et felis viverra mollis posuere.
		</p>
	</div>
</div>
