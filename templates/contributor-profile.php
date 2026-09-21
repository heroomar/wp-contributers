<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wpkct_profile = isset( $wpkct_profile )
	? sanitize_text_field( $wpkct_profile )
	: '';

$wpkct_contributor_query = new WP_Query(
	array(
		'post_type'              => 'wpkct_contributor',
		'post_status'            => 'publish',
		'posts_per_page'         => 1,
		'no_found_rows'          => true,
		'meta_query'             => array(
			array(
				'key'     => '_wpkct_org_slug',
				'value'   => $wpkct_profile,
				'compare' => '=',
			),
		),
	)
);

if ( ! $wpkct_contributor_query->have_posts() ) {
	wp_reset_postdata();

	echo '<p>' . esc_html__( 'No contributor found.', 'contributors-team' ) . '</p>';
	return;
}

$wpkct_contributor = $wpkct_contributor_query->posts[0];

wp_reset_postdata();

$wpkct_avatar_urls = get_post_meta(
	$wpkct_contributor->ID,
	'_wpkct_org_avatar_urls',
	true
);

$wpkct_avatar = is_array( $wpkct_avatar_urls )
	? ( $wpkct_avatar_urls[96] ?? '' )
	: '';

$wpkct_bio = $wpkct_contributor->post_content;


$wpkct_args = array(
	'post_type'      => 'wpkct_contribution',
	'posts_per_page' => -1,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'meta_query'     => array(
		array(
			'key'   => '_wpkct_username',
			'value' => $wpkct_profile,
		),
	),
);

$wpkct_query = new WP_Query( $wpkct_args );
?>

<div class="wpkcs-profile-page">
	<div class="wpkcs-profile-card">
		<div class="wpkcs-profile-avatar">
			<?php if ( $wpkct_avatar ) : ?>
				<img
					src="<?php echo esc_url( $wpkct_avatar ); ?>"
					alt="<?php echo esc_attr( $wpkct_profile ); ?>"
				>
			<?php else : ?>
				<div class="wpkcs-avatar-placeholder">
					<?php echo esc_html( strtoupper( substr( $wpkct_profile, 0, 1 ) ) ); ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="wpkcs-profile-content">
			<h1 class="wpkcs-profile-name">
				<?php echo esc_html( $wpkct_profile ); ?>
			</h1>
			<div class="wpkcs-profile-role">
				<?php esc_html_e( 'WordPress Contributor', 'contributors-team' ); ?>
			</div>
			<div class="wpkcs-profile-bio">
				<?php echo wp_kses_post( wpautop( $wpkct_bio ) ); ?>
			</div>
		</div>
	</div>
	<div class="wpkcs-timeline-wrapper">
		<h2 class="wpkcs-section-title">
			<?php esc_html_e( 'Contributions', 'contributors-team' ); ?>
		</h2>
		<?php if ( $wpkct_query->have_posts() ) : ?>
			<div class="wpkcs-timeline">
				<?php
				while ( $wpkct_query->have_posts() ) :
					$wpkct_query->the_post();

					$wpkct_type = get_post_meta(
						get_the_ID(),
						'_wpkct_type',
						true
					);

					$wpkct_link = get_post_meta(
						get_the_ID(),
						'_wpkct_link',
						true
					);

					$wpkct_time = get_post_meta(
						get_the_ID(),
						'_wpkct_time_spent',
						true
					);

					$wpkct_date = get_post_meta(
						get_the_ID(),
						'_wpkct_date',
						true
					);
					?>
					<div class="wpkcs-timeline-item">
						<div class="wpkcs-timeline-dot"></div>
						<div class="wpkcs-timeline-card">
							<div class="wpkcs-timeline-header">
								<span class="wpkcs-contribution-type">
									<?php echo esc_html( $wpkct_type ); ?>
								</span>
								<span class="wpkcs-contribution-date">
									<?php echo esc_html( date_i18n( 'F j, Y', strtotime( $wpkct_date ) ) ); ?>
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
									⏱ <?php echo esc_html( $wpkct_time ); ?>
								</span>
								<?php if ( $wpkct_link ) : ?>
									<a
										href="<?php echo esc_url( $wpkct_link ); ?>"
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