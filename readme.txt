=== Pronamic Pay ===
Contributors: pronamic, remcotolsma 
Tags: ideal, bank, payment, gravity forms, forms, payment, woocommerce, recurring-payments, shopp, rabobank, friesland bank, ing, mollie, omnikassa, wpsc, wpecommerce, commerce, e-commerce, cart
Donate link: https://www.pronamic.eu/donate/?for=wp-plugin-pronamic-pay&source=wp-plugin-readme-txt
Requires at least: 4.7
Tested up to: 5.8
Requires PHP: 5.6
Stable tag: 6.9.4

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
*	DigiWallet
*	EMS - e-Commerce
*	ICEPAY
*	iDEAL Simulator - iDEAL Professional / Advanced / Zelfbouw (v3)
*	ING - iDEAL Basic
*	ING - iDEAL Advanced (v3)
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

The Pronamic Pay plugin extends WordPress extensions with payment methods such as iDEAL, Bancontact, Sofort and credit cards. To offer the payment methods to the visitors of your WordPress website you also require one of these e-commerce or form builder extensions.

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


== Changelog ==

= 6.9.4 - 2021-08-18 =
*	Updated WordPress pay EMS e-Commerce library to version `3.0.1`: https://github.com/wp-pay-gateways/ems-e-commerce/releases/tag/3.0.1.
	*	Fixed `chargetotal` number format.

= 6.9.3 - 2021-08-18 =
*	Updated WordPress pay Adyen library to version `2.0.1`: https://github.com/wp-pay-gateways/adyen/releases/tag/2.0.1.
	*	No longer require PHP `intl` extensie.
	*	Simplified exception handling.
*	Updated WordPress pay Buckaroo library to version `3.0.2`: https://github.com/wp-pay-gateways/buckaroo/releases/tag/3.0.2.
	*	Fix "Fatal error: Uncaught Error: Undefined class constant 'V_PAY'".

= 6.9.2 - 2021-08-17 =
*	Updated WordPress pay core library to version `3.0.1`: https://github.com/pronamic/wp-pay-core/releases/tag/3.0.1
	*	Added debug page for subscriptions follow-up payments.
	*	Added support for 'American Express' payment method.
	*	Added support for 'Mastercard' payment method.
	*	Added support for 'Visa' payment method.
	*	Added support for 'V PAY' payment method.
*	Updated WordPress pay MemberPress library to version `3.0.2`: https://github.com/wp-pay-extensions/memberpress/releases/tag/3.0.2.
	*	Fixed "Fatal error: Uncaught Error: Call to a member function get_periods() on bool".
*	Updated WordPress pay WooCommerce library to version `3.0.1`: https://github.com/wp-pay-extensions/woocommerce/releases/tag/3.0.1.
	*	Added American Express, Mastercard, V PAY and Visa payment gateways.
*	Updated WordPress pay OmniKassa 2.0 library to version `3.0.1`: https://github.com/wp-pay-gateways/omnikassa-2/releases/tag/3.0.1.
	*	Added support for Mastercard, V PAY and Visa.
*	Updated WordPress pay Buckaroo library to version `3.0.1`: https://github.com/wp-pay-gateways/buckaroo/releases/tag/3.0.1.
	*	Added support for American Express, Maestro, Mastercard, V PAY and Visa.
	*	Save `CustomerIBAN` and `CustomerBIC` for Sofort payments.

= 6.9.1 - 2021-08-13 =
*	Updated WordPress pay MemberPress library to version `3.0.1`: https://github.com/wp-pay-extensions/memberpress/releases/tag/3.0.1.
	*	Fixed "Fatal error: Uncaught Error: Class 'Pronamic\WordPress\Pay\Extensions\MemberPress\Money' not found".
*	Updated WordPress pay PayPal library to version `1.0.1`: https://github.com/wp-pay-gateways/paypal/releases/tag/1.0.1.
	*	Improved support for tax.

= 6.9.0 - 2021-08-09 =
*	Updated WordPress pay core library to version `3.0.0`: https://github.com/pronamic/wp-pay-core/releases/tag/3.0.0.
*	Updated WordPress pay money library to version `3.0.0`: https://github.com/pronamic/wp-money/releases/tag/2.0.0.
*	Added WordPress pay PayPal library version `1.0.0`: https://github.com/wp-pay-gateways/paypal/releases/tag/1.0.0.
*	Added support for SprayPay payment method.
*	Removed deprecated ING Kassa Compleet gateway, the `api.kassacompleet.nl` endpoint is no longer available.

[See changelog for all versions.](https://www.pronamic.eu/plugins/pronamic-pay/changelog/)

== Links ==

*	[Pronamic](https://www.pronamic.eu/)
*	[Remco Tolsma](https://www.remcotolsma.nl/)
