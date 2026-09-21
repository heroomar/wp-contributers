<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wpkcs-contributors-grid">
	<?php
	if ( $query->have_posts() ) :
		while ( $query->have_posts() ) :
			$query->the_post();

			$post_id    = get_the_ID();
			$wpkct_contributor = new WPKCT_Contributor( $post_id );
			$wpkct_avatar      = $wpkct_contributor->get_avatar();
			$wpkct_username    = $wpkct_contributor->get_username();
			?>
			<div
				class="wpkcs-contributor-card"
				style="cursor: pointer;"
				onclick="window.location.href='<?php echo esc_url( '/contributions/?p=' . rawurlencode( $wpkct_username ) ); ?>'"
			>
				<div class="wpkcs-card-avatar">
					<?php if ( $wpkct_avatar ) : ?>
						<img
							src="<?php echo esc_url( $wpkct_avatar ); ?>"
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
						<?php echo esc_html( get_post_meta( $post_id, '_wpkct_org_name', true ) ); ?>
					</h3>
					<div class="wpkcs-card-username">
						@<?php echo esc_html( $wpkct_username ); ?>
					</div>
					<div class="wpkcs-card-footer">
						<span class="wpkcs-card-count">
							<?php echo esc_html( $wpkct_contributor->get_cotribution_count() ); ?>
							<?php esc_html_e( 'Contributions', 'contributors-team' ); ?>
						</span>
					</div>
				</div>
			</div>
			<?php
		endwhile;

		wp_reset_postdata();
	else :
		echo '<p>' . esc_html__( 'No contributors found.', 'contributors-team' ) . '</p>';
	endif;
	?>
</div>