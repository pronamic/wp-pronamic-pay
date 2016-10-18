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
		<h3><?php esc_html_e( 'Step 1: open an account with a payment provider', 'pronamic_ideal' ); ?></h3>
		
		<p>
			<?php

esc_html_e( "To start accepting online payments, you'll first need to register with a payment provider. Payment
providers are responsible for the actual processing of transactions. We support most popular Dutch payment
providers and banks, but are not a payment provider ourselves. The Pronamic iDEAL plugin is the glue between
the chosen payment provider and a wide variety of supported e-commerce plugins.", 'pronamic_ideal' );

			?>

		</p>

		<p>
			<?php

			printf(
				'» <a href="%1$s" title="%2$s">%2$s</a>',
				esc_url( add_query_arg( array(
					'page' => 'pronamic_pay_tools',
					'tab'  => 'gateways',
				), admin_url( 'admin.php' ) ) ),
				__( 'View supported payment providers', 'pronamic_ideal' )
			);

			?>
		</p>
	</div>

	<div class="col">
		<img src="<?php echo esc_attr( plugins_url( 'images/getting-started/payment-providers.png', Pronamic_WP_Pay_Plugin::$file ) ); ?>" />
	</div>
</div>

<div class="feature-section two-col">
	<div class="col">
		<img src="<?php echo esc_attr( plugins_url( 'images/getting-started/new-gateway-configuration.png', Pronamic_WP_Pay_Plugin::$file ) ); ?>" />
	</div>

	<div class="col">
		<h3><?php esc_html_e( 'Step 2: add payment provider configuration', 'pronamic_ideal' ); ?></h3>

		<p>
			<?php

esc_html_e( 'Create a new configuration via Pay » Configurations » Add new. The account details to use
are provided by the payment provider via e-mail or through a dashboard.', 'pronamic_ideal' );

			?>
		</p>

		<p>
			<?php

esc_html_e( 'Some payment providers need additional setup to receive automatic payment status updates in WordPress. Please
see the "Transaction Feedback" tab of the configuration for details.', 'pronamic_ideal' );

			?>
		</p>

		<p>
			<?php

			printf(
				'» <a href="%1$s" title="%2$s">%2$s</a>',
				esc_url( add_query_arg( array(
					'post_type' => 'pronamic_gateway',
				), admin_url( 'post-new.php' ) ) ),
				__( 'Add new payment provider configuration', 'pronamic_ideal' )
			);

			?>
		</p>
	</div>
</div>

<div class="feature-section two-col">
	<div class="col">
		<h3><?php esc_html_e( 'Step 3: test configuration', 'pronamic_ideal' ); ?></h3>

		<p>
			<?php

esc_html_e( 'After the payment provider configuration has been added, the "Test" section below the
settings box can be used to test the configuration. You can choose from various payment methods (depending on the
payment provider) and enter an amount for the payment. The configuration is working properly if you are redirected to a
screen that mentions the same amount.', 'pronamic_ideal' );

			?>
		</p>

		<p>
			<?php

esc_html_e( 'Testing different amounts might be mandatory for your payment provider. If so, an additional box with
amounts will be shown. You need to test all these amounts before your live account will be activated by the payment provider.', 'pronamic_ideal' );

			?>
		</p>
	</div>

	<div class="col">
		<img src="<?php echo esc_attr( plugins_url( 'images/getting-started/meta-box-test.png', Pronamic_WP_Pay_Plugin::$file ) ); ?>" />
	</div>
</div>

<div class="feature-section two-col">
	<div class="col">
		<img src="<?php echo esc_attr( plugins_url( 'images/getting-started/extensions.png', Pronamic_WP_Pay_Plugin::$file ) ); ?>" />
	</div>

	<div class="col">
		<h3><?php esc_html_e( 'Step 4: configure e-commerce plugin', 'pronamic_ideal' ); ?></h3>
		
		<p>
			<?php

esc_html_e( 'To actually use the configuration to receive payments, you need to configure your e-commerce
plugin. Many popular e-commerce plugins are supported. These plugins usually have their own payment settings
page, where you choose which configuration to use in the payment methods that are provided by the Pronamic iDEAL plugin.', 'pronamic_ideal' );

			?>
		</p>
	</div>
</div>
