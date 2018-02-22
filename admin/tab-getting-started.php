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

esc_html_e(
	"To start accepting online payments, you'll first need to register with a payment provider. Payment
providers are responsible for the actual processing of transactions. We support most popular Dutch payment
providers and banks, but are not a payment provider ourselves. The Pronamic Pay plugin is the glue between
the chosen payment provider and a wide variety of supported e-commerce plugins.", 'pronamic_ideal'
);

			?>

		</p>

		<p>
			<?php

			printf(
				'» <a href="%1$s" title="%2$s">%2$s</a>',
				esc_url(
					add_query_arg(
						array(
							'page' => 'pronamic_pay_tools',
							'tab'  => 'gateways',
						), admin_url( 'admin.php' )
					)
				),
				esc_html__( 'View supported payment providers', 'pronamic_ideal' )
			);

			?>
		</p>
	</div>

	<div class="col">
		<img src="https://www.pronamic.nl/wp-content/uploads/2016/10/pronamic-ideal-payment-providers.png" />
	</div>
</div>

<div class="feature-section two-col">
	<div class="col">
		<img src="https://www.pronamic.nl/wp-content/uploads/2016/10/pronamic-ideal-new-gateway-configuration.png" />
	</div>

	<div class="col">
		<h3><?php esc_html_e( 'Step 2: add payment provider configuration', 'pronamic_ideal' ); ?></h3>

		<p>
			<?php

esc_html_e(
	'Create a new configuration via Pay » Configurations » Add new. The account details to use
are provided by the payment provider via e-mail or through a dashboard.', 'pronamic_ideal'
);

			?>
		</p>

		<p>
			<?php

esc_html_e(
	'Some payment providers need additional setup to receive automatic payment status updates in WordPress. Please
see the "Transaction Feedback" tab of the configuration for details.', 'pronamic_ideal'
);

			?>
		</p>

		<p>
			<?php

			printf(
				'» <a href="%1$s" title="%2$s">%2$s</a>',
				esc_url(
					add_query_arg(
						array(
							'post_type' => 'pronamic_gateway',
						), admin_url( 'post-new.php' )
					)
				),
				esc_html__( 'Add new payment provider configuration', 'pronamic_ideal' )
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

esc_html_e(
	'After the payment provider configuration has been added, the "Test" section below the
settings box can be used to test the configuration. You can choose from various payment methods (depending on the
payment provider) and enter an amount for the payment. The configuration is working properly if you are redirected to a
screen that mentions the same amount.', 'pronamic_ideal'
);

			?>
		</p>

		<p>
			<?php

esc_html_e(
	'Testing different amounts might be mandatory for your payment provider. If so, an additional box with
amounts will be shown. You need to test all these amounts before your live account will be activated by the payment provider.', 'pronamic_ideal'
);

			?>
		</p>
	</div>

	<div class="col">
		<img src="https://www.pronamic.nl/wp-content/uploads/2016/10/pronamic-ideal-meta-box-test.png" />
	</div>
</div>

<div class="feature-section two-col">
	<div class="col">
		<img src="https://www.pronamic.nl/wp-content/uploads/2016/10/pronamic-ideal-extensions.png" />
	</div>

	<div class="col">
		<h3><?php esc_html_e( 'Step 4: configure e-commerce plugin', 'pronamic_ideal' ); ?></h3>

		<p>
			<?php

esc_html_e(
	'To actually use the configuration to receive payments, you need to configure your e-commerce
plugin. Many popular e-commerce plugins are supported. These plugins usually have their own payment settings
page, where you choose which configuration to use in the payment methods that are provided by the Pronamic iDEAL plugin.', 'pronamic_ideal'
);

			?>
		</p>

		<p>
			<?php

			printf(
				'» <a href="%1$s" title="%2$s">%2$s</a>',
				esc_url(
					add_query_arg(
						array(
							'page' => 'pronamic_pay_tools',
							'tab'  => 'extensions',
						), admin_url( 'admin.php' )
					)
				),
				esc_html__( 'View supported e-commerce plugins', 'pronamic_ideal' )
			);

			?>
		</p>
	</div>
</div>
