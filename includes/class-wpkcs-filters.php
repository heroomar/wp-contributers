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
 * Handles custom template filters for contributor post types.
 *
 * @package Contributors_Team
 */
class WPKCS_Filters {

	/**
	 * Initializes the template-related filters.
	 */
	public function __construct() {
		add_filter( 'single_template', array( $this, 'wpkcs_contributor_single_template' ) );
		add_filter( 'archive_template', array( $this, 'wpkcs_contributor_archive_template' ) );
	}

	/**
	 * Loads the plugin template for individual contributor pages.
	 *
	 * @param string $template The default single post template.
	 *
	 * @return string The template path to use.
	 */
	public function wpkcs_contributor_single_template( $template ) {

		if ( is_singular( 'wpkcs_contributor' ) ) {

			$plugin_template = WPKCS_PLUGIN_PATH . 'templates/single-contributor.php';

			if ( file_exists( $plugin_template ) ) {
				return $plugin_template;
			}
		}

		return $template;
	}

	/**
	 * Loads the plugin template for the contributor archive page.
	 *
	 * @param string $template The default archive template.
	 *
	 * @return string The template path to use.
	 */
	public function wpkcs_contributor_archive_template( $template ) {

		if ( is_post_type_archive( 'wpkcs_contributor' ) ) {

			$plugin_template = WPKCS_PLUGIN_PATH . 'templates/archive-contributor.php';

			if ( file_exists( $plugin_template ) ) {
				return $plugin_template;
			}
		}

		return $template;
	}
}
