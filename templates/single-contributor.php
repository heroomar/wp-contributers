<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


get_header();

while ( have_posts() ) :
	the_post();

	$post_id     = get_the_ID();
	$wpkcs_contributor = new WPKCS_Contributor( $post_id );
	$wpkcs_username    = $wpkcs_contributor->get_username();
	?>
	<div class="contributor-profile">
		<div class="contributor-top">
			<div class="contributor-image">
				<?php
				if ( has_post_thumbnail() ) {
					the_post_thumbnail(
						'medium',
						array(
							'class' => 'profile-img',
						)
					);
				} elseif ( $wpkcs_avatar = $wpkcs_contributor->get_avatar( 650 ) ) {
					?>
					<img
						src="<?php echo esc_url( $wpkcs_avatar ); ?>"
						alt="<?php the_title_attribute(); ?>"
						class="profile-img"
					>
					<?php
				}
				?>
			</div>

			<div class="contributor-info">
				<h2><?php echo esc_html( $wpkcs_contributor->full_name() ); ?></h2>

				<div class="bio">
					<?php the_content(); ?>
					<?php echo esc_html( $wpkcs_contributor->get_bio() ); ?>
				</div>

				<a
					class="profile-btn"
					href="<?php echo esc_url( 'https://profiles.wordpress.org/' . rawurlencode( $wpkcs_username ) ); ?>"
					target="_blank"
					rel="noopener noreferrer"
				>
					<?php esc_html_e( 'WORDPRESS.ORG PROFILE', 'contributors-team' ); ?>
				</a>

				<div class="social-icons share-icons">
					<?php
					$wpkcs_share_url   = rawurlencode( get_permalink() );
					$wpkcs_share_title = rawurlencode( get_the_title() );
					?>

					<a
						href="<?php echo esc_url( 'https://www.facebook.com/sharer.php?u=' . $wpkcs_share_url ); ?>"
						target="_blank"
						rel="noopener noreferrer"
						class="share-icon facebook"
					>f</a>

					<a
						href="<?php echo esc_url( 'https://twitter.com/share?url=' . $wpkcs_share_url . '&text=' . $wpkcs_share_title ); ?>"
						target="_blank"
						rel="noopener noreferrer"
						class="share-icon twitter"
					>𝕏</a>

					<a
						href="<?php echo esc_url( 'https://www.linkedin.com/shareArticle?mini=true&url=' . $wpkcs_share_url . '&title=' . $wpkcs_share_title ); ?>"
						target="_blank"
						rel="noopener noreferrer"
						class="share-icon linkedin"
					>in</a>

					<a
						href="<?php echo esc_url( 'mailto:?subject=' . $wpkcs_share_title . '&body=' . $wpkcs_share_url ); ?>"
						class="share-icon email"
					>✉</a>

					<a
						href="<?php echo esc_url( 'https://pinterest.com/pin/create/button/?url=' . $wpkcs_share_url ); ?>"
						target="_blank"
						rel="noopener noreferrer"
						class="share-icon pinterest"
					>P</a>

					<a
						href="<?php echo esc_url( 'https://t.me/share/url?url=' . $wpkcs_share_url ); ?>"
						target="_blank"
						rel="noopener noreferrer"
						class="share-icon telegram"
					>➤</a>
				</div>
			</div>
		</div>

		<?php
		$wpkcs_contributions = $wpkcs_contributor->get_user_contributions();

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Public filter parameter; no data is modified.
		$wpkcs_current_type = isset( $_GET['type'] )
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Public filter parameter; no data is modified.
			? sanitize_text_field( wp_unslash( $_GET['type'] ) )
			: ( $wpkcs_contributions[0]['type'] ?? '' );

		$wpkcs_contribution_data = array();
		?>

		<div class="contributor-tabs">
			<ul>
				<?php
				foreach ( $wpkcs_contributions as $wpkcs_contribution ) {
					if ( $wpkcs_contribution['type'] === $wpkcs_current_type ) {
						$wpkcs_contribution_data = $wpkcs_contribution['data'];
					}
					?>
					<li class="<?php echo esc_attr( $wpkcs_contribution['type'] === $wpkcs_current_type ? 'active' : '' ); ?>">
						<a href="<?php echo esc_url( add_query_arg( 'type', $wpkcs_contribution['type'] ) ); ?>">
							<?php echo esc_html( $wpkcs_contribution['name'] ); ?>
						</a>
					</li>
					<?php
				}
				?>
			</ul>
		</div>

		<?php
		$wpkcs_months = array();
		?>

		<div class="contribution-list">
			<?php
			foreach ( $wpkcs_contribution_data as $wpkcs_value ) {
				$wpkcs_screenshot = get_post_meta( $wpkcs_value['ID'], '_wpkcs_screenshot', true );
				$wpkcs_date       = get_post_meta( $wpkcs_value['ID'], '_wpkcs_date', true );
				$wpkcs_title      = get_post_meta( $wpkcs_value['ID'], '_wpkcs_title', true );
				$wpkcs_link       = get_post_meta( $wpkcs_value['ID'], '_wpkcs_link', true );

				if ( 'Photos Contribution' === $wpkcs_current_type ) {
					?>
					<div class="photo-contribution">
						<?php echo wp_get_attachment_image( $wpkcs_screenshot, 'small' ); ?>
					</div>
					<?php
					continue;
				}

				$wpkcs_timestamp = strtotime( $wpkcs_date );
				$wpkcs_month_key = gmdate( 'M', $wpkcs_timestamp );

				if ( ! isset( $wpkcs_months[ $wpkcs_month_key ] ) ) {
					$wpkcs_months[ $wpkcs_month_key ] = '-';
					?>
					<h3>
						<strong>
							<?php esc_html_e( 'CONTRIBUTION MONTH:', 'contributors-team' ); ?>
							<?php echo esc_html( strtoupper( gmdate( 'M', $wpkcs_timestamp ) ) ); ?>,
							<?php echo esc_html( gmdate( 'Y', $wpkcs_timestamp ) ); ?>
						</strong>
					</h3>
					<?php
				}
				?>

				<div class="contribution-item">
					<?php if ( in_array( $wpkcs_current_type, array( 'Code Contribution', 'Translation', 'Support Forum', 'Meetup', 'Learn WordPress' ), true ) ) : ?>
						<div>
							<a href="<?php echo esc_url( $wpkcs_link ); ?>">
								<?php echo esc_html( $wpkcs_title ); ?>
							</a>
						</div>
					<?php endif; ?>

					<div class="contribution-meta">
						<span>
							<?php esc_html_e( 'Date:', 'contributors-team' ); ?>
							<?php echo esc_html( $wpkcs_date ); ?>
						</span>
					</div>

					<?php
					if ( $wpkcs_screenshot ) {
						echo wp_get_attachment_image( $wpkcs_screenshot, 'medium' );
					}
					?>
				</div>
			<?php } ?>
		</div>

		<?php wp_reset_postdata(); ?>
	</div>
	<?php
endwhile;

get_footer();
?>