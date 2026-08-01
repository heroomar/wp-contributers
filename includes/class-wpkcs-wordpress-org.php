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
			'https://profiles.wordpress.org/wp-json/wporg/v1/users/' . $username
		);

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$body = json_decode(
			wp_remote_retrieve_body( $response ),
			true
		);

		if ( empty( $body ) ) {
			return false;
		}

		if (!is_array($body) || !isset($body['name'])){
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

		$raw_profile = wp_remote_get(
			'https://profiles.wordpress.org/' . $username
		);

		$raw_profile = explode("<h3>Bio</h3>",wp_remote_retrieve_body($raw_profile));

		if (isset($raw_profile[1])){
			$bio = explode("</div>",$raw_profile[1])[0];
			$body['bio'] = strip_tags($bio);
		}

		foreach ($body as $key => $value) {
			update_post_meta( $id, '_wpkcs_org_' . $key, $value );
		}


		$contributor = new WPKCS_Contributor( $id );

		if ($avatar_url = $contributor->get_avatar()){
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';
			require_once ABSPATH . 'wp-admin/includes/image.php';

			// Download image to temporary location.
			$tmp = download_url( "https:".$avatar_url );
            

			if (!is_wp_error( $tmp )) {
				$file = array(
					'name'     => rand(1111,9999)."_" . $contributor->get_username() . '.jpg',
					'tmp_name' => $tmp,
				);

				// Upload to media library.
				$attachment_id = media_handle_sideload( $file, $id );

				// Remove temp file on failure.
				if ( is_wp_error( $attachment_id ) ) {
					@unlink( $tmp );
					
				} else {
					// Set featured image.
					set_post_thumbnail( $id, $attachment_id );
				}
			}
		}
		

		return $body;
	}
}