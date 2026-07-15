<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPKCS_Filters {

	public function __construct() {

		add_filter( 'single_template', array($this, 'wpkcs_contributor_single_template') );
		add_filter( 'archive_template', array($this, 'wpkcs_contributor_archive_template') );
	}

	function wpkcs_contributor_single_template( $template ) {

		if ( is_singular( 'wpkcs_contributor' ) ) {
			

			$plugin_template = WPKCS_PLUGIN_PATH . 'templates/single-contributor.php';
			

			if ( file_exists( $plugin_template ) ) {
				
				return $plugin_template;
			}
		}

		return $template;
	}


	function wpkcs_contributor_archive_template( $template ) {

		if ( is_post_type_archive( 'wpkcs_contributor' ) ) {

			$plugin_template = WPKCS_PLUGIN_PATH . 'templates/archive-contributor.php';

			if ( file_exists( $plugin_template ) ) {
				return $plugin_template;
			}
		}

		return $template;
	}
}