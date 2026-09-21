<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wpkct_status  = filter_input( INPUT_GET, 'wpkct_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
$wpkct_message = filter_input( INPUT_GET, 'message', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

if ( 'success' === $wpkct_status ) {
	?>
	<div class="wpkcs-notice-success">
		<?php echo esc_html( $wpkct_message ); ?>
	</div>
	<?php
}

if ( 'error' === $wpkct_status ) {
	?>
	<div class="wpkcs-notice-error">
		<?php echo esc_html( $wpkct_message ); ?>
	</div>
	<?php
}
?>
<form method="post" enctype="multipart/form-data" class="wpkcs-form">
	<?php wp_nonce_field( 'wpkct_submit_contribution', 'wpkct_nonce' ); ?>
	<input type="text" name="wpkct_name" placeholder="<?php esc_attr_e( 'Your Name', 'contributors-team' ); ?>" required>
	<input type="text" name="wpkct_wporg_username" placeholder="<?php esc_attr_e( 'WordPress.org Username', 'contributors-team' ); ?>" required>
	<select name="wpkct_contribution_type" required>
		<option value=""><?php esc_html_e( 'Select Contribution Type', 'contributors-team' ); ?></option>
		<option value="Photos Contribution"><?php esc_html_e( 'Photos Contribution', 'contributors-team' ); ?></option>
		<option value="Translation"><?php esc_html_e( 'Translation', 'contributors-team' ); ?></option>
		<option value="Support Forum"><?php esc_html_e( 'Support Forum', 'contributors-team' ); ?></option>
		<option value="Meetup"><?php esc_html_e( 'Meetup Participation', 'contributors-team' ); ?></option>
		<option value="Documentation"><?php esc_html_e( 'Documentation Contribution', 'contributors-team' ); ?></option>
		<option value="Learn WordPress"><?php esc_html_e( 'Learn WordPress', 'contributors-team' ); ?></option>
		<option value="Code Contribution"><?php esc_html_e( 'Code Contribution', 'contributors-team' ); ?></option>
	</select>
	<input type="text" name="wpkct_contribution_title" placeholder="<?php esc_attr_e( 'Contribution Title', 'contributors-team' ); ?>">
	<input type="url" name="wpkct_contribution_link" placeholder="<?php esc_attr_e( 'Contribution Link', 'contributors-team' ); ?>">
	<input type="text" name="wpkct_time_spent" placeholder="<?php esc_attr_e( 'Estimated Time Spent', 'contributors-team' ); ?>" required>
	<input type="date" value="<?php echo esc_attr( current_time( 'Y-m-d' ) ); ?>" name="wpkct_date" required>
	<input type="file" name="wpkct_screenshot">
	<label>
		<input type="checkbox" name="wpkct_confirmation" value="1" required>
		<?php esc_html_e( 'I agree that my name and contribution details may be publicly shared.', 'contributors-team' ); ?>
	</label>
	<button type="submit" name="wpkct_submit_form">
		<?php esc_html_e( 'Submit Contribution', 'contributors-team' ); ?>
	</button>
</form>