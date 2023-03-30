=== Pronamic Pay ===
Contributors: pronamic, remcotolsma 
Tags: ideal, bank, payment, gravity forms, forms, payment, woocommerce, recurring-payments, shopp, rabobank, friesland bank, ing, mollie, omnikassa, wpsc, wpecommerce, commerce, e-commerce, cart
Donate link: https://www.pronamic.eu/donate/?for=wp-plugin-pronamic-pay&source=wp-plugin-readme-txt
Requires at least: 5.9
Tested up to: 6.2
Requires PHP: 7.4
Stable tag: 9.4.1

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

= 9.4.1 =
### Fixed

- Fixed refunded amount checks.

### Composer

- Changed `automattic/jetpack-autoloader` from `v2.11.17` to `v2.11.18`.
	Release notes: https://github.com/Automattic/jetpack-autoloader/releases/tag/v2.11.18
- Changed `wp-pay-extensions/easy-digital-downloads` from `v4.3.2` to `v4.3.3`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-easy-digital-downloads/releases/tag/v4.3.3
- Changed `wp-pay-extensions/gravityforms` from `v4.5.3` to `v4.5.4`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-gravityforms/releases/tag/v4.5.4
- Changed `wp-pay-extensions/memberpress` from `v4.7.6` to `v4.7.7`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-memberpress/releases/tag/v4.7.7
- Changed `wp-pay-extensions/woocommerce` from `v4.5.4` to `v4.5.5`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-woocommerce/releases/tag/v4.5.5
- Changed `wp-pay/core` from `v4.9.0` to `v4.9.1`.
	Release notes: https://github.com/pronamic/wp-pay-core/releases/tag/v4.9.1
Full set of changes: [`9.4.0...9.4.1`][9.4.1]

[9.4.1]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.4.0...v9.4.1

= 9.4.0 =
### Changed

- Tested up to WordPress version 6.2. ([c7b8768](https://github.com/pronamic/wp-pronamic-pay/commit/c7b8768b58c05c55ee22edae2f6de4905c6d5f56))
- Use Jetpack autoloader. ([778b554](https://github.com/pronamic/wp-pronamic-pay/commit/778b554b29857b5537b41523dc3916fa2f6101ee))
- Support for refunds further expanded and improved.
- Simplified and improved payment status pages generator.
- Built-in forms no longer create WordPress users for each entry.

### Added

- Added a home URL modification watcher to warn users about consequences related to webhooks and subscriptions.
- Addes support for metadata in payment lines.
- Ingenico: Added Yoursafe iDEAL issuer.
- Mollie: Added support for Mollie orders refunds.
- Mollie: Added support for in3 payment method.
- Rabo Smart Pay: Added support for Rabo Smart Pay refunds.

### Composer

- Added `ext-json` `*`.
- Added `automattic/jetpack-autoloader` `^2.11`.
- Added `pronamic/wp-gravityforms-nl` `^3.0`.
- Added `pronamic/wp-http` `^1.2`.
- Added `pronamic/wp-mollie` `^1.2`.
- Changed `pronamic/wp-datetime` from `v2.1.2` to `v2.1.4`.
	Release notes: https://github.com/pronamic/wp-datetime/releases/tag/v2.1.4
- Changed `pronamic/wp-html` from `v2.1.1` to `v2.1.3`.
	Release notes: https://github.com/pronamic/wp-html/releases/tag/v2.1.3
- Changed `pronamic/wp-money` from `v2.2.1` to `v2.4.0`.
	Release notes: https://github.com/pronamic/wp-money/releases/tag/v2.4.0
- Changed `pronamic/wp-number` from `v1.2.1` to `v1.3.0`.
	Release notes: https://github.com/pronamic/wp-number/releases/tag/v1.3.0
- Changed `wp-pay-extensions/charitable` from `v4.3.1` to `v4.3.1`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-charitable/releases/tag/v4.3.1
- Changed `wp-pay-extensions/contact-form-7` from `v3.2.2` to `v3.2.4`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-contact-form-7/releases/tag/v3.2.4
- Changed `wp-pay-extensions/easy-digital-downloads` from `v4.3.1` to `v4.3.2`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-easy-digital-downloads/releases/tag/v4.3.2
- Changed `wp-pay-extensions/event-espresso` from `v4.2.1` to `v4.2.2`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-event-espresso/releases/tag/v4.2.2
- Changed `wp-pay-extensions/formidable-forms` from `v4.3.2` to `v4.3.3`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-formidable-forms/releases/tag/v4.3.3
- Changed `wp-pay-extensions/give` from `v4.2.1` to `v4.2.2`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-give/releases/tag/v4.2.2
- Changed `wp-pay-extensions/gravityforms` from `v4.5.2` to `v4.5.3`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-gravityforms/releases/tag/v4.5.3
- Changed `wp-pay-extensions/memberpress` from `v4.7.5` to `v4.7.6`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-memberpress/releases/tag/v4.7.6
- Changed `wp-pay-extensions/ninjaforms` from `v3.2.1` to `v3.2.2`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-ninjaforms/releases/tag/v3.2.2
- Changed `wp-pay-extensions/restrict-content-pro` from `v4.3.2` to `v4.3.3`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-restrict-content-pro/releases/tag/v4.3.3
- Changed `wp-pay-extensions/woocommerce` from `v4.5.2` to `v4.5.4`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-woocommerce/releases/tag/v4.5.4
- Changed `wp-pay-gateways/adyen` from `v4.4.3` to `v4.4.3`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-adyen/releases/tag/v4.4.3
- Changed `wp-pay-gateways/buckaroo` from `v4.3.1` to `v4.3.2`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-buckaroo/releases/tag/v4.3.2
- Changed `wp-pay-gateways/digiwallet` from `v3.3.1` to `v3.3.2`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-digiwallet/releases/tag/v3.3.2
- Changed `wp-pay-gateways/ems-e-commerce` from `v4.3.1` to `v4.3.2`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-ems-e-commerce/releases/tag/v4.3.2
- Changed `wp-pay-gateways/icepay` from `v4.3.2` to `v4.3.3`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-icepay/releases/tag/v4.3.3
- Changed `wp-pay-gateways/ideal` from `v4.1.1` to `v4.1.2`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-ideal/releases/tag/v4.1.2
- Changed `wp-pay-gateways/ideal-advanced-v3` from `v4.3.3` to `v4.3.4`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-ideal-advanced-v3/releases/tag/v4.3.4
- Changed `wp-pay-gateways/ideal-basic` from `v4.3.1` to `v4.3.2`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-ideal-basic/releases/tag/v4.3.2
- Changed `wp-pay-gateways/mollie` from `v4.7.4` to `v4.7.6`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-mollie/releases/tag/v4.7.6
- Changed `wp-pay-gateways/multisafepay` from `v4.3.1` to `v4.3.2`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-multisafepay/releases/tag/v4.3.2
- Changed `wp-pay-gateways/ogone` from `v4.4.1` to `v4.5.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-ingenico/releases/tag/v4.5.0
- Changed `wp-pay-gateways/omnikassa-2` from `v4.4.2` to `v4.4.3`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-omnikassa-2/releases/tag/v4.4.3
- Changed `wp-pay-gateways/pay-nl` from `v4.5.1` to `v4.5.2`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-pay-nl/releases/tag/v4.5.2
- Changed `wp-pay-gateways/paypal` from `v2.3.1` to `v2.3.2`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-paypal/releases/tag/v2.3.2
- Changed `wp-pay/core` from `v4.7.3` to `v4.9.0`.
	Release notes: https://github.com/pronamic/wp-pay-core/releases/tag/v4.9.0
- Changed `wp-pay/fundraising` from `v3.2.0` to `v3.2.1`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-fundraising/releases/tag/v3.2.1
Full set of changes: [`9.3.6...9.4.0`][9.4.0]

[9.4.0]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.3.6...v9.4.0

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

[See changelog for all versions.](https://www.pronamic.eu/plugins/pronamic-pay/changelog/)

== Links ==

*	[Pronamic](https://www.pronamic.eu/)
*	[Remco Tolsma](https://www.remcotolsma.nl/)
