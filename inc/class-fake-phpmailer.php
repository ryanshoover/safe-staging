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
			if ( ! $this->preSend() ) {
				return false;
			}

			return true;
		} catch ( \phpmailerException $e ) {
			return false;
		}
	}
}
