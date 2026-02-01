<?php
/**
 * Shortcodes for displaying animals
 * 
 * @package Lost_Found_Animal
 * @author Wojtek Kobylecki / Bella Design Studio
 * @version 1.0.1
 */

if (!defined('ABSPATH')) exit;

class LFA_Shortcodes {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_shortcode('lost_found_animals', array($this, 'render_grid'));
        add_filter('single_template', array($this, 'single_template'));
    }
    
    public function render_grid($atts) {
        $atts = shortcode_atts(array(
            'limit' => -1,
            'status' => '',
            'columns' => 4,
            'show_filters' => 'true',
        ), $atts);
        
        $args = array(
            'post_type' => 'animal',
            'posts_per_page' => intval($atts['limit']),
            'orderby' => 'date',
            'order' => 'DESC',
        );
        
        if (!empty($atts['status'])) {
            $args['meta_query'] = array(
                array(
                    'key' => '_lfa_status',
                    'value' => sanitize_text_field($atts['status']),
                )
            );
        }
        
        $query = new WP_Query($args);
        $columns = intval($atts['columns']);
        if ($columns < 1 || $columns > 4) $columns = 4;
        
        ob_start();
        ?>
        <div class="lfa-container">
            
            <?php if ($atts['show_filters'] === 'true') : ?>
            <div class="lfa-filters">
                <div class="lfa-filters-row">
                    <span class="lfa-count"><?php printf(__('Showing %s animal(s)', 'lost-found-animal'), '<span id="lfa-count">' . $query->found_posts . '</span>'); ?></span>
                    
                    <div class="lfa-controls">
                        <select id="lfa-filter-status" class="lfa-select">
                            <option value=""><?php _e('All Status', 'lost-found-animal'); ?></option>
                            <option value="Found Today"><?php _e('Found Today', 'lost-found-animal'); ?></option>
                            <option value="Found"><?php _e('Found', 'lost-found-animal'); ?></option>
                            <option value="Available"><?php _e('Available', 'lost-found-animal'); ?></option>
                            <option value="Reunited"><?php _e('Reunited', 'lost-found-animal'); ?></option>
                            <option value="Not Available"><?php _e('Not Available', 'lost-found-animal'); ?></option>
                        </select>
                        
                        <select id="lfa-filter-gender" class="lfa-select">
                            <option value=""><?php _e('All Genders', 'lost-found-animal'); ?></option>
                            <option value="Male"><?php _e('Male', 'lost-found-animal'); ?></option>
                            <option value="Female"><?php _e('Female', 'lost-found-animal'); ?></option>
                        </select>
                        
                        <select id="lfa-sort" class="lfa-select">
                            <option value="newest"><?php _e('Newest First', 'lost-found-animal'); ?></option>
                            <option value="oldest"><?php _e('Oldest First', 'lost-found-animal'); ?></option>
                            <option value="name-asc"><?php _e('Name A-Z', 'lost-found-animal'); ?></option>
                            <option value="name-desc"><?php _e('Name Z-A', 'lost-found-animal'); ?></option>
                        </select>
                        
                        <button type="button" id="lfa-reset" class="lfa-reset"><?php _e('Reset', 'lost-found-animal'); ?></button>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <div id="lfa-grid" class="lfa-grid lfa-cols-<?php echo $columns; ?>">
                <?php
                if ($query->have_posts()) :
                    while ($query->have_posts()) : $query->the_post();
                        $this->render_card(get_the_ID());
                    endwhile;
                    wp_reset_postdata();
                else :
                ?>
                <div class="lfa-empty">
                    <p><?php _e('No animals found.', 'lost-found-animal'); ?></p>
                </div>
                <?php endif; ?>
            </div>
            
            <div id="lfa-no-results" class="lfa-empty" style="display: none;">
                <p><?php _e('No animals match your filters.', 'lost-found-animal'); ?></p>
                <button type="button" onclick="document.getElementById('lfa-reset').click();" class="lfa-btn"><?php _e('Reset Filters', 'lost-found-animal'); ?></button>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    private function render_card($post_id) {
        $status = lfa_get_meta($post_id, 'status');
        $location = lfa_get_meta($post_id, 'location');
        $breed = lfa_get_meta($post_id, 'breed');
        $gender = lfa_get_meta($post_id, 'gender');
        $found_date = lfa_get_meta($post_id, 'found_date');
        
        $images = lfa_get_all_images($post_id);
        $badge = lfa_get_badge($status);
        $has_gallery = count($images) > 1;
        ?>
        <div class="lfa-card" 
             data-status="<?php echo esc_attr($status); ?>"
             data-gender="<?php echo esc_attr($gender); ?>"
             data-date="<?php echo get_the_date('Y-m-d'); ?>"
             data-name="<?php echo esc_attr(get_the_title()); ?>">
            
            <a href="<?php the_permalink(); ?>" class="lfa-card-image">
                <?php if (!empty($images)) : ?>
                    <div class="lfa-gallery-hover" data-images='<?php echo esc_attr(json_encode(array_column($images, 'card'))); ?>'>
                        <?php foreach ($images as $i => $img) : ?>
                            <img src="<?php echo esc_url($img['card']); ?>" 
                                 alt="<?php the_title_attribute(); ?>"
                                 class="<?php echo $i === 0 ? 'active' : ''; ?>">
                        <?php endforeach; ?>
                        
                        <?php if ($has_gallery) : ?>
                            <div class="lfa-dots">
                                <?php for ($i = 0; $i < count($images); $i++) : ?>
                                    <span class="<?php echo $i === 0 ? 'active' : ''; ?>"></span>
                                <?php endfor; ?>
                            </div>
                            <span class="lfa-photo-count"><?php echo count($images); ?></span>
                        <?php endif; ?>
                    </div>
                <?php else : ?>
                    <div class="lfa-no-photo">
                        <span><?php _e('No Photo', 'lost-found-animal'); ?></span>
                    </div>
                <?php endif; ?>
                
                <span class="lfa-badge" style="background: <?php echo esc_attr($badge['color']); ?>;">
                    <?php echo esc_html($badge['text']); ?>
                </span>
            </a>
            
            <div class="lfa-card-body">
                <h3 class="lfa-card-title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h3>
                
                <p class="lfa-card-location">
                    <?php echo esc_html($location ? $location : __('Unknown location', 'lost-found-animal')); ?>
                </p>
                
                <div class="lfa-card-tags">
                    <?php if ($breed) : ?>
                        <span class="lfa-tag"><?php echo esc_html($breed); ?></span>
                    <?php endif; ?>
                    <?php if ($gender) : ?>
                        <span class="lfa-tag"><?php echo esc_html($gender); ?></span>
                    <?php endif; ?>
                    <?php if ($found_date) : ?>
                        <span class="lfa-tag"><?php echo date_i18n('j M', strtotime($found_date)); ?></span>
                    <?php endif; ?>
                </div>
                
                <a href="<?php the_permalink(); ?>" class="lfa-btn"><?php _e('View Details', 'lost-found-animal'); ?></a>
            </div>
        </div>
        <?php
    }
    
    public function single_template($template) {
        global $post;
        
        if ($post && $post->post_type === 'animal') {
            $plugin_template = LFA_PLUGIN_DIR . 'templates/single-animal.php';
            if (file_exists($plugin_template)) {
                return $plugin_template;
            }
        }
        
        return $template;
    }
}

// Initialize
LFA_Shortcodes::instance();
