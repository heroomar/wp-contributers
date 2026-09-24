<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


require WPKCT_PLUGIN_PATH . 'templates/contributor-header.php';

while ( have_posts() ) :
	the_post();

	$post_id     = get_the_ID();
	$wpkct_contributor = new WPKCT_Contributor( $post_id );
	$wpkct_username    = $wpkct_contributor->get_username();
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
				} elseif ( $wpkct_avatar = $wpkct_contributor->get_avatar( 650 ) ) {
					?>
					<img
						src="<?php echo esc_url( $wpkct_avatar ); ?>"
						alt="<?php the_title_attribute(); ?>"
						class="profile-img"
					>
					<?php
				}
				?>
			</div>

			<div class="contributor-info">
				<h2><?php echo esc_html( $wpkct_contributor->full_name() ); ?></h2>

				<div class="bio">
					<?php the_content(); ?>
					<?php echo esc_html( $wpkct_contributor->get_bio() ); ?>
				</div>

				<a
					class="profile-btn"
					href="<?php echo esc_url( 'https://profiles.wordpress.org/' . rawurlencode( $wpkct_username ) ); ?>"
					target="_blank"
					rel="noopener noreferrer"
				>
					<?php esc_html_e( 'WORDPRESS.ORG PROFILE', 'contributors-team' ); ?>
				</a>

				<div class="social-icons share-icons">
					<?php
					$wpkct_share_url   = rawurlencode( get_permalink() );
					$wpkct_share_title = rawurlencode( get_the_title() );
					?>

					<a
						href="<?php echo esc_url( 'https://www.facebook.com/sharer.php?u=' . $wpkct_share_url ); ?>"
						target="_blank"
						rel="noopener noreferrer"
						class="share-icon facebook"
					>f</a>

					<a
						href="<?php echo esc_url( 'https://twitter.com/share?url=' . $wpkct_share_url . '&text=' . $wpkct_share_title ); ?>"
						target="_blank"
						rel="noopener noreferrer"
						class="share-icon twitter"
					>𝕏</a>

					<a
						href="<?php echo esc_url( 'https://www.linkedin.com/shareArticle?mini=true&url=' . $wpkct_share_url . '&title=' . $wpkct_share_title ); ?>"
						target="_blank"
						rel="noopener noreferrer"
						class="share-icon linkedin"
					>in</a>

					<a
						href="<?php echo esc_url( 'mailto:?subject=' . $wpkct_share_title . '&body=' . $wpkct_share_url ); ?>"
						class="share-icon email"
					>✉</a>

					<a
						href="<?php echo esc_url( 'https://pinterest.com/pin/create/button/?url=' . $wpkct_share_url ); ?>"
						target="_blank"
						rel="noopener noreferrer"
						class="share-icon pinterest"
					>P</a>

					<a
						href="<?php echo esc_url( 'https://t.me/share/url?url=' . $wpkct_share_url ); ?>"
						target="_blank"
						rel="noopener noreferrer"
						class="share-icon telegram"
					>➤</a>
				</div>
			</div>
		</div>

		<?php
		$wpkct_contributions = $wpkct_contributor->get_user_contributions();

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Public filter parameter; no data is modified.
		$wpkct_current_type = isset( $_GET['type'] )
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Public filter parameter; no data is modified.
			? sanitize_text_field( wp_unslash( $_GET['type'] ) )
			: ( $wpkct_contributions[0]['type'] ?? '' );

		$wpkct_contribution_data = array();
		?>

		<div class="contributor-tabs">
			<ul>
				<?php
				foreach ( $wpkct_contributions as $wpkct_contribution ) {
					if ( $wpkct_contribution['type'] === $wpkct_current_type ) {
						$wpkct_contribution_data = $wpkct_contribution['data'];
					}
					?>
					<li class="<?php echo esc_attr( $wpkct_contribution['type'] === $wpkct_current_type ? 'active' : '' ); ?>">
						<a href="<?php echo esc_url( add_query_arg( 'type', $wpkct_contribution['type'] ) ); ?>">
							<?php echo esc_html( $wpkct_contribution['name'] ); ?>
						</a>
					</li>
					<?php
				}
				?>
			</ul>
		</div>

		<?php
		$wpkct_months = array();
		?>

		<div class="contribution-list">
			<?php
			foreach ( $wpkct_contribution_data as $wpkct_value ) {
				$wpkct_screenshot = get_post_meta( $wpkct_value['ID'], '_wpkct_screenshot', true );
				$wpkct_date       = get_post_meta( $wpkct_value['ID'], '_wpkct_date', true );
				$wpkct_title      = get_post_meta( $wpkct_value['ID'], '_wpkct_title', true );
				$wpkct_link       = get_post_meta( $wpkct_value['ID'], '_wpkct_link', true );

				if ( 'Photos Contribution' === $wpkct_current_type ) {
					?>
					<div class="photo-contribution">
						<?php echo wp_get_attachment_image( $wpkct_screenshot, 'small' ); ?>
					</div>
					<?php
					continue;
				}

				$wpkct_timestamp = strtotime( $wpkct_date );
				$wpkct_month_key = gmdate( 'M', $wpkct_timestamp );

				if ( ! isset( $wpkct_months[ $wpkct_month_key ] ) ) {
					$wpkct_months[ $wpkct_month_key ] = '-';
					?>
					<h3>
						<strong>
							<?php esc_html_e( 'CONTRIBUTION MONTH:', 'contributors-team' ); ?>
							<?php echo esc_html( strtoupper( gmdate( 'M', $wpkct_timestamp ) ) ); ?>,
							<?php echo esc_html( gmdate( 'Y', $wpkct_timestamp ) ); ?>
						</strong>
					</h3>
					<?php
				}
				?>

				<div class="contribution-item">
					<?php if ( in_array( $wpkct_current_type, array( 'Code Contribution', 'Translation', 'Support Forum', 'Meetup', 'Learn WordPress' ), true ) ) : ?>
						<div>
							<a href="<?php echo esc_url( $wpkct_link ); ?>">
								<?php echo esc_html( $wpkct_title ); ?>
							</a>
						</div>
					<?php endif; ?>

					<div class="contribution-meta">
						<span>
							<?php esc_html_e( 'Date:', 'contributors-team' ); ?>
							<?php echo esc_html( $wpkct_date ); ?>
						</span>
					</div>

					<?php
					if ( $wpkct_screenshot ) {
						echo wp_get_attachment_image( $wpkct_screenshot, 'medium' );
					}
					?>
				</div>
			<?php } ?>
		</div>

		<?php wp_reset_postdata(); ?>
	</div>
	<?php
endwhile;

require WPKCT_PLUGIN_PATH . 'templates/contributor-footer.php';
?>
