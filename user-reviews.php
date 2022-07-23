<?php
ob_start();
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.fiverr.com/junaidzx90
 * @since             1.0.0
 * @package           User_Reviews
 *
 * @wordpress-plugin
 * Plugin Name:       User Reviews
 * Plugin URI:        https://www.fiverr.com
 * Description:       This plugin is used to collect user reviews.
 * Version:           1.0.0
 * Author:            Developer Junayed
 * Author URI:        https://www.fiverr.com/junaidzx90
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       user-reviews
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

header("Cache-Control: no-cache");

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'USER_REVIEWS_VERSION', '1.0.0' );
$imageAlert = '';
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-user-reviews-activator.php
 */
function activate_user_reviews() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-user-reviews-activator.php';
	User_Reviews_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-user-reviews-deactivator.php
 */
function deactivate_user_reviews() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-user-reviews-deactivator.php';
	User_Reviews_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_user_reviews' );
register_deactivation_hook( __FILE__, 'deactivate_user_reviews' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-user-reviews.php';

function get_ur_reviews_ratings($ratings){
	$output = '';
	$x = 0;
	for($i = 0; $i < intval($ratings); $i++){
		$output .= '<i style="color: #ff9800;" class="fas fa-star"></i>';
		$x++;
	}
	$left = 5-$x;
	for ($y=0; $y < $left; $y++) { 
		$output .= '<i style="color: #ddd;" class="fas fa-star"></i>';
	}

	return $output;
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_user_reviews() {

	$plugin = new User_Reviews();
	$plugin->run();

}
run_user_reviews();
