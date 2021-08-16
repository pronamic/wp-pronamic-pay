=== Pronamic Pay ===
Contributors: pronamic, remcotolsma 
Tags: ideal, bank, payment, gravity forms, forms, payment, woocommerce, recurring-payments, shopp, rabobank, friesland bank, ing, mollie, omnikassa, wpsc, wpecommerce, commerce, e-commerce, cart
Donate link: https://www.pronamic.eu/donate/?for=wp-plugin-pronamic-pay&source=wp-plugin-readme-txt
Requires at least: 4.7
Tested up to: 5.8
Requires PHP: 5.6
Stable tag: 6.9.2

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


== Are there any known plugin conflicts? ==

Unfortunately WordPress is notorious for conflicts between themes and plugins. It is unavoidable as you have no control over what other plugins and themes do. While we do take steps to avoid conflicts as best we can, we have no control over other plugins or themes.

As conflicts are found we will update this list. If you discover a conflict with a another plugin, please notify us.

Here is a list of known plugin conflicts:

=== WPML ===

The [WPML](https://wpml.org/) plugin(s) can conflict with multiple gateways. A lot of the gateways use `home_url( '/' )` to retrieve the WordPress home URL. The WPML plugins hooks in to this function to change the home URL to the correct language URL. This can result in incorrect checksums, signatures and hashes.

=== WordPress HTTPS ===

The [WordPress HTTPS](https://wordpress.org/plugins/wordpress-https/) can conflict with the OmniKassa payment method. It can cause invalid signature errors. The WordPress HTTPS plugin parses the complete output of an WordPress website and changes 'http' URLs to 'https' URLs, this  results in OmniKassa data that no longer matches the signature.


== Changelog ==

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

= 6.8.0 - 2021-06-21 =
*	Updated WordPress pay core library to version 2.7.2.
	*	Added payment method to subscription details when cancelling/renewing a subscription.
	*	Added refunded amount in payments overview amount column.
	*	Fixed using user locale on payment redirect and subscription action pages. #136
	*	Improved changing subscription mandate.
*	Updated WordPress pay Adyen library to version 1.3.2.
	*	Updated to API version 64 and Drop-in SDK version 3.15.0 (adds support for ACH Direct Debit payment method).
	*	Updated documentation of the `pronamic_pay_adyen_checkout_head` action.
*	Updated WordPress pay Buckaroo library to version 2.2.0.
	*	Added initial support for refunds.
	*	Added WP-CLI command to retrieve transaction status and refunds info.
	*	Updated integration to JSON API.
	*	Switched to WordPress REST API for Push URL.
*	Updated WordPress pay iDEAL Basic library to version 2.2.0.
	*	Switched to REST API for notification URL.
*	Updated WordPress pay Ingenico library to version 2.1.3.
	*	Fixed updating payment transaction ID from transaction feedback.
*	Updated WordPress pay Contact Form 7 library to version 1.1.1.
	*	Improved error handling on form submission.
*	Updated WordPress pay Easy Digital Downloads library to version 2.2.0.
	*	Added initial support for refunds. #129
*	Updated WordPress pay Gravity Forms library to version 2.7.0.
	*	Added initial support for refunds. #119
*	Updated WordPress pay MemberPress library to version 2.3.3.
	*	Added subscription mandate selection link to account update page.
	*	Fixed updating gateway in subscription/transaction on payment method update (via mandate selection URL).
*	Updated WordPress pay WooCommerce library to version 2.3.1.
	*	Fixed updating WooCommerce order for refunds on payment update. #130
*	Updated WordPress HTTP library to version 1.1.1.
*	Updated WordPress pay DigiWallet library to version 1.0.1.
*	Updated WordPress pay Mollie library to version 2.2.4.

= 6.7.2 - 2021-05-28 =
*	Added WordPress pay DigiWallet library version 1.0.0.
*	Updated WordPress pay core library to version 2.7.1.
	*	Added transaction description setting to payment forms.
	*	Updated payment methods logos to version 1.6.6.
	*	Fixed missing `On Hold` status in payment status map.
*	Updated WordPress pay OmniKassa 2.0 library to version 2.3.4.
	*	Added support for gateway configuration specific webhook URLs.
	*	Improved webhook error handling.
*	Updated WordPress pay TargetPay library to version 2.2.0.
	*	Deprecated gateway in favor of DigiWallet.
	*	Improved error handling.
	*	Added documentation.
*	Updated WordPress pay Charitable library to version 2.2.3.
	*	Improved using default gateway configuration.
*	Updated WordPress pay Gravity Forms library to version 2.6.1.
	*	Improved Gravity Forms 2.5.3 compatibility.
	*	Fixed payment feed conditional logic setting.
	*	Fixed loading admin script in form editor.
*	Updated WordPress pay MemberPress library to version 2.3.2.
	*	Improved setting tax amount and rate in trial phase.
*	Updated WordPress pay Ninja Forms library to version 1.5.1.
	*	Improved delayed actions.

[See changelog for all versions.](https://www.pronamic.eu/plugins/pronamic-pay/changelog/)

== Links ==

*	[Pronamic](https://www.pronamic.eu/)
*	[Remco Tolsma](https://www.remcotolsma.nl/)
