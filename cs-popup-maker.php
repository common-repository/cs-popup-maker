<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              #
 * @since             1.0
 * @package           Cs_Popup_Maker
 *
 * @wordpress-plugin
 * Plugin Name:       CS Popup Maker
 * Plugin URI:        #
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           3.0.3
 * Author:            catchsquare
 * Contributors:      catchsquare,csarmy,ashokmhrj,abindrard
 * Author URI:        https://catchsquare.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cs-popup-maker
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
define( 'CS_POPUP_MAKER_VERSION', '3.0.3' );

/**
 * Plugin Path
 */
define( 'CS_POPUP_MAKER_PATH', plugin_dir_path(__FILE__) );
define( 'CS_POPUP_MAKER_URL', plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cs-popup-maker-activator.php
 */
function activate_cs_popup_maker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cs-popup-maker-activator.php';
	Cs_Popup_Maker_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cs-popup-maker-deactivator.php
 */
function deactivate_cs_popup_maker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cs-popup-maker-deactivator.php';
	Cs_Popup_Maker_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_cs_popup_maker' );
register_deactivation_hook( __FILE__, 'deactivate_cs_popup_maker' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cs-popup-maker.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cs_popup_maker() {

	$plugin = new Cs_Popup_Maker();
	$plugin->run();

}
run_cs_popup_maker();
