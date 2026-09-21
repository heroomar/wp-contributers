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
 * Loads the plugin classes and initializes their functionality.
 *
 * @package Contributors_Team
 */
class WPKCT_Loader {

	/**
	 * Loads the required plugin files and initializes plugin components.
	 *
	 * @return void
	 */
	public function run() {

		require_once WPKCT_PLUGIN_PATH . 'includes/class-wpkcs-post-types.php';
		require_once WPKCT_PLUGIN_PATH . 'includes/class-wpkcs-assets.php';
		require_once WPKCT_PLUGIN_PATH . 'includes/class-wpkcs-shortcodes.php';
		require_once WPKCT_PLUGIN_PATH . 'includes/class-wpkcs-form-handler.php';
		require_once WPKCT_PLUGIN_PATH . 'includes/class-wpkcs-wordpress-org.php';
		require_once WPKCT_PLUGIN_PATH . 'includes/class-wpkcs-mailer.php';
		require_once WPKCT_PLUGIN_PATH . 'includes/class-wpkcs-contributor.php';
		require_once WPKCT_PLUGIN_PATH . 'includes/class-wpkcs-filters.php';

		new WPKCT_Post_Types();
		new WPKCT_Assets();
		new WPKCT_Shortcodes();
		new WPKCT_Form_Handler();
		new WPKCT_Admin();
		new WPKCT_Filters();
	}
}
