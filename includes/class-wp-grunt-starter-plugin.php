<?php
/**
 * Main WP_Grunt_Starter_Plugin class file
 * 
 * @package WP Grunt Starter Plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class WP_Grunt_Starter_Plugin {

    /**
	 * Plugin object
	 */
    private static $instance;


    /**
     * Insures that only one instance of WP_Grunt_Starter_Plugin exists in memory at any one time.
     * 
     * @return WP_Grunt_Starter_Plugin The one true instance of WP_Grunt_Starter_Plugin
     */
    public static function instance() {
        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof WP_Grunt_Starter_Plugin ) ) {
            self::$instance = new WP_Grunt_Starter_Plugin;
            self::$instance->includes();

            do_action_ref_array( 'wp_grunt_starter_plugin_loaded', self::$instance ); 
        }
        
        return self::$instance;
    }


    /**
     * Include the goodies
     *
     * @return void
     */
    public function includes() {
        
    }


    /**
     * Throw error on object clone
     *
     * @return void
     */
    public function __clone() {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wp-grunt-starter-plugin' ), '1.0.0' );
    }


    /**
     * Disable unserializing of the class
     * 
     * @return void
     */
    public function __wakeup() {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wp-grunt-starter-plugin' ), '1.0.0' );
    }

}