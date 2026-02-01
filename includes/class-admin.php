<?php
/**
 * Admin functionality - columns
 *
 * @package Lost_Found_Animal
 * @author  Wojtek Kobylecki / Bella Design Studio
 * @version 1.0.2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Admin Class
 */
class LFA_Admin {

    /**
     * Single instance
     *
     * @var LFA_Admin|null
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return LFA_Admin
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        add_filter( 'manage_animal_posts_columns', array( $this, 'columns' ) );
        add_action( 'manage_animal_posts_custom_column', array( $this, 'column_content' ), 10, 2 );
        add_filter( 'manage_edit-animal_sortable_columns', array( $this, 'sortable' ) );
    }

    /**
     * Define custom columns
     *
     * @param array $columns Existing columns.
     * @return array
     */
    public function columns( $columns ) {
        $new               = array();
        $new['cb']         = $columns['cb'];
        $new['lfa_photo']  = __( 'Photo', 'lost-found-animal' );
        $new['title']      = $columns['title'];
        $new['lfa_status'] = __( 'Status', 'lost-found-animal' );
        $new['lfa_location'] = __( 'Location', 'lost-found-animal' );
        $new['lfa_breed']  = __( 'Breed', 'lost-found-animal' );
        $new['lfa_type']   = __( 'Type', 'lost-found-animal' );
        $new['date']       = $columns['date'];
        return $new;
    }

    /**
     * Render custom column content
     *
     * @param string $column  Column name.
     * @param int    $post_id Post ID.
     */
    public function column_content( $column, $post_id ) {
        switch ( $column ) {
            case 'lfa_photo':
                if ( has_post_thumbnail( $post_id ) ) {
                    echo get_the_post_thumbnail( $post_id, array( 50, 50 ), array( 'style' => 'border-radius:4px;' ) );
                } else {
                    echo '<span style="color:#999;">' . esc_html__( 'No photo', 'lost-found-animal' ) . '</span>';
                }
                break;

            case 'lfa_status':
                $status = get_post_meta( $post_id, '_lfa_status', true );
                $badge  = lfa_get_badge( $status );
                echo '<span style="background:' . esc_attr( $badge['color'] ) . ';color:#fff;padding:3px 8px;border-radius:3px;font-size:11px;">';
                echo esc_html( $badge['text'] );
                echo '</span>';
                break;

            case 'lfa_location':
                echo esc_html( get_post_meta( $post_id, '_lfa_location', true ) );
                break;

            case 'lfa_breed':
                echo esc_html( get_post_meta( $post_id, '_lfa_breed', true ) );
                break;

            case 'lfa_type':
                $type = get_post_meta( $post_id, '_lfa_type', true );
                echo esc_html( $type ? $type : 'Dog' );
                break;
        }
    }

    /**
     * Define sortable columns
     *
     * @param array $columns Sortable columns.
     * @return array
     */
    public function sortable( $columns ) {
        $columns['lfa_status'] = 'lfa_status';
        return $columns;
    }
}

// Initialize
LFA_Admin::instance();
