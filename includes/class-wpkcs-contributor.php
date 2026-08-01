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
		return get_post_meta( $this->post_id , '_wpkcs_org_bio', true ); 	
	}

	public function full_name(){
		return get_post_meta( $this->post_id , '_wpkcs_org_name', true ); 	
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


	function get_user_contributions($posts_per_page = 5)
	{
		$types = array(

            'Code Contribution' => 'CODE',

            'Learn WordPress' => 'LEARN',

            'Meetup' => 'MEETUPS',

            'Photos Contribution' => 'PHOTOS',

            'Translation' => 'TRANSLATIONS',

            'Support Forum' => 'SUPPORT FORUM',

            'Documentation' => 'DOCUMENTATION',

            'Other' => 'OTHER',

        );

		$contributions = array();

		foreach($types as $type => $name){
			$contribution = [
				'name' => $name,
				'type' => $type,
				'data' => []
			];
			$query = new WP_Query(array(
				'post_type'      => 'wpkcs_contribution',
				'posts_per_page' => $posts_per_page,
				'orderby'        => 'date',
				'order'          => 'DESC',
				'meta_query'     => array(
					'relation' => 'AND',
					array(
						'key'   => '_wpkcs_username',
						'value' => $this->get_username(),
					),
					array(
						'key'   => '_wpkcs_type',
						'value' => $type,
					),
				),
			));

			if ($query->have_posts()) {
				while ($query->have_posts()) {
					$query->the_post();

					$contribution['data'][] = array(
						'ID'      => get_the_ID(),
						'title'   => get_the_title(),
						'date'    => get_the_date(),
						// 'content' => get_the_content(),
						// 'post'    => get_post(),
					);
				}
				wp_reset_postdata();
				$contributions[]=$contribution;
			}
			
		}

		return $contributions;
	}


	
}