<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://getmysite.in
 * @since             1.0.0
 * @package           Sample_Dk
 *
 * @wordpress-plugin
 * Plugin Name:       sample
 * Plugin URI:        https://getmysite.in
 * Description:       testing plugin generator by website
 * Version:           1.0.0
 * Author:            dinesh
 * Author URI:        https://getmysite.in/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sample-dk
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SAMPLE_DK_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sample-dk-activator.php
 */
function activate_sample_dk() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sample-dk-activator.php';
	Sample_Dk_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sample-dk-deactivator.php
 */
function deactivate_sample_dk() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sample-dk-deactivator.php';
	Sample_Dk_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_sample_dk' );
register_deactivation_hook( __FILE__, 'deactivate_sample_dk' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-sample-dk.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_sample_dk() {

	$plugin = new Sample_Dk();
	$plugin->run();

}
run_sample_dk();
