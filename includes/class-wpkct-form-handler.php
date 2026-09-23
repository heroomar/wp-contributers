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
 * Handles frontend contribution form submissions.
 *
 * @package Contributors_Team
 */
class WPKCT_Form_Handler {

	/**
	 * Initializes the form submission handler.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'wpkct_handle_form_submission' ) );
	}

	/**
	 * Redirects the user back to the referring page with a status message.
	 *
	 * @param string $status  The status of the submission.
	 * @param string $message The message to display to the user.
	 *
	 * @return void
	 */
	private function wpkct_redirect_with_message( $status, $message ) {
		$redirect_url = add_query_arg(
			array(
				'wpkct_status' => $status,
				'message'      => $message,
			),
			wp_get_referer()
		);

		wp_safe_redirect( $redirect_url );
		exit;
	}

	/**
	 * Processes the contribution form submission.
	 *
	 * Validates the submitted data, verifies the security nonce,
	 * retrieves the WordPress.org profile, creates the contribution
	 * post, processes the optional screenshot, and sends an
	 * administrative notification email.
	 *
	 * @return void
	 */
	public function wpkct_handle_form_submission() {
		if ( ! isset( $_POST['wpkct_submit_form'] ) ) {
			return;
		}

		// Verify the form nonce before processing submitted data.
		if (
			! isset( $_POST['wpkct_nonce'] ) ||
			! wp_verify_nonce(
				sanitize_text_field( wp_unslash( $_POST['wpkct_nonce'] ) ),
				'wpkct_submit_contribution'
			)
		) {
			$this->wpkct_redirect_with_message(
				'error',
				__( 'Security verification failed.', 'contributors-team' )
			);
		}

		// Sanitize submitted form fields.
		$name = isset( $_POST['wpkct_name'] )
			? sanitize_text_field( wp_unslash( $_POST['wpkct_name'] ) )
			: '';

		$username = isset( $_POST['wpkct_wporg_username'] )
			? sanitize_text_field( wp_unslash( $_POST['wpkct_wporg_username'] ) )
			: '';

		$type = isset( $_POST['wpkct_contribution_type'] )
			? sanitize_text_field( wp_unslash( $_POST['wpkct_contribution_type'] ) )
			: '';

		$link = isset( $_POST['wpkct_contribution_link'] )
			? esc_url_raw( wp_unslash( $_POST['wpkct_contribution_link'] ) )
			: '';

		$title = isset( $_POST['wpkct_contribution_title'] )
			? sanitize_text_field( wp_unslash( $_POST['wpkct_contribution_title'] ) )
			: '';

		$time_spent = isset( $_POST['wpkct_time_spent'] )
			? sanitize_text_field( wp_unslash( $_POST['wpkct_time_spent'] ) )
			: '';

		$date = isset( $_POST['wpkct_date'] )
			? sanitize_text_field( wp_unslash( $_POST['wpkct_date'] ) )
			: '';

		// Validate required fields.
		if (
			empty( $name ) ||
			empty( $username ) ||
			empty( $type ) ||
			empty( $time_spent ) ||
			empty( $date )
		) {
			$this->wpkct_redirect_with_message(
				'error',
				__( 'Please fill all required fields.', 'contributors-team' )
			);
		}

		// Verify that the submitted WordPress.org username exists.
		$wp_org_profile = WPKCT_WordPress_Org::wpkct_fetch_profile( $username );

		if (
			true !== $wp_org_profile &&
			( ! is_array( $wp_org_profile ) || ! isset( $wp_org_profile['name'] ) )
		) {
			$this->wpkct_redirect_with_message(
				'error',
				__( 'WordPress.org profile could not be found. Please check the username.', 'contributors-team' )
			);
		}

		// Create the contribution as pending review.
		$post_id = wp_insert_post(
			array(
				'post_type'   => 'wpkct_contribution',
				'post_status' => 'pending',
				'post_title'  => $name . ' - ' . $type,
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			$this->wpkct_redirect_with_message(
				'error',
				$post_id->get_error_message()
			);
		}

		// Store the contribution metadata.
		update_post_meta( $post_id, '_wpkct_username', $username );
		update_post_meta( $post_id, '_wpkct_type', $type );
		update_post_meta( $post_id, '_wpkct_link', $link );
		update_post_meta( $post_id, '_wpkct_title', $title );
		update_post_meta( $post_id, '_wpkct_time_spent', $time_spent );
		update_post_meta( $post_id, '_wpkct_date', $date );

		// Process the optional contribution screenshot safely.
		if (
			isset( $_FILES['wpkct_screenshot'] ) &&
			is_array( $_FILES['wpkct_screenshot'] )
		) {
			$uploaded_file = array(
				'name'     => isset( $_FILES['wpkct_screenshot']['name'] )
					? sanitize_file_name( wp_unslash( $_FILES['wpkct_screenshot']['name'] ) )
					: '',
				'type'     => isset( $_FILES['wpkct_screenshot']['type'] )
					? sanitize_text_field( wp_unslash( $_FILES['wpkct_screenshot']['type'] ) )
					: '',
				'tmp_name' => isset( $_FILES['wpkct_screenshot']['tmp_name'] )
					? $_FILES['wpkct_screenshot']['tmp_name'] // <-- Keep raw tmp_name path intact!
					: '',
				'error'    => isset( $_FILES['wpkct_screenshot']['error'] )
					? absint( $_FILES['wpkct_screenshot']['error'] )
					: UPLOAD_ERR_NO_FILE,
				'size'     => isset( $_FILES['wpkct_screenshot']['size'] )
					? absint( $_FILES['wpkct_screenshot']['size'] )
					: 0,
			);

			if (
				UPLOAD_ERR_OK === $uploaded_file['error'] &&
				! empty( $uploaded_file['name'] ) &&
				! empty( $uploaded_file['tmp_name'] )
			) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
				require_once ABSPATH . 'wp-admin/includes/media.php';
				require_once ABSPATH . 'wp-admin/includes/image.php';

				$file_type = wp_check_filetype( $uploaded_file['name'] );

				$allowed_types = array(
					'jpg',
					'jpeg',
					'png',
					'webp',
				);

				// Validate the uploaded screenshot file type.
				if ( ! in_array( $file_type['ext'], $allowed_types, true ) ) {
					$this->wpkct_redirect_with_message(
						'error',
						__( 'Invalid file type.', 'contributors-team' )
					);
				}

				// Add the uploaded screenshot to the WordPress Media Library.
				$attachment_id = media_handle_sideload(
					$uploaded_file,
					$post_id
				);

				if ( is_wp_error( $attachment_id ) ) {
					$this->wpkct_redirect_with_message(
						'error',
						$attachment_id->get_error_message()
					);
				}

				update_post_meta(
					$post_id,
					'_wpkct_screenshot',
					$attachment_id
				);
			}
		}

		// Notify the site administrator about the new contribution.
		WPKCT_Mailer::wpkct_send_admin_email( $post_id );

		// Redirect the contributor after successful submission.
		$this->wpkct_redirect_with_message(
			'success',
			__( 'Contribution submitted successfully and is pending review.', 'contributors-team' )
		);
	}
}
