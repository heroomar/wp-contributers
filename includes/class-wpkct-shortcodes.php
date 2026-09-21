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
class WPKCT_Shortcodes {

	/**
	 * Initializes the plugin shortcodes.
	 */
	public function __construct() {

		add_shortcode(
			'wpkct_contribution_form',
			array( $this, 'wpkct_render_form' )
		);

	}

	/**
	 * Renders the contribution submission form.
	 *
	 * @return string The rendered contribution form.
	 */
	public function wpkct_render_form() {

		ob_start();

		include WPKCT_PLUGIN_PATH . 'templates/contribution-form.php';

		return ob_get_clean();
	}

	
}
