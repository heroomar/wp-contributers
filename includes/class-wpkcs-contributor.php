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
 * Represents a contributor and provides access to contributor data.
 *
 * @package Contributors_Team
 */
class WPKCS_Contributor {

	/**
	 * Contributor post ID.
	 *
	 * @var int|null
	 */
	protected $post_id = null;

	/**
	 * Initializes the contributor object.
	 *
	 * @param int $id Contributor post ID.
	 */
	public function __construct( $id ) {
		$this->post_id = absint( $id );
	}

	/**
	 * Update the `s` query parameter in a URL.
	 *
	 * @param string $url  The original URL.
	 * @param int    $size The new size value.
	 *
	 * @return string Updated URL.
	 */
	private function update_avatar_size( $url, $size = 350 ) {
		if ( empty( $url ) ) {
			return $url;
		}

		$decoded_url = html_entity_decode( $url );
		$parts       = wp_parse_url( $decoded_url );

		if ( empty( $parts['query'] ) ) {
			return $url;
		}

		parse_str( $parts['query'], $query );

		$query['s']    = absint( $size );
		$parts['query'] = http_build_query( $query );

		$new_url = ( isset( $parts['scheme'] ) ? $parts['scheme'] . '://' : '' )
			. ( $parts['host'] ?? '' )
			. ( isset( $parts['port'] ) ? ':' . $parts['port'] : '' )
			. ( $parts['path'] ?? '' )
			. '?' . $parts['query']
			. ( isset( $parts['fragment'] ) ? '#' . $parts['fragment'] : '' );

		return esc_url( $new_url );
	}

	/**
	 * Get the contributor username.
	 *
	 * @return string Contributor username.
	 */
	public function get_username() {
		return get_post_meta(
			$this->post_id,
			'_wpkcs_org_slug',
			true
		);
	}

	/**
	 * Get the contributor avatar URL.
	 *
	 * @param int $size Requested avatar size.
	 *
	 * @return string Contributor avatar URL.
	 */
	public function get_avatar( $size = 350 ) {
		$avatar_urls = get_post_meta(
			$this->post_id,
			'_wpkcs_org_avatar_urls',
			true
		);

		$url = is_array( $avatar_urls ) ? ( $avatar_urls[96] ?? '' ) : '';

		return $this->update_avatar_size( $url, $size );
	}

	/**
	 * Get the contributor biography.
	 *
	 * @return string Contributor biography.
	 */
	public function get_bio() {
		return get_post_meta(
			$this->post_id,
			'_wpkcs_org_bio',
			true
		);
	}

	/**
	 * Get the contributor's full name.
	 *
	 * @return string Contributor full name.
	 */
	public function full_name() {
		return get_post_meta(
			$this->post_id,
			'_wpkcs_org_name',
			true
		);
	}

	/**
	 * Get the total number of contributions for the contributor.
	 *
	 * @return int Number of contributions.
	 */
	public function get_cotribution_count() {
		$contributions = new WP_Query(
			array(
				'post_type'      => 'wpkcs_contribution',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'meta_query'     => array(
					array(
						'key'   => '_wpkcs_username',
						'value' => $this->get_username(),
					),
				),
			)
		);

		return absint( $contributions->found_posts );
	}

	/**
	 * Get the contributor's recent contributions grouped by type.
	 *
	 * @param int $posts_per_page Number of contributions to retrieve per type.
	 *
	 * @return array Contributor contributions grouped by type.
	 */
	public function get_user_contributions( $posts_per_page = 5 ) {
		$types = array(
			'Code Contribution'   => __( 'CODE', 'contributors-team' ),
			'Learn WordPress'     => __( 'LEARN', 'contributors-team' ),
			'Meetup'              => __( 'MEETUPS', 'contributors-team' ),
			'Photos Contribution' => __( 'PHOTOS', 'contributors-team' ),
			'Translation'         => __( 'TRANSLATIONS', 'contributors-team' ),
			'Support Forum'       => __( 'SUPPORT FORUM', 'contributors-team' ),
			'Documentation'       => __( 'DOCUMENTATION', 'contributors-team' ),
			'Other'               => __( 'OTHER', 'contributors-team' ),
		);

		$contributions = array();

		foreach ( $types as $type => $name ) {
			$contribution = array(
				'name' => $name,
				'type' => $type,
				'data' => array(),
			);

			$query = new WP_Query(
				array(
					'post_type'      => 'wpkcs_contribution',
					'posts_per_page' => absint( $posts_per_page ),
					'meta_key'       => '_wpkcs_date',
					'orderby'        => 'meta_value',
					'meta_type'      => 'DATE',
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
				)
			);

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();

					$contribution['data'][] = array(
						'ID'    => get_the_ID(),
						'title' => get_the_title(),
						'date'  => get_the_date(),
					);
				}

				wp_reset_postdata();
				$contributions[] = $contribution;
			}
		}

		return $contributions;
	}
}
