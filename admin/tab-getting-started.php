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
		<img src="<?php echo esc_attr( plugins_url( 'images/getting-started/payment-providers.png', $this->plugin->file ) ); ?>" />
	</div>
</div>

<div class="feature-section two-col">
	<div class="col">
		<img src="<?php echo esc_attr( plugins_url( 'images/getting-started/new-gateway-configuration.png', $this->plugin->file ) ); ?>" />
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
			<?php

esc_html_e( 'Once the gateway settings are entered correctly and the configuration is saved it can be tested.
Under the configuration fields there is a special block which can be used to test the configuration.
Here you can enter a test amount and confirm that the configuration works properly.
Once you are correctly redirected to your payment provider you know that your configuration works.'
, 'pronamic_ideal' );

			?>
		</p>
	</div>

	<div class="col">
		<img src="<?php echo esc_attr( plugins_url( 'images/getting-started/meta-box-test.png', $this->plugin->file ) ); ?>" />
	</div>
</div>

<div class="feature-section two-col">
	<div class="col">
		<img src="<?php echo esc_attr( plugins_url( 'images/getting-started/extensions.png', $this->plugin->file ) ); ?>" />
	</div>

	<div class="col">
		<h3><?php esc_html_e( 'Step 4 - Configure E-commerce Plugin', 'pronamic_ideal' ); ?></h3>
		
		<p>
			<?php

esc_html_e( 'After configuring and testing the gateway, you can configure your e-commerce plugin.
The Pronamic iDEAL plugin supports many popular WordPress plugins.
These plug-ins often have their own configuration pages where you can activate iDEAL
 Here you can then also select the previously created gateway configuration.'
, 'pronamic_ideal' );

			?>
		</p>
	</div>
</div>
