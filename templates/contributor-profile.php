<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$profile = isset( $profile )
	? sanitize_text_field( $profile )
	: '';

$contributor_query = new WP_Query(
	array(
		'post_type'              => 'wpkcs_contributor',
		'post_status'            => 'publish',
		'posts_per_page'         => 1,
		'no_found_rows'          => true,
		'meta_query'             => array(
			array(
				'key'     => '_wpkcs_org_slug',
				'value'   => $profile,
				'compare' => '=',
			),
		),
	)
);

if ( ! $contributor_query->have_posts() ) {
	wp_reset_postdata();

	echo '<p>' . esc_html__( 'No contributor found.', 'wp-contributers' ) . '</p>';
	return;
}

$contributor = $contributor_query->posts[0];

wp_reset_postdata();

$avatar_urls = get_post_meta(
	$contributor->ID,
	'_wpkcs_org_avatar_urls',
	true
);

$avatar = is_array( $avatar_urls )
	? ( $avatar_urls[96] ?? '' )
	: '';

$bio = $contributor->post_content;


$args = array(
	'post_type'      => 'wpkcs_contribution',
	'posts_per_page' => -1,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'meta_query'     => array(
		array(
			'key'   => '_wpkcs_username',
			'value' => $profile,
		),
	),
);

$query = new WP_Query( $args );
?>

<div class="wpkcs-profile-page">
	<div class="wpkcs-profile-card">
		<div class="wpkcs-profile-avatar">
			<?php if ( $avatar ) : ?>
				<img
					src="<?php echo esc_url( $avatar ); ?>"
					alt="<?php echo esc_attr( $profile ); ?>"
				>
			<?php else : ?>
				<div class="wpkcs-avatar-placeholder">
					<?php echo esc_html( strtoupper( substr( $profile, 0, 1 ) ) ); ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="wpkcs-profile-content">
			<h1 class="wpkcs-profile-name">
				<?php echo esc_html( $profile ); ?>
			</h1>
			<div class="wpkcs-profile-role">
				<?php esc_html_e( 'WordPress Contributor', 'wp-contributers' ); ?>
			</div>
			<div class="wpkcs-profile-bio">
				<?php echo wp_kses_post( wpautop( $bio ) ); ?>
			</div>
		</div>
	</div>
	<div class="wpkcs-timeline-wrapper">
		<h2 class="wpkcs-section-title">
			<?php esc_html_e( 'Contributions', 'wp-contributers' ); ?>
		</h2>
		<?php if ( $query->have_posts() ) : ?>
			<div class="wpkcs-timeline">
				<?php
				while ( $query->have_posts() ) :
					$query->the_post();

					$type = get_post_meta(
						get_the_ID(),
						'_wpkcs_type',
						true
					);

					$link = get_post_meta(
						get_the_ID(),
						'_wpkcs_link',
						true
					);

					$time = get_post_meta(
						get_the_ID(),
						'_wpkcs_time_spent',
						true
					);

					$date = get_post_meta(
						get_the_ID(),
						'_wpkcs_date',
						true
					);
					?>
					<div class="wpkcs-timeline-item">
						<div class="wpkcs-timeline-dot"></div>
						<div class="wpkcs-timeline-card">
							<div class="wpkcs-timeline-header">
								<span class="wpkcs-contribution-type">
									<?php echo esc_html( $type ); ?>
								</span>
								<span class="wpkcs-contribution-date">
									<?php echo esc_html( date_i18n( 'F j, Y', strtotime( $date ) ) ); ?>
								</span>
							</div>
							<h3 class="wpkcs-contribution-title">
								<?php the_title(); ?>
							</h3>
							<div class="wpkcs-contribution-content">
								<?php the_content(); ?>
							</div>
							<div class="wpkcs-contribution-footer">
								<span class="wpkcs-time-spent">
									⏱ <?php echo esc_html( $time ); ?>
								</span>
								<?php if ( $link ) : ?>
									<a
										href="<?php echo esc_url( $link ); ?>"
										target="_blank"
										rel="noopener noreferrer"
										class="wpkcs-view-link"
									>
										<?php esc_html_e( 'View Contribution →', 'wp-contributers' ); ?>
									</a>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<?php
				endwhile;

				wp_reset_postdata();
				?>
			</div>
		<?php else : ?>
			<p><?php esc_html_e( 'No contributions found.', 'wp-contributers' ); ?></p>
		<?php endif; ?>
	</div>
</div>