<?php

/**
 * Prevent direct access to this file.
 *
 * @package Contributors_Team
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles email notifications for contributor submissions.
 *
 * @package Contributors_Team
 */
class WPKCS_Mailer {

	/**
	 * Sends an email notification to the site administrator
	 * when a new contribution is submitted.
	 *
	 * @param int $post_id Contribution post ID.
	 *
	 * @return bool Whether the email was successfully sent.
	 */
	public static function wpkcs_send_admin_email( $post_id ) {

		$admin_email = get_option( 'admin_email' );

		$subject = 'New Contribution Submitted';

		$message = 'A new contribution has been submitted.';

		return wp_mail(
			$admin_email,
			$subject,
			$message
		);
	}
}
