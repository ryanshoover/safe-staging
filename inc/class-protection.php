<?php
/**
 * Primary protection class for staging sites.
 *
 * @package safe-staging
 */

namespace SafeStaging;

/**
 * Define the protection class.
 */
class Protection {

	/**
	 * Hook into WordPress
	 */
	public static function hooks() {
		if ( self::is_production() ) {
			return;
		}

		$self = new self();

		// Stop all emails.
		$self->replace_mailer();

		// Remove all but most basic payment gateways.
		add_filter( 'woocommerce_payment_gateways', [ $self, 'disable_payment_gateways' ], 999999 );

		// Force Stripe to Test mode.
		add_filter( 'option_woocommerce_stripe_settings', [ $self, 'force_stripe_test_mode' ] );

		// Tell WooCommerce Subscriptions this is a test site.
		add_filter( 'woocommerce_subscriptions_is_duplicate_site', '__return_true' );
	}

	/**
	 * Is this the production website?
	 *
	 * @return boolean True if the site is the production site.
	 */
	public static function is_production() {
		$is_prod  = false;
		$prod_url = production_url();
		$curr_url = site_url();
		$rgx      = '/https?\:\/\/(?:www\.)?/';

		$prod_url = preg_replace( $rgx, '', $prod_url );
		$curr_url = preg_replace( $rgx, '', $curr_url );

		// Test if we don't have a prod url or if we're on the production site
		if ( empty( $prod_url ) || $prod_url === $curr_url ) {
			$is_prod = true;
		}

		return apply_filters( 'safe_staging_is_production', $is_prod );
	}

	/**
	 * Replace WordPress's PHPMailer with our fake version.
	 *
	 * @global object $phpmailer PHPMailer class.
	 */
	protected function replace_mailer() {
		global $phpmailer;

		// phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
		$phpmailer = new Fake_PHPMailer();
	}

	/**
	 * Disable all but the core manual payment gateways
	 *
	 * @param array $methods All payment gateways.
	 * @return array         Only safe payment gateways.
	 */
	public function disable_payment_gateways( $methods ) {
		$methods = [
			'WC_Gateway_BACS', // Bank account transfer
			'WC_Gateway_Cheque', // Check
			'WC_Gateway_COD', // Cash on delivery
			'WC_Stripe_Subs_Compat', // Stripe basic
		];

		return $methods;
	}

	/**
	 * Force WooCommerce Stripe test mode
	 *
	 * @param array $options Stripe options.
	 * @return array         Stripe options with testmode on.
	 */
	public function force_stripe_test_mode( $options ) {
		$options['testmode'] = 'yes';

		return $options;
	}
}
