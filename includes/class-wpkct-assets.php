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
class WPKCT_Assets {

	/**
	 * Initializes the asset-related hooks.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'wpkct_enqueue_assets' ) );
	}

	/**
	 * Enqueues the plugin's frontend styles and scripts.
	 *
	 * @return void
	 */
	public function wpkct_enqueue_assets() {

		wp_enqueue_style(
			'wpkcs-style',
			WPKCT_PLUGIN_URL . 'assets/css/wpkcs-style.css',
			array(),
			WPKCT_VERSION
		);

		wp_enqueue_script(
			'wpkcs-script',
			WPKCT_PLUGIN_URL . 'assets/js/wpkcs-script.js',
			array( 'jquery' ),
			WPKCT_VERSION,
			true
		);
	}
}
