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
function WC() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
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

		$this->testAdminNotice( 'Configure Safe Staging to protect your staging sites from accidental emails and orders.' );
	}

	/**
	 * Test our admin staging notice.
	 */
	public function testAdminStagingNotice() {
		// Filter the production URL.
		add_filter(
			'safe_staging_production_url',
			function() {
				return 'http://wordpress.local';
			}
		);

		// Filter that this is a staging site.
		add_filter( 'safe_staging_is_production', '__return_false' );

		ob_start();
		$this->notices->do_admin_notices();
		$result = ob_get_clean();

		$this->testAdminNotice( 'This is a staging or local site. Production features are disabled.' );
	}

	/**
	 * Test our staging notice in a WooCommerce site.
	 */
	public function testAdminProductionNotice() {
		// Filter the production URL.
		add_filter(
			'safe_staging_production_url',
			function() {
				return 'http://wordpress.local';
			}
		);

		// Filter that this is a staging site.
		add_filter( 'safe_staging_is_production', '__return_true' );

		$this->testAdminNotice( 'This is a production site. Any changes will immediately go live.' );
	}

	/**
	 * Test if the right admin notice shows given conditions we set up.
	 *
	 * @param string $message Message to look for.
	 */
	protected function testAdminNotice( $message ) {
		ob_start();
		$this->notices->do_admin_notices();
		$result = ob_get_clean();

		$this->assertContains(
			$message,
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
		$this->notices->do_checkout_notice();
		$result = ob_get_clean();

		$this->assertContains(
			'This is a staging site. Please make all purchases at the',
			$result
		);
	}
}
