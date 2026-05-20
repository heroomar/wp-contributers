<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPKCS_Mailer {

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