<?php
$status  = filter_input( INPUT_GET, 'wpkcs_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
$message = filter_input( INPUT_GET, 'message', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

if ( 'success' === $status ) {
	?>
	<div class="wpkcs-notice-success">
		<?php echo esc_html( $message ); ?>
	</div>
	<?php
}

if ( 'error' === $status ) {
	?>
	<div class="wpkcs-notice-error">
		<?php echo esc_html( $message ); ?>
	</div>
	<?php
}
?>
<form method="post" enctype="multipart/form-data" class="wpkcs-form">
	<?php wp_nonce_field( 'wpkcs_submit_contribution', 'wpkcs_nonce' ); ?>
	<input type="text" name="wpkcs_name" placeholder="<?php esc_attr_e( 'Your Name', 'wp-contributers' ); ?>" required>
	<input type="text" name="wpkcs_wporg_username" placeholder="<?php esc_attr_e( 'WordPress.org Username', 'wp-contributers' ); ?>" required>
	<select name="wpkcs_contribution_type" required>
		<option value=""><?php esc_html_e( 'Select Contribution Type', 'wp-contributers' ); ?></option>
		<option value="Photos Contribution"><?php esc_html_e( 'Photos Contribution', 'wp-contributers' ); ?></option>
		<option value="Translation"><?php esc_html_e( 'Translation', 'wp-contributers' ); ?></option>
		<option value="Support Forum"><?php esc_html_e( 'Support Forum', 'wp-contributers' ); ?></option>
		<option value="Meetup"><?php esc_html_e( 'Meetup Participation', 'wp-contributers' ); ?></option>
		<option value="Documentation"><?php esc_html_e( 'Documentation Contribution', 'wp-contributers' ); ?></option>
		<option value="Learn WordPress"><?php esc_html_e( 'Learn WordPress', 'wp-contributers' ); ?></option>
		<option value="Code Contribution"><?php esc_html_e( 'Code Contribution', 'wp-contributers' ); ?></option>
	</select>
	<input type="text" name="wpkcs_contribution_title" placeholder="<?php esc_attr_e( 'Contribution Title', 'wp-contributers' ); ?>">
	<input type="url" name="wpkcs_contribution_link" placeholder="<?php esc_attr_e( 'Contribution Link', 'wp-contributers' ); ?>">
	<input type="text" name="wpkcs_time_spent" placeholder="<?php esc_attr_e( 'Estimated Time Spent', 'wp-contributers' ); ?>" required>
	<input type="date" value="<?php echo esc_attr( current_time( 'Y-m-d' ) ); ?>" name="wpkcs_date" required>
	<input type="file" name="wpkcs_screenshot">
	<label>
		<input type="checkbox" name="wpkcs_confirmation" value="1" required>
		<?php esc_html_e( 'I agree that my name and contribution details may be publicly shared.', 'wp-contributers' ); ?>
	</label>
	<button type="submit" name="wpkcs_submit_form">
		<?php esc_html_e( 'Submit Contribution', 'wp-contributers' ); ?>
	</button>
</form>