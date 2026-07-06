<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPKCS_Loader {

	public function run() {

		require_once WPKCS_PLUGIN_PATH . 'includes/class-wpkcs-post-types.php';
		require_once WPKCS_PLUGIN_PATH . 'includes/class-wpkcs-assets.php';
		require_once WPKCS_PLUGIN_PATH . 'includes/class-wpkcs-shortcodes.php';
		require_once WPKCS_PLUGIN_PATH . 'includes/class-wpkcs-form-handler.php';
		require_once WPKCS_PLUGIN_PATH . 'includes/class-wpkcs-admin.php';
		require_once WPKCS_PLUGIN_PATH . 'includes/class-wpkcs-wordpress-org.php';
		require_once WPKCS_PLUGIN_PATH . 'includes/class-wpkcs-mailer.php';
		require_once WPKCS_PLUGIN_PATH . 'includes/class-wpkcs-contributor.php';

		new WPKCS_Post_Types();
		new WPKCS_Assets();
		new WPKCS_Shortcodes();
		new WPKCS_Form_Handler();
		new WPKCS_Admin();
	}
}