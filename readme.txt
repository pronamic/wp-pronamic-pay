=== Pronamic Pay ===
Contributors: pronamic, remcotolsma 
Tags: ideal, bank, payment, gravity forms, forms, payment, woocommerce, recurring-payments, shopp, rabobank, friesland bank, ing, mollie, omnikassa, wpsc, wpecommerce, commerce, e-commerce, cart
Donate link: https://www.pronamic.eu/donate/?for=wp-plugin-pronamic-pay&source=wp-plugin-readme-txt
Requires at least: 5.9
Tested up to: 6.3
Requires PHP: 7.4
Stable tag: 9.4.11

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

= 9.4.11 =
### Fixed

- Fixed setting Billie payment method status.

### Commits

- Updated Mollie library. ([b8e1384](https://github.com/pronamic/wp-pronamic-pay/commit/b8e138490f2cf882336356d30632e0ff989eeabe))

### Composer

- Changed `wp-pay-extensions/contact-form-7` from `v3.3.0` to `v3.3.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-contact-form-7/releases/tag/v3.3.0
- Changed `wp-pay-gateways/mollie` from `v4.7.9` to `v4.7.10`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-mollie/releases/tag/v4.7.10
- Changed `wp-pay/core` from `v4.11.0` to `v4.11.0`.
	Release notes: https://github.com/pronamic/wp-pay-core/releases/tag/v4.11.0

Full set of changes: [`9.4.10...9.4.11`][9.4.11]

[9.4.11]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.4.10...v9.4.11

= 9.4.10 =
### Changed

- Improved processing of Contact Form 7 form submission data.

### Fixed

- Fixed compatibility with plugin "Send PDF for Contact Form 7".

### Commits

- Updated translations. ([f4010df](https://github.com/pronamic/wp-pronamic-pay/commit/f4010dff0272f2324f8cb7d1ca02d49292c75b62))
- Updated libraries. ([b5c46c7](https://github.com/pronamic/wp-pronamic-pay/commit/b5c46c7eb7391c4fbabcd0ed46d17b3b7fc087ef))
- Tested up to 6.3. ([f0f003a](https://github.com/pronamic/wp-pronamic-pay/commit/f0f003af6a95bfd2da5477d37a1732ed96f1e0ba))

### Composer

- Changed `automattic/jetpack-autoloader` from `v2.11.21` to `v2.11.22`.
	Release notes: https://github.com/Automattic/jetpack-autoloader/releases/tag/v2.11.22
- Changed `pronamic/wp-mollie` from `v1.2.2` to `v1.2.3`.
	Release notes: https://github.com/pronamic/wp-mollie/releases/tag/v1.2.3
- Changed `woocommerce/action-scheduler` from `3.6.1` to `3.6.2`.
	Release notes: https://github.com/woocommerce/action-scheduler/releases/tag/3.6.2
- Changed `wp-pay-extensions/contact-form-7` from `v3.2.5` to `v3.3.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-contact-form-7/releases/tag/v3.3.0
- Changed `wp-pay-extensions/woocommerce` from `v4.5.7` to `v4.5.8`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-woocommerce/releases/tag/v4.5.8
- Changed `wp-pay-gateways/adyen` from `v4.4.5` to `v4.4.6`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-adyen/releases/tag/v4.4.6
- Changed `wp-pay-gateways/mollie` from `v4.7.8` to `v4.7.9`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-mollie/releases/tag/v4.7.9
- Changed `wp-pay/core` from `v4.10.1` to `v4.11.0`.
	Release notes: https://github.com/pronamic/wp-pay-core/releases/tag/v4.11.0

Full set of changes: [`9.4.9...9.4.10`][9.4.10]

[9.4.10]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.4.9...v9.4.10

= 9.4.9 =
### Fixed

- Fixed 'Forms' admin menu item not shown with new plugin installations.

### Composer

- Changed `pronamic/wp-pronamic-pay-forms` from `v1.0.1` to `v1.0.3`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-forms/releases/tag/v1.0.3

Full set of changes: [`9.4.8...9.4.9`][9.4.9]

[9.4.9]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.4.8...v9.4.9

= 9.4.8 =
### Fixed

- Fixed untranslated plugin.

### Commits

- Use relative `^wp-content/` path in PHPCS config. ([1950c8f](https://github.com/pronamic/wp-pronamic-pay/commit/1950c8f0eebaca92602274fab22b20e928bdcd07))

Full set of changes: [`9.4.7...9.4.8`][9.4.8]

[9.4.8]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.4.7...v9.4.8

= 9.4.7 =
### Commits

- Added forms plugin integration. ([6174b96](https://github.com/pronamic/wp-pronamic-pay/commit/6174b96f3924828f3a8429f92e128a5413769bcd))

### Composer

- Changed `pronamic/wp-pronamic-pay-forms` from `v1.0.0` to `v1.0.1`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-forms/releases/tag/v1.0.1
- Changed `wp-pay/core` from `v4.10.0` to `v4.10.1`.
	Release notes: https://github.com/pronamic/wp-pay-core/releases/tag/v4.10.1

Full set of changes: [`9.4.6...9.4.7`][9.4.7]

[9.4.7]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.4.6...v9.4.7

[See changelog for all versions.](https://github.com/pronamic/wp-pronamic-pay/blob/main/CHANGELOG.md)

== Links ==

*	[Pronamic](https://www.pronamic.eu/)
*	[Remco Tolsma](https://www.remcotolsma.nl/)
