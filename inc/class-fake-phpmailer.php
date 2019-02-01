<?php
/**
 * Subclass of PHPMailer to prevent Sending.
 *
 * This subclass of PHPMailer replaces the send() method
 * with a method that does not send.
 * This subclass is based on the Fe_Stop_Emails_Fake_PHPMailer class
 * in https://wordpress.org/plugins/stop-emails
 *
 * @since 1.0
 * @see PHPMailer
 * @package safe-staging
 */

namespace SafeStaging;

// Load PHPMailer class, so we can subclass it.
require_once ABSPATH . WPINC . '/class-phpmailer.php';

/**
 * Fake PHPMailer to stop sending emails.
 */
class Fake_PHPMailer extends \PHPMailer {

	/**
	 * Determine if the email is a whitelisted one.
	 *
	 * Only some emails from WordPress should actually be sent.
	 * Check if the current is email is one of those.
	 *
	 * @return bool Should we send the email.
	 */
	protected function is_whitelist_email() {
		$whitelisted = false;

		// Is this the Lost Password email?
		if ( did_action( 'lostpassword_post' ) ) {
			$whitelisted = true;
		}

		return apply_filters( 'safe_staging_is_whitelist_email', $whitelisted, $this );
	}

	/**
	 * Replacement send() method that does not send.
	 *
	 * Unlike the PHPMailer send method,
	 * this method never calls the method postSend(),
	 * which is where the email is actually sent
	 *
	 * @since 0.8.0
	 * @return bool
	 */
	public function send() {
		try {
			// If this email is whitelisted, send it.
			if ( $this->is_whitelist_email() ) {
				return parent::send();
			}

			// If preSend failed, fail this function.
			if ( ! $this->preSend() ) {
				return false;
			}

			// Claim we sent the email.
			return true;
		} catch (phpmailerException $exc) {
			return false;
		}
	}
}
