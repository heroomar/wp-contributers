<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPKCS_Admin {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'wpkcs_admin_menu' ) );
	}

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

	public function wpkcs_dashboard_page() {

		echo '<div class="wrap">';
		echo '<h1>WP Kitchen Contributors</h1>';
		echo '</div>';
	}
}