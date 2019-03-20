<?php // phpcs:ignore WordPress.Files.Filename
/**
 * Test that the admin page settings operate correclty.
 *
 * @package safe-staging
 */

/**
 * Mock WooCommerce's functions.
 *
 * @return boolean true. always true.
 */
function WC() {
	return true;
}

/**
 * Mock WooCommerce's functions.
 *
 * @return boolean true. always true.
 */
function is_cart() {
	return true;
}

/**
 * Mock WooCommerce's functions.
 *
 * @param string $notice The standard notice to show.
 * @return string Common string from plugin.
 */
function wc_add_notice( $notice) {
	echo wp_kses_post( $notice );
}

/**
 * Define the RenderTest class and its tests.
 */
class NoticesTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * Set up the class.
	 */
	public function setUp() {
		parent::setUp();

		$this->notices = new \SafeStaging\Notices();
	}

	/**
	 * Test our admin setup notice.
	 */
	public function testAdminSetupNotice() {
		ob_start();
		$this->notices->do_admin_notices();
		$result = ob_get_clean();

		$this->assertContains(
			'Configure Safe Staging to protect your staging sites from accidental emails and orders.',
			$result
		);
	}

	/**
	 * Test our admin staging notice.
	 */
	public function testAdminStagingNotice() {
		// Filter the production URL value.
		add_filter(
			'safe_staging_production_url',
			function() {
				return 'http://ryan.hoover.ws';
			}
		);

		// Filter that this is a staging site.
		add_filter( 'safe_staging_is_production', '__return_false' );

		ob_start();
		$this->notices->do_admin_notices();
		$result = ob_get_clean();

		$this->assertContains(
			'Safe Staging has identified this as a non-production site and has disabled production features.',
			$result
		);
	}

	/**
	 * Test our staging notice in a WooCommerce site.
	 */
	public function testWooCommerceStagingNotice() {
		// Filter that this is a staging site.
		add_filter( 'safe_staging_is_production', '__return_false' );

		ob_start();
		$this->notices->do_admin_notices();
		$result = ob_get_clean();

		$this->assertContains(
			'Configure Safe Staging to protect your staging sites from accidental emails and orders.',
			$result
		);
	}
}
