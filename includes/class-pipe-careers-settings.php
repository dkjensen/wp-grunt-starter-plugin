<?php
/**
 * Pipe_Careers_Settings class file
 * 
 * @package Pipe Careers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class Pipe_Careers_Settings {

    /**
     * Setup
     * 
     * @return void
     */
    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save' ) );
    }


    /**
     * Register new meta boxes on landing pages
     *
     * @return void
     */
    public function meta_boxes() {
        add_meta_box( 'landingpage_options', __( 'Options', 'pipe-careers' ), array( $this, 'options_meta_box' ), 'landingpage', 'side' );
    }


    /**
     * Meta box content
     *
     * @param [type] $post
     * @return void
     */
    public function options_meta_box( $post ) {
        ?>

        <p><label><input type="checkbox" name="_pro" value="yes" <?php checked( get_post_meta( $post->ID, '_pro', true ), 'yes' ); ?> /> <?php _e( 'Professionals only?', 'pipe-careers' ); ?></label></p>

        <?php
    }


    /**
     * Save hook custom meta boxes
     *
     * @param integer $post_id
     * @return void
     */
    public function save( $post_id ) {
        if ( isset( $_POST['_pro'] ) ) {
            update_post_meta( $post_id, '_pro', $_POST['_pro'] );
        }
    }

}

return new Pipe_Careers_Settings;