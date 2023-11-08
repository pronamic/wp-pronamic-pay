=== Pronamic Pay ===
Contributors: pronamic, remcotolsma
Tags: ideal, bank, payment, gravity forms, forms, payment, woocommerce, recurring-payments, shopp, rabobank, friesland bank, ing, mollie, omnikassa, wpsc, wpecommerce, commerce, e-commerce, cart
Donate link: https://www.pronamic.eu/donate/?for=wp-plugin-pronamic-pay&source=wp-plugin-readme-txt
Requires at least: 5.9
Tested up to: 6.3
Requires PHP: 8.0
Stable tag: 9.6.1

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

= 9.6.1 =
### Composer

- Changed `wp-pay-gateways/omnikassa-2` from `v4.5.1` to `v4.5.2`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-omnikassa-2/releases/tag/v4.5.2

Full set of changes: [`9.6.0...9.6.1`][9.6.1]

[9.6.1]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.6.0...v9.6.1

= 9.6.0 =
### Changed

- Use https://github.com/pronamic/pronamic-wp-updater. ([c21646b](https://github.com/pronamic/wp-pronamic-pay/commit/c21646b187681672963a55ff2767cea4bda7bd4d))
- Use WordPress file data header notation for version, core now uses `get_file_data` to retrieve version. ([1982c9e](https://github.com/pronamic/wp-pronamic-pay/commit/1982c9e1c3908336c5a0a20b205201c1acdaa859))
- Bump to PHP 8. ([1384639](https://github.com/pronamic/wp-pronamic-pay/commit/1384639ef4514672574fc83051924a90476ae841))

### Removed

- Removed Payvision, deprecated. ([979deeb](https://github.com/pronamic/wp-pronamic-pay/commit/979deebebd7ad1e30490c1eba56762c96a104932))
- Removed `global $pronamic_ideal`, no longer used. ([561edb2](https://github.com/pronamic/wp-pronamic-pay/commit/561edb252335f732790a0caaeb1f256057b3d5af))
- Removed expired certificates. ([03c37db](https://github.com/pronamic/wp-pronamic-pay/commit/03c37db833fc8445a8656d6de0eafc64628b518c))
- Removed redirect message view, is now part of core. ([f57abce](https://github.com/pronamic/wp-pronamic-pay/commit/f57abce7c6a4e77764df5041d2b60127df42207d))
- Removed exception view, is now part of core. ([36b9fc2](https://github.com/pronamic/wp-pronamic-pay/commit/36b9fc2dbbbe9a3f924a1088cae6fe8b288f2aca))
- Removed error view, is now part of core library. ([0c2a471](https://github.com/pronamic/wp-pronamic-pay/commit/0c2a471f1bc837c1780ecad22660b5e7a1b70183))

### Composer

- Added `pronamic/pronamic-wp-updater` `^1.0`.
- Changed `php` from `>=7.4` to `>=8.0`.
- Changed `automattic/jetpack-autoloader` from `v2.11.22` to `v2.12.0`.
	Release notes: https://github.com/Automattic/jetpack-autoloader/releases/tag/v2.12.0
- Changed `pronamic/wp-datetime` from `v2.1.4` to `v2.1.7`.
	Release notes: https://github.com/pronamic/wp-datetime/releases/tag/v2.1.7
- Changed `pronamic/wp-gravityforms-nl` from `v3.0.6` to `v3.0.7`.
	Release notes: https://github.com/pronamic/wp-gravityforms-nl/releases/tag/v3.0.7
- Changed `pronamic/wp-html` from `v2.1.3` to `v2.2.1`.
	Release notes: https://github.com/pronamic/wp-html/releases/tag/v2.2.1
- Changed `pronamic/wp-mollie` from `v1.2.3` to `v1.4.0`.
	Release notes: https://github.com/pronamic/wp-mollie/releases/tag/v1.4.0
- Changed `pronamic/wp-money` from `v2.4.1` to `v2.4.3`.
	Release notes: https://github.com/pronamic/wp-money/releases/tag/v2.4.3
- Changed `pronamic/wp-pronamic-pay-forms` from `v1.1.0` to `v1.1.1`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-forms/releases/tag/v1.1.1
- Changed `woocommerce/action-scheduler` from `3.6.2` to `3.6.4`.
	Release notes: https://github.com/woocommerce/action-scheduler/releases/tag/3.6.4
- Changed `wp-pay-extensions/contact-form-7` from `v3.3.0` to `v3.3.2`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-contact-form-7/releases/tag/v3.3.2
- Changed `wp-pay-extensions/formidable-forms` from `v4.4.0` to `v4.4.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-formidable-forms/releases/tag/v4.4.0
- Changed `wp-pay-extensions/gravityforms` from `v4.5.6` to `v4.5.8`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-gravityforms/releases/tag/v4.5.8
- Changed `wp-pay-extensions/ninjaforms` from `v3.2.3` to `v3.2.4`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-ninjaforms/releases/tag/v3.2.4
- Changed `wp-pay-extensions/restrict-content-pro` from `v4.4.0` to `v4.4.1`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-restrict-content-pro/releases/tag/v4.4.1
- Changed `wp-pay-extensions/woocommerce` from `v4.5.9` to `v4.6.3`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-woocommerce/releases/tag/v4.6.3
- Changed `wp-pay-gateways/adyen` from `v4.4.6` to `v4.4.8`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-adyen/releases/tag/v4.4.8
- Changed `wp-pay-gateways/ems-e-commerce` from `v4.3.4` to `v4.3.5`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-ems-e-commerce/releases/tag/v4.3.5
- Changed `wp-pay-gateways/icepay` from `v4.3.5` to `v4.3.6`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-icepay/releases/tag/v4.3.6
- Changed `wp-pay-gateways/ideal-advanced-v3` from `v4.3.6` to `v4.3.7`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-ideal-advanced-v3/releases/tag/v4.3.7
- Changed `wp-pay-gateways/mollie` from `v4.7.11` to `v4.8.1`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-mollie/releases/tag/v4.8.1
- Changed `wp-pay-gateways/multisafepay` from `v4.3.3` to `v4.3.4`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-multisafepay/releases/tag/v4.3.4
- Changed `wp-pay-gateways/ogone` from `v4.5.3` to `v4.5.4`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-ingenico/releases/tag/v4.5.4
- Changed `wp-pay-gateways/omnikassa-2` from `v4.4.5` to `v4.5.1`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-omnikassa-2/releases/tag/v4.5.1
- Changed `wp-pay-gateways/pay-nl` from `v4.5.5` to `v4.5.6`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-pay-nl/releases/tag/v4.5.6
- Changed `wp-pay-gateways/paypal` from `v2.3.5` to `v2.3.6`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-paypal/releases/tag/v2.3.6
- Changed `wp-pay/core` from `v4.12.0` to `v4.14.1`.
	Release notes: https://github.com/pronamic/wp-pay-core/releases/tag/v4.14.1

Full set of changes: [`9.5.0...9.6.0`][9.6.0]

[9.6.0]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.5.0...v9.6.0

= 9.5.1 =
### Fixed

- Fixed duplicate payment status email with Restrict Content Pro.

### Composer

- Changed `automattic/jetpack-autoloader` from `v2.11.22` to `v2.12.0`.
	Release notes: https://github.com/Automattic/jetpack-autoloader/releases/tag/v2.12.0
- Changed `woocommerce/action-scheduler` from `3.6.2` to `3.6.3`.
	Release notes: https://github.com/woocommerce/action-scheduler/releases/tag/3.6.3
- Changed `wp-pay-extensions/restrict-content-pro` from `v4.4.0` to `v4.4.1`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-restrict-content-pro/releases/tag/v4.4.1

Full set of changes: [`9.5.0...9.5.1`][9.5.1]

[9.5.1]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.5.0...v9.5.1

= 9.5.0 =
### Composer

- Changed `pronamic/wp-gravityforms-nl` from `v3.0.5` to `v3.0.6`.
	Release notes: https://github.com/pronamic/wp-gravityforms-nl/releases/tag/v3.0.6
- Changed `pronamic/wp-money` from `v2.4.0` to `v2.4.1`.
	Release notes: https://github.com/pronamic/wp-money/releases/tag/v2.4.1
- Changed `pronamic/wp-pronamic-pay-forms` from `v1.0.3` to `v1.1.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-forms/releases/tag/v1.1.0
- Changed `wp-pay-extensions/formidable-forms` from `v4.3.4` to `v4.4.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-formidable-forms/releases/tag/v4.4.0
- Changed `wp-pay-extensions/restrict-content-pro` from `v4.3.6` to `v4.4.0`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-restrict-content-pro/releases/tag/v4.4.0
- Changed `wp-pay-extensions/woocommerce` from `v4.5.8` to `v4.5.9`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-woocommerce/releases/tag/v4.5.9
- Changed `wp-pay-gateways/ideal-basic` from `v4.3.4` to `v4.3.5`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-ideal-basic/releases/tag/v4.3.5
- Changed `wp-pay-gateways/mollie` from `v4.7.10` to `v4.7.11`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-mollie/releases/tag/v4.7.11
- Changed `wp-pay-gateways/ogone` from `v4.5.2` to `v4.5.3`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-ingenico/releases/tag/v4.5.3
- Changed `wp-pay-gateways/pay-nl` from `v4.5.4` to `v4.5.5`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-pay-nl/releases/tag/v4.5.5
- Changed `wp-pay-gateways/paypal` from `v2.3.4` to `v2.3.5`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-paypal/releases/tag/v2.3.5
- Changed `wp-pay/core` from `v4.11.0` to `v4.12.0`.
	Release notes: https://github.com/pronamic/wp-pay-core/releases/tag/v4.12.0
- Changed `wp-pay/fundraising` from `v3.2.2` to `v3.2.3`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-fundraising/releases/tag/v3.2.3

Full set of changes: [`9.4.11...9.5.0`][9.5.0]

[9.5.0]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.4.11...v9.5.0

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

[See changelog for all versions.](https://github.com/pronamic/wp-pronamic-pay/blob/main/CHANGELOG.md)

== Links ==

*	[Pronamic](https://www.pronamic.eu/)
*	[Remco Tolsma](https://www.remcotolsma.nl/)
