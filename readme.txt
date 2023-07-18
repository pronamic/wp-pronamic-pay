=== Pronamic Pay ===
Contributors: pronamic, remcotolsma 
Tags: ideal, bank, payment, gravity forms, forms, payment, woocommerce, recurring-payments, shopp, rabobank, friesland bank, ing, mollie, omnikassa, wpsc, wpecommerce, commerce, e-commerce, cart
Donate link: https://www.pronamic.eu/donate/?for=wp-plugin-pronamic-pay&source=wp-plugin-readme-txt
Requires at least: 5.9
Tested up to: 6.2
Requires PHP: 7.4
Stable tag: 9.4.6

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

= 9.4.6 =
### Composer

- Added `pronamic/wp-pronamic-pay-forms` `^1.0`.
- Changed `wp-pay-extensions/memberpress` from `v4.7.9` to `v4.7.10`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-memberpress/releases/tag/v4.7.10
- Changed `wp-pay-extensions/restrict-content-pro` from `v4.3.5` to `v4.3.6`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-restrict-content-pro/releases/tag/v4.3.6
- Changed `wp-pay/core` from `v4.9.4` to `v4.10.0`.
	Release notes: https://github.com/pronamic/wp-pay-core/releases/tag/v4.10.0

Full set of changes: [`9.4.5...9.4.6`][9.4.6]

[9.4.6]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.4.5...v9.4.6

= 9.4.5 =
### Changed

- 📣 Mollie payment methods that can be used as a first payment for recurring
  payments are marked with support for recurring payments. For extensions such
  as WooCommerce Subscriptions, MemberPress and Restrict Content Pro, this may
  mean that payment methods such as Bancontact, Belfius, EPS, Giropay, iDEAL,
  KBC and Sofort can now also be visible when paying for subscriptions. Where
  previously the "Direct Debit (mandate via iDEAL)" payment method/gateway had
  to be used, the regular iDEAL payment method/gateway can now also be used.

### Commits

- Removed notifications about removed extensions. ([d9bdd88](https://github.com/pronamic/wp-pronamic-pay/commit/d9bdd88cfd44b85192340876dd92562629a3891c))
- Also replace `pronamic-money` text domain. ([eec3016](https://github.com/pronamic/wp-pronamic-pay/commit/eec30169e695392ee8b7dbccad22dab630c24e51))
- Added `nl_NL_formal` based on `nl_NL`. ([23400f4](https://github.com/pronamic/wp-pronamic-pay/commit/23400f49b5599b49121bcb4382d9c1abe07628de))
- Added `nl_BE` based on `nl_NL`. ([d4864c5](https://github.com/pronamic/wp-pronamic-pay/commit/d4864c5039dc32c14c645eb1a3430ead27c8afda))
- Fixed "PHP Fatal error:  Uncaught Error: Class "Pronamic\Deployer\Changelog" not found". ([f06ed60](https://github.com/pronamic/wp-pronamic-pay/commit/f06ed602ad9151771a9011b32a91617d1c2a4c89))

### Composer

- Changed `pronamic/wp-mollie` from `v1.2.1` to `v1.2.2`.
	Release notes: https://github.com/pronamic/wp-mollie/releases/tag/v1.2.2
- Changed `woocommerce/action-scheduler` from `3.6.0` to `3.6.1`.
	Release notes: https://github.com/woocommerce/action-scheduler/releases/tag/3.6.1
- Changed `wp-pay-extensions/charitable` from `v4.3.2` to `v4.3.3`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-charitable/releases/tag/v4.3.3
- Changed `wp-pay-extensions/gravityforms` from `v4.5.5` to `v4.5.6`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-gravityforms/releases/tag/v4.5.6
- Changed `wp-pay-extensions/memberpress` from `v4.7.8` to `v4.7.9`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-memberpress/releases/tag/v4.7.9
- Changed `wp-pay-extensions/restrict-content-pro` from `v4.3.4` to `v4.3.5`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-restrict-content-pro/releases/tag/v4.3.5
- Changed `wp-pay-extensions/woocommerce` from `v4.5.6` to `v4.5.7`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-woocommerce/releases/tag/v4.5.7
- Changed `wp-pay-gateways/adyen` from `v4.4.4` to `v4.4.5`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-adyen/releases/tag/v4.4.5
- Changed `wp-pay-gateways/digiwallet` from `v3.3.3` to `v3.3.4`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-digiwallet/releases/tag/v3.3.4
- Changed `wp-pay-gateways/ems-e-commerce` from `v4.3.3` to `v4.3.4`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-ems-e-commerce/releases/tag/v4.3.4
- Changed `wp-pay-gateways/icepay` from `v4.3.4` to `v4.3.5`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-icepay/releases/tag/v4.3.5
- Changed `wp-pay-gateways/ideal-advanced-v3` from `v4.3.5` to `v4.3.6`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-ideal-advanced-v3/releases/tag/v4.3.6
- Changed `wp-pay-gateways/ideal-basic` from `v4.3.3` to `v4.3.4`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-ideal-basic/releases/tag/v4.3.4
- Changed `wp-pay-gateways/mollie` from `v4.7.7` to `v4.7.8`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-mollie/releases/tag/v4.7.8
- Changed `wp-pay-gateways/ogone` from `v4.5.1` to `v4.5.2`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-ingenico/releases/tag/v4.5.2
- Changed `wp-pay-gateways/omnikassa-2` from `v4.4.4` to `v4.4.5`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-omnikassa-2/releases/tag/v4.4.5
- Changed `wp-pay-gateways/pay-nl` from `v4.5.3` to `v4.5.4`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-pay-nl/releases/tag/v4.5.4
- Changed `wp-pay/core` from `v4.9.3` to `v4.9.4`.
	Release notes: https://github.com/pronamic/wp-pay-core/releases/tag/v4.9.4

Full set of changes: [`9.4.4...9.4.5`][9.4.5]

[9.4.5]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.4.4...v9.4.5

= 9.4.4 =
### Commits

- Changed text domain from `pronamic_ideal` to `pronamic-ideal`. ([a61ad49](https://github.com/pronamic/wp-pronamic-pay/commit/a61ad497b56e6f2b54901bac4a15875b66fa3ccb))
- Updated extensions tested up to versions. ([555b376](https://github.com/pronamic/wp-pronamic-pay/commit/555b376c5c4a28c5e5b9ae827304538e431049b6))

### Composer

- Changed `automattic/jetpack-autoloader` from `v2.11.18` to `v2.11.21`.
	Release notes: https://github.com/Automattic/jetpack-autoloader/releases/tag/v2.11.21
- Changed `pronamic/wp-gravityforms-nl` from `v3.0.4` to `v3.0.5`.
	Release notes: https://github.com/pronamic/wp-gravityforms-nl/releases/tag/v3.0.5
- Changed `woocommerce/action-scheduler` from `3.5.4` to `3.6.0`.
	Release notes: https://github.com/woocommerce/action-scheduler/releases/tag/3.6.0
- Changed `wp-pay-extensions/contact-form-7` from `v3.2.4` to `v3.2.5`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-contact-form-7/releases/tag/v3.2.5
- Changed `wp-pay-extensions/easy-digital-downloads` from `v4.3.3` to `v4.3.4`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-easy-digital-downloads/releases/tag/v4.3.4
- Changed `wp-pay-extensions/event-espresso` from `v4.2.2` to `v4.2.3`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-event-espresso/releases/tag/v4.2.3
- Changed `wp-pay-extensions/formidable-forms` from `v4.3.3` to `v4.3.4`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-formidable-forms/releases/tag/v4.3.4
- Changed `wp-pay-extensions/give` from `v4.2.2` to `v4.2.3`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-give/releases/tag/v4.2.3
- Changed `wp-pay-extensions/gravityforms` from `v4.5.4` to `v4.5.5`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-gravityforms/releases/tag/v4.5.5
- Changed `wp-pay-extensions/memberpress` from `v4.7.7` to `v4.7.8`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-memberpress/releases/tag/v4.7.8
- Changed `wp-pay-extensions/ninjaforms` from `v3.2.2` to `v3.2.3`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-ninjaforms/releases/tag/v3.2.3
- Changed `wp-pay-extensions/restrict-content-pro` from `v4.3.3` to `v4.3.4`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-restrict-content-pro/releases/tag/v4.3.4
- Changed `wp-pay-extensions/woocommerce` from `v4.5.5` to `v4.5.6`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-woocommerce/releases/tag/v4.5.6
- Changed `wp-pay-gateways/buckaroo` from `v4.3.2` to `v4.3.3`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-buckaroo/releases/tag/v4.3.3
- Changed `wp-pay-gateways/digiwallet` from `v3.3.2` to `v3.3.3`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-digiwallet/releases/tag/v3.3.3
- Changed `wp-pay-gateways/ems-e-commerce` from `v4.3.2` to `v4.3.3`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-ems-e-commerce/releases/tag/v4.3.3
- Changed `wp-pay-gateways/icepay` from `v4.3.3` to `v4.3.4`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-icepay/releases/tag/v4.3.4
- Changed `wp-pay-gateways/ideal` from `v4.1.2` to `v4.1.3`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-ideal/releases/tag/v4.1.3
- Changed `wp-pay-gateways/ideal-advanced-v3` from `v4.3.4` to `v4.3.5`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-ideal-advanced-v3/releases/tag/v4.3.5
- Changed `wp-pay-gateways/ideal-basic` from `v4.3.2` to `v4.3.3`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-ideal-basic/releases/tag/v4.3.3
- Changed `wp-pay-gateways/mollie` from `v4.7.6` to `v4.7.7`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-mollie/releases/tag/v4.7.7
- Changed `wp-pay-gateways/multisafepay` from `v4.3.2` to `v4.3.3`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-multisafepay/releases/tag/v4.3.3
- Changed `wp-pay-gateways/ogone` from `v4.5.0` to `v4.5.1`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-ingenico/releases/tag/v4.5.1
- Changed `wp-pay-gateways/omnikassa-2` from `v4.4.3` to `v4.4.4`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-omnikassa-2/releases/tag/v4.4.4
- Changed `wp-pay-gateways/pay-nl` from `v4.5.2` to `v4.5.3`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-pay-nl/releases/tag/v4.5.3
- Changed `wp-pay-gateways/paypal` from `v2.3.2` to `v2.3.4`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-paypal/releases/tag/v2.3.4
- Changed `wp-pay/core` from `v4.9.2` to `v4.9.3`.
	Release notes: https://github.com/pronamic/wp-pay-core/releases/tag/v4.9.3
- Changed `wp-pay/fundraising` from `v3.2.1` to `v3.2.2`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-fundraising/releases/tag/v3.2.2

Full set of changes: [`9.4.3...9.4.4`][9.4.4]

[9.4.4]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.4.3...v9.4.4

= 9.4.3 =
### Fixed

- Fixed error caused by undefined `PRONAMIC_PAY_DEBUG` constant.
- Fixed warning about missing `package.json` file in Mollie integration.

### Commits

- Check if `PRONAMIC_PAY_DEBUG` constant is defined. ([10c8e53](https://github.com/pronamic/wp-pronamic-pay/commit/10c8e5374fc6e28564f9fe5499b81005f8736a03))
- Make sure to define `PRONAMIC_PAY_DEBUG` constant (accidentally removed in 778b554b). ([6e41f23](https://github.com/pronamic/wp-pronamic-pay/commit/6e41f234654a4e192718907728746e569449be07))
- Mark 'Old platform' integrations for ING iDEAL Advanced as deprecated. ([82cd05e](https://github.com/pronamic/wp-pronamic-pay/commit/82cd05ee7615d96075ee7484138a6b4687e418d0))
- Updated translations. ([b056794](https://github.com/pronamic/wp-pronamic-pay/commit/b0567941d165a7209972d9e64e82b7280200ef13))

### Composer

- Changed `pronamic/wp-mollie` from `v1.2.0` to `v1.2.1`.
	Release notes: https://github.com/pronamic/wp-mollie/releases/tag/v1.2.1

Full set of changes: [`9.4.2...9.4.3`][9.4.3]

[9.4.3]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.4.2...v9.4.3

= 9.4.2 =
### Fixed

- Fixed fatal error due to errorneous translations.

### Commits

- Updated translations. ([a416552](https://github.com/pronamic/wp-pronamic-pay/commit/a41655239944cf37900daca960c3468dc51fd59d))

### Composer

- Changed `wp-pay-extensions/charitable` from `v4.3.1` to `v4.3.2`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-charitable/releases/tag/v4.3.2
- Changed `wp-pay-gateways/adyen` from `v4.4.3` to `v4.4.4`.
	Release notes: https://github.com/pronamic/wp-pronamic-pay-adyen/releases/tag/v4.4.4
- Changed `wp-pay/core` from `v4.9.1` to `v4.9.2`.
	Release notes: https://github.com/pronamic/wp-pay-core/releases/tag/v4.9.2

Full set of changes: [`9.4.1...9.4.2`][9.4.2]

[9.4.2]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.4.1...v9.4.2

[See changelog for all versions.](https://www.pronamic.eu/plugins/pronamic-pay/changelog/)

== Links ==

*	[Pronamic](https://www.pronamic.eu/)
*	[Remco Tolsma](https://www.remcotolsma.nl/)
