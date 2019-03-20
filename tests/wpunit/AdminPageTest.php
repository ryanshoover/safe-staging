<?php // phpcs:ignore WordPress.Files.Filename
/**
 * Test that the admin page settings operate correclty.
 *
 * @package safe-staging
 */

/**
 * Define the RenderTest class and its tests.
 */
class AdminPageTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * Set up the class.
	 */
	public function setUp() {
		parent::setUp();

		$this->admin_page = new \SafeStaging\Admin_Page();
	}

	/**
	 * Test that we get our expected payment gateways
	 * that will be enabled.
	 */
	public function testProductionUrlGetsEncoded() {
		$encoded_url = $this->admin_page->encode_url( 'http://ryan.hoover.ws' );

		$this->assertEquals( 'aHR0cDovL3J5YW4uaG9vdmVyLndz', $encoded_url );
	}

	/**
	 * Test that the Admin Page settings
	 * contain the production URL
	 *
	 * @return void
	 */
	public function testAdminPageRenders() {
		// Filter the production URL value.
		add_filter(
			'safe_staging_production_url',
			function() {
				return 'http://ryan.hoover.ws';
			}
		);

		// Get template value.
		ob_start();
		$this->admin_page->render_page();
		$results = ob_get_clean();

		$this->assertContains(
			'value="http://ryan.hoover.ws"',
			$results
		);
	}
}
