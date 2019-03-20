[![CircleCI](https://circleci.com/gh/ryanshoover/safe-staging/tree/master.svg?style=svg)](https://circleci.com/gh/ryanshoover/safe-staging/tree/master)

# Safe Staging

* Contributors: ryanshoover
* Tags: staging, woocommerce, email
* Requires at least: 4.7
* Tested up to: 5.1
* Requires PHP: 7.0
* License: GPL3
* License URI: www.gnu.org/licenses/gpl-3.0.en.html

Safely copy your WordPress, WooCommerce, and membership site between production and staging.

## Description

Simply define your production url in settings and copy your site to your staging instance without fear. The staging site won't send any emails and won't process any payments.

### Why should I use the plugin

If you host your site on a managed host that provides a staging instance (WP Engine, Siteground, and others), or if you run a staging instance for a self-hosted website, you may have found that WordPress and WooCommerce will automatically send emails and process payments from the staging site. Whenever you clone your production site to your staging site, you would normally need to complete a number of steps to make your site "safe for staging."

Instead, install this plugin in production, set the production URL, and safely copy your site to and from staging. No other steps needed!

### Features on Staging

A "noindex" tag is added to all pages. Your staging site won't show up in Google.

WordPress emails are stopped. The site won't send any emails except for the password reset email. Please note, this feature may not be compatible with plugins that offload email to a 3rd party service.

The WooCommerce checkout page has a warning message notifying the visitor they are viewing the staging site.

WooCommerce payment gateways are suspended. Bank account transfer, Cash on Delivery and Check are left enabled. Stripe is automatically put into test mode.

WooCommerce Subscriptions is put into staging mode.

## Installation

1. Upload the plugin to `/wp-content/plugins/`
2. Activate the plugin at `/wp-admin/plugins.php`
3. Set the URL for your production site at `/wp-admin/options-general.php?page=safe-staging`

## Frequently Asked Questions

### Can I complicate how the plugin determines what the production URL is?

The filter `safe_staging_is_production` will let you change what the plugin sees as the production site.
For example, the filter below will let you support an alternative production URL.

```php
/**
 * Change whether Safe Staging thinks the current site
 * is the production site.
 *
 * @param bool $is_prod Is this the production site.
 * @return bool         Whether we should treat this as an alternative production site.
 */
add_filter(
    'safe_staging_is_production',
    function( $is_prod ) {
        $alternative_prod_url = 'https://myothersite.com';

        if ( site_url() === $alternative_prod_url ) {
            $is_prod = true;
        }

        return $is_prod;
    }
);
```

= Can I let other emails get sent on staging sites? =

The filter `safe_staging_is_whitelist_email` will let you intervene just before an email is blocked.
For example, the filter below will let you support an alternative production URL.

```php
/**
 * Determine whether a particular email should be sent.
 *
 * In this case we test if the to recipient is our admin address.
 *
 * @param bool   $whitelisted Should the email actually send.
 * @param object $this        Instance of the Fake PHPMailer class.
 * @return bool               Whitelist value tested against the recipient.
 */
add_filter(
    'safe_staging_is_whitelist_email'
    function( $whitelisted, $phpmailer ) {
        if ( 'admin@mysite.com' === $phpmailer->getToAddresses() ) {
            $whitelisted = true;
        }

        return $whitelisted;
    },
    10,
    2
);
```

= Can I change the message that shows on the checkout page? =

The filter `safe_staging_checkout_notice` will let you override the message shown on the cart and checkout pages.

```php
/**
 * Change the warning message that gets displayed on the checkout page
 * of staging sites.
 *
 * @return string New message to show on the checkout page.
 */
add_filter(
    'safe_staging_checkout_notice',
    function() {
        return 'You\'ve found our staging site! You might want to go back to the production site.';
    }
)
```

== Upgrade notice ==

None needed.

== Changelog ==

= 0.2.4 =

* Clarifies readme code examples

= 0.2.3 =

* Adds support for CI / CD code management

= 0.2.1 =

* Bumps compatibility to 5.1.
* Adds uninstall file.

= 0.2 =

* Adds noindex tag to the staging site.
