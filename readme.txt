=== Pronamic Pay ===
Contributors: pronamic, remcotolsma 
Tags: ideal, bank, payment, gravity forms, forms, payment, woocommerce, recurring-payments, shopp, rabobank, friesland bank, ing, mollie, omnikassa, wpsc, wpecommerce, commerce, e-commerce, cart
Donate link: https://www.pronamic.eu/donate/?for=wp-plugin-pronamic-ideal&source=wp-plugin-readme-txt
Requires at least: 4.7
Tested up to: 5.5
Requires PHP: 5.6
Stable tag: 6.3.1

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
*	[Contact Form 7](https://contactform7.com/) (requires [Basic license](https://www.pronamic.eu/plugins/pronamic-ideal/))
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
*	Adyen (requires [Pro license](https://www.pronamic.eu/plugins/pronamic-ideal/))
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

= 6.3.0 - 2020-07-08 =
*	Added support for Contact Form 7 plugin (requires Basic license).
*	Updated WordPress pay core library to version 2.4.0.
	*	Added support for customer company name.
	*	Added support for updating subscription mandate.
	*	Added support for VAT number (validation via VIES).
	*	Added `get_pronamic_subscriptions_by_source()` function.
	*	Fixed possible duplicate payment on upgrade if pending recurring payment exists.
	*	Fixed updating subscription status to 'On Hold' only if subscription is not already active, when processing first payment.
	*	Improved subscription date calculations.
	*	Updated admin tour.
*	Updated WordPress Money library to version 1.2.5.
	*	Added support for parsing negative amounts and `5,-` notation for amounts without minor units.
	*	Updated currency symbols.
*	Updated WordPress pay Adyen library to version 1.1.2.
	*	Fixed possible conflicting payments caused by double clicking submit button.
	*	Removed empty meta data from payment request JSON.
*	Updated WordPress pay Mollie library to version 2.1.4.
	*	Added filter `pronamic_pay_mollie_payment_metadata` for Mollie payment metadata.
	*	Added support for updating subscription mandate.
*	Updated WordPress pay Ingenico library to version 2.1.1.
	*	Added exception for Ingenico error when retrieving order status.
*	Updated WordPress pay OmniKassa 2.0 library to version 2.2.4.
	*	Switched to new endpoint at `/order/server/api/v2/order`.
	*	Removed obsolete update of payment transaction ID.
*	Updated WordPress pay Easy Digital Downloads library to version 2.1.2.
	*	Added support for company name and VAT number from the custom Pronamic EDD plugins.
	*	Fixed registering `cancelled` post status for use in EDD payments table view filters.
*	Updated WordPress pay Gravity Forms library to version 2.4.1.
	*	Added support for company name and VAT number.
	*	Improved Gravity Forms 2.5 beta compatibility.
*	Updated WordPress pay Restrict Content Pro library to version 2.2.2.
	*	Added support for subscription frequency.
	*	Fixed using existing subscription for membership.
	*	Fixed expiring membership if first payment expires but subscription is already active.
*	Updated WordPress pay WooCommerce library to version 2.1.2.

= 6.2.0 - 2020-06-03 =
*	Updated WordPress pay core library to version 2.3.2.
	*	Add support for new fundraising add-on (requires Pro license).
	*	Add payment origin post ID.
	*	Add 'Pronamic Pay' block category.
	*	Fix subscriptions without next payment date.
	*	Fix incorrect formatted amount in payment form block.
*	Updated WordPress pay Mollie library to version 2.1.3.
	*	Add support for Mollie payment billing email and filter `pronamic_pay_mollie_payment_billing_email`.
*	Updated WordPress pay OmniKassa 2.0 library to version 2.2.3.
	*	Fix incorrect payments order when handling order status notifications.
*	Updated WordPress pay Charitable library to version 2.1.2.
	*	Add telephone number to payment data.
	*	Fix error handling.
*	Updated WordPress pay Gravity Forms library to version 2.4.0.
	*	Add filter `pronamic_pay_gravityforms_delay_actions` for delayed actions.
	*	Fix empty formatted amount in entry notes if value is `0`.
*	Updated WordPress pay MultiSafepay library to version 2.1.1.
*	Updated WordPress pay Formidable Forms library to version 2.1.3.
*	Updated WordPress pay s2Member library to version 2.1.2.

= 6.1.2 - 2020-04-20 =
*	Updated WordPress pay Buckaroo library to version 2.1.1.
	*	Fixed HTML entities in payment description resulting in invalid signature error.
*	Updated WordPress pay EMS e-Commerce; library to version 2.1.1.
	*	Fixed incorrect default tag in description of Order ID settings field.
*	Updated WordPress pay Gravity Forms library to version 2.3.1.
	*	Fixed PHP notices and warnings.
	*	Use integration version number for scripts and styles.
*	Updated WordPress pay MemberPress library to version 2.1.2.
	*	Fixed setting `complete` transaction status to `pending` again on free downgrade.
*	Updated WordPress pay Adyen library to version 1.1.1.
	*	Fixed not using billing address country code on drop-in payment redirect page.
	*	Added support for payment metadata via `pronamic_pay_adyen_payment_metadata` filter.
	*	Added advanced gateway configuration setting for `merchantOrderReference` parameter.
	*	Added browser information to payment request.
	*	Removed shopper reference from payment request.
	*	Removed payment status request from drop-in gateway supported features.
*	Updated WordPress pay OmniKassa 2.0 library to version 2.2.2.
	*	Improved webhook handling if multiple gateway configurations exist.
*	Updated WordPress pay Formidable Forms library to version 2.1.2.
	*	Updated settings description for delaying email notifications.

= 6.1.1 - 2020-04-06 =
*	Updated deployment script.

[See changelog for all versions.](https://www.pronamic.eu/plugins/pronamic-ideal/changelog/)

== Links ==

*	[Pronamic](https://www.pronamic.eu/)
*	[Remco Tolsma](https://www.remcotolsma.nl/)
