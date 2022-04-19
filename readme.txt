=== Pronamic Pay ===
Contributors: pronamic, remcotolsma 
Tags: ideal, bank, payment, gravity forms, forms, payment, woocommerce, recurring-payments, shopp, rabobank, friesland bank, ing, mollie, omnikassa, wpsc, wpecommerce, commerce, e-commerce, cart
Donate link: https://www.pronamic.eu/donate/?for=wp-plugin-pronamic-pay&source=wp-plugin-readme-txt
Requires at least: 5.2
Tested up to: 5.9
Requires PHP: 7.4
Stable tag: 8.2.2

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
*	Payvision (requires [Basic license](https://www.pronamic.eu/plugins/pronamic-pay/))
*	Rabobank - OmniKassa 2.0
*	Rabobank - iDEAL Professional
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

= 8.2.2 - 2022-04-19 =
*	Updated WordPress core library to version 4.1.2.
	*	Fixed plugin updater.
*	Updated WordPress WooCommerce library to version 4.1.1.

= 8.2.1 - 2022-04-13 =
*	Updated WordPress core library to version 4.1.1.
*	Updated WordPress Charitable library to version 4.1.0.
*	Updated WordPress Adyen library to version 3.1.1.
*	Updated WordPress OmniKassa 2.0 library to version 4.1.1.
*	Updated WordPress Sisow library to version 4.1.1.

= 8.2.0 - 2022-04-12 =
*	Updated WordPress core library to version 4.1.0.
	*	Added a user interface to change a subscription's next payment date.
	*	Added a count badge in the WordPress admin menu for the number of subscriptions on hold.
	*	The next payment date is now stored in the subscription and no longer in the subscription phases.
	*	The general / global gateway integration mode setting for test or live mode is removed.
	*	Sorting payments by customer or transaction number in the WordPress admin dashboard has been removed.
*	Updated WordPress WooCommerce library to version 4.1.0.
	*	Transform expired WooCommerce subscription status to Pronamic status Completed.
	*	Add failure reason notice on 'Pay for order' page (pronamic/wp-pronamic-pay-adyen#2).
	*	Added support for WooCommerce Blocks.
	*	Fix resetting trial phase next payment date on payment status update.
	*	Ignore seconds in calculation of subscription trial phase interval.
*	Updated WordPress Restrict Content Pro library to version 4.1.0.
	*	Transform expired Restrict Content Pro membership to Pronamic status Completed.
	*	Fix missing gateway registration key.
	*	Simplify gateway registration and supported features.
*	Updated WordPress MemberPress library to version 4.1.0.
	*	Call limit reached actions on subscription completion.
*	Updated WordPress Gravity Forms library to version 4.1.0.
	*	Improve payment and subscription source text when Gravity Forms plugin is not active.
	*	Fix possible invalid empty conditional logic object.
	*	Add support for gf_list_* CSS classes in payment methods field.
*	Updated WordPress Formidable Forms library to version 4.1.0.
	*	Add payment action setting for gateway configuration.
*	Updated WordPress Easy Digital Downloads library to version 4.1.0.
	*	Add company name controller.
*	Updated WordPress Charitable library to version 4.1.0.
*	Updated WordPress Sisow library to version 4.1.0.
*	Updated WordPress Payvision library to version 3.1.0.
*	Updated WordPress PayPal library to version 2.1.0.
*	Updated WordPress Pay.nl library to version 4.1.0.
*	Updated WordPress OmniKassa 2.0 library to version 4.1.0.
	*	Added support for iDEAL issuers.
*	Updated WordPress Ingenico library to version 4.1.0.
*	Updated WordPress MultiSafepay library to version 4.1.0.
*	Updated WordPress Mollie library to version 4.1.0.
*	Updated WordPress iDEAL Basic library to version 4.1.0.
*	Updated WordPress iDEAL Advanced v3 library to version 4.1.0.
*	Updated WordPress ICEPAY library to version 4.1.0.
*	Updated WordPress EMS e-Commerce library to version 4.1.0.
*	Updated WordPress DigiWallet library to version 3.1.0.
*	Updated WordPress Buckaroo library to version 4.1.0.
*	Updated WordPress Adyen library to version 3.1.0.

= 8.1.0 - 2022-02-16 =
*	Added support for new ING iDEAL Advanced platform.
*	Updated WordPress core library to version 4.0.2.
	*	Changed minimum PHP version requirement to `7.4` (https://github.com/pronamic/wp-pronamic-pay/issues/274).
	*	Changed follow-up payments query to subscriptions which needed renewal in past 24 hours only.
	*	Added next payment date column in subscriptions admin (https://github.com/pronamic/wp-pronamic-pay/issues/288).
	*	Fixed empty payment description admin column.
	*	Fixed error on subscription mandate selection page with invalid Mollie customer.
	*	Fixed possible infinite loop on updating active payment methods (https://github.com/pronamic/wp-pay-core/issues/54).
	*	Fixed setting Mollie sequence type when manually re-trying payment for a period.
	*	Updated scheduling follow-up payments pages.
	*	Updated site health tests and debug information.
	*	Updated pronamic/wp-pay-logos library to version `1.7.1`.
	*	Removed time from next payment dates in admin.
*	Updated WordPress Adyen library to version 3.0.1.
	*	Added support for Klarna Pay Now and Klarna Pay Over Time.
	*	Added support for Afterpay and the Adyen `afterpaytouch` payment method indicator.
	*	Updated drop-in error handling (https://github.com/pronamic/wp-pronamic-pay-adyen/issues/2).
*	Updated WordPress MultiSafepay library to version 4.0.1.
	*	Fixed possible error "Call to a member function get_total() on null".
*	Updated WordPress Gravity Forms library to version 4.0.1.
	*	Fixed processing delayed feeds during fulfilment of free payments (e.g. user registration for entry with discount; https://github.com/pronamic/wp-pronamic-pay/issues/279).
*	Updated WordPress MemberPress library to version 4.0.1.
	*	Fixed MemberPress gateway capabilities based on gateway support.
	*	Fixed confirming subscription confirmation transaction only for recurring payments.
*	Updated WordPress Ninja Forms library to version 3.0.1.
	*	Fixed delaying all actions (https://github.com/pronamic/wp-pronamic-pay-ninjaforms/issues/4).
*	Updated WordPress WooCommerce library to version 4.0.1.
	*	Added Klarna Pay Now and Klarna Pay Over Time gateways.
	*	Added support for multiple subscriptions.
	*	Fixed adding periods to payments.
	*	Fixed handling subscription payment method changes.
	*	Fixed setting input fields only if gateway is enabled.
	*	Updated AfterPay.nl and Afterpay.com method descriptions to clarify differences in target countries.
	*	Updated subscription source texts.
*	Updated WordPress Mollie library to version 4.0.1.

= 8.0.0 - 2022-01-13 =
*	Removed support for Event Espresso 3 → https://www.pronamic.eu/pronamic-pay-support-for-event-espresso-3-removed/.
*	Removed support for s2Member → https://www.pronamic.eu/pronamic-pay-support-for-s2member-removed/.
*	Removed support for WP eCommerce → https://www.pronamic.eu/pronamic-pay-support-for-wp-ecommerce-removed/.
*	Updated WordPress core library to version `4.0.0`.
*	Refactored subscription follow-up payments processes.
*	Increased WordPress requirement to version `5.2` or higher.
*	Updated all extension libraries → https://github.com/wp-pay-extensions.
*	Updated all gateway libraries → https://github.com/wp-pay-gateways.
*	Added https://actionscheduler.org/ library for subscription processes.
*	Added payment method icon to amount column and info meta boxes.
*	Added BLIK payment method.
*	Added MB WAY payment method.
*	Added TWINT payment method.
*	Added new ABN ARMO signing certificate for iDEAL (valid until 29-09-2026).
*	Added new ING signing certificate for iDEAL (valid until 29-09-2026).
*	Added new Rabobank signing certificate for iDEAL (valid until 29-09-2026).

[See changelog for all versions.](https://www.pronamic.eu/plugins/pronamic-pay/changelog/)

== Links ==

*	[Pronamic](https://www.pronamic.eu/)
*	[Remco Tolsma](https://www.remcotolsma.nl/)
