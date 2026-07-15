<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPKCS_Contributor {

	protected $post_id = null;
	public function __construct($id) {
		$this->post_id = $id;
	}

	
	/**
	 * Update the `s` query parameter in a URL.
	 *
	 * @param string $url  The original URL.
	 * @param int    $size The new size value.
	 *
	 * @return string
	 */
	private function update_avatar_size( $url, $size = 350 ) {
		if ( empty( $url ) ) {
			return $url;
		}

		// Decode HTML entities like &#038; to &
		$decoded_url = html_entity_decode( $url );

		$parts = wp_parse_url( $decoded_url );

		if ( empty( $parts['query'] ) ) {
			return $url;
		}

		parse_str( $parts['query'], $query );

		// Update the size parameter.
		$query['s'] = absint( $size );

		// Rebuild the query string.
		$parts['query'] = http_build_query( $query );

		$new_url = ( isset( $parts['scheme'] ) ? $parts['scheme'] . '://' : '' ) .
			( $parts['host'] ?? '' ) .
			( isset( $parts['port'] ) ? ':' . $parts['port'] : '' ) .
			( $parts['path'] ?? '' ) .
			'?' . $parts['query'] .
			( isset( $parts['fragment'] ) ? '#' . $parts['fragment'] : '' );

		// Convert & back to &#038; for WordPress output.
		return esc_url( $new_url );
	}
	

	public function get_username(){
		return 	get_post_meta(
					$this->post_id,
					'_wpkcs_org_slug',
					true
				);
	}

	public function get_avatar($size = 350){
		$url = get_post_meta(
					$this->post_id,
					"_wpkcs_org_avatar_urls"
				)[0][96] ?? '';
		$url = $this->update_avatar_size( $url, $size );
		return $url;
	}

	public function get_bio(){
		return get_post_field( 'post_content', $this->post_id ); 	
	}

	public function get_cotribution_count(){
		$contributions = new WP_Query(
                array(
                    'post_type'      => 'wpkcs_contribution',
                    'posts_per_page' => -1,
                    'meta_query'     => array(
                        array(
                            'key'   => '_wpkcs_username',
                            'value' => $this->get_username(),
                        ),
                    ),
                )
            );

        return $contributions->found_posts;
	}

	
}