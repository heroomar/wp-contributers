<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wpkcs_profile = isset( $wpkcs_profile )
	? sanitize_text_field( $wpkcs_profile )
	: '';

$wpkcs_contributor_query = new WP_Query(
	array(
		'post_type'              => 'wpkcs_contributor',
		'post_status'            => 'publish',
		'posts_per_page'         => 1,
		'no_found_rows'          => true,
		'meta_query'             => array(
			array(
				'key'     => '_wpkcs_org_slug',
				'value'   => $wpkcs_profile,
				'compare' => '=',
			),
		),
	)
);

if ( ! $wpkcs_contributor_query->have_posts() ) {
	wp_reset_postdata();

	echo '<p>' . esc_html__( 'No contributor found.', 'contributors-team' ) . '</p>';
	return;
}

$wpkcs_contributor = $wpkcs_contributor_query->posts[0];

wp_reset_postdata();

$wpkcs_avatar_urls = get_post_meta(
	$wpkcs_contributor->ID,
	'_wpkcs_org_avatar_urls',
	true
);

$wpkcs_avatar = is_array( $wpkcs_avatar_urls )
	? ( $wpkcs_avatar_urls[96] ?? '' )
	: '';

$wpkcs_bio = $wpkcs_contributor->post_content;


$wpkcs_args = array(
	'post_type'      => 'wpkcs_contribution',
	'posts_per_page' => -1,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'meta_query'     => array(
		array(
			'key'   => '_wpkcs_username',
			'value' => $wpkcs_profile,
		),
	),
);

$wpkcs_query = new WP_Query( $wpkcs_args );
?>

<div class="wpkcs-profile-page">
	<div class="wpkcs-profile-card">
		<div class="wpkcs-profile-avatar">
			<?php if ( $wpkcs_avatar ) : ?>
				<img
					src="<?php echo esc_url( $wpkcs_avatar ); ?>"
					alt="<?php echo esc_attr( $wpkcs_profile ); ?>"
				>
			<?php else : ?>
				<div class="wpkcs-avatar-placeholder">
					<?php echo esc_html( strtoupper( substr( $wpkcs_profile, 0, 1 ) ) ); ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="wpkcs-profile-content">
			<h1 class="wpkcs-profile-name">
				<?php echo esc_html( $wpkcs_profile ); ?>
			</h1>
			<div class="wpkcs-profile-role">
				<?php esc_html_e( 'WordPress Contributor', 'contributors-team' ); ?>
			</div>
			<div class="wpkcs-profile-bio">
				<?php echo wp_kses_post( wpautop( $wpkcs_bio ) ); ?>
			</div>
		</div>
	</div>
	<div class="wpkcs-timeline-wrapper">
		<h2 class="wpkcs-section-title">
			<?php esc_html_e( 'Contributions', 'contributors-team' ); ?>
		</h2>
		<?php if ( $wpkcs_query->have_posts() ) : ?>
			<div class="wpkcs-timeline">
				<?php
				while ( $wpkcs_query->have_posts() ) :
					$wpkcs_query->the_post();

					$wpkcs_type = get_post_meta(
						get_the_ID(),
						'_wpkcs_type',
						true
					);

					$wpkcs_link = get_post_meta(
						get_the_ID(),
						'_wpkcs_link',
						true
					);

					$wpkcs_time = get_post_meta(
						get_the_ID(),
						'_wpkcs_time_spent',
						true
					);

					$wpkcs_date = get_post_meta(
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
									<?php echo esc_html( $wpkcs_type ); ?>
								</span>
								<span class="wpkcs-contribution-date">
									<?php echo esc_html( date_i18n( 'F j, Y', strtotime( $wpkcs_date ) ) ); ?>
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
									⏱ <?php echo esc_html( $wpkcs_time ); ?>
								</span>
								<?php if ( $wpkcs_link ) : ?>
									<a
										href="<?php echo esc_url( $wpkcs_link ); ?>"
										target="_blank"
										rel="noopener noreferrer"
										class="wpkcs-view-link"
									>
										<?php esc_html_e( 'View Contribution →', 'contributors-team' ); ?>
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
			<p><?php esc_html_e( 'No contributions found.', 'contributors-team' ); ?></p>
		<?php endif; ?>
	</div>
</div>