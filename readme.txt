=== Pronamic Pay ===
Contributors: pronamic, remcotolsma 
Tags: ideal, bank, payment, gravity forms, forms, payment, woocommerce, recurring-payments, shopp, rabobank, friesland bank, ing, mollie, omnikassa, wpsc, wpecommerce, commerce, e-commerce, cart
Donate link: https://www.pronamic.eu/donate/?for=wp-plugin-pronamic-pay&source=wp-plugin-readme-txt
Requires at least: 4.7
Tested up to: 5.5
Requires PHP: 5.6
Stable tag: 6.5.0

The Pronamic Pay plugin adds payment methods like iDEAL, Bancontact, credit card and more to your WordPress site for a variety of payment providers.


== Description ==

Pronamic Pay is the best plugin available to accept payments on your site with support for payment methods like iDEAL (Netherlands), Bancontact (Belgium), Sofort (Europe) and credit card, among others. Easily add the configuration details of your payment service provider account and enable the payment method in one of the supported e-commerce plugins. With over 200,000 downloads, the plugin has proven itself as a reliable WordPress solution to use for your payments.

= Key Benefits =

*   Supports a wide variety of payment providers.
*   Seamless integration with popular e-commerce and form builder plugins.
*   Automatically updates payment status of orders in WordPress.
*   Easily manage (multiple) payment provider configurations.
*   Continually updated to support the latest e-commerce plugins.
*   Built-in generation of required security certificates.
*   Works with all popular WordPress e-commerce plugins.
*   Recurring payments support for Mollie.
*   Reliable payment solution, with over 200,000 downloads.

= Supported WordPress e-commerce plugins =

*	[Charitable](https://www.wpcharitable.com/)
*	[Contact Form 7](https://contactform7.com/) (requires [Basic license](https://www.pronamic.eu/plugins/pronamic-pay/))
*	[Easy Digital Downloads](https://easydigitaldownloads.com/)
*	[Event Espresso 3](https://eventespresso.com/)
*	[Event Espresso 3 Lite](https://eventespresso.com/)
*	[Event Espresso 4](https://eventespresso.com/)
*	[Event Espresso 4 Decaf](https://eventespresso.com/)
*	[Formidable Forms](https://formidableforms.com/)
*	[Give](https://givewp.com/)
*	[Gravity Forms](https://www.gravityforms.com/)
*	[Gravity Forms AWeber Add-On](https://www.gravityforms.com/add-ons/aweber/)
*	[Gravity Forms Campaign Monitor Add-On](https://www.gravityforms.com/add-ons/campaign-monitor/)
*	[Gravity Forms MailChimp Add-On](https://www.gravityforms.com/add-ons/mailchimp/)
*	[Gravity Forms User Registration Add-On](https://www.gravityforms.com/add-ons/user-registration/)
*	[Gravity Forms Zapier Add-On](https://www.gravityforms.com/add-ons/zapier/)
*	[MemberPress](https://www.memberpress.com/)
*	[Ninja Forms](https://ninjaforms.com/)
*	[Restrict Content Pro](https://restrictcontentpro.com/)
*	[s2Member®](https://s2member.com/)
*	[WooCommerce](https://woocommerce.com/)
*	[WP e-Commerce](https://wpecommerce.org/)

= Supported payment providers =

*	ABN AMRO - iDEAL Zelfbouw (v3)
*	Adyen (requires [Pro license](https://www.pronamic.eu/plugins/pronamic-pay/))
*	Buckaroo - HTML
*	Deutsche Bank - iDEAL Expert (v3)
*	EMS - e-Commerce
*	ICEPAY
*	iDEAL Simulator - iDEAL Professional / Advanced / Zelfbouw (v3)
*	ING - iDEAL Basic
*	ING - iDEAL Advanced (v3)
*	ING - Kassa Compleet
*	Mollie
*	MultiSafepay - Connect
*	Ingenico/Ogone - DirectLink
*	Ingenico/Ogone - OrderStandard
*	Pay.nl
*	Payvision (requires [Basic license](https://www.pronamic.eu/plugins/pronamic-pay/))
*	Rabobank - OmniKassa 2.0
*	Rabobank - iDEAL Professional (v3)
*	Sisow
*	TargetPay - iDEAL


== Installation ==

= Requirements =

The Pronamic Pay plugin extends WordPress extensions with payment methods such as iDEAL, Bancontact, Sofort and credit cards. To offer the payment methods to the vistors of your WordPress website you also require one of these e-commerce or form builder extensions.

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t need to leave your web browser. To do an automatic install of Pronamic Pay, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type “Pronamic Pay” and click Search Plugins. Once you’ve found the plugin you can view details about it such as the the point release, rating and description. Most importantly of course, you can install it by simply clicking “Install Now”.

= Manual installation =

The manual installation method involves downloading the plugin and uploading it to your webserver via your favourite FTP application. The WordPress codex contains [instructions on how to do this](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

= Updating =

Automatic updates should work like a charm; as always though, ensure you backup your site just in case.


== Screenshots ==

1. Payment provider configurations
2. Edit configuration
3. Built-in payment forms
4. Edit payment form
5. Payment form sample
6. Payments
7. Subscriptions
8. Reports
9. Settings
10. Gravity Forms - Form editor
11. Gravity Forms - Feed editor
12. Gravity Forms - Sample payment form
13. WooCommerce - Checkout settings
14. WooCommerce - Checkout form
15. WooCommerce - Checkout with credit card and valid mandate (mandates work with payment provider Mollie, subscriptions require WooCommerce Subscriptions)
16. s2Member - Button generator
17. Getting started


== Are there any known plugin conflicts? ==

Unfortunately WordPress is notorious for conflicts between themes and plugins. It is unavoidable as you have no control over what other plugins and themes do. While we do take steps to avoid conflicts as best we can, we have no control over other plugins or themes.

As conflicts are found we will update this list. If you discover a conflict with a another plugin, please notify us.

Here is a list of known plugin conflicts:

=== WPML ===

The [WPML](https://wpml.org/) plugin(s) can conflict with multiple gateways. A lot of the gateways use `home_url( '/' )` to retrieve the WordPress home URL. The WPML plugins hooks in to this function to change the home URL to the correct language URL. This can result in incorrect checksums, signatures and hashes.

=== WordPress HTTPS ===

The [WordPress HTTPS](https://wordpress.org/plugins/wordpress-https/) can conflict with the OmniKassa payment method. It can cause invalid signature errors. The WordPress HTTPS plugin parses the complete output of an WordPress website and changes 'http' URLs to 'https' URLs, this  results in OmniKassa data that no longer matches the signature.


== Changelog ==

= 6.5.0 - 2020-11-18 =
*	Added support for Payvision gateway (requires Basic license).
*	Updated WordPress pay iDEAL Advanced library to version 2.1.3.
	*	Fix regression in payment status retrieval.
*	Removed deprecated Fibonacci ORANGE gateway.
*	Removed deprecated Mollie iDEAL gateway.
*	Removed deprecated Nocks gateway.
*	Removed deprecated Rabobank OmniKassa gateway.

= 6.4.1 - 2020-11-10 =
*	Updated WordPress pay iDEAL Advanced library to version 2.1.2.
	*	Fixed acquirer URL.
*	Updated WordPress pay iDEAL Basic library to version 2.1.2.
	*	Fixed acquirer URL.

= 6.4.0 - 2020-11-09 =
*	Updated WordPress pay core library to version 2.5.0.
	*	Added support for subscription phases.
	*	Added support for Przelewy24 payment method.
	*	Improved data stores, reuse data from memory.
	*	Catch money parser exceptions in blocks.
	*	Introduced some traits for the DRY principle.
	*	Payments can be linked to multiple subscription periods.
	*	Improved support for subscription alignment and proration.
	*	Added REST API endpoint for subscription phases.
	*	Removed `$subscription->get_total_amount()` in favor of getting amount from phases.
	*	Removed ability to manually change subscription amount for now.
	*	No longer start recurring payments for expired subscriptions.
*	Updated WordPress pay Adyen library to version 1.2.0.
	*	Added REST route permission callbacks.
*	Updated WordPress pay Mollie library to version 2.2.0.
	*	Added Przelewy24 payment method.
	*	Added REST route permission callback.
	*	Improved determining customer if previously used customer has been removed at Mollie.
	*	Fixed filtering next payment delivery date.
	*	Fixed incorrect check for failed payment bank reason detail.
*	Updated WordPress pay Nocks library to version 2.2.0.
	*	Deprecated gateway as Nocks no longer exists (https://guldenbites.com/2020/05/15/nocks-announcement/).
*	Updated WordPress pay OmniKassa 2.0 library to version 2.3.0.
	*	Switched to REST API for webhook.
	*	Catch input JSON validation exception in webhook listener.
*	Updated WordPress pay Pay.nl library to version 2.1.1.
	*	Limited first and last name to 32 characters.
*	Updated WordPress pay Charitable library to version 2.1.3.
	*	Improved getting user data from donation.
*	Updated WordPress pay Contact Form 7 library to version 1.0.2.
	*	Fixed getting amount from free text value.
*	Updated WordPress pay Formidable Forms library to version 2.1.4.
	*	Improved error handling on payment start.
	*	Fixed incorrect amount when using product fields.
*	Updated WordPress pay Gravity Forms library to version 2.5.0.
	*	Changed 'Frequency' to 'Number of Periods' in payment feed subscription settings.
	*	Changed 'Synchronized payment date' to 'Fixed Subscription Period' in payment feed subscription settings.
	*	Places Euro symbol left of amount in Gravity Forms currency when using Dutch language.
	*	Added Dutch address notation for Gravity Forms.
	*	Added support for new subscription phases and periods.
	*	Fixed unselected options in payment method selector after processing conditional logic.
*	Updated WordPress pay MemberPress library to version 2.2.0.
	*	Added Przelewy24 payment method.
	*	Added support for new subscription phases and periods.
	*	Added support for trials and (prorated) upgrades/downgrade.
	*	Set Pronamic Pay subscription on hold if non-recurring payment fails.
*	Updated WordPress pay Restrict Content Pro library to version 2.3.0.
	*	Changed setting the next payment date 1 day earlier, to prevent temporary membership expirations.
	*	No longer mark Pronamic Pay subscriptions as expired when a Restrict Content Pro membership expires.
	*	Added support for new subscription phases and periods.
	*	Added support for trials to credit card and direct debit methods.
	*	Added support for payment fees.
*	Updated WordPress pay s2Member library to version 2.2.0.
	*	Added support for new subscription phases and periods.
	*	Fixed processing list servers for recurring payments.
*	Updated WordPress pay WooCommerce library to version 2.2.0.
	*	Updated iDEAL logo.
	*	Added Przelewy24 payment method.
	*	Added support for new subscription phases and periods.
	*	Fixed incorrect 'Awaiting payment' order note for recurring payments in some cases.
	*	Fixed using default payment description if setting is empty.

= 6.3.2 - 2020-08-05 =
*	Updated WordPress pay MemberPress library to version 2.1.3.
	*	Fixed reactivating cancelled MemberPress subscription when pending recurring payment completes.
*	Updated WordPress pay WooCommerce library to version 2.1.4.
	*	Fixed possible error on WooCommerce products admin page.

= 6.3.1 - 2020-07-23 =
*	Updated WordPress pay core library to version 2.4.1.
	*	Added email address as fallback for customer name in payments and subscriptions overview and details.
	*	Fixed using deprecated `email` and `customer_name` properties.
*	Updated WordPress pay Restrict Content Pro library to version 2.2.3.
	*	Fixed possible 'Fatal error: Call to a member function `get_id()` on null'.
*	Updated WordPress pay s2Member library to version 2.1.3.
	*	Fixed creating empty subscriptions.
*	Updated WordPress pay WooCommerce library to version 2.1.3.
	*	Fixed compatibility with WooCommerce EU VAT Number plugin.

[See changelog for all versions.](https://www.pronamic.eu/plugins/pronamic-pay/changelog/)

== Links ==

*	[Pronamic](https://www.pronamic.eu/)
*	[Remco Tolsma](https://www.remcotolsma.nl/)
