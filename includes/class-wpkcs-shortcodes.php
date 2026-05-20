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

		add_shortcode(
			'wpkcs_profile',
			array( $this, 'wpkcs_render_profile' )
		);

		add_shortcode(
			'wpkcs_contributors',
			array( $this, 'wpkcs_render_contributors' )
		);
	}

	public function wpkcs_render_form() {

		ob_start();

		include WPKCS_PLUGIN_PATH . 'templates/contribution-form.php';

		return ob_get_clean();
	}

	public function wpkcs_render_profile( $atts ) {

		$atts = shortcode_atts(
			array(
				'profile' => '',
			),
			$atts
		);

		ob_start();

		$profile = sanitize_text_field( $atts['profile'] );

		include WPKCS_PLUGIN_PATH . 'templates/contributor-profile.php';

		return ob_get_clean();
	}

	public function wpkcs_render_contributors() {

		$args = array(
			'post_type'      => 'wpkcs_contributor',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'orderby'        => 'title',
			'order'          => 'ASC',
		);

		$query = new WP_Query( $args );

		ob_start();

		?>

		<div class="wpkcs-contributors-grid">

			<?php
			if ( $query->have_posts() ) :

				while ( $query->have_posts() ) :
					$query->the_post();

					$post_id = get_the_ID();

					$username = get_post_meta(
						$post_id,
						'_wpkcs_org_slug',
						true
					);

					

					$avatar = get_post_meta(
						$post_id,
						"_wpkcs_org_avatar_urls"
					)[0][96] ?? ''; 

					$bio = wp_trim_words(
						get_the_content(),
						20,
						'...'
					);

					$contributions = new WP_Query(
						array(
							'post_type'      => 'wpkcs_contribution',
							'posts_per_page' => -1,
							'meta_query'     => array(
								array(
									'key'   => '_wpkcs_username',
									'value' => $username,
								),
							),
						)
					);

					$count = $contributions->found_posts;

					?>

					<div class="wpkcs-contributor-card" style="cursor: pointer;" onclick="window.location.href ='<?= "/contributions/?p=".$username; ?>'" >

						<div class="wpkcs-card-avatar">

							<?php if ( $avatar ) : ?>

								<img
									src="<?php echo esc_url( $avatar ); ?>"
									alt="<?php the_title_attribute(); ?>"
								>

							<?php else : ?>

								<div class="wpkcs-card-placeholder">

									<?php
									echo esc_html(
										strtoupper(
											substr(
												get_the_title(),
												0,
												1
											)
										)
									);
									?>

								</div>

							<?php endif; ?>

						</div>

						<div class="wpkcs-card-content">

							<h3 class="wpkcs-card-name">
								<?php the_title(); ?>
							</h3>

							<div class="wpkcs-card-username">
								@
								<?php echo esc_html( $username ); ?>
							</div>

							<div class="wpkcs-card-bio">
								<?php echo esc_html( $bio ); ?>
							</div>

							<div class="wpkcs-card-footer">

								<span class="wpkcs-card-count">

									<?php
									echo esc_html( $count );
									?>

									Contributions

								</span>

								<!-- <a
									href="<?php echo esc_url( add_query_arg( 'profile', $username, get_permalink() ) ); ?>"
									class="wpkcs-card-button"
								>
									View Profile
								</a> -->

							</div>

						</div>

					</div>

					<?php

				endwhile;

				wp_reset_postdata();

			else :

				echo '<p>No contributors found.</p>';

			endif;
			?>

		</div>

		<?php

		return ob_get_clean();
	}
}