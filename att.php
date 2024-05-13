<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/EsubalewAmenu
 * @since             1.0.0
 * @package           Attp
 *
 * @wordpress-plugin
 * Plugin Name:       ADA Tracking Tool
 * Plugin URI:        https://github.com/EsubalewAmenu/ADA-Tracking-tool
 * Description:       ADA Tracking tool
 * Version:           1.0.0
 * Author:            Esubalew A
 * Author URI:        https://github.com/EsubalewAmenu/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       attp
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
define( 'ATTP_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-attp-activator.php
 */
function attp_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-attp-activator.php';
	Attp_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-attp-deactivator.php
 */
function attp_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-attp-deactivator.php';
	Attp_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'attp_activate' );
register_deactivation_hook( __FILE__, 'attp_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-attp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function attp_run() {

	$plugin = new Attp();
	$plugin->run();

}
attp_run();
