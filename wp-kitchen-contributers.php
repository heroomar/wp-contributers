<?php
/**
 * Plugin Name: Wp Kitchen Contributers
 * Plugin URI: https://example.com
 * Description: Contribution management system for WP Kitchen contributors.
 * Version: 1.0.0
 * Author: Your Name
 * Text Domain: wp-kitchen-contributers
 * Domain Path: /languages
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
