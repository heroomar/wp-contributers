<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPKCS_Post_Types {

	public function __construct() {
		add_action( 'init', array( $this, 'wpkcs_register_post_types' ) );
		add_action( 'post_edit_form_tag', function () {
			echo ' enctype="multipart/form-data"';
		} );
	}

	public function wpkcs_register_post_types() {

		register_post_type(
			'wpkcs_contribution',
			array(
				'labels' => array(
					'name'                  => 'Contributions',
					'singular_name'         => 'Contribution',
					'add_new'               => 'Add Contribution',
					'add_new_item'          => 'Add Contribution',
					'edit_item'             => 'Edit Contribution',
					'new_item'              => 'New Contribution',
					'view_item'             => 'View Contribution',
					'search_items'          => 'Search Contributions',
					'not_found'             => 'No Contributions found',
					'not_found_in_trash'    => 'No Contributions found in Trash',
					'all_items'             => 'All Contributions',
					'menu_name'             => 'Contributions',
					'name_admin_bar'        => 'Contribution',
				),
				'public'      => true,
				'supports'    => array( 'title', 'editor', 'thumbnail' ),
				'show_in_rest'=> true,
			)
		);

		register_post_type(
			'wpkcs_contributor',
			array(
				'labels' => array(
					'name'                  => 'Contributor Profiles',
					'singular_name'         => 'Contributor',
					'add_new'               => 'Add Contributor',
					'add_new_item'          => 'Add Contributor',
					'edit_item'             => 'Edit Contributor',
					'new_item'              => 'New Contributor',
					'view_item'             => 'View Contributor',
					'search_items'          => 'Search Contributors',
					'not_found'             => 'No Contributors found',
					'not_found_in_trash'    => 'No Contributors found in Trash',
					'all_items'             => 'All Contributors',
					'menu_name'             => 'Contributors',
					'name_admin_bar'        => 'Contributor',
				),
				'public'      => true,
				'has_archive'  => true,
				'rewrite'      => array(
					'slug'       => 'contributors',
					'with_front' => false,
				),
				'supports'    => array( 'title', 'editor', 'thumbnail' ),
				'show_in_rest'=> true,
			)
		);
	}
}