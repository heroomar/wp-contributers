<?php
get_header();
?>

<h1><?php esc_html_e( 'Contributors', 'wp-contributers' ); ?></h1>

<div class="wpkcs-contributors-grid">

	<?php
	if ( have_posts() ) :

		while ( have_posts() ) :
			the_post();

			$post_id    = get_the_ID();
			$contributor = new WPKCS_Contributor( $post_id );
			$avatar      = $contributor->get_avatar();
			?>

			<a
				class="wpkcs-contributor-card"
				href="<?php the_permalink(); ?>"
			>

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
						<?php echo esc_html( get_post_meta( $post_id, '_wpkcs_org_name', true ) ); ?>
					</h3>

					<div class="wpkcs-card-username">
						@
						<?php echo esc_html( $contributor->get_username() ); ?>
					</div>

					<div class="wpkcs-card-footer">

						<span class="wpkcs-card-count">

							<?php
							echo esc_html( $contributor->get_cotribution_count() );
							?>

							<?php esc_html_e( 'Contributions', 'wp-contributers' ); ?>

						</span>

					</div>

				</div>

			</a>

			<?php

		endwhile;

		wp_reset_postdata();

	else :

		echo '<p>' . esc_html__( 'No contributors found.', 'wp-contributers' ) . '</p>';

	endif;
	?>

</div>

<?php
get_footer();
?>