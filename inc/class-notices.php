<?php
/**
 * Add notices to WordPress.
 *
 * @package safe-staging
 */

namespace SafeStaging;

/**
 * Define the Notices class.
 */
class Notices {

	/**
	 * Hook into WordPress and WooCommerce.
	 */
	public static function hooks() {
		$self = new self();

		add_action( 'admin_notices', [ $self, 'do_admin_notices' ] );
		add_action( 'wp_ajax_safe-staging-setup-dismiss', [ $self, 'dismiss_setup_notice' ] );

		add_action( 'wp_head', [ $self, 'do_checkout_notice' ] );
	}

	/**
	 * Render any admin notices.
	 */
	public function do_admin_notices() {
		$prod_url           = production_url();
		$is_setup_dismissed = get_user_meta( get_current_user_id(), SLUG . '_notice_setup_dismiss' );

		if ( empty( $prod_url ) && empty( $is_setup_dismissed ) ) {
			include PATH . 'templates/setup-notice.php';
		}

		if ( ! Protection::is_production() ) {
			include PATH . 'templates/staging-notice.php';
		}
	}

	/**
	 * Exempt the user from the setup notice.
	 */
	public function dismiss_setup_notice() {
		update_user_meta( get_current_user_id(), SLUG . '_notice_setup_dismiss', true );
	}

	/**
	 * Render a notice on WooCommerce cart and checkout pages.
	 */
	public function do_checkout_notice() {
		if ( Protection::is_production() || ! function_exists( 'WC' ) ) {
			return;
		}

		if ( ! is_cart() && ! is_checkout() ) {
			return;
		}

		$notice = sprintf(
			'%s <a href="%s">%s</a>',
			__( 'This is a staging site. Please make all purchases at the', 'safe-staging' ),
			production_url(),
			__( 'production site', 'safe-staging' )
		);

		wc_add_notice( $notice, 'error' );
	}
}
