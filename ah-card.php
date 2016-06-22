<?php

/**
 * AH Card
 *
 * This Plugin lets a user Generate an unique card number for spesific roles.
 *
 * @link              https://www.andreherberth.com
 * @since             0.8.0
 * @package           Ah_Card
 *
 * @wordpress-plugin
 * Plugin Name:       AH Card
 * Plugin URI:        https://github.com/andreherberth/ah-card
 * Description:       This Plugin allows the generation of a Card number for members of spesified groups. 
 * Version:           0.8.5
 * Author:            Andre Herberth
 * Author URI:        https://www.ourwp.net
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ah-card
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * First, we utilize a custom REPO for updates. Spesifically we are downloading from GITHUB.
 * Credits for the code: https://github.com/YahnisElsts/plugin-update-checker
 * License: MIT License (https://en.wikipedia.org/wiki/MIT_License)
 */
require '3rdparty/updater/plugin-update-checker.php';
$className = PucFactory::getLatestClassVersion('PucGitHubChecker');
$myUpdateChecker = new $className(
    'https://github.com/andreherberth/ah-card',
    __FILE__,
    'master'
);

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ah-card-activator.php
 */
function activate_ah_card() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ah-card-activator.php';
	Ah_Card_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ah-card-deactivator.php
 */
function deactivate_ah_card() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ah-card-deactivator.php';
	Ah_Card_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ah_card' );
register_deactivation_hook( __FILE__, 'deactivate_ah_card' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ah-card.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.8.0
 */
function run_ah_card() {

	$plugin = new Ah_Card();
	$plugin->run();

}
run_ah_card();
