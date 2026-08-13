<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPKCS_Meta_Boxes {

	public function __construct() {

		/*
		|--------------------------------------------------------------------------
		| Meta Boxes
		|--------------------------------------------------------------------------
		*/

		add_action(
			'add_meta_boxes',
			array( $this, 'wpkcs_register_meta_boxes' )
		);

		/*
		|--------------------------------------------------------------------------
		| Save Meta
		|--------------------------------------------------------------------------
		*/

		add_action(
			'save_post',
			array( $this, 'wpkcs_save_contribution_meta' )
		);

		// add_action(
		// 	'save_post',
		// 	array( $this, 'wpkcs_save_contributor_meta' )
		// );

		/*
		|--------------------------------------------------------------------------
		| Admin Columns
		|--------------------------------------------------------------------------
		*/

		add_filter(
			'manage_wpkcs_contributor_posts_columns',
			array( $this, 'wpkcs_contributor_columns' )
		);

		add_action(
			'manage_wpkcs_contributor_posts_custom_column',
			array( $this, 'wpkcs_contributor_column_content' ),
			10,
			2
		);

		add_filter(
			'manage_wpkcs_contribution_posts_columns',
			array( $this, 'wpkcs_contribution_columns' )
		);

		add_action(
			'manage_wpkcs_contribution_posts_custom_column',
			array( $this, 'wpkcs_contribution_column_content' ),
			10,
			2
		);
	}

	/*
	|--------------------------------------------------------------------------
	| Register Meta Boxes
	|--------------------------------------------------------------------------
	*/

	public function wpkcs_register_meta_boxes() {

		add_meta_box(
			'wpkcs_contribution_details',
			'Contribution Details',
			array( $this, 'wpkcs_contribution_meta_box_callback' ),
			'wpkcs_contribution',
			'normal',
			'default'
		);

		// add_meta_box(
		// 	'wpkcs_contributor_details',
		// 	'Contributor Details',
		// 	array( $this, 'wpkcs_contributor_meta_box_callback' ),
		// 	'wpkcs_contributor',
		// 	'normal',
		// 	'default'
		// );
	}

	/*
	|--------------------------------------------------------------------------
	| Contribution Meta Box
	|--------------------------------------------------------------------------
	*/

	public function wpkcs_contribution_meta_box_callback( $post ) {

		wp_nonce_field(
			'wpkcs_save_contribution_meta',
			'wpkcs_contribution_nonce'
		);

		$username   = get_post_meta( $post->ID, '_wpkcs_username', true );
		$type       = get_post_meta( $post->ID, '_wpkcs_type', true );
		$link       = get_post_meta( $post->ID, '_wpkcs_link', true );
		$time       = get_post_meta( $post->ID, '_wpkcs_time_spent', true );
		$date       = get_post_meta( $post->ID, '_wpkcs_date', true );
		$screenshot = get_post_meta( $post->ID, '_wpkcs_screenshot', true );

		?>

		<table class="form-table">

			<tr>
				<th>WordPress.org Username</th>
				<td>
					<input type="text" name="wpkcs_username"
					value="<?php echo esc_attr( $username ); ?>"
					class="regular-text">
				</td>
			</tr>

			<tr>
				<th>Contribution Type</th>
				<td>
					<select name="wpkcs_type" class="regular-text" required>
						<option  value="">Select Contribution Type</option>
						<option <?php echo esc_attr( $type ) == "Photos Contribution" ? 'selected' : ''; ?> value="Photos Contribution">Photos Contribution</option>
						<option <?php echo esc_attr( $type ) == "Translation" ? 'selected' : ''; ?> value="Translation">Translation</option>
						<option <?php echo esc_attr( $type ) == "Support Forum" ? 'selected' : ''; ?> value="Support Forum">Support Forum</option>
						<option <?php echo esc_attr( $type ) == "Meetup" ? 'selected' : ''; ?> value="Meetup">Meetup Participation</option>
						<option <?php echo esc_attr( $type ) == "Documentation" ? 'selected' : ''; ?> value="Documentation">Documentation Contribution</option>
						<option <?php echo esc_attr( $type ) == "Learn WordPress" ? 'selected' : ''; ?> value="Learn WordPress">Learn WordPress</option>
						<option <?php echo esc_attr( $type ) == "Code Contribution" ? 'selected' : ''; ?> value="Code Contribution">Code Contribution</option>
						<!-- <option <?php echo esc_attr( $type ) == "Other" ? 'selected' : ''; ?> value="Other">Other</option> -->
					</select>
				</td>
			</tr>

			<tr>
				<th>Contribution Link</th>
				<td>
					<input type="url" name="wpkcs_link"
					value="<?php echo esc_url( $link ); ?>"
					class="regular-text">
				</td>
			</tr>

			<tr>
				<th>Time Spent</th>
				<td>
					<input type="text" name="wpkcs_time_spent"
					value="<?php echo esc_attr( $time ); ?>"
					class="regular-text">
				</td>
			</tr>

			<tr>
				<th>Date</th>
				<td>
					<input type="date" name="wpkcs_date"
					value="<?php echo esc_attr( $date ); ?>">
				</td>
			</tr>

			<tr>
				<th>Screenshot</th>
				<td>

					<?php
					if ( $screenshot ) {

						echo wp_get_attachment_image(
							$screenshot,
							array( 150, 150 )
						);
					}
					?>

					 <br><br>

					<input
						type="file"
						name="wpkcs_screenshot"
						accept=".jpg,.jpeg,.png,.webp">

				</td>
			</tr>

		</table>

		<?php
	}

	/*
	|--------------------------------------------------------------------------
	| Contributor Meta Box
	|--------------------------------------------------------------------------
	*/

	public function wpkcs_contributor_meta_box_callback( $post ) {

		wp_nonce_field(
			'wpkcs_save_contributor_meta',
			'wpkcs_contributor_nonce'
		);

		$contributor = new WPKCS_Contributor( $post->ID );


        

		?>

		<table class="form-table">

			<tr>
				<th>WordPress.org Username</th>
				<td>
					<input type="text"
					name="wpkcs_wporg_username"
					value="<?php echo esc_attr( $contributor->get_username() ); ?>"
					class="regular-text">
				</td>
			</tr>

			<tr>
				<th>Avatar</th>
				<td>

					<?php if ( $avatar = $contributor->get_avatar() ) : ?>

						<img
							src="<?php echo esc_url( $avatar ); ?>"
							width="120"
							alt=""
						>

					<?php endif; ?>

				</td>
			</tr>

			<tr>
				<th>Bio</th>
				<td>

					<textarea
						name="wpkcs_bio"
						rows="6"
						class="large-text"><?php echo esc_textarea( $contributor->get_bio() ); ?></textarea>

				</td>
			</tr>

		</table>

		<?php
	}

	/*
	|--------------------------------------------------------------------------
	| Save Contribution Meta
	|--------------------------------------------------------------------------
	*/

	public function wpkcs_save_contribution_meta( $post_id ) {

		if (
			! isset( $_POST['wpkcs_contribution_nonce'] ) ||
			! wp_verify_nonce(
				sanitize_text_field(
					wp_unslash(
						$_POST['wpkcs_contribution_nonce']
					)
				),
				'wpkcs_save_contribution_meta'
			)
		) {
			return; 
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( 'wpkcs_contribution' !== get_post_type( $post_id ) ) {
			return;
		}

		$username = $_POST['wpkcs_username'];
		$type = $_POST['wpkcs_type'];
		$link = $_POST['wpkcs_link'];
		$time_spent = $_POST['wpkcs_time_spent'];
		$date = $_POST['wpkcs_date'];

		$wp_org_profile = WPKCS_WordPress_Org::wpkcs_fetch_profile( $username );

		if ($wp_org_profile !== true && (!is_array($wp_org_profile) || !isset($wp_org_profile['name']))){
			// Wordpress.org profile could not be found please check username.
			wp_die(
				'Wordpress.org profile could not be found please check username.',
				'Validation Error',
				array(
					'back_link' => true,
				)
			);
		}

		update_post_meta(
			$post_id,
			'_wpkcs_username',
			sanitize_text_field(
				wp_unslash( $_POST['wpkcs_username'] ?? '' )
			)
		);

		update_post_meta(
			$post_id,
			'_wpkcs_type',
			sanitize_text_field(
				wp_unslash( $_POST['wpkcs_type'] ?? '' )
			)
		);

		update_post_meta(
			$post_id,
			'_wpkcs_link',
			esc_url_raw(
				wp_unslash( $_POST['wpkcs_link'] ?? '' )
			)
		);

		update_post_meta(
			$post_id,
			'_wpkcs_time_spent',
			sanitize_text_field(
				wp_unslash( $_POST['wpkcs_time_spent'] ?? '' )
			)
		);

		update_post_meta(
			$post_id,
			'_wpkcs_date',
			sanitize_text_field(
				wp_unslash( $_POST['wpkcs_date'] ?? '' )
			)
		);

		if ( ! empty( $_FILES['wpkcs_screenshot']['name'] ) ) {

			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';
			require_once ABSPATH . 'wp-admin/includes/image.php';

			$file_type = wp_check_filetype(
				$_FILES['wpkcs_screenshot']['name']
			);

			$allowed_types = array(
				'jpg',
				'jpeg',
				'png',
				'webp',
			);

			if ( in_array( $file_type['ext'], $allowed_types, true ) ) {

				$attachment_id = media_handle_upload(
					'wpkcs_screenshot',
					$post_id
				);

				if ( ! is_wp_error( $attachment_id ) ) {

					update_post_meta(
						$post_id,
						'_wpkcs_screenshot',
						$attachment_id
					);
				}
			}
		}



	}

	/*
	|--------------------------------------------------------------------------
	| Save Contributor Meta
	|--------------------------------------------------------------------------
	*/

	public function wpkcs_save_contributor_meta( $post_id ) {

		if (
			! isset( $_POST['wpkcs_contributor_nonce'] ) ||
			! wp_verify_nonce(
				sanitize_text_field(
					wp_unslash(
						$_POST['wpkcs_contributor_nonce']
					)
				),
				'wpkcs_save_contributor_meta'
			)
		) {
			return;
		}

		if ( 'wpkcs_contributor' !== get_post_type( $post_id ) ) {
			return;
		}

		update_post_meta(
			$post_id,
			'_wpkcs_wporg_username',
			sanitize_text_field(
				wp_unslash(
					$_POST['wpkcs_wporg_username'] ?? ''
				)
			)
		);

		update_post_meta(
			$post_id,
			'_wpkcs_bio',
			wp_kses_post(
				wp_unslash(
					$_POST['wpkcs_bio'] ?? ''
				)
			)
		);
	}

	/*
	|--------------------------------------------------------------------------
	| Contributor Columns
	|--------------------------------------------------------------------------
	*/

	public function wpkcs_contributor_columns( $columns ) {

		$columns['wpkcs_avatar']   = 'Avatar';
		$columns['wpkcs_username'] = 'Username';

		return $columns;
	}

	public function wpkcs_contributor_column_content(
		$column,
		$post_id
	) {

		if ( 'wpkcs_avatar' === $column ) {

			// $avatar = get_post_meta(
			// 	$post_id,
			// 	'_wpkcs_avatar',
			// 	true
			// );
            $avatar = get_post_meta(
                $post_id,
                "_wpkcs_org_avatar_urls"
            )[0][96] ?? ''; 

			if ( $avatar ) {

				echo '<img src="' .
				esc_url( $avatar ) .
				'" width="50">';
			}
		}

		if ( 'wpkcs_username' === $column ) {

			// echo esc_html(
			// 	get_post_meta(
			// 		$post_id,
			// 		'_wpkcs_wporg_username',
			// 		true
			// 	)
			// );
            $link = get_post_meta(
                $post_id,
                "_wpkcs_org_link",
                true
            ) ?? '';
            echo '<a href="'.$link.'" >'.$link.'</a>';
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Contribution Columns
	|--------------------------------------------------------------------------
	*/

	public function wpkcs_contribution_columns( $columns ) {

		$columns['wpkcs_type'] = 'Contribution Type';
		$columns['wpkcs_user'] = 'Username';
		$columns['wpkcs_date'] = 'Contribution Date';

		return $columns;
	}

	public function wpkcs_contribution_column_content(
		$column,
		$post_id
	) {

		if ( 'wpkcs_type' === $column ) {

			echo esc_html(
				get_post_meta(
					$post_id,
					'_wpkcs_type',
					true
				)
			);
		}

		if ( 'wpkcs_user' === $column ) {

			echo esc_html(
				get_post_meta(
					$post_id,
					'_wpkcs_username',
					true
				)
			);
		}

		if ( 'wpkcs_date' === $column ) {

			echo esc_html(
				get_post_meta(
					$post_id,
					'_wpkcs_date',
					true
				)
			);
		}
	}
}