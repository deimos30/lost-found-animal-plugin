<?php
/**
 * Meta Boxes for Animal
 * 
 * @package Lost_Found_Animal
 * @author Wojtek Kobylecki / Bella Design Studio
 * @version 1.0.1
 */

if (!defined('ABSPATH')) exit;

class LFA_Meta_Boxes {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post_animal', array($this, 'save'), 10, 2);
        add_action('do_meta_boxes', array($this, 'rename_featured_image'));
    }
    
    public function add_meta_boxes() {
        add_meta_box(
            'lfa_details',
            __('Animal Details', 'lost-found-animal'),
            array($this, 'render_details'),
            'animal',
            'normal',
            'high'
        );
        
        add_meta_box(
            'lfa_gallery',
            __('Photo Gallery (Additional Photos)', 'lost-found-animal'),
            array($this, 'render_gallery'),
            'animal',
            'normal',
            'high'
        );
    }
    
    public function rename_featured_image() {
        remove_meta_box('postimagediv', 'animal', 'side');
        add_meta_box(
            'postimagediv',
            __('Main Photo (Featured Image)', 'lost-found-animal'),
            'post_thumbnail_meta_box',
            'animal',
            'side',
            'high'
        );
    }
    
    public function render_details($post) {
        // Single nonce for ALL fields
        wp_nonce_field('lfa_save_animal_data', 'lfa_nonce');
        
        // Get values
        $type = get_post_meta($post->ID, '_lfa_type', true);
        $location = get_post_meta($post->ID, '_lfa_location', true);
        $breed = get_post_meta($post->ID, '_lfa_breed', true);
        $color = get_post_meta($post->ID, '_lfa_color', true);
        $gender = get_post_meta($post->ID, '_lfa_gender', true);
        $age = get_post_meta($post->ID, '_lfa_age', true);
        $status = get_post_meta($post->ID, '_lfa_status', true);
        $found_date = get_post_meta($post->ID, '_lfa_found_date', true);
        $microchip = get_post_meta($post->ID, '_lfa_microchip', true);
        
        if (empty($status)) $status = 'Found';
        if (empty($type)) $type = 'Dog';
        ?>
        
        <div class="lfa-help-box">
            <strong>📷 <?php _e('How to add photos:', 'lost-found-animal'); ?></strong><br>
            1. <strong><?php _e('Main Photo:', 'lost-found-animal'); ?></strong> <?php _e('Use "Main Photo (Featured Image)" in the right sidebar (scroll down) - this is the PRIMARY photo shown on cards.', 'lost-found-animal'); ?><br>
            2. <strong><?php _e('Extra Photos:', 'lost-found-animal'); ?></strong> <?php _e('Use "Photo Gallery" section below to add more photos.', 'lost-found-animal'); ?>
        </div>
        
        <table class="lfa-table">
            <tr>
                <th><label for="lfa_type"><?php _e('Animal Type', 'lost-found-animal'); ?> *</label></th>
                <td>
                    <select id="lfa_type" name="lfa_type">
                        <option value="Dog" <?php selected($type, 'Dog'); ?>><?php _e('Dog', 'lost-found-animal'); ?></option>
                        <option value="Cat" <?php selected($type, 'Cat'); ?>><?php _e('Cat', 'lost-found-animal'); ?></option>
                        <option value="Other" <?php selected($type, 'Other'); ?>><?php _e('Other', 'lost-found-animal'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="lfa_location"><?php _e('Location Found', 'lost-found-animal'); ?> *</label></th>
                <td>
                    <input type="text" id="lfa_location" name="lfa_location" value="<?php echo esc_attr($location); ?>" class="regular-text" placeholder="<?php _e('e.g., Bures Road, Suffolk', 'lost-found-animal'); ?>">
                </td>
            </tr>
            <tr>
                <th><label for="lfa_breed"><?php _e('Breed', 'lost-found-animal'); ?></label></th>
                <td>
                    <input type="text" id="lfa_breed" name="lfa_breed" value="<?php echo esc_attr($breed); ?>" class="regular-text" placeholder="<?php _e('e.g., Beagle Mix, Golden Retriever', 'lost-found-animal'); ?>">
                </td>
            </tr>
            <tr>
                <th><label for="lfa_color"><?php _e('Color / Markings', 'lost-found-animal'); ?></label></th>
                <td>
                    <input type="text" id="lfa_color" name="lfa_color" value="<?php echo esc_attr($color); ?>" class="regular-text" placeholder="<?php _e('e.g., Brown with white patches', 'lost-found-animal'); ?>">
                </td>
            </tr>
            <tr>
                <th><label for="lfa_gender"><?php _e('Gender', 'lost-found-animal'); ?></label></th>
                <td>
                    <select id="lfa_gender" name="lfa_gender">
                        <option value=""><?php _e('Select Gender', 'lost-found-animal'); ?></option>
                        <option value="Male" <?php selected($gender, 'Male'); ?>><?php _e('Male', 'lost-found-animal'); ?></option>
                        <option value="Female" <?php selected($gender, 'Female'); ?>><?php _e('Female', 'lost-found-animal'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="lfa_age"><?php _e('Estimated Age', 'lost-found-animal'); ?></label></th>
                <td>
                    <input type="text" id="lfa_age" name="lfa_age" value="<?php echo esc_attr($age); ?>" class="regular-text" placeholder="<?php _e('e.g., Puppy, Adult, Senior, ~2 years', 'lost-found-animal'); ?>">
                </td>
            </tr>
            <tr>
                <th><label for="lfa_status"><?php _e('Status', 'lost-found-animal'); ?> *</label></th>
                <td>
                    <select id="lfa_status" name="lfa_status">
                        <option value="Found Today" <?php selected($status, 'Found Today'); ?>><?php _e('Found Today', 'lost-found-animal'); ?></option>
                        <option value="Found" <?php selected($status, 'Found'); ?>><?php _e('Found', 'lost-found-animal'); ?></option>
                        <option value="Available" <?php selected($status, 'Available'); ?>><?php _e('Available for Adoption', 'lost-found-animal'); ?></option>
                        <option value="Reunited" <?php selected($status, 'Reunited'); ?>><?php _e('Reunited', 'lost-found-animal'); ?></option>
                        <option value="Not Available" <?php selected($status, 'Not Available'); ?>><?php _e('Not Available', 'lost-found-animal'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="lfa_found_date"><?php _e('Date Found', 'lost-found-animal'); ?></label></th>
                <td>
                    <input type="date" id="lfa_found_date" name="lfa_found_date" value="<?php echo esc_attr($found_date); ?>">
                </td>
            </tr>
            <tr>
                <th><label for="lfa_microchip"><?php _e('Microchip', 'lost-found-animal'); ?></label></th>
                <td>
                    <select id="lfa_microchip" name="lfa_microchip">
                        <option value=""><?php _e('Unknown', 'lost-found-animal'); ?></option>
                        <option value="Yes" <?php selected($microchip, 'Yes'); ?>><?php _e('Yes', 'lost-found-animal'); ?></option>
                        <option value="No" <?php selected($microchip, 'No'); ?>><?php _e('No', 'lost-found-animal'); ?></option>
                        <option value="Unreadable" <?php selected($microchip, 'Unreadable'); ?>><?php _e('Unreadable', 'lost-found-animal'); ?></option>
                    </select>
                </td>
            </tr>
        </table>
        <?php
    }
    
    public function render_gallery($post) {
        $gallery = get_post_meta($post->ID, '_lfa_gallery', true);
        $ids = !empty($gallery) ? array_filter(array_map('intval', explode(',', $gallery))) : array();
        ?>
        
        <p class="description"><?php _e('Add extra photos of this animal. These will appear in a gallery on the animal\'s detail page.', 'lost-found-animal'); ?></p>
        
        <div id="lfa-gallery-wrapper">
            <div id="lfa-gallery-images">
                <?php
                foreach ($ids as $id) {
                    $url = wp_get_attachment_image_url($id, 'thumbnail');
                    if ($url) {
                        echo '<div class="lfa-gallery-item" data-id="' . esc_attr($id) . '">';
                        echo '<img src="' . esc_url($url) . '" alt="">';
                        echo '<button type="button" class="lfa-remove-image">&times;</button>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
            
            <input type="hidden" name="lfa_gallery" id="lfa_gallery" value="<?php echo esc_attr($gallery); ?>">
            
            <p style="margin-top: 15px;">
                <button type="button" id="lfa-add-images" class="button button-primary">
                    <?php _e('Add Photos', 'lost-found-animal'); ?>
                </button>
            </p>
        </div>
        <?php
    }
    
    public function save($post_id, $post) {
        // Verify nonce
        if (!isset($_POST['lfa_nonce']) || !wp_verify_nonce($_POST['lfa_nonce'], 'lfa_save_animal_data')) {
            return;
        }
        
        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Fields to save
        $fields = array('type', 'status', 'location', 'breed', 'color', 'gender', 'age', 'found_date', 'microchip');
        
        foreach ($fields as $field) {
            $key = 'lfa_' . $field;
            if (isset($_POST[$key])) {
                $value = sanitize_text_field($_POST[$key]);
                update_post_meta($post_id, '_lfa_' . $field, $value);
            }
        }
        
        // Save gallery - CRITICAL
        if (isset($_POST['lfa_gallery'])) {
            $raw = sanitize_text_field($_POST['lfa_gallery']);
            
            // Clean: keep only valid numeric IDs
            $ids = explode(',', $raw);
            $clean = array();
            
            foreach ($ids as $id) {
                $id = trim($id);
                if (is_numeric($id) && intval($id) > 0) {
                    $clean[] = intval($id);
                }
            }
            
            $value = implode(',', $clean);
            update_post_meta($post_id, '_lfa_gallery', $value);
        }
    }
}

// Initialize
LFA_Meta_Boxes::instance();
