<?php

/**
 * Plugin Name:       Rank Savant 
 * Plugin URI:        https://ranksavant.com/
 * Description:       Turn Your Website Into a Local Search Magnet. Rank Savant is a game changer for Local SEO
 * Version:           1.0.0
 * Author:            Rocketship
 * Author URI:        https://getrocketship.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ranksavant-integration
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


defined( 'RANKSAVANT_INTEGRATION_PATH' ) || define( 'RANKSAVANT_INTEGRATION_PATH', plugin_dir_path( __FILE__ ) );
defined( 'RANKSAVANT_INTEGRATION_URL' ) || define( 'RANKSAVANT_INTEGRATION_URL',  plugin_dir_url( __FILE__ ) );
defined( 'RANKSAVANT_INTEGRATION_BASE' ) || define( 'RANKSAVANT_INTEGRATION_BASE', plugin_basename( __FILE__ ) );
defined( 'RANKSAVANT_INTEGRATION_DIR' ) || define( 'RANKSAVANT_INTEGRATION_DIR', plugin_basename( __DIR__ ) );
$RANKSAVANT_INTEGRATION_version = get_file_data(RANKSAVANT_INTEGRATION_PATH.basename(RANKSAVANT_INTEGRATION_BASE), array('Version'), 'plugin');
$RANKSAVANT_INTEGRATION_textDomain = get_file_data(RANKSAVANT_INTEGRATION_PATH.basename(RANKSAVANT_INTEGRATION_BASE), array('Text Domain'), 'plugin');
$RANKSAVANT_INTEGRATION_pluginName = get_file_data(RANKSAVANT_INTEGRATION_PATH.basename(RANKSAVANT_INTEGRATION_BASE), array('Plugin Name'), 'plugin');

/**
 * Currently plugin version.
 */
defined( 'RANKSAVANT_INTEGRATION_VERSION' ) || define( 'RANKSAVANT_INTEGRATION_VERSION', $RANKSAVANT_INTEGRATION_version[0] );

/**
 * The unique identifier.
 */
defined( 'RANKSAVANT_INTEGRATION_DOMAIN' ) || define( 'RANKSAVANT_INTEGRATION_DOMAIN', $RANKSAVANT_INTEGRATION_textDomain[0] );

/**
 * Plugin Name
 */
defined( 'RANKSAVANT_INTEGRATION_NAME' ) || define( 'RANKSAVANT_INTEGRATION_NAME', $RANKSAVANT_INTEGRATION_pluginName[0] );


/**
 * The code that runs during plugin activation.
*/
function ranksavant_activate_integration() {
	require_once RANKSAVANT_INTEGRATION_PATH . 'includes/class-ranksavant-integration-activator.php';
	RANKSAVANT_INTEGRATION_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function ranksavant_deactivate_integration() {
	require_once RANKSAVANT_INTEGRATION_PATH . 'includes/class-ranksavant-integration-deactivator.php';
	RANKSAVANT_INTEGRATION_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'ranksavant_activate_integration' );
register_deactivation_hook( __FILE__, 'ranksavant_deactivate_integration' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require RANKSAVANT_INTEGRATION_PATH . 'includes/class-ranksavant-integration.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function ranksavant_run_integration() {

	$plugin = new RANKSAVANT_INTEGRATION();
	$plugin->run();

}
ranksavant_run_integration();
