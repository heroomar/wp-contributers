<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPKCS_Shortcodes {

	public function __construct() {

		add_shortcode(
			'wpkcs_contribution_form',
			array( $this, 'wpkcs_render_form' )
		);

		// add_shortcode(
		// 	'wpkcs_profile',
		// 	array( $this, 'wpkcs_render_profile' )
		// );

		// add_shortcode(
		// 	'wpkcs_contributors',
		// 	array( $this, 'wpkcs_render_contributors' )
		// );
	}

	public function wpkcs_render_form() {

		ob_start();

		include WPKCS_PLUGIN_PATH . 'templates/contribution-form.php';

		return ob_get_clean();
	}

	// public function wpkcs_render_profile( $atts ) {

	// 	$atts = shortcode_atts(
	// 		array(
	// 			'profile' => '',
	// 		),
	// 		$atts
	// 	);

	// 	ob_start();

	// 	$profile = sanitize_text_field( $atts['profile'] );

	// 	include WPKCS_PLUGIN_PATH . 'templates/contributor-profile.php';

	// 	return ob_get_clean();
	// }

	// public function wpkcs_render_contributors() {

	// 	$args = array(
	// 		'post_type'      => 'wpkcs_contributor',
	// 		'posts_per_page' => -1,
	// 		'post_status'    => 'publish',
	// 		'orderby'        => 'title',
	// 		'order'          => 'ASC',
	// 	);

	// 	$query = new WP_Query( $args );

	// 	ob_start();

	// 	include WPKCS_PLUGIN_PATH . 'templates/contributors-list.php';

	// 	return ob_get_clean();
	// }
}