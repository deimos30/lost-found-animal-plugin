<?php
/**
 * Admin functionality - columns
 * 
 * @package Lost_Found_Animal
 * @author Wojtek Kobylecki / Bella Design Studio
 * @version 1.0.1
 */

if (!defined('ABSPATH')) exit;

class LFA_Admin {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_filter('manage_animal_posts_columns', array($this, 'columns'));
        add_action('manage_animal_posts_custom_column', array($this, 'column_content'), 10, 2);
        add_filter('manage_edit-animal_sortable_columns', array($this, 'sortable'));
    }
    
    public function columns($columns) {
        $new = array();
        $new['cb'] = $columns['cb'];
        $new['lfa_photo'] = __('Photo', 'lost-found-animal');
        $new['title'] = $columns['title'];
        $new['lfa_status'] = __('Status', 'lost-found-animal');
        $new['lfa_location'] = __('Location', 'lost-found-animal');
        $new['lfa_breed'] = __('Breed', 'lost-found-animal');
        $new['lfa_type'] = __('Type', 'lost-found-animal');
        $new['date'] = $columns['date'];
        return $new;
    }
    
    public function column_content($column, $post_id) {
        switch ($column) {
            case 'lfa_photo':
                if (has_post_thumbnail($post_id)) {
                    echo get_the_post_thumbnail($post_id, array(50, 50), array('style' => 'border-radius:4px;'));
                } else {
                    echo '<span style="color:#999;">' . __('No photo', 'lost-found-animal') . '</span>';
                }
                break;
                
            case 'lfa_status':
                $status = get_post_meta($post_id, '_lfa_status', true);
                $badge = lfa_get_badge($status);
                echo '<span style="background:' . esc_attr($badge['color']) . ';color:#fff;padding:3px 8px;border-radius:3px;font-size:11px;">';
                echo esc_html($badge['text']);
                echo '</span>';
                break;
                
            case 'lfa_location':
                echo esc_html(get_post_meta($post_id, '_lfa_location', true));
                break;
                
            case 'lfa_breed':
                echo esc_html(get_post_meta($post_id, '_lfa_breed', true));
                break;
                
            case 'lfa_type':
                $type = get_post_meta($post_id, '_lfa_type', true);
                echo esc_html($type ? $type : 'Dog');
                break;
        }
    }
    
    public function sortable($columns) {
        $columns['lfa_status'] = 'lfa_status';
        return $columns;
    }
}

// Initialize
LFA_Admin::instance();
