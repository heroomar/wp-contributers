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

		foreach ($body as $key => $value) {
			update_post_meta( $id, '_wpkcs_org_' . $key, $value );
		}

		return $body;
	}
}