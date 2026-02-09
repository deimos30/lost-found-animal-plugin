<?php
/**
 * Custom Post Type: Animal
 *
 * @package Lost_Found_Animal
 * @author  Wojtek Kobylecki / Bella Design Studio
 * @version 1.0.5
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Post Type Class
 */
class LFA_Post_Type {

    private static $instance = null;

    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'init', array( $this, 'register' ) );
    }

    public function register() {
        $labels = array(
            'name'               => __( 'Animals', 'lost-found-animal' ),
            'singular_name'      => __( 'Animal', 'lost-found-animal' ),
            'menu_name'          => __( 'Lost & Found Animals', 'lost-found-animal' ),
            'add_new'            => __( 'Add New', 'lost-found-animal' ),
            'add_new_item'       => __( 'Add New Animal', 'lost-found-animal' ),
            'edit_item'          => __( 'Edit Animal', 'lost-found-animal' ),
            'new_item'           => __( 'New Animal', 'lost-found-animal' ),
            'view_item'          => __( 'View Animal', 'lost-found-animal' ),
            'search_items'       => __( 'Search Animals', 'lost-found-animal' ),
            'not_found'          => __( 'No animals found', 'lost-found-animal' ),
            'not_found_in_trash' => __( 'No animals found in trash', 'lost-found-animal' ),
            'all_items'          => __( 'All Animals', 'lost-found-animal' ),
        );

        $args = array(
            'labels'              => $labels,
            'public'              => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'query_var'           => true,
            'rewrite'             => array( 'slug' => 'animal' ),
            'capability_type'     => 'post',
            'has_archive'         => true,
            'hierarchical'        => false,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-pets',
            'supports'            => array( 'title', 'editor', 'thumbnail' ),
            'show_in_rest'        => false,
        );

        register_post_type( 'animal', $args );
    }
}

LFA_Post_Type::instance();
