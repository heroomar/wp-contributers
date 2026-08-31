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
 * Handles contribution and contributor meta boxes and admin list columns.
 *
 * @package Contributors_Team
 */
class WPKCS_Meta_Boxes {

	/**
	 * Initializes meta box, save, and admin column hooks.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'wpkcs_register_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'wpkcs_save_contribution_meta' ) );

		add_filter( 'manage_wpkcs_contributor_posts_columns', array( $this, 'wpkcs_contributor_columns' ) );
		add_action( 'manage_wpkcs_contributor_posts_custom_column', array( $this, 'wpkcs_contributor_column_content' ), 10, 2 );

		add_filter( 'manage_wpkcs_contribution_posts_columns', array( $this, 'wpkcs_contribution_columns' ) );
		add_action( 'manage_wpkcs_contribution_posts_custom_column', array( $this, 'wpkcs_contribution_column_content' ), 10, 2 );
	}

	/**
	 * Registers plugin meta boxes.
	 *
	 * @return void
	 */
	public function wpkcs_register_meta_boxes() {
		add_meta_box(
			'wpkcs_contribution_details',
			__( 'Contribution Details', 'contributors-team' ),
			array( $this, 'wpkcs_contribution_meta_box_callback' ),
			'wpkcs_contribution',
			'normal',
			'default'
		);
	}

	/**
	 * Displays the contribution details meta box.
	 *
	 * @param WP_Post $post Current post object.
	 *
	 * @return void
	 */
	public function wpkcs_contribution_meta_box_callback( $post ) {
		wp_nonce_field( 'wpkcs_save_contribution_meta', 'wpkcs_contribution_nonce' );

		$username   = get_post_meta( $post->ID, '_wpkcs_username', true );
		$type       = get_post_meta( $post->ID, '_wpkcs_type', true );
		$link       = get_post_meta( $post->ID, '_wpkcs_link', true );
		$time       = get_post_meta( $post->ID, '_wpkcs_time_spent', true );
		$date       = get_post_meta( $post->ID, '_wpkcs_date', true );
		$screenshot = get_post_meta( $post->ID, '_wpkcs_screenshot', true );
		?>

		<table class="form-table">
			<tr>
				<th>
					<label for="wpkcs_username"><?php esc_html_e( 'WordPress.org Username', 'contributors-team' ); ?></label>
				</th>
				<td>
					<input
						type="text"
						id="wpkcs_username"
						name="wpkcs_username"
						value="<?php echo esc_attr( $username ); ?>"
						class="regular-text"
					>
				</td>
			</tr>
			<tr>
				<th>
					<label for="wpkcs_type"><?php esc_html_e( 'Contribution Type', 'contributors-team' ); ?></label>
				</th>
				<td>
					<select name="wpkcs_type" id="wpkcs_type" class="regular-text" required>
						<option value=""><?php esc_html_e( 'Select Contribution Type', 'contributors-team' ); ?></option>
						<option value="Photos Contribution" <?php selected( $type, 'Photos Contribution' ); ?>>
							<?php esc_html_e( 'Photos Contribution', 'contributors-team' ); ?>
						</option>
						<option value="Translation" <?php selected( $type, 'Translation' ); ?>>
							<?php esc_html_e( 'Translation', 'contributors-team' ); ?>
						</option>
						<option value="Support Forum" <?php selected( $type, 'Support Forum' ); ?>>
							<?php esc_html_e( 'Support Forum', 'contributors-team' ); ?>
						</option>
						<option value="Meetup" <?php selected( $type, 'Meetup' ); ?>>
							<?php esc_html_e( 'Meetup Participation', 'contributors-team' ); ?>
						</option>
						<option value="Documentation" <?php selected( $type, 'Documentation' ); ?>>
							<?php esc_html_e( 'Documentation Contribution', 'contributors-team' ); ?>
						</option>
						<option value="Learn WordPress" <?php selected( $type, 'Learn WordPress' ); ?>>
							<?php esc_html_e( 'Learn WordPress', 'contributors-team' ); ?>
						</option>
						<option value="Code Contribution" <?php selected( $type, 'Code Contribution' ); ?>>
							<?php esc_html_e( 'Code Contribution', 'contributors-team' ); ?>
						</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>
					<label for="wpkcs_link"><?php esc_html_e( 'Contribution Link', 'contributors-team' ); ?></label>
				</th>
				<td>
					<input
						type="url"
						id="wpkcs_link"
						name="wpkcs_link"
						value="<?php echo esc_attr( $link ); ?>"
						class="regular-text"
					>
				</td>
			</tr>
			<tr>
				<th>
					<label for="wpkcs_time_spent"><?php esc_html_e( 'Time Spent', 'contributors-team' ); ?></label>
				</th>
				<td>
					<input
						type="text"
						id="wpkcs_time_spent"
						name="wpkcs_time_spent"
						value="<?php echo esc_attr( $time ); ?>"
						class="regular-text"
					>
				</td>
			</tr>
			<tr>
				<th>
					<label for="wpkcs_date"><?php esc_html_e( 'Date', 'contributors-team' ); ?></label>
				</th>
				<td>
					<input
						type="date"
						id="wpkcs_date"
						name="wpkcs_date"
						value="<?php echo esc_attr( $date ); ?>"
					>
				</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Screenshot', 'contributors-team' ); ?></th>
				<td>
					<?php
					if ( $screenshot ) {
						echo wp_get_attachment_image( $screenshot, array( 150, 150 ) );
					}
					?>
					<br><br>
					<input
						type="file"
						name="wpkcs_screenshot"
						accept=".jpg,.jpeg,.png,.webp"
					>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Displays the contributor details meta box.
	 *
	 * @param WP_Post $post Current post object.
	 *
	 * @return void
	 */
	public function wpkcs_contributor_meta_box_callback( $post ) {
		wp_nonce_field( 'wpkcs_save_contributor_meta', 'wpkcs_contributor_nonce' );

		$contributor = new WPKCS_Contributor( $post->ID );
		$avatar      = $contributor->get_avatar();
		?>

		<table class="form-table">
			<tr>
				<th>
					<label for="wpkcs_wporg_username"><?php esc_html_e( 'WordPress.org Username', 'contributors-team' ); ?></label>
				</th>
				<td>
					<input
						type="text"
						id="wpkcs_wporg_username"
						name="wpkcs_wporg_username"
						value="<?php echo esc_attr( $contributor->get_username() ); ?>"
						class="regular-text"
					>
				</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Avatar', 'contributors-team' ); ?></th>
				<td>
					<?php if ( $avatar ) : ?>
						<img
							src="<?php echo esc_url( $avatar ); ?>"
							width="120"
							alt=""
						>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<th>
					<label for="wpkcs_bio"><?php esc_html_e( 'Bio', 'contributors-team' ); ?></label>
				</th>
				<td>
					<textarea
						name="wpkcs_bio"
						id="wpkcs_bio"
						rows="6"
						class="large-text"
					><?php echo esc_textarea( $contributor->get_bio() ); ?></textarea>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Saves contribution metadata.
	 *
	 * @param int $post_id The ID of the post being saved.
	 *
	 * @return void
	 */
	public function wpkcs_save_contribution_meta( $post_id ) {
		if (
			! isset( $_POST['wpkcs_contribution_nonce'] ) ||
			! wp_verify_nonce(
				sanitize_text_field( wp_unslash( $_POST['wpkcs_contribution_nonce'] ) ),
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

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$username   = isset( $_POST['wpkcs_username'] ) ? sanitize_text_field( wp_unslash( $_POST['wpkcs_username'] ) ) : '';
		$type       = isset( $_POST['wpkcs_type'] ) ? sanitize_text_field( wp_unslash( $_POST['wpkcs_type'] ) ) : '';
		$link       = isset( $_POST['wpkcs_link'] ) ? esc_url_raw( wp_unslash( $_POST['wpkcs_link'] ) ) : '';
		$time_spent = isset( $_POST['wpkcs_time_spent'] ) ? sanitize_text_field( wp_unslash( $_POST['wpkcs_time_spent'] ) ) : '';
		$date       = isset( $_POST['wpkcs_date'] ) ? sanitize_text_field( wp_unslash( $_POST['wpkcs_date'] ) ) : '';

		// Verify that the submitted WordPress.org username exists.
		$wp_org_profile = WPKCS_WordPress_Org::wpkcs_fetch_profile( $username );

		if (
			true !== $wp_org_profile &&
			( ! is_array( $wp_org_profile ) || ! isset( $wp_org_profile['name'] ) )
		) {
			wp_die(
				esc_html__( 'WordPress.org profile could not be found. Please check the username.', 'contributors-team' ),
				esc_html__( 'Validation Error', 'contributors-team' ),
				array(
					'back_link' => true,
				)
			);
		}

		// Save contribution metadata.
		update_post_meta( $post_id, '_wpkcs_username', $username );
		update_post_meta( $post_id, '_wpkcs_type', $type );
		update_post_meta( $post_id, '_wpkcs_link', $link );
		update_post_meta( $post_id, '_wpkcs_time_spent', $time_spent );
		update_post_meta( $post_id, '_wpkcs_date', $date );

		// Process the optional screenshot upload.
		if (
			isset( $_FILES['wpkcs_screenshot'] ) &&
			is_array( $_FILES['wpkcs_screenshot'] ) &&
			! empty( $_FILES['wpkcs_screenshot']['name'] )
		) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';
			require_once ABSPATH . 'wp-admin/includes/image.php';

			$file_name = isset( $_FILES['wpkcs_screenshot']['name'] )
				? sanitize_file_name( wp_unslash( $_FILES['wpkcs_screenshot']['name'] ) )
				: '';

			$file_type = wp_check_filetype( $file_name );

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

	/**
	 * Saves contributor metadata.
	 *
	 * @param int $post_id The ID of the post being saved.
	 *
	 * @return void
	 */
	public function wpkcs_save_contributor_meta( $post_id ) {
		if (
			! isset( $_POST['wpkcs_contributor_nonce'] ) ||
			! wp_verify_nonce(
				sanitize_text_field( wp_unslash( $_POST['wpkcs_contributor_nonce'] ) ),
				'wpkcs_save_contributor_meta'
			)
		) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( 'wpkcs_contributor' !== get_post_type( $post_id ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		update_post_meta(
			$post_id,
			'_wpkcs_wporg_username',
			sanitize_text_field(
				wp_unslash( $_POST['wpkcs_wporg_username'] ?? '' )
			)
		);

		update_post_meta(
			$post_id,
			'_wpkcs_bio',
			wp_kses_post(
				wp_unslash( $_POST['wpkcs_bio'] ?? '' )
			)
		);
	}

	/**
	 * Adds custom columns to the contributor admin list.
	 *
	 * @param array $columns Existing admin columns.
	 *
	 * @return array Modified admin columns.
	 */
	public function wpkcs_contributor_columns( $columns ) {
		$columns['wpkcs_avatar']   = __( 'Avatar', 'contributors-team' );
		$columns['wpkcs_username'] = __( 'Username', 'contributors-team' );

		return $columns;
	}

	/**
	 * Displays content for custom contributor admin columns.
	 *
	 * @param string $column  Current column name.
	 * @param int    $post_id Current post ID.
	 *
	 * @return void
	 */
	public function wpkcs_contributor_column_content( $column, $post_id ) {
		if ( 'wpkcs_avatar' === $column ) {
			$avatar_urls = get_post_meta(
				$post_id,
				'_wpkcs_org_avatar_urls',
				true
			);

			$avatar = $avatar_urls[96] ?? '';

			if ( $avatar ) {
				echo '<img src="' . esc_url( $avatar ) . '" width="50" alt="' . esc_attr__( 'Avatar', 'contributors-team' ) . '">';
			}
		}

		if ( 'wpkcs_username' === $column ) {
			$link = get_post_meta(
				$post_id,
				'_wpkcs_org_link',
				true
			);

			if ( $link ) {
				echo '<a href="' . esc_url( $link ) . '" target="_blank" rel="noopener noreferrer">';
				echo esc_html( $link );
				echo '</a>';
			}
		}
	}

	/**
	 * Adds custom columns to the contribution admin list.
	 *
	 * @param array $columns Existing admin columns.
	 *
	 * @return array Modified admin columns.
	 */
	public function wpkcs_contribution_columns( $columns ) {
		$columns['wpkcs_type'] = __( 'Contribution Type', 'contributors-team' );
		$columns['wpkcs_user'] = __( 'Username', 'contributors-team' );
		$columns['wpkcs_date'] = __( 'Contribution Date', 'contributors-team' );

		return $columns;
	}

	/**
	 * Displays content for custom contribution admin columns.
	 *
	 * @param string $column  Current column name.
	 * @param int    $post_id Current post ID.
	 *
	 * @return void
	 */
	public function wpkcs_contribution_column_content( $column, $post_id ) {
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
