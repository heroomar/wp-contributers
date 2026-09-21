<?php
/**
 * Plugin Name: Contributors Team
 * Plugin URI: https://wpkitchen.com/contributors-team
 * Description: Contributors management and contribution tracking for WordPress communities.
 * Version: 1.0.0
 * Author: Abdul Rahman Pomy
 * Text Domain: contributors-team
 * Domain Path: /languages
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WPKCT_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPKCT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WPKCT_VERSION', '1.0.0' );

require_once WPKCT_PLUGIN_PATH . 'includes/class-wpkcs-loader.php';
require_once WPKCT_PLUGIN_PATH . 'includes/class-wpkcs-meta-boxes.php';

new WPKCT_Meta_Boxes();

function wpkct_run_plugin() {
	$plugin = new WPKCT_Loader();
	$plugin->run();
}

wpkct_run_plugin();