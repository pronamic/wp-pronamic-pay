=== Pronamic Pay ===
Contributors: pronamic, remcotolsma 
Tags: ideal, bank, payment, gravity forms, forms, payment, woocommerce, recurring-payments, shopp, rabobank, friesland bank, ing, mollie, omnikassa, wpsc, wpecommerce, commerce, e-commerce, cart
Donate link: https://www.pronamic.eu/donate/?for=wp-plugin-pronamic-pay&source=wp-plugin-readme-txt
Requires at least: 5.9
Tested up to: 6.1
Requires PHP: 7.4
Stable tag: 9.3.6

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

= 9.3.6 =
### Added

- Added payment method selection on manual subscription renewal. ([af9c0c9](https://github.com/pronamic/wp-pay-core/commit/af9c0c922b6abcbf5ff1c5bc9417a2ad9568db21))
- Added support for border control in fundraising blocks. ([950af6b](https://github.com/wp-pay/fundraising/commit/950af6b120ea111c7ef9c5f493cfec81e96dbf5b))
- Added support for multiple free text value options in Contact Form 7. ([84d9856](https://github.com/pronamic/wp-pronamic-pay-contact-form-7/commit/84d9856461da4f915fed5485bf60818162c120cf))

### Fixed

- Fixed duplicate execution of `$gateway->start( $payment )` in redirect routine of HTML form gateways. ([467aeb5](https://github.com/pronamic/wp-pay-core/commit/467aeb59e24846c0bbd01e88ff5e1191bcfde6b5))
- Changed payment amount to `0.00` for credit card and PayPal authorizations when updating mandate. ([3132ff6](https://github.com/pronamic/wp-pay-core/commit/3132ff61a0a8f78f98c8f499e584364d7bfc869a))

### Removed

- Removed default border from fundraising blocks. ([a6b7bdf](https://github.com/wp-pay/fundraising/commit/a6b7bdfd831dff4c771159eee071b131c3f6a9b1))

### Composer

- Changed `wp-pay-extensions/contact-form-7` from `v3.2.1` to `v3.2.2`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-contact-form-7/releases/tag/v3.2.2
- Changed `wp-pay/core` from `v4.7.2` to `v4.7.3`.
	Release notes: https://github.com/pronamic/wp-pay-core/releases/tag/v4.7.3
- Changed `wp-pay/fundraising` from `v3.1.1` to `v3.2.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-fundraising/releases/tag/v3.2.0
Full set of changes: [`9.3.5...9.3.6`][9.3.6]

[9.3.6]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.3.5...v9.3.6

= 9.3.5 =
### Fixed

- Fixed field inputs options in recurring amount settings field of Gravity Forms.
- Fixed expiry date of MemberPress transaction with trial period.
- Fixed running Mollie integration installation.

### Composer

- Changed `wp-pay-extensions/gravityforms` from `v4.5.1` to `v4.5.2`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-gravityforms/releases/tag/v4.5.2
- Changed `wp-pay-extensions/memberpress` from `v4.7.4` to `v4.7.5`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-memberpress/releases/tag/v4.7.5
- Changed `wp-pay-gateways/mollie` from `v4.7.3` to `v4.7.4`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-mollie/releases/tag/v4.7.4
Full set of changes: [`9.3.4...9.3.5`][9.3.5]

[9.3.5]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.3.4...v9.3.5

= 9.3.4 =
### Composer

- Changed `wp-pay-extensions/memberpress` from `v4.7.3` to `v4.7.4`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-memberpress/releases/tag/v4.7.4
- Changed `wp-pay-gateways/adyen` from `v4.4.1` to `v4.4.3`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-adyen/releases/tag/v4.4.3
Full set of changes: [`9.3.3...9.3.4`][9.3.4]

[9.3.4]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.3.3...v9.3.4

= 9.3.3 =
### Composer

- Changed `wp-pay-extensions/memberpress` from `v4.7.2` to `v4.7.3`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-memberpress/releases/tag/v4.7.3
- Changed `wp-pay-extensions/restrict-content-pro` from `v4.3.1` to `v4.3.2`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-restrict-content-pro/releases/tag/v4.3.2
- Changed `wp-pay-extensions/woocommerce` from `v4.5.1` to `v4.5.2`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-woocommerce/releases/tag/v4.5.2
- Changed `wp-pay-gateways/icepay` from `v4.3.1` to `v4.3.2`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-icepay/releases/tag/v4.3.2
- Changed `wp-pay-gateways/ideal-advanced-v3` from `v4.3.2` to `v4.3.3`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-ideal-advanced-v3/releases/tag/v4.3.3
- Changed `wp-pay-gateways/mollie` from `v4.7.2` to `v4.7.3`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-mollie/releases/tag/v4.7.3
- Changed `wp-pay/core` from `v4.7.1` to `v4.7.2`.
	Release notes: https://github.com/pronamic/wp-pay-core/releases/tag/v4.7.2
Full set of changes: [`9.3.2...9.3.3`][9.3.3]

[9.3.3]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.3.2...v9.3.3

= 9.3.2 =
### Commits

- Lower PHP requirement to PHP 7.4. ([785a9c3](https://github.com/pronamic/wp-pronamic-pay/commit/785a9c385f843e218128c4f924fa0cac1c5d25d6))

### Composer

- Changed `php` from `>=8.0` to `>=7.4`.
- Changed `wp-pay-extensions/charitable` from `v4.3.0` to `v4.3.1`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-charitable/releases/tag/v4.3.1
- Changed `wp-pay-gateways/adyen` from `v4.4.0` to `v4.4.1`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-adyen/releases/tag/v4.4.1

Full set of changes: [`9.3.1...9.3.2`][9.3.2]

[9.3.2]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.3.1...v9.3.2

[See changelog for all versions.](https://www.pronamic.eu/plugins/pronamic-pay/changelog/)

== Links ==

*	[Pronamic](https://www.pronamic.eu/)
*	[Remco Tolsma](https://www.remcotolsma.nl/)
