<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPKCS_WordPress_Org {

	public static function wpkcs_fetch_profile( $username ) {

		$existing = get_page_by_title(
			$username,
			OBJECT,
			'wpkcs_contributor'
		);

		if ( $existing ) {
			return true;
		}

		$response = wp_remote_get(
			'https://profiles.wordpress.org/wp-json/wporg/v1/users/' . rawurlencode( $username )
		);

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$body = json_decode(
			wp_remote_retrieve_body( $response ),
			true
		);

		if ( empty( $body ) || ! is_array( $body ) || ! isset( $body['name'] ) ) {
			return false;
		}

		$id = wp_insert_post(
			array(
				'post_type'    => 'wpkcs_contributor',
				'post_status'  => 'publish',
				'post_title'   => sanitize_text_field( $username ),
				'post_content' => wp_kses_post( $body['description'] ?? '' ),
			)
		);

		if ( is_wp_error( $id ) || ! $id ) {
			return false;
		}

		$raw_profile_response = wp_remote_get(
			'https://profiles.wordpress.org/' . rawurlencode( $username )
		);

		if ( ! is_wp_error( $raw_profile_response ) ) {
			$raw_profile = explode(
				'<h3>Bio</h3>',
				wp_remote_retrieve_body( $raw_profile_response )
			);

			if ( isset( $raw_profile[1] ) ) {
				$bio = explode( '</div>', $raw_profile[1] )[0];
				$body['bio'] = sanitize_text_field( wp_strip_all_tags( $bio ) );
			}
		}

		foreach ( $body as $key => $value ) {
			update_post_meta(
				$id,
				'_wpkcs_org_' . sanitize_key( $key ),
				$value
			);
		}

		$contributor = new WPKCS_Contributor( $id );

		$avatar_url = $contributor->get_avatar();

		if ( $avatar_url ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';
			require_once ABSPATH . 'wp-admin/includes/image.php';

			$avatar_url = ( strpos( $avatar_url, '//' ) === 0 )
				? 'https:' . $avatar_url
				: $avatar_url;

			$tmp = download_url( esc_url_raw( $avatar_url ) );

			if ( ! is_wp_error( $tmp ) ) {
				$file = array(
					'name'     => wp_unique_filename(
						wp_upload_dir()['path'],
						sanitize_file_name(
							$contributor->get_username() . '.jpg'
						)
					),
					'tmp_name' => $tmp,
				);

				$attachment_id = media_handle_sideload( $file, $id );

				if ( is_wp_error( $attachment_id ) ) {
					wp_delete_file( $tmp );
				} else {
					set_post_thumbnail( $id, $attachment_id );
				}
			}
		}

		return $body;
	}
}