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
 * Handles plugin asset registration and enqueuing.
 *
 * @package Contributors_Team
 */
class WPKCS_Assets {

	/**
	 * Initializes the asset-related hooks.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'wpkcs_enqueue_assets' ) );
	}

	/**
	 * Enqueues the plugin's frontend styles and scripts.
	 *
	 * @return void
	 */
	public function wpkcs_enqueue_assets() {

		wp_enqueue_style(
			'wpkcs-style',
			WPKCS_PLUGIN_URL . 'assets/css/wpkcs-style.css',
			array(),
			WPKCS_VERSION
		);

		wp_enqueue_script(
			'wpkcs-script',
			WPKCS_PLUGIN_URL . 'assets/js/wpkcs-script.js',
			array( 'jquery' ),
			WPKCS_VERSION,
			true
		);
	}
}
