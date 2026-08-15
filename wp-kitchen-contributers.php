<?php
/**
 * Plugin Name: Wp Contributers
 * Plugin URI: https://wpkitchen.com
 * Description: Contribution management system for WordPress contributors.
 * Version: 1.0.0
 * Author: Pomy
 * Text Domain: wp-contributers
 * Domain Path: /languages
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WPKCS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPKCS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WPKCS_VERSION', '1.0.0' );

require_once WPKCS_PLUGIN_PATH . 'includes/class-wpkcs-loader.php';
require_once WPKCS_PLUGIN_PATH . 'includes/class-wpkcs-meta-boxes.php';

new WPKCS_Meta_Boxes();

function wpkcs_run_plugin() {
	$plugin = new WPKCS_Loader();
	$plugin->run();
}

wpkcs_run_plugin();