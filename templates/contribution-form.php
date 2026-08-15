<?php
if ( isset( $_GET['wpkcs_status'] ) ) {
	$status  = sanitize_text_field( wp_unslash( $_GET['wpkcs_status'] ) );
	$message = isset( $_GET['message'] ) ? sanitize_text_field( wp_unslash( $_GET['message'] ) ) : '';

	if ( 'success' === $status ) {
		echo '<div class="wpkcs-notice-success">' . esc_html( $message ) . '</div>';
	}

	if ( 'error' === $status ) {
		echo '<div class="wpkcs-notice-error">' . esc_html( $message ) . '</div>';
	}
}
?>
<form method="post" enctype="multipart/form-data" class="wpkcs-form">
	<?php wp_nonce_field( 'wpkcs_submit_contribution', 'wpkcs_nonce' ); ?>
	<input type="text" name="wpkcs_name" placeholder="<?php esc_attr_e( 'Your Name', 'wpkcs' ); ?>" required>
	<input type="text" name="wpkcs_wporg_username" placeholder="<?php esc_attr_e( 'WordPress.org Username', 'wpkcs' ); ?>" required>
	<select name="wpkcs_contribution_type" required>
		<option value=""><?php esc_html_e( 'Select Contribution Type', 'wpkcs' ); ?></option>
		<option value="Photos Contribution"><?php esc_html_e( 'Photos Contribution', 'wpkcs' ); ?></option>
		<option value="Translation"><?php esc_html_e( 'Translation', 'wpkcs' ); ?></option>
		<option value="Support Forum"><?php esc_html_e( 'Support Forum', 'wpkcs' ); ?></option>
		<option value="Meetup"><?php esc_html_e( 'Meetup Participation', 'wpkcs' ); ?></option>
		<option value="Documentation"><?php esc_html_e( 'Documentation Contribution', 'wpkcs' ); ?></option>
		<option value="Learn WordPress"><?php esc_html_e( 'Learn WordPress', 'wpkcs' ); ?></option>
		<option value="Code Contribution"><?php esc_html_e( 'Code Contribution', 'wpkcs' ); ?></option>
	</select>
	<input type="text" name="wpkcs_contribution_title" placeholder="<?php esc_attr_e( 'Contribution Title', 'wpkcs' ); ?>">
	<input type="url" name="wpkcs_contribution_link" placeholder="<?php esc_attr_e( 'Contribution Link', 'wpkcs' ); ?>">
	<input type="text" name="wpkcs_time_spent" placeholder="<?php esc_attr_e( 'Estimated Time Spent', 'wpkcs' ); ?>" required>
	<input type="date" value="<?php echo esc_attr( current_time( 'Y-m-d' ) ); ?>" name="wpkcs_date" required>
	<input type="file" name="wpkcs_screenshot">
	<label>
		<input type="checkbox" name="wpkcs_confirmation" value="1" required>
		<?php esc_html_e( 'I agree that my name and contribution details may be publicly shared.', 'wpkcs' ); ?>
	</label>
	<button type="submit" name="wpkcs_submit_form">
		<?php esc_html_e( 'Submit Contribution', 'wpkcs' ); ?>
	</button>
</form>