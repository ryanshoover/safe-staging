<?php
/**
 * Creates an admin page to handle specific settings.
 *
 * @package safe-staging
 */

namespace SafeStaging;

/**
 * Define the class.
 */
class Admin_Page {

	/**
	 * Initialize the admin page.
	 */
	public static function init() {
		$self = new self();

		add_action( 'admin_menu', [ $self, 'add_page' ] );
		add_action( 'admin_init', [ $self, 'register_settings' ] );
	}

	/**
	 * Register our setting with WordPress.
	 */
	public function register_settings() {
		register_setting(
			SLUG,
			OPT_PROD_URL,
			[
				'type'              => 'string',
				'description'       => 'URL of production site',
				'sanitize_callback' => [ $this, 'encode_url' ],
			]
		);
	}

	/**
	 * Encode a URL using base64.
	 *
	 * This protects the production URL
	 * from search-replace functionality used on many staging systems
	 * that will replace the production url with the staging url.
	 *
	 * @param string $url URL that needs to be protected.
	 * @return string     Base 64 encoded version of the URL.
	 */
	public function encode_url( $url ) {
		// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
		return base64_encode( esc_url_raw( $url ) );
	}

	/**
	 * Register the options page.
	 */
	public function add_page() {
		add_options_page(
			__( 'Safe Staging', 'safe-staging' ),
			__( 'Safe Staging', 'safe-staging' ),
			'manage_options',
			SLUG,
			[ $this, 'render_page' ]
		);
	}

	/**
	 * Render the options page.
	 */
	public function render_page() {
		include PATH . 'templates/admin-page.php';
	}
}
