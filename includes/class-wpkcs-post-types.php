<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPKCS_Post_Types {

	public function __construct() {
		add_action( 'init', array( $this, 'wpkcs_register_post_types' ) );
	}

	public function wpkcs_register_post_types() {

		register_post_type(
			'wpkcs_contribution',
			array(
				'label'       => 'Contributions',
				'public'      => true,
				'supports'    => array( 'title', 'editor', 'thumbnail' ),
				'show_in_rest'=> true,
			)
		);

		register_post_type(
			'wpkcs_contributor',
			array(
				'label'       => 'Contributor Profiles',
				'public'      => true,
				'supports'    => array( 'title', 'editor', 'thumbnail' ),
				'show_in_rest'=> true,
			)
		);
	}
}