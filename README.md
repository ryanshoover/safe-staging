# Safe Staging

* Contributors: ryanshoover
* Tags: staging, woocommerce, email
* Requires at least: 4.7
* Tested up to: 5
* Requires PHP: 7.0
* License: GPL3
* License URI: www.gnu.org/licenses/gpl-3.0.en.html

Safely copy your WordPress, WooCommerce, and membership site between production and staging.

## Description

Simply define your production url in settings and copy your site to your staging instance without fear. The staging site won\'t send any emails and won\'t process any payments.

### Why should I use the plugin

If you host your site on a managed host that provides a staging instance (WP Engine, Siteground, and others), or if you run a staging instance for a self-hosted website, you may have found that WordPress and WooCommerce will automatically send emails and process payments from the staging site. Whenever you clone your production site to your staging site, you would normally need to complete a number of steps to make your site "safe for staging."

Instead, install this plugin in production, set the production URL, and safely copy your site to and from staging. No other steps needed!

### Features on Staging

WordPress Emails are stopped. The site won\'t send any emails (may not be compatible with plugins that offload mail to a 3rd party service).

The WooCommerce checkout page has a warning message notifying the visitor they are viewing the staging site.

WooCommerce payment gateways are suspended. Bank account transfer, Cash on Delivery and Check are left enabled. Stripe is automatically put into test mode.

WooCommerce Subscriptions is put into staging mode.

## Installation

1. Upload the plugin to wp-content/plugins/
2. Activate the plugin at wp-admin/plugins.php
3. Set the URL for your production site at /wp-admin/options-general.php?page=safe-staging