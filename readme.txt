=== Pronamic Pay ===
Contributors: pronamic, remcotolsma 
Tags: ideal, bank, payment, gravity forms, forms, payment, woocommerce, recurring-payments, shopp, rabobank, friesland bank, ing, mollie, omnikassa, wpsc, wpecommerce, commerce, e-commerce, cart
Donate link: https://www.pronamic.eu/donate/?for=wp-plugin-pronamic-pay&source=wp-plugin-readme-txt
Requires at least: 4.7
Tested up to: 5.8
Requires PHP: 5.6
Stable tag: 8.0.0-alpha.1

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

= 7.0.2 - 2021-09-30 =
*	Updated WordPress core library to version `3.2.0`.
	*	Start using `<input type="number">` in payment forms en test meta box.
	*	Removed deprecated `Util::string_to_amount( $value )` function.
	*	Updated logo library to version `1.6.8` for new Bancontact logo.
	*	Improved security by using correct escaping functions.
*	Updated WordPress pay Fundraising library to version `2.0.3`.
	*	Load assets from relative links.
	*	Added Update URI in plugin file.
	*	Included and use `block.json`.
	*	Improved security by using correct escaping functions.

= 7.0.1 - 2021-09-16 =
*	Updated WordPress pay Adyen library to version 2.0.4.
	*	Added support for the PayPal payment method (pronamic/wp-pronamic-pay#180).
	*	Added country code to Apple Pay payment method configuration.
*	Updated WordPress pay Fundraising library to version 2.0.2.
	*	Fixed blocks not loading in editor (pronamic/wp-pronamic-pay#204).
*	Updated WordPress pay Gravity Forms library to version 3.0.2.
	*	Updated issuers field to only use active payment feeds.
	*	Fixed duplicate `pronamic_payment_id` entry meta (pronamic/wp-pronamic-pay#208).
	*	Fixed empty merge tags in 'Form is submitted' notification event.
*	Updated WordPress core library to version 3.1.1.
*	Updated WordPress pay Charitable library to version 3.0.1.

= 7.0.0 - 2021-09-03 =
*	Updated WordPress core library to version 3.1.0.
	*	No longer create recurring payments for subscriptions with the status `Failed` (see https://github.com/pronamic/wp-pronamic-pay/issues/188#issuecomment-907155800).
	*	No longer set payments with an empty amount to success (gateways and extensions should handle this).
	*	Subscription renewal page uses last failed period for manual renewal, if failed period has not yet passed.
	*	Fixed block titles (pronamic/wp-pronamic-pay#185).
	*	Fixed layout issue with input HTML on subscription renewal page.
	*	Fixed script error in payment form block.
*	Updated WordPress pay Mollie library to version 3.1.0.
	*	Added `pronamic_pay_mollie_payment_description` filter (with example).
	*	Removed check for empty amount, `0` amount is allowed for credit card authorizations.
*	Updated WordPress pay Gravity Forms library to version 3.0.1.
	*	Updated processing of free payments (allows credit card authorizations for subscriptions).
*	Updated WordPress pay MemberPress library to version 3.1.0.
	*	Completely revised integration.
	*	Improved support for free (amount = 0) transactions.
	*	Improved support for subscription upgrades and downgrades.
	*	Account page 'Update' allows users to manually pay for last period if payment failed.
	*	Added Pronamic payment column to the MemberPress transactions table in WordPress admin dashboard.
	*	Temporarily removed support for suspend and resume subscriptions due to unintended behavior.
*	Updated WordPress pay WooCommerce library to version 3.0.2.
	*	Set pending order status when awaiting payment.
	*	Fixed using non-existing `shipping_phone` order property.
*	Updated Pronamic WordPress DateTime library to version 1.2.2.
*	Updated WordPress pay Adyen library to version 2.0.3.
*	Updated WordPress pay Fundraising library to version 2.0.1.
*	Updated WordPress pay PayPal library to version 1.0.2.

= 6.9.6 - 2021-08-24 =
*	Updated WordPress pay Pay.nl library to version `3.0.1`
	*	Fixed "Fatal error: Uncaught Error: Call to undefined method Pronamic\WordPress\Money\Money::get_including_tax()".

= 6.9.5 - 2021-08-19 =
*	Updated WordPress pay Adyen library to version `2.0.2`: https://github.com/wp-pay-gateways/adyen/releases/tag/2.0.2.
	*	Adyen drop-in gateway supports Klarna Pay Later payment method.
*	Updated WordPress pay MemberPress library to version `3.0.3`: https://github.com/wp-pay-extensions/memberpress/releases/tag/3.0.3.
	*	Added Giropay gateway.

[See changelog for all versions.](https://www.pronamic.eu/plugins/pronamic-pay/changelog/)

== Links ==

*	[Pronamic](https://www.pronamic.eu/)
*	[Remco Tolsma](https://www.remcotolsma.nl/)
