<?php
/**
 * Main Pipe_Careers class file
 * 
 * @package Pipe Careers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class Pipe_Careers {

    /**
	 * Plugin object
	 */
    private static $instance;


    /**
     * Insures that only one instance of Pipe_Careers exists in memory at any one time.
     * 
     * @return Pipe_Careers The one true instance of Pipe_Careers
     */
    public static function instance() {
        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Pipe_Careers ) ) {
            self::$instance = new Pipe_Careers;
            self::$instance->includes();

            do_action_ref_array( 'pipe_careers_loaded', self::$instance ); 
        }
        
        return self::$instance;
    }


    /**
     * Include the goodies
     *
     * @return void
     */
    public function includes() {
        include_once 'class-pipe-careers-setup.php';
        include_once 'class-pipe-careers-search.php';
        include_once 'class-pipe-careers-settings.php';
    }


    /**
     * Throw error on object clone
     *
     * @return void
     */
    public function __clone() {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'pipe-careers' ), '1.0.0' );
    }


    /**
     * Disable unserializing of the class
     * 
     * @return void
     */
    public function __wakeup() {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'pipe-careers' ), '1.0.0' );
    }

}