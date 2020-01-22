<?php
/**
 * Pipe_Careers_Search class file
 * 
 * @package Pipe Careers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class Pipe_Careers_Search {

    /**
     * Setup
     * 
     * @return void
     */
    public function __construct() {
        add_action( 'pre_get_posts', array( $this, 'search_query' ) );
        add_action( 'wp', array( $this, 'search_query_empty' ) );
        add_action( 'wp', array( $this, 'landingpage_direct' ) );
        add_action( 'wp_head', array( $this, 'allow_geolocation' ) );

        add_shortcode( 'pipecareers_search', array( $this, 'search_shortcode' ) );
        add_shortcode( 'pipe_careers_search', array( $this, 'search_shortcode' ) );
    }


    /**
     * Modify our UA local search query
     *
     * @param WP_Query $query
     * @return void
     */
    public function search_query( $query ) { 
        if ( $query->is_main_query() && ! is_admin() && is_post_type_archive( 'landingpage' ) ) {
            if ( ! empty( $_GET['pro'] ) ) {
                $meta_query = array(
                    'relation'  => 'AND',
                    array(
                        'key'       => '_pro',
                        'value'     => 'yes',
                        'compare'   => '='
                    ) 
                );
            } else {
                $meta_query = array(
                    'relation'  => 'OR',
                    array(
                        'key'       => '_pro',
                        'value'     => 'yes',
                        'compare'   => '!='
                    ),
                    array(
                        'key'       => '_pro',
                        'value'     => 'yes',
                        'compare'   => 'NOT EXISTS'
                    ),
                );
            }
    
            $query->set( 'orderby', 'title' );
            $query->set( 'order', 'ASC' );
            $query->set( 'posts_per_page', -1 );
            $query->set( 'meta_query', $meta_query );
        }
    }


    /**
     * Modify the search query if no results are returned
     *
     * @return void
     */
    public function search_query_empty() {
        global $wp_query;

        if ( empty( $wp_query->posts ) ) {
            if ( ! empty( $wp_query->query['county'] ) ) {
                $county = $wp_query->get( 'county' );
                $wp_query->set( 'county', null );
            }

            $wp_query->get_posts();
            $wp_query->set( 'county_results_none', $county );
        }
    }


    /**
     * Attempt to redirect the user to their appropriate UA local page
     *
     * @return void
     */
    public function landingpage_direct() {
        global $wp_query;

        if ( ! is_admin() && is_post_type_archive( 'landingpage' ) ) {
            if ( $wp_query->found_posts == 1 ) {
                wp_redirect( get_permalink( $wp_query->posts[0]->ID ) );
                exit;
            }
        }
    }


    /**
     * Whether to allow geolocation on a particular page
     *
     * @return void
     */
    public function allow_geolocation() {
        $allow = true;

        if ( is_singular( 'landingpage' ) ) {
            $allow = false;
        }

        $allow = apply_filters( 'pipe_careers_allow_geolocation', $allow );

        if ( ! $allow ) : 
        ?>

        <script>var pc_allow_geolocation = false;</script>

        <?php 
        endif;
    }


    /**
     * Search form shortcode
     *
     * @param array $atts
     * @param string $content
     * @return string
     */
    public function search_shortcode( $atts, $content = '' ) {
        $atts = shortcode_atts( array(
            'action'    => home_url( 'explore' )
        ), $atts );
    
        ob_start();
        ?>
    
        <div class="pipecareers-search">
            <form method="get" action="<?php print esc_url( $atts['action'] ); ?>">
                <div class="pipecareers-search-page" data-page="1">
                    <div class="fields-inline">
                        <?php wp_dropdown_categories( array(
                            'taxonomy'          => 'state',
                            'name'              => 'state',
                            'option_none_value' => '',
                            'hide_empty'        => 0,
                            'show_option_none'  => __( 'Select State', 'pipe-careers' ),
                            'value_field'       => 'slug',
                            'orderby'			=> 'name',
                            'selected'          => isset( $_GET['sel_state'] ) ? $_GET['sel_state'] : ''
                        ) ); ?>
                        <a class="button use-location" title="<?php _e( 'Use my location', 'pipe-careers' ); ?>" href="#" onclick="return false;"><span class="pcicon-gps"></span></a>
                    </div>
                </div>
                <div class="pipecareers-search-page" data-page="2" data-controls="false">
                    <label class="field-label"><?php _e( 'Are you experienced?', 'pipe-careers' ); ?></label>
                    <label for="pro-0" class="radio-label"><input type="radio" name="pro" value="0" id="pro-0" /> <?php _e( 'Little or No Experience', 'pipe-careers' ); ?></label> 
                    <label for="pro-1" class="radio-label"><input type="radio" name="pro" value="1" id="pro-1" /> <?php _e( 'A Lot of Experience', 'pipe-careers' ); ?></label>
                </div>
                <div class="pipecareers-search-page" data-page="3">
                    <?php wp_dropdown_categories( array(
                        'taxonomy'          => 'trade',
                        'name'              => 'trade',
                        'option_none_value' => '',
                        'hide_empty'        => 0,
                        'show_option_none'  => __( 'Select Trade', 'pipe-careers' ),
                        'value_field'       => 'slug',
                        'orderby'			=> 'name',
                        'selected'          => isset( $_GET['trade'] ) ? $_GET['trade'] : '',
                    ) ); ?>
                </div>
            </form>
        </div>

        <?php if ( isset( $_GET['sel_state'] ) ) : ?>

            <script>setTimeout( function() { jQuery( 'select[name="state"]' ).trigger('change' ); }, 1000 );</script>

        <?php endif; ?>
    
        <?php
        return ob_get_clean();
    }
}

return new Pipe_Careers_Search;