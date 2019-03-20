<?php // phpcs:ignore WordPress.Files.Filename
/**
 * Test that the protection methods return expected results.
 *
 * @package safe-staging
 */

/**
 * Define the RenderTest class and its tests.
 */
class ProtectionTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * Set up the class.
	 */
	public function setUp() {
		parent::setUp();

		// This class always tests on presumed staging sites.
		add_filter( 'safe_staging_is_production', '__return_false' );
	}

	/**
	 * Test that we get our expected payment gateways
	 * that will be enabled.
	 */
	public function testDisablePaymentGateways() {
		$protection = new SafeStaging\Protection();

		$gateways = $protection->disable_payment_gateways( [] );

		$this->assertArraySubset(
			$gateways,
			[
				'WC_Gateway_BACS',
				'WC_Gateway_Cheque',
				'WC_Gateway_COD',
				'WC_Stripe_Subs_Compat',
			]
		);
	}

	/**
	 * Test that we're accurately replacing the PHP Mailer
	 * with our Fake PHP Mailer.
	 */
	public function testReplaceMailer() {
		global $phpmailer;

		// phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
		$phpmailer = new \PHPMailer();

		\SafeStaging\Protection::hooks();

		$this->assertTrue( is_a( $phpmailer, 'SafeStaging\Fake_PHPMailer' ) );
	}

	/**
	 * Test that the noindex file is appropriately added to the head.
	 */
	public function testNoRobots() {
		// Set up our hooks.
		\SafeStaging\Protection::hooks();

		// Get the output of `wp_head`
		ob_start();
		do_action( 'wp_head' );
		$result = ob_get_clean();

		$this->assertContains( "<meta name='robots' content='noindex,follow' />", $result );
	}
}
