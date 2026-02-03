<?php
/**
 * Settings page for Lost & Found Animal plugin
 *
 * @package Lost_Found_Animal
 * @author Wojtek Kobylecki / Bella Design Studio
 * @version 1.1.0
 */

if (!defined('ABSPATH')) exit;

class LFA_Settings {

    private static $instance = null;
    private $option_key = 'lfa_settings';

    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_menu', array($this, 'add_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_color_picker'));
    }

    /**
     * Get a single setting value with fallback default
     */
    public static function get($key, $default = null) {
        $defaults = self::defaults();
        $options = get_option('lfa_settings', array());

        if ($default === null && isset($defaults[$key])) {
            $default = $defaults[$key];
        }

        return isset($options[$key]) && $options[$key] !== '' ? $options[$key] : $default;
    }

    /**
     * Default settings
     */
    public static function defaults() {
        return array(
            'columns'        => 4,
            'limit'          => -1,
            'show_filters'   => 'yes',
            'filter_bg'      => '#f5f5f4',
        );
    }

    /**
     * Add submenu under Lost & Found Animals
     */
    public function add_menu() {
        add_submenu_page(
            'edit.php?post_type=animal',
            __('Display Settings', 'lost-found-animal'),
            __('Settings', 'lost-found-animal'),
            'manage_options',
            'lfa-settings',
            array($this, 'render_page')
        );
    }

    /**
     * Enqueue color picker on settings page
     */
    public function enqueue_color_picker($hook) {
        if ($hook !== 'animal_page_lfa-settings') {
            return;
        }
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
    }

    /**
     * Register settings
     */
    public function register_settings() {
        register_setting($this->option_key, $this->option_key, array($this, 'sanitize'));

        add_settings_section(
            'lfa_display_section',
            __('Display Defaults', 'lost-found-animal'),
            array($this, 'section_description'),
            'lfa-settings'
        );

        add_settings_field(
            'columns',
            __('Grid Columns', 'lost-found-animal'),
            array($this, 'field_columns'),
            'lfa-settings',
            'lfa_display_section'
        );

        add_settings_field(
            'limit',
            __('Animals Limit', 'lost-found-animal'),
            array($this, 'field_limit'),
            'lfa-settings',
            'lfa_display_section'
        );

        add_settings_field(
            'show_filters',
            __('Show Filters', 'lost-found-animal'),
            array($this, 'field_show_filters'),
            'lfa-settings',
            'lfa_display_section'
        );

        add_settings_field(
            'filter_bg',
            __('Filter Bar Color', 'lost-found-animal'),
            array($this, 'field_filter_bg'),
            'lfa-settings',
            'lfa_display_section'
        );
    }

    /**
     * Sanitize settings
     */
    public function sanitize($input) {
        $clean = array();
        $defaults = self::defaults();

        $clean['columns'] = isset($input['columns']) ? intval($input['columns']) : $defaults['columns'];
        if ($clean['columns'] < 1 || $clean['columns'] > 4) {
            $clean['columns'] = $defaults['columns'];
        }

        $clean['limit'] = isset($input['limit']) ? intval($input['limit']) : $defaults['limit'];
        if ($clean['limit'] < -1) {
            $clean['limit'] = -1;
        }

        $clean['show_filters'] = isset($input['show_filters']) ? 'yes' : 'no';

        $clean['filter_bg'] = isset($input['filter_bg']) ? sanitize_hex_color($input['filter_bg']) : $defaults['filter_bg'];
        if (empty($clean['filter_bg'])) {
            $clean['filter_bg'] = $defaults['filter_bg'];
        }

        return $clean;
    }

    /**
     * Section description
     */
    public function section_description() {
        echo '<p>' . __('Configure default display settings for the <code>[lost_found_animals]</code> shortcode. Shortcode attributes will override these defaults.', 'lost-found-animal') . '</p>';
    }

    /**
     * Field: Columns
     */
    public function field_columns() {
        $value = self::get('columns', 4);
        echo '<select name="lfa_settings[columns]">';
        for ($i = 1; $i <= 4; $i++) {
            printf(
                '<option value="%d" %s>%d</option>',
                $i,
                selected($value, $i, false),
                $i
            );
        }
        echo '</select>';
        echo '<p class="description">' . __('Number of columns in the animal grid (1-4).', 'lost-found-animal') . '</p>';
    }

    /**
     * Field: Limit
     */
    public function field_limit() {
        $value = self::get('limit', -1);
        echo '<input type="number" name="lfa_settings[limit]" value="' . esc_attr($value) . '" min="-1" step="1" class="small-text">';
        echo '<p class="description">' . __('Maximum number of animals to display. Use -1 for unlimited.', 'lost-found-animal') . '</p>';
    }

    /**
     * Field: Show Filters
     */
    public function field_show_filters() {
        $value = self::get('show_filters', 'yes');
        echo '<label>';
        echo '<input type="checkbox" name="lfa_settings[show_filters]" value="yes" ' . checked($value, 'yes', false) . '>';
        echo ' ' . __('Display filter bar above the animal grid', 'lost-found-animal');
        echo '</label>';
    }

    /**
     * Field: Filter Bar Color
     */
    public function field_filter_bg() {
        $value = self::get('filter_bg', '#f5f5f4');
        echo '<input type="text" name="lfa_settings[filter_bg]" value="' . esc_attr($value) . '" class="lfa-color-picker" data-default-color="#f5f5f4">';
        echo '<p class="description">' . __('Background color of the filter bar.', 'lost-found-animal') . '</p>';
    }

    /**
     * Render settings page
     */
    public function render_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

            <form method="post" action="options.php">
                <?php
                settings_fields($this->option_key);
                do_settings_sections('lfa-settings');
                submit_button();
                ?>
            </form>

            <hr>
            <h2><?php _e('Shortcode Usage', 'lost-found-animal'); ?></h2>
            <p><?php _e('Use the shortcode below on any page or post. Attributes override the settings above.', 'lost-found-animal'); ?></p>
            <code>[lost_found_animals]</code>
            <p><?php _e('Available attributes:', 'lost-found-animal'); ?></p>
            <ul style="list-style:disc;padding-left:20px;">
                <li><code>limit</code> — <?php _e('Number of animals (-1 for all)', 'lost-found-animal'); ?></li>
                <li><code>columns</code> — <?php _e('Grid columns (1-4)', 'lost-found-animal'); ?></li>
                <li><code>show_filters</code> — <?php _e('"true" or "false"', 'lost-found-animal'); ?></li>
                <li><code>status</code> — <?php _e('Filter by status (e.g. "Available", "Found")', 'lost-found-animal'); ?></li>
            </ul>

            <script>
            jQuery(document).ready(function($) {
                $('.lfa-color-picker').wpColorPicker();
            });
            </script>
        </div>
        <?php
    }
}

// Initialize
LFA_Settings::instance();
