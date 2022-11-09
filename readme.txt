=== Pronamic Pay ===
Contributors: pronamic, remcotolsma 
Tags: ideal, bank, payment, gravity forms, forms, payment, woocommerce, recurring-payments, shopp, rabobank, friesland bank, ing, mollie, omnikassa, wpsc, wpecommerce, commerce, e-commerce, cart
Donate link: https://www.pronamic.eu/donate/?for=wp-plugin-pronamic-pay&source=wp-plugin-readme-txt
Requires at least: 5.2
Tested up to: 6.1
Requires PHP: 7.4
Stable tag: 9.1.2

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
*	[WooCommerce](https://woocommerce.com/)

= Supported payment providers =

*	ABN AMRO - iDEAL Zelfbouw
*	Adyen (requires [Pro license](https://www.pronamic.eu/plugins/pronamic-pay/))
*	Buckaroo - HTML
*	Deutsche Bank - iDEAL Expert
*	DigiWallet
*	EMS - e-Commerce
*	ICEPAY
*	iDEAL Simulator - iDEAL Professional / Advanced / Zelfbouw
*	ING - iDEAL Basic
*	ING - iDEAL Advanced
*	ING - iDEAL Advanced (new platform)
*	Mollie
*	MultiSafepay - Connect
*	Ingenico/Ogone - DirectLink
*	Ingenico/Ogone - OrderStandard
*	Pay.nl
*	Rabobank - OmniKassa 2.0
*	Rabobank - iDEAL Professional
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

= 9.1.2 - 2022-11-09 =
*	Updated WordPress pay WooCommerce library to version 4.3.2.
	*	Fixed "Fatal error: Uncaught Error: Call to undefined function wcs_get_subscriptions_for_order()". ([#29](https://github.com/pronamic/wp-pronamic-pay-woocommerce/issues/29)

= 9.1.1 - 2022-11-07 =
*	Updated WordPress pay WooCommerce library to version `4.3.1`.
	*	Fixed "Fatal error: Uncaught Error: Call to undefined function wcs_get_subscription()". Props @jeffreyvr. [#28](https://github.com/pronamic/wp-pronamic-pay-woocommerce/pull/28)

= 9.1.0 - 2022-11-07 =
*	Updated WordPress pay core library to version `4.5.0`.
	*	Catch exceptions while retrieving options from for example iDEAL issuer select fields. ([#78](https://github.com/pronamic/wp-pay-core/issues/78))
	*	Allow subscription payments at gateways that don't have support for recurring payments. ([pronamic/wp-pronamic-pay-woocommerce#15](https://github.com/pronamic/wp-pronamic-pay-woocommerce/issues/15))
	*	Added MobilePay payment method. ([pronamic/wp-pronamic-pay-adyen#16](https://github.com/pronamic/wp-pronamic-pay-adyen/issues/16))
*	Updated WordPress pay Event Espresso library to version `4.1.2`.
	*	Fixed "Expected type 'null|array'. Found 'string'.".
*	Updated WordPress pay Gravity Forms library to version `4.3.0`.
	*	No support for manual renewals with Gravity Forms.
*	Updated WordPress pay MemberPress library to version `4.6.0`.
	*	Prevent recurring payment at gateways without recurring support. [#7](https://github.com/pronamic/wp-pronamic-pay-memberpress/pull/7)
*	Updated WordPress pay WooCommerce library to version `4.3.0`.
	*	Fixed subscription status not updated if admin reactivates a WooCommerce subscription. [#25](https://github.com/pronamic/wp-pronamic-pay-woocommerce/issues/25)
	*	Fixed fatal error while cancelling subscription. Props @knit-pay. [#14](https://github.com/pronamic/wp-pronamic-pay-woocommerce/issues/14)
	*	Fixed payment method field errors not displayed in WooCommerce checkout block. [#22](https://github.com/pronamic/wp-pronamic-pay-woocommerce/issues/22)
	*	Added MobilePay payment method. [pronamic/wp-pronamic-pay-adyen#16](https://github.com/pronamic/wp-pronamic-pay-adyen/issues/16)
*	Updated WordPress pay Adyen library to version `4.3.0`.
	*	Added MobilePay payment method. [#16](https://github.com/pronamic/wp-pronamic-pay-adyen/issues/16)
*	Updated WordPress pay Mollie library to version `4.5.0`.
	*	Added user agent to HTTP requests to Mollie. [#13](https://github.com/pronamic/wp-pronamic-pay-mollie/issues/13)
*	Updated WordPress pay Rabo Smart Pay (formerly OmniKassa) library to version `4.3.0`.
	*	Changed name from "OmniKassa" to "Rabo Smart Pay". [#13](https://github.com/pronamic/wp-pronamic-pay-omnikassa-2/issues/13)
	*	Enrich payments methods from new `order/server/api/payment-brands` endpoint. [#15](https://github.com/pronamic/wp-pronamic-pay-omnikassa-2/issues/15)
	*	Added support for SOFORT payment method. [#16](https://github.com/pronamic/wp-pronamic-pay-omnikassa-2/issues/16)
*	Updated WordPress pay Pay. library to version `4.3.0`.
	*	Updated dashboard URL to https://my.pay.nl/. [#3](https://github.com/pronamic/wp-pronamic-pay-pay-nl/pull/3)
	*	Added payment provider URL filter. [#3](https://github.com/pronamic/wp-pronamic-pay-pay-nl/pull/3)
	*	Update integration name from "Pay.nl" to "Pay.". [#2](https://github.com/pronamic/wp-pronamic-pay-pay-nl/issues/2)

= 9.0.1 - 2022-10-11 =
*	Updated WordPress pay core library to version 4.4.1.
	*	Added support for multi-dimensional array in `Util::html_hidden_fields()` method ([#73](https://github.com/pronamic/wp-pay-core/issues/73)).
	*	Fixed setting empty consumer bank details object ([pronamic/wp-pronamic-pay-mollie#11](https://github.com/pronamic/wp-pronamic-pay-mollie/issues/11)).
	*	Removed unused gateway subscription methods.
*	Updated WordPress pay Adyen library to version 4.2.3.
	*	Updated Adyen Drop-in to version `5.27.0` ([#14](https://github.com/pronamic/wp-pronamic-pay-adyen/issues/14)).
	*	Fixed error triggered by Adyen drop-in with Swish payment method on mobile.
*	Updated WordPress pay Buckaroo library to version 4.2.2.
	*	Fixed possible "Warning: Invalid argument supplied for foreach()" when enriching payment methods ([#7](https://github.com/pronamic/wp-pronamic-pay-buckaroo/issues/7)).
*	Updated WordPress pay Mollie library to version 4.4.1.
	*	Fixed recurring payments using latest mandate of Mollie customer instead of subscription mandate ([#11](https://github.com/pronamic/wp-pronamic-pay-mollie/issues/11)).
*	Updated WordPress pay Gravity Forms library to version 4.2.2.
	*	Fixed catching exceptions in issuer field ([#10](https://github.com/pronamic/wp-pronamic-pay-gravityforms/issues/10)).

= 9.0.0 - 2022-09-27 =
*	Updated WordPress pay core library to version 4.4.0.
	*	Fixed list table styling on mobile ([#72](https://github.com/pronamic/wp-pay-core/issues/72)).
	*	Refactored payments methods and fields support.
	*	Removed phone number field from test meta box.
	*	Removed Sisow reservation payments support.
*	Updated WordPress pay Buckaroo library to version 4.2.1.
	*	Updated payment methods registration.
	*	Updated for Sisow via Buckaroo integration ([pronamic/wp-pronamic-pay-sisow#3](https://github.com/pronamic/wp-pronamic-pay-sisow/issues/3)).
*	Updated WordPress pay Mollie library to version 4.4.0.
	*	Fixed empty billing email address causing `Unprocessable Entity - The email address '' is invalid` error.
	*	Updated payment methods registration.
*	Updated WordPress pay Easy Digital Downloads library to version 4.2.1.
	*	Fixed Easy Digital Downloads 3 compatibility.
	*	Updated for new payment methods and fields registration.
*	Updated WordPress pay Gravity Forms library to version 4.2.1.
	*	Fixed conditional logic object without any logic.
	*	Updated for new payment methods and fields registration.
*	Updated WordPress pay WooCommerce library to version 4.2.0.
	*	Added upgrade script to add missing Pronamic subscription ID to WooCommerce subscription meta ([#11](https://github.com/pronamic/wp-pronamic-pay-woocommerce/issues/11)).
	*	Updated for new payment methods and fields registration.
	*	Improved WooCommerce Blocks support.
*	Updated WordPress DateTime library to version 2.0.3.
*	Updated WordPress HTML library to version 2.0.2.
*	Updated WordPress HTTP library to version 1.1.3.
*	Updated WordPress Money library to version 2.0.3.
*	Updated WordPress Number library to version 1.1.1.
*	Updated WordPress pay Fundraising library to version 3.0.2.
*	Updated WordPress pay Adyen library to version 4.2.1.
*	Updated WordPress pay DigiWallet library to version 3.2.1.
*	Updated WordPress pay EMS e-Commerce; library to version 4.2.0.
*	Updated WordPress pay ICEPAY library to version 4.2.0.
*	Updated WordPress pay iDEAL library to version 4.0.1.
*	Updated WordPress pay iDEAL Advanced v3 library to version 4.2.0.
*	Updated WordPress pay iDEAL Basic library to version 4.2.0.
*	Updated WordPress pay MultiSafepay library to version 4.2.0.
*	Updated WordPress pay Ingenico library to version 4.2.0.
*	Updated WordPress pay OmniKassa 2.0 library to version 4.2.0.
*	Updated WordPress pay Pay.nl library to version 4.2.0.
*	Updated WordPress pay PayPal library to version 2.2.1.
*	Updated WordPress pay Charitable library to version 4.2.1.
*	Updated WordPress pay Contact Form 7 library to version 3.1.1.
*	Updated WordPress pay Event Espresso library to version 4.1.1.
*	Updated WordPress pay Formidable Forms library to version 4.2.1.
*	Updated WordPress pay Give library to version 4.1.1.
*	Updated WordPress pay MemberPress library to version 4.5.1.
*	Updated WordPress pay Ninja Forms library to version 3.1.1.
*	Updated WordPress pay Restrict Content Pro library to version 4.2.1.

[See changelog for all versions.](https://www.pronamic.eu/plugins/pronamic-pay/changelog/)

== Links ==

*	[Pronamic](https://www.pronamic.eu/)
*	[Remco Tolsma](https://www.remcotolsma.nl/)
