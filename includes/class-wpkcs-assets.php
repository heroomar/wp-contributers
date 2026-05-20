<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPKCS_Assets {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'wpkcs_enqueue_assets' ) );
	}

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