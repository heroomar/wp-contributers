<?php
if ( isset( $_GET['wpkcs_status'] ) ) {

	$status  = sanitize_text_field( wp_unslash( $_GET['wpkcs_status'] ) );
	$message = isset( $_GET['message'] )
		? sanitize_text_field( wp_unslash( $_GET['message'] ) )
		: '';

	if ( 'success' === $status ) {
		echo '<div class="wpkcs-notice-success">';
		echo esc_html( $message );
		echo '</div>';
	}

	if ( 'error' === $status ) {
		echo '<div class="wpkcs-notice-error">';
		echo esc_html( $message );
		echo '</div>';
	}
}
?>
<form method="post" enctype="multipart/form-data" class="wpkcs-form">

	<?php wp_nonce_field( 'wpkcs_submit_contribution', 'wpkcs_nonce' ); ?>

	<input type="text" name="wpkcs_name" placeholder="Your Name" required>

	<input type="text" name="wpkcs_wporg_username" placeholder="WordPress.org Username" required>

	<select name="wpkcs_contribution_type" required>
		<option value="">Select Contribution Type</option>
		<option value="Photos Contribution">Photos Contribution</option>
		<option value="Translation">Translation</option>
		<option value="Support Forum">Support Forum</option>
		<option value="Meetup">Meetup Participation</option>
		<option value="Documentation">Documentation Contribution</option>
		<option value="Learn WordPress">Learn WordPress</option>
		<option value="Code Contribution">Code Contribution</option>
		<option value="Other">Other</option>
	</select>

	<input type="text" name="wpkcs_contribution_title" placeholder="Contribution Title" >

	<input type="url" name="wpkcs_contribution_link" placeholder="Contribution Link" >

	<input type="text" name="wpkcs_time_spent" placeholder="Estimated Time Spent" required>

	<input type="date" value="<?= date("Y-m-d") ?>" name="wpkcs_date" required>

	<input type="file" name="wpkcs_screenshot">

	<label>
		<input type="checkbox" name="wpkcs_confirmation" value="1" required>
		I agree that my name and contribution details may be publicly shared.
	</label>

	<button type="submit" name="wpkcs_submit_form">
		Submit Contribution
	</button>

</form>