=== Pronamic Pay ===
Contributors: pronamic, remcotolsma 
Tags: ideal, bank, payment, gravity forms, forms, payment, woocommerce, recurring-payments, shopp, rabobank, friesland bank, ing, mollie, omnikassa, wpsc, wpecommerce, commerce, e-commerce, cart
Donate link: https://www.pronamic.eu/donate/?for=wp-plugin-pronamic-ideal&source=wp-plugin-readme-txt
Requires at least: 4.7
Tested up to: 5.2
Requires PHP: 5.6
Stable tag: 5.7.4

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
*	[iThemes Exchange](https://ithemes.com/exchange/)
*	[Jigoshop](https://www.jigoshop.com/)
*	[MemberPress](https://www.memberpress.com/)
*	[Membership 2](https://wordpress.org/plugins/membership/)
*	[Membership Premium](https://premium.wpmudev.org/project/membership/)
*	[Ninja Forms](https://ninjaforms.com/)
*	[Restrict Content Pro](https://restrictcontentpro.com/)
*	[s2Member®](https://s2member.com/)
*	[Shopp](https://shopplugin.net/)
*	[WooCommerce](https://woocommerce.com/)
*	[WP e-Commerce](https://wpecommerce.org/)
*	[Crowdfunding by Astoundify](https://wordpress.org/plugins/appthemer-crowdfunding/)
*	[ClassiPress](https://www.appthemes.com/themes/classipress/)
*	[JobRoller](https://www.appthemes.com/themes/jobroller/)
*	[Vantage](https://www.appthemes.com/themes/vantage/)
*	[Campaignify](https://astoundify.com/)

= Supported payment providers =

*	ABN AMRO - iDEAL Zelfbouw (v3)
*	Adyen
*	Buckaroo - HTML
*	Deutsche Bank - iDEAL Expert (v3)
*	EMS - e-Commerce
*	Fibonacci ORANGE
*	ICEPAY
*	iDEAL Simulator - iDEAL Professional / Advanced / Zelfbouw (v3)
*	ING - iDEAL Basic
*	ING - iDEAL Advanced (v3)
*	ING - Kassa Compleet
*	Mollie
*	MultiSafepay - Connect
*	Nocks
*	Ingenico/Ogone - DirectLink
*	Ingenico/Ogone - OrderStandard
*	Pay.nl
*	Rabobank - OmniKassa 2.0
*	Rabobank - iDEAL Professional (v3)
*	Sisow
*	TargetPay - iDEAL

= Premium payment providers =

*	Adyen

Premium payment providers require a [Pro license](https://www.pronamic.eu/plugins/pronamic-ideal/).

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

= 5.7.4 - 2019-09-02 =
*	Updated WordPress pay Gravity Forms library to version 2.1.11.
	*	Fix entry payment fulfillment.
*	Updated WordPress pay MemberPress library to version 2.0.10.
	*	Fix error "`DatePeriod::__construct()`: The recurrence count '0' is invalid. Needs to be > 0".

= 5.7.3 - 2019-08-30 =
*	Updated WordPress pay Sisow library to version 2.0.3.
	*	Fix possible error with tax request parameters.
*	Updated WordPress pay iDEAL Advanced v3 library to version 2.0.4.
	*	Removed 'Show details...' toggle link in settings.

= 5.7.2 - 2019-08-30 =
*	Updated WordPress pay core library to version 2.2.3.
	*	Fix not loading gateways.

= 5.7.1 - 2019-08-30 =
*	Updated WordPress pay core library to version 2.2.2.
	*	Improved backwards compatibility for `pronamic_pay_gateways` filter.
*	Updated WordPress pay Gravity Forms library to version 2.1.10.
	*	Fix possible error with subscriptions "Uncaught Exception: DatePeriod::__construct(): This constructor accepts either...".
	*	Improve GF Nested Forms compatibility.
*	Updated WordPress pay WooCommerce library to version 2.0.8.
	*	Fix error "`DatePeriod::__construct()`: The recurrence count '0' is invalid. Needs to be > 0".

= 5.7.0 - 2019-08-26 =
*	Updated WordPress pay Formidable Forms library to version 2.0.3.
	*	Improved Formidable Forms v4 compatibility.
*	Updated WordPress pay MemberPress library to version 2.0.9.
	*	Fix incorrect subscription frequency.
	*	No longer start up follow-up payments for paused subscriptions.
*	Updated WordPress pay Restrict Content Pro library to version 2.1.4.
	*	Fixed support for Restrict Content Pro 3.0.
*	Updated WordPress pay Adyen library to version 1.0.2.
	*	Set country from billing address.
	*	Added action `pronamic_pay_adyen_checkout_head`.
	*	Added `pronamic_pay_adyen_config_object` filter and improved documentation.
*	Updated WordPress pay ICEPAY library to version 2.0.4.
	*	Force language `NL` for unsupported languages (i.e. `EN` for iDEAL).
	*	Only force language if payment method is set.
*	Updated WordPress pay Sisow library to version 2.0.2.
	*	Get available payment methods for merchant from Sisow account.
	*	Transform status `Reversed` to WordPress Pay status `Refunded`.
*	Updated WordPress pay Nocks library to version 2.0.2.
	*	Do not use removed `set_slug()` method.
*	Updated WordPress pay Mollie library to version 2.0.2.
	*	Updated to Mollie API v2, with multicurrency support.
	*	Added EPS payment method.
	*	Added filter for subscription 'Next Payment Delivery Date'.
*	Removed Paytor integration, still supported via the Mollie gateway. For more information see https://www.wp-pay.org/paytor-disappeared-now-part-of-mollie/.
*	Removed Qantani (new platform) integration, still supported via the Mollie gateway.
*	Removed Postcode.nl integration, for more information see https://github.com/wp-pay-gateways/postcode-ideal/blob/master/DEPRECATED.md.

[See changelog for all versions.](https://www.pronamic.eu/plugins/pronamic-ideal/changelog/)

== Links ==

*	[Pronamic](https://www.pronamic.eu/)
*	[Remco Tolsma](https://www.remcotolsma.nl/)
