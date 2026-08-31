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
 * Registers and handles plugin shortcodes.
 *
 * @package Contributors_Team
 */
class WPKCS_Shortcodes {

	/**
	 * Initializes the plugin shortcodes.
	 */
	public function __construct() {

		add_shortcode(
			'wpkcs_contribution_form',
			array( $this, 'wpkcs_render_form' )
		);

	}

	/**
	 * Renders the contribution submission form.
	 *
	 * @return string The rendered contribution form.
	 */
	public function wpkcs_render_form() {

		ob_start();

		include WPKCS_PLUGIN_PATH . 'templates/contribution-form.php';

		return ob_get_clean();
	}

	
}
