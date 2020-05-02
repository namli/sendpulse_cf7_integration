<?php

/**
 * The plugin bootstrap file.
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @see              https://namli.ru
 * @since             0.1.0
 *
 * @wordpress-plugin
 * Plugin Name:       SentPulse CF7 integration
 * Plugin URI:        https://github.com/namli/sendpulse_cf7_integration
 * Description:       This is plugin add tab to CF7 form , with ability to post form fields to SendPulse
 * Version:           0.1.0
 * Author:            Aleksei Andrushchenko
 * Author URI:        https://namli.ru
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sendpulse_cf7
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/*
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('SENDPULSE_CF7_VERSION', '0.1.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sendpulse_cf7-activator.php.
 */
function activate_sendpulse_cf7()
{
    require_once plugin_dir_path(__FILE__).'includes/class-sendpulse_cf7-activator.php';
    Sendpulse_cf7_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sendpulse_cf7-deactivator.php.
 */
function deactivate_sendpulse_cf7()
{
    require_once plugin_dir_path(__FILE__).'includes/class-sendpulse_cf7-deactivator.php';
    Sendpulse_cf7_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_sendpulse_cf7');
register_deactivation_hook(__FILE__, 'deactivate_sendpulse_cf7');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__).'includes/class-sendpulse_cf7.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_sendpulse_cf7()
{
    $plugin = new Sendpulse_cf7();
    $plugin->run();
}
run_sendpulse_cf7();
