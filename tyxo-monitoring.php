<?php
/**
 * @package Tyxo
 */
/*
Plugin Name: Tyxo Monitoring
Plugin URI: https://tyxo.com/
Description: Tyxo helps site builders and WordPress developers add uptime monitoring, live tracking, and public Page Status.
Version: 1.2
Author: Tyxo
Author URI: https://tyxo.com/
License: GPLv2 or later
Text Domain: tyxo.com
*/

define('TYXO_VERSION', '2.0');
define('TYXO_MINIMUM_WP_VERSION', '3.6');
define('TYXO_PLUGIN_NAME', basename(dirname(__FILE__)) );
define('TYXO_PLUGIN_LIB_DIR', plugin_dir_path( __FILE__ ).'lib/');
define('TYXO_PLUGIN_LIB_HTTP', plugin_dir_url("/", __FILE__).TYXO_PLUGIN_NAME.'/lib/');
define('TYXO_API_BASE', 'https://tyxo.com/api/wp/2.0/');
define('TYXO_MENU_PREFIX', 'tyxo-menu-' );

# Try to get tyxo API Key
define('TYXO_API_KEY', get_option('api_key'));
define('TYXO_PROFILE_ID', get_option('profile_big_id'));
define('TYXO_TRACKER_BIG_ID', get_option('tracker_big_id'));
define('TYXO_WEBCHECK_BIG_ID', get_option('webcheck_big_id'));
define('TYXO_STATUSPAGE_BIG_ID', get_option('statuspage_big_id'));

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

require_once(TYXO_PLUGIN_LIB_DIR.'class.tyxo.php');

add_action('init', array('TyxoClass', 'init'));
add_action('admin_menu', array( 'TyxoClass', 'generateMenu'));


/* onActivation redirect to settings page */
register_activation_hook(__FILE__, 'nht_plugin_activate');
add_action('admin_init', 'nht_plugin_redirect');

function nht_plugin_activate() {
    add_option('nht_plugin_do_activation_redirect', true);
}

function nht_plugin_redirect() {
    if (get_option('nht_plugin_do_activation_redirect', false)) {
        delete_option('nht_plugin_do_activation_redirect');
        if(!isset($_GET['activate-multi'])) {
            wp_redirect("admin.php?page=tyxo-menu-settings");
            exit;
        }
    }
}

/* Deactivation hook */
register_deactivation_hook( __FILE__, array('TyxoClass', 'plugin_deactivation'));