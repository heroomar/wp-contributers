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
 * Handles the Contributors Team administration functionality.
 *
 * @package Contributors_Team
 */
class WPKCS_Admin {

	/**
	 * Initializes the admin hooks.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'wpkcs_admin_menu' ) );
	}

	/**
	 * Registers the Contributors Team admin menu page.
	 *
	 * @return void
	 */
	public function wpkcs_admin_menu() {

		add_menu_page(
			'WP Kitchen Contributors',
			'WP Kitchen Contributors',
			'manage_options',
			'wpkcs-dashboard',
			array( $this, 'wpkcs_dashboard_page' ),
			'dashicons-groups'
		);
	}

	/**
	 * Displays the Contributors Team dashboard page.
	 *
	 * @return void
	 */
	public function wpkcs_dashboard_page() {

		echo '<div class="wrap">';
		echo '<h1>WP Kitchen Contributors</h1>';
		echo '</div>';
	}
}
