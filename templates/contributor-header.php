<?php
/**
 * Theme header for contributor pages.
 *
 * @package Contributors_Team
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! wp_is_block_theme() ) {
	get_header();
	return;
}

// Render both parts before wp_head() so their block styles are enqueued in time.
$wpkct_theme_parts = array();
foreach ( array( 'header', 'footer' ) as $wpkct_part ) {
	$wpkct_theme_parts[ $wpkct_part ] = do_blocks(
		'<!-- wp:template-part {"slug":"' . $wpkct_part . '","tagName":"' . $wpkct_part . '"} /-->'
	);
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="wp-site-blocks">
	<?php
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Rendered WordPress blocks.
	echo $wpkct_theme_parts['header'];
	?>
	<main id="wp--skip-link--target" class="wpkct-contributor-main">
