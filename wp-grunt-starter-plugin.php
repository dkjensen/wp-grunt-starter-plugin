<?php
/**
 * Plugin Name: WP Grunt Starter Plugin
 * Description: 
 * Version: 1.0.0
 * Author: David Jensen
 * Author URI: https://dkjensen.com
 * Text Domain: wp-grunt-starter-plugin
 *
 * @package WP Grunt Starter Plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $wp_version;

define( 'WP_GRUNT_STARTER_PLUGIN_VER', '1.0.0' );
define( 'WP_GRUNT_STARTER_PLUGIN_PLUGIN_NAME', 'WP Grunt Starter Plugin' );
define( 'WP_GRUNT_STARTER_PLUGIN_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WP_GRUNT_STARTER_PLUGIN_PLUGIN_URL', plugin_dir_url( __FILE__ ) );


// Load Composer
require WP_GRUNT_STARTER_PLUGIN_PLUGIN_DIR . 'vendor/autoload.php';
require WP_GRUNT_STARTER_PLUGIN_PLUGIN_DIR . 'includes/class-wp-grunt-starter-plugin.php';


function WP_Grunt_Starter_Plugin() {
    return WP_Grunt_Starter_Plugin::instance();
}
WP_Grunt_Starter_Plugin();