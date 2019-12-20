<?php
/**
 * Pipe_Careers_Setup class file
 * 
 * @package Pipe Careers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class Pipe_Careers_Setup {


    /**
     * Setup
     * 
     * @return void
     */
    public function __construct() {
        add_action( 'init', array( $this, 'post_types' ) );
        add_filter( 'post_type_link', array( $this, 'landingpage_post_link' ), 10, 2 );
        add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
    }


    /**
     * Post types and taxonomies registration
     *
     * @return void
     */
    function post_types() {
        register_post_type( 'landingpage', array(
            'labels'             => array(
                'name'               => _x( 'Landing Pages', 'post type general name', 'pipe-careers' ),
                'singular_name'      => _x( 'Landing Page', 'post type singular name', 'pipe-careers' ),
                'menu_name'          => _x( 'Landing Pages', 'admin menu', 'pipe-careers' ),
                'name_admin_bar'     => _x( 'Landing Page', 'add new on admin bar', 'pipe-careers' ),
                'add_new'            => _x( 'Add New', 'landing page', 'pipe-careers' ),
                'add_new_item'       => __( 'Add New Landing Page', 'pipe-careers' ),
                'new_item'           => __( 'New Landing Page', 'pipe-careers' ),
                'edit_item'          => __( 'Edit Landing Page', 'pipe-careers' ),
                'view_item'          => __( 'View Landing Page', 'pipe-careers' ),
                'all_items'          => __( 'All Landing Pages', 'pipe-careers' ),
                'search_items'       => __( 'Search Landing Pages', 'pipe-careers' ),
                'parent_item_colon'  => __( 'Parent Landing Pages:', 'pipe-careers' ),
                'not_found'          => __( 'No landing pages found.', 'pipe-careers' ),
                'not_found_in_trash' => __( 'No landing pages found in Trash.', 'pipe-careers' )
            ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'capability_type'    => 'post',
            'has_archive'        => 'explore',
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-clipboard',
            'rewrite'            => false,
            'supports'           => array( 'title', 'editor', 'thumbnail' )
        ) );

        add_rewrite_rule( '^explore(?:/([0-9]+))?/?$', 'index.php?post_type=landingpage&page=$matches[2]', 'top' );
        add_rewrite_rule( '^([^/]+)/([^/]+)?$', 'index.php?state=$matches[1]&pagename=$matches[2]', 'bottom' );


        register_taxonomy( 'state', 'landingpage', array(
            'hierarchical'          => true,
            'labels'                => array(
                'name'                       => _x( 'States', 'taxonomy general name', 'pipe-careers' ),
                'singular_name'              => _x( 'State', 'taxonomy singular name', 'pipe-careers' ),
                'search_items'               => __( 'Search States', 'pipe-careers' ),
                'popular_items'              => __( 'Popular States', 'pipe-careers' ),
                'all_items'                  => __( 'All States', 'pipe-careers' ),
                'parent_item'                => null,
                'parent_item_colon'          => null,
                'edit_item'                  => __( 'Edit State', 'pipe-careers' ),
                'update_item'                => __( 'Update State', 'pipe-careers' ),
                'add_new_item'               => __( 'Add New State', 'pipe-careers' ),
                'new_item_name'              => __( 'New State Name', 'pipe-careers' ),
                'separate_items_with_commas' => __( 'Separate states with commas', 'pipe-careers' ),
                'add_or_remove_items'        => __( 'Add or remove states', 'pipe-careers' ),
                'choose_from_most_used'      => __( 'Choose from the most used states', 'pipe-careers' ),
                'not_found'                  => __( 'No states found.', 'pipe-careers' ),
                'menu_name'                  => __( 'States', 'pipe-careers' ),
            ),
            'show_ui'               => true,
            'show_admin_column'     => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var'             => true,
        ) );


        register_taxonomy( 'trade', 'landingpage', array(
            'hierarchical'          => true,
            'labels'                => array(
                'name'                       => _x( 'Trades', 'taxonomy general name', 'pipe-careers' ),
                'singular_name'              => _x( 'Trade', 'taxonomy singular name', 'pipe-careers' ),
                'search_items'               => __( 'Search Trades', 'pipe-careers' ),
                'popular_items'              => __( 'Popular Trades', 'pipe-careers' ),
                'all_items'                  => __( 'All Trades', 'pipe-careers' ),
                'parent_item'                => null,
                'parent_item_colon'          => null,
                'edit_item'                  => __( 'Edit Trade', 'pipe-careers' ),
                'update_item'                => __( 'Update Trade', 'pipe-careers' ),
                'add_new_item'               => __( 'Add New Trade', 'pipe-careers' ),
                'new_item_name'              => __( 'New Trade Name', 'pipe-careers' ),
                'separate_items_with_commas' => __( 'Separate trades with commas', 'pipe-careers' ),
                'add_or_remove_items'        => __( 'Add or remove trades', 'pipe-careers' ),
                'choose_from_most_used'      => __( 'Choose from the most used trades', 'pipe-careers' ),
                'not_found'                  => __( 'No trades found.', 'pipe-careers' ),
                'menu_name'                  => __( 'Trades', 'pipe-careers' ),
            ),
            'show_ui'               => true,
            'show_admin_column'     => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var'             => true,
            'rewrite'               => array( 'slug' => 'trade' ),
        ) );


        register_taxonomy( 'county', 'landingpage', array(
            'hierarchical'          => true,
            'labels'                => array(
                'name'                       => _x( 'Counties', 'taxonomy general name', 'pipe-careers' ),
                'singular_name'              => _x( 'County', 'taxonomy singular name', 'pipe-careers' ),
                'search_items'               => __( 'Search Counties', 'pipe-careers' ),
                'popular_items'              => __( 'Popular Counties', 'pipe-careers' ),
                'all_items'                  => __( 'All Counties', 'pipe-careers' ),
                'parent_item'                => null,
                'parent_item_colon'          => null,
                'edit_item'                  => __( 'Edit County', 'pipe-careers' ),
                'update_item'                => __( 'Update County', 'pipe-careers' ),
                'add_new_item'               => __( 'Add New County', 'pipe-careers' ),
                'new_item_name'              => __( 'New County Name', 'pipe-careers' ),
                'separate_items_with_commas' => __( 'Separate counties with commas', 'pipe-careers' ),
                'add_or_remove_items'        => __( 'Add or remove counties', 'pipe-careers' ),
                'choose_from_most_used'      => __( 'Choose from the most used counties', 'pipe-careers' ),
                'not_found'                  => __( 'No counties found.', 'pipe-careers' ),
                'menu_name'                  => __( 'Counties', 'pipe-careers' ),
            ),
            'show_ui'               => true,
            'show_admin_column'     => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var'             => true,
            'rewrite'               => array( 'slug' => 'county' ),
        ) );

        $states = get_terms( array( 'taxonomy' => 'state', 'hide_empty' => false ) );

        foreach ( $states as $state ) {
            add_rewrite_rule( $state->slug . '/(.+?)/?$', 'index.php?taxonomy=state&term=' . $state->slug . '&landingpage=$matches[1]', 'top' );
        }

        register_taxonomy_for_object_type( 'state', 'landingpage' );
        register_taxonomy_for_object_type( 'trade', 'landingpage' );
        register_taxonomy_for_object_type( 'county', 'landingpage' );
    }


    public function landingpage_post_link( $post_link, $post ) {
        if ( false !== strpos( $post_link, '%state%' ) ) {
            $state_type_term = get_the_terms( $post->ID, 'state' );
            $post_link = str_replace( '%state%', array_pop( $state_type_term )->slug, $post_link );
        }

        if ( get_post_type( $post->ID ) == 'landingpage' ) {
            $state_type_term = get_the_terms( $post->ID, 'state' );
            $post_link = home_url( array_pop( $state_type_term )->slug . '/' . $post->post_name );
        }
    
        return $post_link;
    }
    

    /**
     * Attempts to redirect a user to the appropriate UA local landing page if search
     * results are narrowed down far enough
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
     * Make data available to our scripts
     *
     * @return array
     */
    public function script_data() {
        global $wp_query;

        $data = array(
            'current_url'           => home_url( $_SERVER['REQUEST_URI'] ),
            'colors'                => array( '#FFCC00', '#FF9933', '#00CCFF', '#003399', '#CC0000' ),
            'locals'                => array()
        );

        if ( $wp_query->posts ) {
            foreach ( $wp_query->posts as &$postdata ) {
                $counties = wp_get_post_terms( $postdata->ID, 'county', array( 'fields' => 'slugs' ) );

                if ( ! empty( $counties ) ) {
                    $color = next( $data['colors'] ) === false ? reset( $data['colors'] ) : current( $data['colors'] );

                    $data['locals'][ $postdata->ID ] = array(
                        'title'         => $postdata->post_title,
                        'permalink'     => $postdata->guid,
                        'fips'          => array_map( 'intval', $counties ),
                        'color'         => $color
                    );

                    $postdata->color = $color;
                }
            }
        }

        return $data;
    }


    /**
     * Register frontend scripts
     *
     * @return void
     */
    public function scripts() {
        // Slick slider
        wp_register_script( 'slick', PIPE_CAREERS_PLUGIN_URL . 'assets/js/slick.min.js' );
        wp_register_style( 'slick', PIPE_CAREERS_PLUGIN_URL . 'assets/css/slick.min.css' );

        // Mapbox
        wp_register_script( 'mapbox', PIPE_CAREERS_PLUGIN_URL . 'assets/js/mapbox-gl.min.js' );
        wp_register_style( 'mapbox', PIPE_CAREERS_PLUGIN_URL . 'assets/css/mapbox-gl.min.css' );

        // This plugin
        wp_register_script( 'pipe-careers', PIPE_CAREERS_PLUGIN_URL . 'assets/js/frontend.min.js', array( 'jquery', 'mapbox', 'slick' ), PIPE_CAREERS_VER, true );
        wp_enqueue_style( 'pipe-careers', PIPE_CAREERS_PLUGIN_URL . 'assets/css/frontend.min.css', array( 'mapbox', 'slick' ), PIPE_CAREERS_VER );

        wp_localize_script( 'pipe-careers', 'pipecareers', $this->script_data() );

        wp_enqueue_script( 'pipe-careers' );
    }
}

return new Pipe_Careers_Setup;