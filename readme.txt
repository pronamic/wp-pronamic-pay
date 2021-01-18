=== Pronamic Pay ===
Contributors: pronamic, remcotolsma 
Tags: ideal, bank, payment, gravity forms, forms, payment, woocommerce, recurring-payments, shopp, rabobank, friesland bank, ing, mollie, omnikassa, wpsc, wpecommerce, commerce, e-commerce, cart
Donate link: https://www.pronamic.eu/donate/?for=wp-plugin-pronamic-pay&source=wp-plugin-readme-txt
Requires at least: 4.7
Tested up to: 5.6
Requires PHP: 5.6
Stable tag: 6.6.0

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

= 6.6.1 - 2021-01-18 =
*	Updated WordPress pay core library to version 2.6.1.
	*	Added support for recurring payments with Apple Pay.
*	Updated WordPress pay Mollie library to version 2.2.1.
	*	Added support for first payment with regular iDEAL/Bancontact/Sofort payment methods.
	*	Added support for recurring payments with Apple Pay.
	*	Added 'Change Payment State' URL to Mollie payment admin page.
	*	Chargebacks now update subscriptions status to 'On hold' (needs manual review).
*	Updated WordPress pay MultiSafepay library to version 2.1.2.
	*	Added support for In3 pament method.
	*	Added partial support for Santander 'Betaal per maand' payment method.
*	Updated WordPress pay Payvision library to version 1.0.1.
	*	Added business ID to gateway ID column in payments overview.
*	Updated WordPress pay Event Espresso (legacy) library to version 2.3.1.
	*	Fixed syntax errors.
*	Updated WordPress pay MemberPress library to version 2.2.2.
	*	Added support for recurring payments with Apple Pay.
	*	Updated payment method icons to use wp-pay/logos library.

= 6.6.0 - 2021-01-14 =
*	Updated WordPress pay core library to version 2.6.0.
	*	Payment Gateway Referral Exclusions in Google Analytics.
	*	Added Santander payment method.
	*	Ask for confirmation before manually cancelling a subscription.
	*	Redirect to new 'Subscription Canceled' status page after cancelling subscriptions.
	*	Fixed updating subscription dates on next period payment creation.
	*	Only add user agent in payment info meta box if not empty.
	*	Added feature to manually start the next subscription payment.
*	Updated WordPress pay Charitable library to version 2.2.1.
	*	Improved donation total amount value retrieval.
	*	Improved user data support, set adress line 2 and country code.
*	Updated WordPress pay Contact Form 7 to version 1.0.3.
	*	Fix redirecting when scripts are disabled through `wpcf7_load_js` filter.
*	Updated WordPress pay Formidable Forms to version 2.2.0.
	*	Simplified icon hover style.
	*	Updated form action icon.
	*	Added support for form settings redirect success URL.
*	Updated WordPress pay Ninja Forms to version 1.3.0.
	*	Fixed notice payment redirect URL.
*	Updated WordPress pay Restrict Content Pro to version 2.3.1.
	*	Renew inactive membership on successful (retry) payment.
	*	Fix not using checkout label setting.
*	Updated WordPress pay s2Member to version 2.2.1.
	*	Prevent updating eot if (retry) payment period end date is (before) current eot time.
	*	Fix using removed payment data class and multiple status update actions.
	*	Fix setting subscription next payment date for new subscriptions (removes payment data class).
*	Updated WordPress pay WooCommerce to version 2.2.1.
	*	Updated logo library to version 1.6.3 for new iDEAL logo.
	*	Start subscription payment through subscription module instead of plugin.
	*	Move info message up on thank you page.
	*	Add Santander payment method.

= 6.5.1 - 2020-11-19 =
*	Updated WordPress pay core library to version 2.5.1.
	*	Fixed always setting payment customer details.
	*	Fixed setting currency in payment lines amount.
*	Updated WordPress pay Gravity Forms library to version 2.5.1.
	*	Updated getting subscription from payment period.
*	Updated WordPress pay Adyen library to version 1.2.1.
	*	Removed unused configuration to store card details.

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

[See changelog for all versions.](https://www.pronamic.eu/plugins/pronamic-pay/changelog/)

== Links ==

*	[Pronamic](https://www.pronamic.eu/)
*	[Remco Tolsma](https://www.remcotolsma.nl/)
