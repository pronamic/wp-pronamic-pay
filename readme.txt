=== Pronamic Pay ===
Contributors: pronamic, remcotolsma 
Tags: ideal, bank, payment, gravity forms, forms, payment, woocommerce, recurring-payments, shopp, rabobank, friesland bank, ing, mollie, omnikassa, wpsc, wpecommerce, commerce, e-commerce, cart
Donate link: https://www.pronamic.eu/donate/?for=wp-plugin-pronamic-pay&source=wp-plugin-readme-txt
Requires at least: 5.9
Tested up to: 6.1
Requires PHP: 8.0
Stable tag: 9.2.1

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
*	[Contact Form 7](https://contactform7.com/) (requires [Basic license](https://www.pronamicpay.com/))
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
*	Adyen (requires [Pro license](https://www.pronamicpay.com/))
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

= 9.2.1 =
### Fixed

- Gravity Forms - Fixed problem with saving status page settings in payment feed. ([#14](https://github.com/pronamic/wp-pronamic-pay-gravityforms/issues/14))

### Composer

- Changed `composer/installers` from `v1.12.0` to `v2.2.0`.
	Release notes: https://github.com/composer/installers/releases/tag/v2.2.0
- Changed `wp-pay-extensions/gravityforms` from `v4.4.1` to `v4.4.2`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-gravityforms/releases/tag/v4.4.2

Full set of changes: [`9.2.0...9.2.1`][9.2.1]

[9.2.1]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.2.0...v9.2.1

= 9.2.0 =
### Changed

- Requires PHP 8.0. ([b511154](https://github.com/pronamic/wp-pronamic-pay/commit/b511154f25386a11c82f254d4d62b31ee1b26dc0))
- Removed usage of deprecated constant `FILTER_SANITIZE_STRING`. ([883175b](https://github.com/pronamic/wp-pronamic-pay/commit/883175b99f0ffa1418ec192994e44a0ef094d92b))

### Composer

- Changed `php` from `>=7.4` to `>=8.0`.
- Changed `pronamic/wp-datetime` from `2.0.3` to `v2.1.1`.
	Release notes: https://github.com/pronamic/wp-datetime/releases/tag/v2.1.1
- Changed `pronamic/wp-html` from `2.0.2` to `v2.1.0`.
	Release notes: https://github.com/pronamic/wp-html/releases/tag/v2.1.0
- Changed `pronamic/wp-money` from `2.0.3` to `v2.2.0`.
	Release notes: https://github.com/pronamic/wp-money/releases/tag/v2.2.0
- Changed `pronamic/wp-number` from `1.1.1` to `v1.2.0`.
	Release notes: https://github.com/pronamic/wp-number/releases/tag/v1.2.0
- Changed `wp-pay-extensions/charitable` from `4.2.1` to `v4.3.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-charitable/releases/tag/v4.3.0
- Changed `wp-pay-extensions/contact-form-7` from `3.1.2` to `v3.2.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-contact-form-7/releases/tag/v3.2.0
- Changed `wp-pay-extensions/easy-digital-downloads` from `4.2.2` to `v4.3.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-easy-digital-downloads/releases/tag/v4.3.0
- Changed `wp-pay-extensions/event-espresso` from `4.1.2` to `v4.2.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-event-espresso/releases/tag/v4.2.0
- Changed `wp-pay-extensions/formidable-forms` from `4.2.1` to `v4.3.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-formidable-forms/releases/tag/v4.3.0
- Changed `wp-pay-extensions/give` from `4.1.1` to `v4.2.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-give/releases/tag/v4.2.0
- Changed `wp-pay-extensions/gravityforms` from `4.3.0` to `v4.4.1`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-gravityforms/releases/tag/v4.4.1
- Changed `wp-pay-extensions/memberpress` from `4.6.0` to `v4.7.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-memberpress/releases/tag/v4.7.0
- Changed `wp-pay-extensions/ninjaforms` from `3.1.1` to `v3.2.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-ninjaforms/releases/tag/v3.2.0
- Changed `wp-pay-extensions/restrict-content-pro` from `4.2.1` to `v4.3.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-restrict-content-pro/releases/tag/v4.3.0
- Changed `wp-pay-extensions/woocommerce` from `4.3.3` to `v4.4.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-woocommerce/releases/tag/v4.4.0
- Changed `wp-pay-gateways/adyen` from `4.3.1` to `v4.4.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-adyen/releases/tag/v4.4.0
- Changed `wp-pay-gateways/buckaroo` from `4.2.2` to `v4.3.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-buckaroo/releases/tag/v4.3.0
- Changed `wp-pay-gateways/digiwallet` from `3.2.1` to `v3.3.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-digiwallet/releases/tag/v3.3.0
- Changed `wp-pay-gateways/ems-e-commerce` from `4.2.0` to `v4.3.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-ems-e-commerce/releases/tag/v4.3.0
- Changed `wp-pay-gateways/icepay` from `4.2.0` to `v4.3.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-icepay/releases/tag/v4.3.0
- Changed `wp-pay-gateways/ideal` from `4.0.1` to `v4.1.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-ideal/releases/tag/v4.1.0
- Changed `wp-pay-gateways/ideal-advanced-v3` from `4.2.0` to `v4.3.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-ideal-advanced-v3/releases/tag/v4.3.0
- Changed `wp-pay-gateways/ideal-basic` from `4.2.0` to `v4.3.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-ideal-basic/releases/tag/v4.3.0
- Changed `wp-pay-gateways/mollie` from `4.6.0` to `v4.7.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-mollie/releases/tag/v4.7.0
- Changed `wp-pay-gateways/multisafepay` from `4.2.0` to `v4.3.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-multisafepay/releases/tag/v4.3.0
- Changed `wp-pay-gateways/ogone` from `4.2.0` to `v4.3.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-ingenico/releases/tag/v4.3.0
- Changed `wp-pay-gateways/omnikassa-2` from `4.3.0` to `v4.4.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-omnikassa-2/releases/tag/v4.4.0
- Changed `wp-pay-gateways/pay-nl` from `4.4.0` to `v4.5.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-pay-nl/releases/tag/v4.5.0
- Changed `wp-pay-gateways/paypal` from `2.2.2` to `v2.3.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-paypal/releases/tag/v2.3.0
- Changed `wp-pay/core` from `4.5.0` to `v4.6.0`.
	Release notes: https://github.com/pronamic/wp-pay-core/releases/tag/v4.6.0
- Changed `wp-pay/fundraising` from `3.0.3` to `v3.1.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-fundraising/releases/tag/v3.1.0
Full set of changes: [`9.1.3...9.2.0`][9.2.0]

[9.2.0]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.1.3...v9.2.0

= 9.1.3 =
### Fixed
- Updated WordPress pay Easy Digital Downloads library to version 4.2.2.
  - Fix required field indicator HTML escaped. [#5](https://github.com/pronamic/wp-pronamic-pay-easy-digital-downloads/issues/5)
- Updated WordPress pay WooCommerce library to version 4.3.3.
  - Fix creating zero amount refunds. [#31](https://github.com/pronamic/wp-pronamic-pay-woocommerce/issues/31)
- Updated WordPress pay Adyen library to version 4.3.1.
  - Redirect API-only payment methods to payment action URL. [#18](https://github.com/pronamic/wp-pronamic-pay-adyen/issues/18)
  - Make `redirectResult` no longer required in return endpoint. [#19](https://github.com/pronamic/wp-pronamic-pay-adyen/issues/19)

### Changed
- Updated WordPress pay Mollie library to version 4.6.0.
  - Use new `pronamic/wp-mollie` library.
  - Use new `str_*_with` functions, requires WordPress `5.9` or higher.
- Updated WordPress pay Pay. library to version 4.4.0.
  - Updated to REST API version 13: https://rest-api.pay.nl/v13/.
  - Added `statsData` to transaction requests. [#18](https://github.com/pronamic/pronamic-pay/issues/18)

= 9.1.2 =
### Fixed
- Updated WordPress pay WooCommerce library to version 4.3.2.
  - Fixed "Fatal error: Uncaught Error: Call to undefined function wcs_get_subscriptions_for_order()". ([#29](https://github.com/pronamic/wp-pronamic-pay-woocommerce/issues/29))

= 9.1.1 =
### Fixed
- Updated WordPress pay WooCommerce library to version `4.3.1`.
  - Fixed "Fatal error: Uncaught Error: Call to undefined function wcs_get_subscription()". Props @jeffreyvr. [#28](https://github.com/pronamic/wp-pronamic-pay-woocommerce/pull/28)

[See changelog for all versions.](https://www.pronamic.eu/plugins/pronamic-pay/changelog/)

== Links ==

*	[Pronamic](https://www.pronamic.eu/)
*	[Remco Tolsma](https://www.remcotolsma.nl/)
