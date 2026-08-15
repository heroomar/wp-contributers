<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPKCS_Form_Handler {

	public function __construct() {
		add_action( 'init', array( $this, 'wpkcs_handle_form_submission' ) );
	}

	private function wpkcs_redirect_with_message( $status, $message ) {
		$redirect_url = add_query_arg(
			array(
				'wpkcs_status' => $status,
				'message'      => $message,
			),
			wp_get_referer()
		);

		wp_safe_redirect( $redirect_url );
		exit;
	}

	public function wpkcs_handle_form_submission() {
		if ( ! isset( $_POST['wpkcs_submit_form'] ) ) {
			return;
		}

		if (
			! isset( $_POST['wpkcs_nonce'] ) ||
			! wp_verify_nonce(
				sanitize_text_field( wp_unslash( $_POST['wpkcs_nonce'] ) ),
				'wpkcs_submit_contribution'
			)
		) {
			$this->wpkcs_redirect_with_message(
				'error',
				__( 'Security verification failed.', 'wp-contributers' )
			);
		}

		$name = isset( $_POST['wpkcs_name'] )
			? sanitize_text_field( wp_unslash( $_POST['wpkcs_name'] ) )
			: '';

		$username = isset( $_POST['wpkcs_wporg_username'] )
			? sanitize_text_field( wp_unslash( $_POST['wpkcs_wporg_username'] ) )
			: '';

		$type = isset( $_POST['wpkcs_contribution_type'] )
			? sanitize_text_field( wp_unslash( $_POST['wpkcs_contribution_type'] ) )
			: '';

		$link = isset( $_POST['wpkcs_contribution_link'] )
			? esc_url_raw( wp_unslash( $_POST['wpkcs_contribution_link'] ) )
			: '';

		$title = isset( $_POST['wpkcs_contribution_title'] )
			? sanitize_text_field( wp_unslash( $_POST['wpkcs_contribution_title'] ) )
			: '';

		$time_spent = isset( $_POST['wpkcs_time_spent'] )
			? sanitize_text_field( wp_unslash( $_POST['wpkcs_time_spent'] ) )
			: '';

		$date = isset( $_POST['wpkcs_date'] )
			? sanitize_text_field( wp_unslash( $_POST['wpkcs_date'] ) )
			: '';

		if (
			empty( $name ) ||
			empty( $username ) ||
			empty( $type ) ||
			empty( $time_spent ) ||
			empty( $date )
		) {
			$this->wpkcs_redirect_with_message(
				'error',
				__( 'Please fill all required fields.', 'wp-contributers' )
			);
		}

		$wp_org_profile = WPKCS_WordPress_Org::wpkcs_fetch_profile( $username );

		if (
			true !== $wp_org_profile &&
			( ! is_array( $wp_org_profile ) || ! isset( $wp_org_profile['name'] ) )
		) {
			$this->wpkcs_redirect_with_message(
				'error',
				__( 'WordPress.org profile could not be found. Please check the username.', 'wp-contributers' )
			);
		}

		$post_id = wp_insert_post(
			array(
				'post_type'   => 'wpkcs_contribution',
				'post_status' => 'pending',
				'post_title'  => $name . ' - ' . $type,
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			$this->wpkcs_redirect_with_message(
				'error',
				$post_id->get_error_message()
			);
		}

		update_post_meta( $post_id, '_wpkcs_username', $username );
		update_post_meta( $post_id, '_wpkcs_type', $type );
		update_post_meta( $post_id, '_wpkcs_link', $link );
		update_post_meta( $post_id, '_wpkcs_title', $title );
		update_post_meta( $post_id, '_wpkcs_time_spent', $time_spent );
		update_post_meta( $post_id, '_wpkcs_date', $date );

		if (
			isset( $_FILES['wpkcs_screenshot'] ) &&
			is_array( $_FILES['wpkcs_screenshot'] )
		) {
			$uploaded_file = array(
				'name'     => isset( $_FILES['wpkcs_screenshot']['name'] )
					? sanitize_file_name( wp_unslash( $_FILES['wpkcs_screenshot']['name'] ) )
					: '',
				'type'     => isset( $_FILES['wpkcs_screenshot']['type'] )
					? sanitize_text_field( wp_unslash( $_FILES['wpkcs_screenshot']['type'] ) )
					: '',
				'tmp_name' => isset( $_FILES['wpkcs_screenshot']['tmp_name'] )
					? sanitize_text_field( wp_unslash( $_FILES['wpkcs_screenshot']['tmp_name'] ) )
					: '',
				'error'    => isset( $_FILES['wpkcs_screenshot']['error'] )
					? absint( $_FILES['wpkcs_screenshot']['error'] )
					: UPLOAD_ERR_NO_FILE,
				'size'     => isset( $_FILES['wpkcs_screenshot']['size'] )
					? absint( $_FILES['wpkcs_screenshot']['size'] )
					: 0,
			);

			if (
				UPLOAD_ERR_OK === $uploaded_file['error'] &&
				! empty( $uploaded_file['name'] )
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

				if ( ! in_array( $file_type['ext'], $allowed_types, true ) ) {
					$this->wpkcs_redirect_with_message(
						'error',
						__( 'Invalid file type.', 'wp-contributers' )
					);
				}

				$attachment_id = media_handle_sideload(
					$uploaded_file,
					$post_id
				);

				if ( is_wp_error( $attachment_id ) ) {
					$this->wpkcs_redirect_with_message(
						'error',
						$attachment_id->get_error_message()
					);
				}

				update_post_meta(
					$post_id,
					'_wpkcs_screenshot',
					$attachment_id
				);
			}
		}

		WPKCS_Mailer::wpkcs_send_admin_email( $post_id );

		$this->wpkcs_redirect_with_message(
			'success',
			__( 'Contribution submitted successfully and is pending review.', 'wp-contributers' )
		);
	}
}