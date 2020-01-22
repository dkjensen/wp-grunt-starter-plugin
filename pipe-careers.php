<?php
/**
 * Plugin Name: Pipe Careers
 * Description: UA local search and mapping features
 * Version: 1.0.11
 * Author: David Jensen
 * Author URI: https://dkjensen.com
 * Text Domain: pipe-careers
 *
 * @package Pipe Careers
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $wp_version;

define( 'PIPE_CAREERS_VER', '1.0.10' );
define( 'PIPE_CAREERS_PLUGIN_NAME', 'Pipe Careers' );
define( 'PIPE_CAREERS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PIPE_CAREERS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );


// Load Composer
require PIPE_CAREERS_PLUGIN_DIR . 'vendor/autoload.php';
require PIPE_CAREERS_PLUGIN_DIR . 'includes/class-pipe-careers.php';


function Pipe_Careers() {
    return Pipe_Careers::instance();
}
Pipe_Careers();