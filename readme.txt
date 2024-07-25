=== Pronamic Pay ===
Contributors: pronamic, remcotolsma
Tags: pronamic, pay, ideal, payment, gateway
Donate link: https://www.pronamic.eu/donate/?for=wp-plugin-pronamic-pay&source=wp-plugin-readme-txt
Requires at least: 5.9
Tested up to: 6.5
Requires PHP: 8.1
Stable tag: 9.11.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

The Pronamic Pay plugin adds payment methods like iDEAL, Bancontact, credit card and more to your WordPress site for a variety of payment providers.

== Description ==

[Pronamic Pay](https://www.pronamicpay.com/) · [Pronamic](https://www.pronamic.eu/) · [GitHub](https://github.com/pronamic/wp-pronamic-pay)

Pronamic Pay is the best plugin available to accept payments on your site with support for payment methods like iDEAL (Netherlands), Bancontact (Belgium), Sofort (Europe) and credit card, among others. Easily add the configuration details of your payment service provider account and enable the payment method in one of the supported e-commerce plugins. With over 200,000 downloads, the plugin has proven itself as a reliable WordPress solution to use for your payments.

### Key Benefits

*   Supports a wide variety of payment providers.
*   Seamless integration with popular e-commerce and form builder plugins.
*   Automatically updates payment status of orders in WordPress.
*   Easily manage (multiple) payment provider configurations.
*   Continually updated to support the latest e-commerce plugins.
*   Built-in generation of required security certificates.
*   Works with all popular WordPress e-commerce plugins.
*   Recurring payments support for Mollie.
*   Reliable payment solution, with over 200,000 downloads.

### Supported WordPress e-commerce plugins

*	[Charitable](https://www.wpcharitable.com/)
*	[Contact Form 7](https://contactform7.com/) (requires [Premium](https://www.pronamicpay.com/))
*	[Easy Digital Downloads](https://easydigitaldownloads.com/)
*	[Event Espresso 4](https://eventespresso.com/)
*	[Event Espresso 4 Decaf](https://eventespresso.com/)
*	[Formidable Forms](https://formidableforms.com/)
*	[Give](https://givewp.com/)
*	[Gravity Forms](https://www.gravityforms.com/)
*	[MemberPress](https://www.memberpress.com/)
*	[Ninja Forms](https://ninjaforms.com/)
*	[Restrict Content Pro](https://restrictcontentpro.com/)
*	[WooCommerce](https://woocommerce.com/)

### Supported payment providers

*	ABN AMRO - iDEAL Zelfbouw
*	Adyen (requires [Add-On](https://www.pronamicpay.com/))
*	Buckaroo - HTML
*	Deutsche Bank - iDEAL Expert
*	EMS - e-Commerce
*	ICEPAY
*	iDEAL Simulator - iDEAL Professional / Advanced / Zelfbouw
*	ING - iDEAL Basic
*	ING - iDEAL Advanced
*	ING - iDEAL Advanced (new platform)
*	Mollie
*	MultiSafepay - Connect
*	Ingenico/Ogone - OrderStandard
*	Pay.nl
*	Rabobank - Rabo Smart Pay
*	Rabobank - Rabo iDEAL Professional

== Installation ==

### Requirements

The Pronamic Pay plugin extends WordPress extensions with payment methods such as iDEAL, Bancontact, Sofort and credit cards. To offer the payment methods to the visitors of your WordPress website you also require one of these e-commerce or form builder extensions.

### Automatic installation

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t need to leave your web browser. To do an automatic install of Pronamic Pay, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type “Pronamic Pay” and click Search Plugins. Once you’ve found the plugin you can view details about it such as the the point release, rating and description. Most importantly of course, you can install it by simply clicking “Install Now”.

### Manual installation

The manual installation method involves downloading the plugin and uploading it to your webserver via your favourite FTP application. The WordPress codex contains [instructions on how to do this](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

### Updating

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
17. Getting started

== Changelog ==

### [9.11.0] - 2024-07-25

#### Added

- Added support for Restrict Content Pro payment method updates. ([c056a4d](https://github.com/pronamic/wp-pronamic-pay-restrict-content-pro/commit/c056a4deef669cc4c21ed9191ca8dc62a9786ae2))

#### Changed

- Send MemberPress refund notices also for chargebacks. ([024dd78](https://github.com/pronamic/wp-pronamic-pay-memberpress/commit/024dd78b533b12c8183b8872cd4d3d2f2efb5cde))
- Use bundled images instead of https://cdn.wp-pay.org/. ([5d9bed0](https://github.com/pronamic/wp-pay-core/commit/5d9bed040df3dcae9eea819ace6acb4ce2b31ee7))

#### Composer

- Changed `automattic/jetpack-autoloader` from `v3.0.8` to `v3.0.9`.
  Release notes: https://github.com/Automattic/jetpack-autoloader/releases/tag/v3.0.9
- Changed `composer/installers` from `v2.2.0` to `v2.3.0`.
  Release notes: https://github.com/composer/installers/releases/tag/v2.3.0
- Changed `woocommerce/action-scheduler` from `3.8.0` to `3.8.1`.
  Release notes: https://github.com/woocommerce/action-scheduler/releases/tag/3.8.1
- Changed `wp-pay-extensions/memberpress` from `v4.7.11` to `v4.8.0`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-memberpress/releases/tag/v4.8.0
- Changed `wp-pay-extensions/restrict-content-pro` from `v4.5.0` to `v4.6.0`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-restrict-content-pro/releases/tag/v4.6.0
- Changed `wp-pay/core` from `v4.20.0` to `v4.21.0`.
  Release notes: https://github.com/pronamic/wp-pay-core/releases/tag/v4.21.0

Full set of changes: [`9.10.1...9.11.0`][9.11.0]

[9.11.0]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.10.1...v9.11.0

### [9.10.1] - 2024-06-19

#### Commits

- Updated “Requires PHP” to PHP 8.1 (due to pronamic/ideal-issuers library). ([e3b34b7](https://github.com/pronamic/wp-pronamic-pay/commit/e3b34b7897174c70779b77735a1f185a48469a00))
- Fixed warning on https://wordpress.org/plugins/pronamic-ideal/. ([0f327df](https://github.com/pronamic/wp-pronamic-pay/commit/0f327df635c0676d93b0257b905558a20b3751d4))

#### Composer

- Added `pronamic/pronamic-pay-admin-reports` `^1.0`.
- Changed `wp-pay-extensions/event-espresso` from `v4.3.0` to `v4.3.1`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-event-espresso/releases/tag/v4.3.1
- Changed `wp-pay-extensions/woocommerce` from `v4.9.0` to `v4.9.1`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-woocommerce/releases/tag/v4.9.1
- Changed `wp-pay-gateways/omnikassa-2` from `v4.7.1` to `v4.7.2`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-rabo-smart-pay/releases/tag/v4.7.2
- Changed `wp-pay/core` from `v4.19.0` to `v4.20.0`.
  Release notes: https://github.com/pronamic/wp-pay-core/releases/tag/v4.20.0

Full set of changes: [`9.10.0...9.10.1`][9.10.1]

[9.10.1]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.10.0...v9.10.1

### [9.10.0] - 2024-06-12

#### Removed

- Removed `images` folder.

#### Composer

- Changed `automattic/jetpack-autoloader` from `v3.0.7` to `v3.0.8`.
  Release notes: https://github.com/Automattic/jetpack-autoloader/releases/tag/v3.0.8
- Changed `pronamic/wp-number` from `v1.3.1` to `v1.3.2`.
  Release notes: https://github.com/pronamic/wp-number/releases/tag/v1.3.2
- Changed `wp-pay-extensions/event-espresso` from `v4.2.4` to `v4.3.0`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-event-espresso/releases/tag/v4.3.0
- Changed `wp-pay-extensions/gravityforms` from `v4.7.0` to `v4.8.0`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-gravityforms/releases/tag/v4.8.0
- Changed `wp-pay-extensions/woocommerce` from `v4.8.0` to `v4.9.0`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-woocommerce/releases/tag/v4.9.0
- Changed `wp-pay-gateways/mollie` from `v4.11.0` to `v4.12.0`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-mollie/releases/tag/v4.12.0
- Changed `wp-pay-gateways/ogone` from `v4.6.0` to `v4.7.0`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-ingenico/releases/tag/v4.7.0
- Changed `wp-pay/core` from `v4.18.0` to `v4.19.0`.
  Release notes: https://github.com/pronamic/wp-pay-core/releases/tag/v4.19.0

Full set of changes: [`9.9.0...9.10.0`][9.10.0]

[9.10.0]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.9.0...v9.10.0

### [9.9.0] - 2024-05-27

#### Composer

- Changed `php` from `>=8.0` to `>=8.1`.
- Changed `pronamic/wp-http` from `v1.2.2` to `v1.2.3`.
  Release notes: https://github.com/pronamic/wp-http/releases/tag/v1.2.3
- Changed `pronamic/wp-mollie` from `v1.5.1` to `v1.6.0`.
  Release notes: https://github.com/pronamic/wp-mollie/releases/tag/v1.6.0
- Changed `pronamic/wp-number` from `v1.3.0` to `v1.3.1`.
  Release notes: https://github.com/pronamic/wp-number/releases/tag/v1.3.1
- Changed `woocommerce/action-scheduler` from `3.7.4` to `3.8.0`.
  Release notes: https://github.com/woocommerce/action-scheduler/releases/tag/3.8.0
- Changed `wp-pay-gateways/ems-e-commerce` from `v4.3.5` to `v4.4.0`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-ems-e-commerce/releases/tag/v4.4.0
- Changed `wp-pay-gateways/mollie` from `v4.10.3` to `v4.11.0`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-mollie/releases/tag/v4.11.0
- Changed `wp-pay-gateways/omnikassa-2` from `v4.6.0` to `v4.7.1`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-rabo-smart-pay/releases/tag/v4.7.1
- Changed `wp-pay/core` from `v4.17.0` to `v4.18.0`.
  Release notes: https://github.com/pronamic/wp-pay-core/releases/tag/v4.18.0

Full set of changes: [`9.8.1...9.9.0`][9.9.0]

[9.9.0]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.8.1...v9.9.0

### [9.8.1] - 2024-05-15

#### Commits

- Removed DigiWallet and TargetPay. ([684ff99](https://github.com/pronamic/wp-pronamic-pay/commit/684ff993d6ae62fa98234f320d99e04603895994))
- Use `$wpdb->prefix` for table names in uninstall (#375). ([51048f3](https://github.com/pronamic/wp-pronamic-pay/commit/51048f3aea11a927b07e1d67e0559c4536665e65))
- Removed the s2Member screenshot. ([35b9fc9](https://github.com/pronamic/wp-pronamic-pay/commit/35b9fc98ac40998b5f817479bf0106c448557e7c))
- Rename "iDEAL Professional" to "Rabo iDEAL Professional". ([3f49d35](https://github.com/pronamic/wp-pronamic-pay/commit/3f49d357d396ac063037bdc7ba05f4512d816a5c))
- Rename "OmniKassa 2.0" to "Rabo Smart Pay". ([4ee5c3c](https://github.com/pronamic/wp-pronamic-pay/commit/4ee5c3cb454845ec22ef1ef8f94d61bb55ce12c1))

#### Composer

- Removed `wp-pay-gateways/digiwallet` `^3.3`.
- Changed `automattic/jetpack-autoloader` from `v3.0.4` to `v3.0.7`.
  Release notes: https://github.com/Automattic/jetpack-autoloader/releases/tag/v3.0.7
- Changed `pronamic/pronamic-wp-updater` from `v1.0.1` to `v1.0.2`.
  Release notes: https://github.com/pronamic/pronamic-wp-updater/releases/tag/v1.0.2
- Changed `woocommerce/action-scheduler` from `3.7.3` to `3.7.4`.
  Release notes: https://github.com/woocommerce/action-scheduler/releases/tag/3.7.4
- Changed `wp-pay-extensions/gravityforms` from `v4.6.1` to `v4.7.0`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-gravityforms/releases/tag/v4.7.0
- Changed `wp-pay-extensions/restrict-content-pro` from `v4.4.4` to `v4.5.0`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-restrict-content-pro/releases/tag/v4.5.0
- Changed `wp-pay-gateways/adyen` from `v4.5.0` to `v4.5.1`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-adyen/releases/tag/v4.5.1
- Changed `wp-pay-gateways/mollie` from `v4.10.0` to `v4.10.3`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-mollie/releases/tag/v4.10.3
- Changed `wp-pay/core` from `v4.16.0` to `v4.17.0`.
  Release notes: https://github.com/pronamic/wp-pay-core/releases/tag/v4.17.0

Full set of changes: [`9.8.0...9.8.1`][9.8.1]

[9.8.1]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.8.0...v9.8.1

### [9.8.0] - 2024-03-27

#### Added

- Core - Added support for the more general card payment method.
- WooCommerce - Added gateway icon display setting, administrators can now choose a standard icon, a custom icon or no icon.
- MultiSafepay - Added `<plugin>` information to direct transaction request message.
- Rabo Smart Pay - Added `X-Api-User-Agent` header.

#### Changed

- Core - Improved home URL changes detector.
- Core - Improved view of redirection and subscription pages on mobile.

#### Fixed

- WooCommerce Subscriptions - Fixed Pronamic Pay subscription meta box visibility.

#### Composer

- Changed `automattic/jetpack-autoloader` from `v3.0.2` to `v3.0.4`.
  Release notes: https://github.com/Automattic/jetpack-autoloader/releases/tag/v3.0.4
- Changed `pronamic/wp-gravityforms-nl` from `v3.0.7` to `v3.0.9`.
  Release notes: https://github.com/pronamic/wp-gravityforms-nl/releases/tag/v3.0.9
- Changed `pronamic/wp-pronamic-pay-forms` from `v1.1.1` to `v1.1.2`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-forms/releases/tag/v1.1.2
- Changed `woocommerce/action-scheduler` from `3.7.2` to `3.7.3`.
  Release notes: https://github.com/woocommerce/action-scheduler/releases/tag/3.7.3
- Changed `wp-pay-extensions/contact-form-7` from `v3.5.0` to `v3.5.1`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-contact-form-7/releases/tag/v3.5.1
- Changed `wp-pay-extensions/easy-digital-downloads` from `v4.3.4` to `v4.3.5`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-easy-digital-downloads/releases/tag/v4.3.5
- Changed `wp-pay-extensions/event-espresso` from `v4.2.3` to `v4.2.4`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-event-espresso/releases/tag/v4.2.4
- Changed `wp-pay-extensions/gravityforms` from `v4.6.0` to `v4.6.1`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-gravityforms/releases/tag/v4.6.1
- Changed `wp-pay-extensions/ninjaforms` from `v3.3.1` to `v3.3.2`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-ninjaforms/releases/tag/v3.3.2
- Changed `wp-pay-extensions/woocommerce` from `v4.7.1` to `v4.8.0`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-woocommerce/releases/tag/v4.8.0
- Changed `wp-pay-gateways/adyen` from `v4.5.0` to `v4.5.0`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-adyen/releases/tag/v4.5.0
- Changed `wp-pay-gateways/buckaroo` from `v4.3.3` to `v4.3.4`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-buckaroo/releases/tag/v4.3.4
- Changed `wp-pay-gateways/icepay` from `v4.3.6` to `v4.3.7`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-icepay/releases/tag/v4.3.7
- Changed `wp-pay-gateways/ideal-basic` from `v4.3.5` to `v4.3.6`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-ideal-basic/releases/tag/v4.3.6
- Changed `wp-pay-gateways/mollie` from `v4.9.2` to `v4.10.0`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-mollie/releases/tag/v4.10.0
- Changed `wp-pay-gateways/multisafepay` from `v4.3.4` to `v4.4.0`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-multisafepay/releases/tag/v4.4.0
- Changed `wp-pay-gateways/omnikassa-2` from `v4.5.4` to `v4.6.0`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-rabo-smart-pay/releases/tag/v4.6.0
- Changed `wp-pay/core` from `v4.15.1` to `v4.16.0`.
  Release notes: https://github.com/pronamic/wp-pay-core/releases/tag/v4.16.0
- Changed `wp-pay/fundraising` from `v3.2.4` to `v3.2.5`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-fundraising/releases/tag/v3.2.5

Full set of changes: [`9.7.1...9.8.0`][9.8.0]

[9.8.0]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.7.1...v9.8.0

### [9.7.1] - 2024-03-14

#### Added

- Added support for TWINT payment method with Mollie. ([e4b482b](https://github.com/pronamic/wp-pronamic-pay-mollie/commit/e4b482b304c1157e7acd886e0815734e86733c39))

#### Fixed

- Fixed setting Restrict Content Pro refunded payment status on refunds and chargebacks ([#14](https://github.com/pronamic/wp-pronamic-pay-restrict-content-pro/issues/14)). ([09a28d8](https://github.com/pronamic/wp-pronamic-pay-restrict-content-pro/commit/09a28d8304a477fdd4a0eb8cf43481dc62bce764))
- Fixed updating RCP membership status on subscription status update ([#13](https://github.com/pronamic/wp-pronamic-pay-restrict-content-pro/issues/13)). ([7fe6938](https://github.com/pronamic/wp-pronamic-pay-restrict-content-pro/commit/7fe693819524ac32a81daa5a40280f92b0accb39))

#### Composer

- Changed `automattic/jetpack-autoloader` from `v2.12.0` to `v3.0.2`.
  Release notes: https://github.com/Automattic/jetpack-autoloader/releases/tag/v3.0.2
- Changed `pronamic/pronamic-wp-updater` from `v1.0.0` to `v1.0.1`.
  Release notes: https://github.com/pronamic/pronamic-wp-updater/releases/tag/v1.0.1
- Changed `pronamic/wp-mollie` from `v1.5.0` to `v1.5.1`.
  Release notes: https://github.com/pronamic/wp-mollie/releases/tag/v1.5.1
- Changed `woocommerce/action-scheduler` from `3.7.1` to `3.7.2`.
  Release notes: https://github.com/woocommerce/action-scheduler/releases/tag/3.7.2
- Changed `wp-pay-extensions/ninjaforms` from `v3.3.0` to `v3.3.1`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-ninjaforms/releases/tag/v3.3.1
- Changed `wp-pay-extensions/restrict-content-pro` from `v4.4.2` to `v4.4.4`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-restrict-content-pro/releases/tag/v4.4.4
- Changed `wp-pay-gateways/adyen` from `v4.4.8` to `v4.5.0`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-adyen/releases/tag/v4.5.0
- Changed `wp-pay-gateways/mollie` from `v4.9.0` to `v4.9.2`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-mollie/releases/tag/v4.9.2
- Changed `wp-pay-gateways/omnikassa-2` from `v4.5.3` to `v4.5.4`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-rabo-smart-pay/releases/tag/v4.5.4
- Changed `wp-pay/core` from `v4.15.0` to `v4.15.1`.
  Release notes: https://github.com/pronamic/wp-pay-core/releases/tag/v4.15.1
- Changed `wp-pay/fundraising` from `v3.2.3` to `v3.2.4`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-fundraising/releases/tag/v3.2.4

Full set of changes: [`9.7.0...9.7.1`][9.7.1]

[9.7.1]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.7.0...v9.7.1

### [9.7.0] - 2024-02-08

#### Added

- Mollie → Added support for card token. [2941dee](https://github.com/pronamic/wp-mollie/commit/2941dee85b0c7ad2f510c9c1a34ceca1faa91585)
- Mollie/WooCommerce → Added support for Mollie card field/component in WooCommerce legacy checkout. [#40](https://github.com/pronamic/wp-pronamic-pay-mollie/pull/40)

#### Changed

- Contact Form 7 → Improved the support for Contact Form 7 checkbox fields used for the amount to be paid (`pronamic_pay_amount` tag option), multiple checked options/amounts are now added up. ([ba1322a](https://github.com/pronamic/wp-pronamic-pay-contact-form-7/commit/ba1322afb5d859f21281827a263dc94ed0dae350))
- Gravity Forms → Optimize performance by reusing instances of `PayFeed` from memory. ([fa89eab](https://github.com/pronamic/wp-pronamic-pay-gravityforms/commit/fa89eaba746000d5c432b480f1b4f0b4b8e07994))
- Mollie → The HTTP timeout option is increased when connecting to Mollie via WP-Cron, WP-CLI or the Action Scheduler library. [pronamic/wp-pay-core#170](https://github.com/pronamic/wp-pay-core/issues/170)

#### Fixed

- Gravity Forms → Fixed deleting feeds through `PaymentAddOn::delete_feeds()`. ([89f88b7](https://github.com/pronamic/wp-pronamic-pay-gravityforms/commit/89f88b7ea1b27af52418bf34a04b5c31690f5ff3))
- Mollie → Fixed `wp_register_script` and `wp_register_style` are called incorrectly https://github.com/pronamic/wp-pronamic-pay-mollie/issues/42. ([41bfb35](https://github.com/pronamic/wp-pronamic-pay-mollie/commit/41bfb35d058cb50012d2141d111c084f24ec1e3c))
- WooCommerce → Fixed "Fatal error: Uncaught Error: Call to undefined function wc_get_order()" in source text if WooCommerce is not active. ([c4ccf37](https://github.com/pronamic/wp-pronamic-pay-woocommerce/commit/c4ccf3729ea994df23737181c5771abcaf8cd6c6))

#### Removed

- Worldline (formerly Ingenico/Ogone) → Removed `DirectLink` integration. ([51047d6](https://github.com/pronamic/wp-pronamic-pay-ingenico/commit/51047d6c9c73b5b9d63ecd151fa6fff169e39638))

#### Composer

- Changed `automattic/jetpack-autoloader` from `v2.12.0` to `v2.12.0`.
  Release notes: https://github.com/Automattic/jetpack-autoloader/releases/tag/v2.12.0
- Changed `pronamic/wp-mollie` from `v1.4.0` to `v1.5.0`.
  Release notes: https://github.com/pronamic/wp-mollie/releases/tag/v1.5.0
- Changed `woocommerce/action-scheduler` from `3.7.1` to `3.7.1`.
  Release notes: https://github.com/woocommerce/action-scheduler/releases/tag/3.7.1
- Changed `wp-pay-extensions/contact-form-7` from `v3.4.0` to `v3.5.0`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-contact-form-7/releases/tag/v3.5.0
- Changed `wp-pay-extensions/gravityforms` from `v4.5.8` to `v4.6.0`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-gravityforms/releases/tag/v4.6.0
- Changed `wp-pay-extensions/ninjaforms` from `v3.2.4` to `v3.3.0`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-ninjaforms/releases/tag/v3.3.0
- Changed `wp-pay-extensions/restrict-content-pro` from `v4.4.1` to `v4.4.2`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-restrict-content-pro/releases/tag/v4.4.2
- Changed `wp-pay-extensions/woocommerce` from `v4.7.0` to `v4.7.1`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-woocommerce/releases/tag/v4.7.1
- Changed `wp-pay-gateways/mollie` from `v4.8.1` to `v4.9.0`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-mollie/releases/tag/v4.9.0
- Changed `wp-pay-gateways/ogone` from `v4.6.0` to `v4.6.0`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-ingenico/releases/tag/v4.6.0
- Changed `wp-pay/core` from `v4.14.3` to `v4.15.0`.
  Release notes: https://github.com/pronamic/wp-pay-core/releases/tag/v4.15.0

Full set of changes: [`9.6.4...9.7.0`][9.7.0]

[9.7.0]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.6.4...v9.7.0

### [9.6.4] - 2023-12-18

#### Removed

- Removed 'Ingenico - DirectLink' integration (pronamic/wp-pronamic-pay-ingenico/15). ([5322ba9](https://github.com/pronamic/wp-pronamic-pay/commit/5322ba9862e7af3196b8b2dda26723b953933c34))

#### Composer

- Changed `woocommerce/action-scheduler` from `3.6.4` to `3.7.1`.
  Release notes: https://github.com/woocommerce/action-scheduler/releases/tag/3.7.1
- Changed `wp-pay-extensions/contact-form-7` from `v3.3.2` to `v3.4.0`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-contact-form-7/releases/tag/v3.4.0
- Changed `wp-pay-extensions/formidable-forms` from `v4.4.1` to `v4.4.2`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-formidable-forms/releases/tag/v4.4.2
- Changed `wp-pay-extensions/memberpress` from `v4.7.10` to `v4.7.11`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-memberpress/releases/tag/v4.7.11
- Changed `wp-pay-extensions/woocommerce` from `v4.6.3` to `v4.7.0`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-woocommerce/releases/tag/v4.7.0
- Changed `wp-pay-gateways/ogone` from `v4.5.4` to `v4.6.0`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-ingenico/releases/tag/v4.6.0
- Changed `wp-pay-gateways/omnikassa-2` from `v4.5.2` to `v4.5.3`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-omnikassa-2/releases/tag/v4.5.3
- Changed `wp-pay/core` from `v4.14.2` to `v4.14.3`.
  Release notes: https://github.com/pronamic/wp-pay-core/releases/tag/v4.14.3

Full set of changes: [`9.6.3...9.6.4`][9.6.4]

[9.6.4]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.6.3...v9.6.4

### [9.6.3] - 2023-11-15

#### Fixed

- Fixed fatal error in Formidable Forms bank select field.

#### Commits

- WooCommerce tested up to 8.2.2. ([c7bb73c](https://github.com/pronamic/wp-pronamic-pay/commit/c7bb73c30cbf3eb24d700e70b27ca7e8fac6f570))
- Formidable Forms tested up to 6.5.4. ([cdcd068](https://github.com/pronamic/wp-pronamic-pay/commit/cdcd068623c9befe91eb26d12cbb838f29cdf92d))
- WordPress tested up to 6.4. ([78ba625](https://github.com/pronamic/wp-pronamic-pay/commit/78ba625833028741c1d15f2500187a8dc9fa9d25))

#### Composer

- Changed `wp-pay-extensions/formidable-forms` from `v4.4.0` to `v4.4.1`.
  Release notes: https://github.com/pronamic/wp-pronamic-pay-formidable-forms/releases/tag/v4.4.1

Full set of changes: [`9.6.2...9.6.3`][9.6.3]

[9.6.3]: https://github.com/pronamic/wp-pronamic-pay/compare/v9.6.2...v9.6.3

[See changelog for all versions.](https://github.com/pronamic/wp-pronamic-pay/blob/main/CHANGELOG.md)

== Links ==

*	[Pronamic](https://www.pronamic.eu/)
*	[Remco Tolsma](https://www.remcotolsma.nl/)
