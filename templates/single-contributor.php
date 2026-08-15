<?php
get_header();
while ( have_posts() ) :
	the_post();
	$post_id     = get_the_ID();
	$contributor = new WPKCS_Contributor( $post_id );
	$username    = $contributor->get_username();
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
				} elseif ( $avatar = $contributor->get_avatar( 650 ) ) {
					?>
					<img
						src="<?php echo esc_url( $avatar ); ?>"
						alt="<?php the_title_attribute(); ?>"
						class="profile-img"
					>
					<?php
				}
				?>
			</div>
			<div class="contributor-info">
				<h2><?php echo esc_html( $contributor->full_name() ); ?></h2>
				<div class="bio">
					<?php the_content(); ?>
					<?php echo esc_html( $contributor->get_bio() ); ?>
				</div>
				<a
					class="profile-btn"
					href="<?php echo esc_url( 'https://profiles.wordpress.org/' . rawurlencode( $username ) ); ?>"
					target="_blank"
					rel="noopener noreferrer"
				>
					<?php esc_html_e( 'WORDPRESS.ORG PROFILE', 'wpkcs' ); ?>
				</a>
				<div class="social-icons share-icons">
					<?php
					$share_url   = rawurlencode( get_permalink() );
					$share_title = rawurlencode( get_the_title() );
					?>
					<a
						href="<?php echo esc_url( 'https://www.facebook.com/sharer.php?u=' . $share_url ); ?>"
						target="_blank"
						rel="noopener noreferrer"
						class="share-icon facebook"
					>f</a>
					<a
						href="<?php echo esc_url( 'https://twitter.com/share?url=' . $share_url . '&text=' . $share_title ); ?>"
						target="_blank"
						rel="noopener noreferrer"
						class="share-icon twitter"
					>𝕏</a>
					<a
						href="<?php echo esc_url( 'https://www.linkedin.com/shareArticle?mini=true&url=' . $share_url . '&title=' . $share_title ); ?>"
						target="_blank"
						rel="noopener noreferrer"
						class="share-icon linkedin"
					>in</a>
					<a
						href="<?php echo esc_url( 'mailto:?subject=' . $share_title . '&body=' . $share_url ); ?>"
						class="share-icon email"
					>✉</a>
					<a
						href="<?php echo esc_url( 'https://pinterest.com/pin/create/button/?url=' . $share_url ); ?>"
						target="_blank"
						rel="noopener noreferrer"
						class="share-icon pinterest"
					>P</a>
					<a
						href="<?php echo esc_url( 'https://t.me/share/url?url=' . $share_url ); ?>"
						target="_blank"
						rel="noopener noreferrer"
						class="share-icon telegram"
					>➤</a>
				</div>
			</div>
		</div>
		<?php
		$contributions = $contributor->get_user_contributions();
		$current_type  = isset( $_GET['type'] )
			? sanitize_text_field( wp_unslash( $_GET['type'] ) )
			: ( $contributions[0]['type'] ?? '' );
		?>
		<div class="contributor-tabs">
			<ul>
				<?php
				foreach ( $contributions as $contribution ) {
					if ( $contribution['type'] === $current_type ) {
						$contribution_data = $contribution['data'];
					}
					?>
					<li class="<?php echo esc_attr( $contribution['type'] === $current_type ? 'active' : '' ); ?>">
						<a href="<?php echo esc_url( add_query_arg( 'type', $contribution['type'] ) ); ?>">
							<?php echo esc_html( $contribution['name'] ); ?>
						</a>
					</li>
					<?php
				}
				?>
			</ul>
		</div>
		<?php
		$months = array();
		?>
		<div class="contribution-list">
			<?php
			foreach ( $contribution_data ?? array() as $value ) {
				$screenshot = get_post_meta( $value['ID'], '_wpkcs_screenshot', true );
				$date       = get_post_meta( $value['ID'], '_wpkcs_date', true );
				$title      = get_post_meta( $value['ID'], '_wpkcs_title', true );
				$link       = get_post_meta( $value['ID'], '_wpkcs_link', true );

				if ( 'Photos Contribution' === $current_type ) {
					?>
					<div class="photo-contribution">
						<?php echo wp_get_attachment_image( $screenshot, 'small' ); ?>
					</div>
					<?php
					continue;
				}

				$month_key = date( 'M', strtotime( $date ) );

				if ( ! isset( $months[ $month_key ] ) ) {
					$months[ $month_key ] = '-';
					?>
					<h3>
						<strong>
							<?php esc_html_e( 'CONTRIBUTION MONTH:', 'wpkcs' ); ?>
							<?php echo esc_html( strtoupper( date( 'M', strtotime( $date ) ) ) ); ?>,
							<?php echo esc_html( date( 'Y', strtotime( $date ) ) ); ?>
						</strong>
					</h3>
					<?php
				}
				?>
				<div class="contribution-item">
					<?php if ( in_array( $current_type, array( 'Code Contribution', 'Translation', 'Support Forum', 'Meetup', 'Learn WordPress' ), true ) ) : ?>
						<div>
							<a href="<?php echo esc_url( $link ); ?>">
								<?php echo esc_html( $title ); ?>
							</a>
						</div>
					<?php endif; ?>
					<div class="contribution-meta">
						<span>
							<?php esc_html_e( 'Date:', 'wpkcs' ); ?>
							<?php echo esc_html( $date ); ?>
						</span>
					</div>
					<?php
					if ( $screenshot ) {
						echo wp_get_attachment_image( $screenshot, 'medium' );
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