<?php
/**
 * Theme footer for contributor pages.
 *
 * @package Contributors_Team
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! wp_is_block_theme() ) {
	get_footer();
	return;
}
?>
	</main>
	<?php
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Rendered WordPress blocks.
	echo $wpkct_theme_parts['footer'];
	?>
</div>
<?php wp_footer(); ?>
</body>
</html>
