<?php
/**
 * Plugin Name: Lost & Found Animal
 * Plugin URI: https://github.com/deimos30/lost-found-animal-plugin
 * Description: Manage lost and found animals with photo gallery, filtering, and shortcode display. Works with any theme.
 * Version: 1.0.3
 * Author: Wojtek Kobylecki / Bella Design Studio
 * Author URI: https://github.com/deimos30
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: lost-found-animal
 * Requires at least: 5.0
 * Requires PHP: 7.4
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Plugin constants
define( 'LFA_VERSION', '1.0.3' );
define( 'LFA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'LFA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'LFA_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Main Plugin Class
 */
final class Lost_Found_Animal {

    /**
     * Single instance
     *
     * @var Lost_Found_Animal|null
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return Lost_Found_Animal
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
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Include required files
     */
    private function includes() {
        require_once LFA_PLUGIN_DIR . 'includes/class-post-type.php';
        require_once LFA_PLUGIN_DIR . 'includes/class-meta-boxes.php';
        require_once LFA_PLUGIN_DIR . 'includes/class-shortcodes.php';
        require_once LFA_PLUGIN_DIR . 'includes/class-admin.php';
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
        add_action( 'after_setup_theme', array( $this, 'image_sizes' ) );

        register_activation_hook( __FILE__, array( $this, 'activate' ) );
        register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
    }

    /**
     * Register custom image sizes
     */
    public function image_sizes() {
        add_image_size( 'lfa-card', 400, 300, true );
        add_image_size( 'lfa-large', 800, 600, true );
        add_image_size( 'lfa-thumb', 100, 100, true );
    }

    /**
     * Enqueue frontend scripts and styles
     */
    public function frontend_scripts() {
        global $post;

        // Load only when needed
        $should_load = false;
        if ( is_singular( 'animal' ) ) {
            $should_load = true;
        } elseif ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'lost_found_animals' ) ) {
            $should_load = true;
        }

        if ( $should_load ) {
            wp_enqueue_style( 'lfa-frontend', LFA_PLUGIN_URL . 'assets/css/frontend.css', array(), LFA_VERSION );
            wp_enqueue_script( 'lfa-frontend', LFA_PLUGIN_URL . 'assets/js/frontend.js', array( 'jquery' ), LFA_VERSION, true );
        }
    }

    /**
     * Enqueue admin scripts and styles
     *
     * @param string $hook Current admin page hook.
     */
    public function admin_scripts( $hook ) {
        global $post_type;

        if ( 'animal' === $post_type && in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
            wp_enqueue_media();
            wp_enqueue_style( 'lfa-admin', LFA_PLUGIN_URL . 'assets/css/admin.css', array(), LFA_VERSION );
            wp_enqueue_script( 'lfa-admin', LFA_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery' ), LFA_VERSION, true );
        }
    }

    /**
     * Plugin activation
     */
    public function activate() {
        LFA_Post_Type::instance()->register();
        flush_rewrite_rules();
    }

    /**
     * Plugin deactivation
     */
    public function deactivate() {
        flush_rewrite_rules();
    }
}

/**
 * Initialize plugin
 *
 * @return Lost_Found_Animal
 */
function lfa() {
    return Lost_Found_Animal::instance();
}
add_action( 'plugins_loaded', 'lfa' );

/**
 * Get animal meta value
 *
 * @param int    $post_id Post ID.
 * @param string $key     Meta key without prefix.
 * @return mixed
 */
function lfa_get_meta( $post_id, $key ) {
    return get_post_meta( $post_id, '_lfa_' . $key, true );
}

/**
 * Get gallery images
 *
 * @param int $post_id Post ID.
 * @return array
 */
function lfa_get_gallery( $post_id ) {
    $gallery = get_post_meta( $post_id, '_lfa_gallery', true );
    if ( empty( $gallery ) ) {
        return array();
    }

    $ids    = array_filter( array_map( 'intval', explode( ',', $gallery ) ) );
    $images = array();

    foreach ( $ids as $id ) {
        if ( $id > 0 ) {
            $thumb = wp_get_attachment_image_url( $id, 'lfa-thumb' );
            $card  = wp_get_attachment_image_url( $id, 'lfa-card' );
            $full  = wp_get_attachment_image_url( $id, 'large' );

            if ( $thumb ) {
                $images[] = array(
                    'id'    => $id,
                    'thumb' => $thumb,
                    'card'  => $card ? $card : $thumb,
                    'full'  => $full ? $full : $card,
                );
            }
        }
    }

    return $images;
}

/**
 * Get all images (featured + gallery)
 *
 * @param int $post_id Post ID.
 * @return array
 */
function lfa_get_all_images( $post_id ) {
    $images = array();

    // Featured image first
    if ( has_post_thumbnail( $post_id ) ) {
        $id       = get_post_thumbnail_id( $post_id );
        $images[] = array(
            'id'    => $id,
            'thumb' => get_the_post_thumbnail_url( $post_id, 'lfa-thumb' ),
            'card'  => get_the_post_thumbnail_url( $post_id, 'lfa-card' ),
            'full'  => get_the_post_thumbnail_url( $post_id, 'large' ),
        );
    }

    // Gallery images (avoid duplicates)
    $gallery     = lfa_get_gallery( $post_id );
    $featured_id = has_post_thumbnail( $post_id ) ? get_post_thumbnail_id( $post_id ) : 0;

    foreach ( $gallery as $img ) {
        if ( $img['id'] !== $featured_id ) {
            $images[] = $img;
        }
    }

    return $images;
}

/**
 * Get status badge data
 *
 * @param string $status Status value.
 * @return array
 */
function lfa_get_badge( $status ) {
    $badges = array(
        'Found Today'   => array(
            'color' => '#f97316',
            'text'  => 'FOUND TODAY',
        ),
        'Found'         => array(
            'color' => '#3b82f6',
            'text'  => 'FOUND',
        ),
        'Available'     => array(
            'color' => '#8b5cf6',
            'text'  => 'FOR ADOPTION',
        ),
        'Reunited'      => array(
            'color' => '#10b981',
            'text'  => 'REUNITED!',
        ),
        'Not Available' => array(
            'color' => '#6b7280',
            'text'  => 'NOT AVAILABLE',
        ),
    );

    return isset( $badges[ $status ] ) ? $badges[ $status ] : array(
        'color' => '#6b7280',
        'text'  => strtoupper( $status ),
    );
}
