<?php
/**
 * Settings Page for Lost & Found Animal
 *
 * @package Lost_Found_Animal
 * @author  Wojtek Kobylecki / Bella Design Studio
 * @version 1.0.5
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Settings Class
 */
class LFA_Settings {

    /**
     * Single instance
     *
     * @var LFA_Settings|null
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return LFA_Settings
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
        add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
    }

    /**
     * Add settings page under Animals menu
     */
    public function add_settings_page() {
        add_submenu_page(
            'edit.php?post_type=animal',
            __( 'Settings', 'lost-found-animal' ),
            __( 'Settings', 'lost-found-animal' ),
            'manage_options',
            'lfa-settings',
            array( $this, 'render_settings_page' )
        );
    }

    /**
     * Register settings
     */
    public function register_settings() {
        register_setting( 'lfa_settings_group', 'lfa_settings', array( $this, 'sanitize_settings' ) );

        // === DISPLAY SECTION ===
        add_settings_section(
            'lfa_display_section',
            __( 'Grid Settings', 'lost-found-animal' ),
            array( $this, 'display_section_callback' ),
            'lfa-settings'
        );

        add_settings_field(
            'columns',
            __( 'Grid Columns', 'lost-found-animal' ),
            array( $this, 'columns_field' ),
            'lfa-settings',
            'lfa_display_section'
        );

        add_settings_field(
            'limit',
            __( 'Animals Limit', 'lost-found-animal' ),
            array( $this, 'limit_field' ),
            'lfa-settings',
            'lfa_display_section'
        );

        // === FILTER BAR SECTION ===
        add_settings_section(
            'lfa_filter_section',
            __( 'Filter Bar Settings', 'lost-found-animal' ),
            array( $this, 'filter_section_callback' ),
            'lfa-settings'
        );

        add_settings_field(
            'show_filters',
            __( 'Show Filter Bar', 'lost-found-animal' ),
            array( $this, 'show_filters_field' ),
            'lfa-settings',
            'lfa_filter_section'
        );

        add_settings_field(
            'filter_width',
            __( 'Filter Bar Width', 'lost-found-animal' ),
            array( $this, 'filter_width_field' ),
            'lfa-settings',
            'lfa_filter_section'
        );

        add_settings_field(
            'filter_alignment',
            __( 'Filter Bar Alignment', 'lost-found-animal' ),
            array( $this, 'filter_alignment_field' ),
            'lfa-settings',
            'lfa_filter_section'
        );

        // === COLORS SECTION ===
        add_settings_section(
            'lfa_colors_section',
            __( 'Color Settings', 'lost-found-animal' ),
            array( $this, 'colors_section_callback' ),
            'lfa-settings'
        );

        add_settings_field(
            'filter_bar_color',
            __( 'Filter Bar Background', 'lost-found-animal' ),
            array( $this, 'filter_bar_color_field' ),
            'lfa-settings',
            'lfa_colors_section'
        );

        add_settings_field(
            'reset_button_color',
            __( 'Reset Button Color', 'lost-found-animal' ),
            array( $this, 'reset_button_color_field' ),
            'lfa-settings',
            'lfa_colors_section'
        );
    }

    /**
     * Sanitize settings
     *
     * @param array $input Input values.
     * @return array
     */
    public function sanitize_settings( $input ) {
        $sanitized = array();

        // Columns
        $sanitized['columns'] = isset( $input['columns'] ) ? absint( $input['columns'] ) : 4;
        if ( $sanitized['columns'] < 1 || $sanitized['columns'] > 4 ) {
            $sanitized['columns'] = 4;
        }

        // Limit
        $sanitized['limit'] = isset( $input['limit'] ) ? intval( $input['limit'] ) : -1;

        // Show filters
        $sanitized['show_filters'] = isset( $input['show_filters'] ) && 'yes' === $input['show_filters'] ? 'yes' : 'no';

        // Filter width
        $valid_widths = array( 'compact', 'medium', 'large', 'full' );
        $sanitized['filter_width'] = isset( $input['filter_width'] ) && in_array( $input['filter_width'], $valid_widths, true ) ? $input['filter_width'] : 'medium';

        // Filter alignment
        $valid_alignments = array( 'left', 'center', 'right' );
        $sanitized['filter_alignment'] = isset( $input['filter_alignment'] ) && in_array( $input['filter_alignment'], $valid_alignments, true ) ? $input['filter_alignment'] : 'left';

        // Colors
        $sanitized['filter_bar_color'] = isset( $input['filter_bar_color'] ) ? sanitize_hex_color( $input['filter_bar_color'] ) : '#f5f5f4';
        if ( empty( $sanitized['filter_bar_color'] ) ) {
            $sanitized['filter_bar_color'] = '#f5f5f4';
        }

        $sanitized['reset_button_color'] = isset( $input['reset_button_color'] ) ? sanitize_hex_color( $input['reset_button_color'] ) : '#e7e5e4';
        if ( empty( $sanitized['reset_button_color'] ) ) {
            $sanitized['reset_button_color'] = '#e7e5e4';
        }

        return $sanitized;
    }

    /**
     * Display section callback
     */
    public function display_section_callback() {
        echo '<p>' . esc_html__( 'Configure how the animals grid is displayed.', 'lost-found-animal' ) . '</p>';
    }

    /**
     * Filter section callback
     */
    public function filter_section_callback() {
        echo '<p>' . esc_html__( 'Configure the filter bar appearance and position.', 'lost-found-animal' ) . '</p>';
    }

    /**
     * Colors section callback
     */
    public function colors_section_callback() {
        echo '<p>' . esc_html__( 'Customize the colors to match your theme.', 'lost-found-animal' ) . '</p>';
    }

    /**
     * Columns field
     */
    public function columns_field() {
        $options = get_option( 'lfa_settings', array() );
        $value   = isset( $options['columns'] ) ? $options['columns'] : 4;
        ?>
        <select name="lfa_settings[columns]" id="lfa_columns">
            <option value="1" <?php selected( $value, 1 ); ?>>1 <?php esc_html_e( 'Column', 'lost-found-animal' ); ?></option>
            <option value="2" <?php selected( $value, 2 ); ?>>2 <?php esc_html_e( 'Columns', 'lost-found-animal' ); ?></option>
            <option value="3" <?php selected( $value, 3 ); ?>>3 <?php esc_html_e( 'Columns', 'lost-found-animal' ); ?></option>
            <option value="4" <?php selected( $value, 4 ); ?>>4 <?php esc_html_e( 'Columns', 'lost-found-animal' ); ?></option>
        </select>
        <p class="description"><?php esc_html_e( 'Number of columns in the animals grid.', 'lost-found-animal' ); ?></p>
        <?php
    }

    /**
     * Limit field
     */
    public function limit_field() {
        $options = get_option( 'lfa_settings', array() );
        $value   = isset( $options['limit'] ) ? $options['limit'] : -1;
        ?>
        <input type="number" name="lfa_settings[limit]" id="lfa_limit" value="<?php echo esc_attr( $value ); ?>" min="-1" class="small-text">
        <p class="description"><?php esc_html_e( 'Maximum number of animals to display. Use -1 for unlimited.', 'lost-found-animal' ); ?></p>
        <?php
    }

    /**
     * Show filters field
     */
    public function show_filters_field() {
        $options = get_option( 'lfa_settings', array() );
        $value   = isset( $options['show_filters'] ) ? $options['show_filters'] : 'yes';
        ?>
        <label style="margin-right: 20px;">
            <input type="radio" name="lfa_settings[show_filters]" value="yes" <?php checked( $value, 'yes' ); ?>>
            <?php esc_html_e( 'Yes', 'lost-found-animal' ); ?>
        </label>
        <label>
            <input type="radio" name="lfa_settings[show_filters]" value="no" <?php checked( $value, 'no' ); ?>>
            <?php esc_html_e( 'No', 'lost-found-animal' ); ?>
        </label>
        <?php
    }

    /**
     * Filter width field
     */
    public function filter_width_field() {
        $options = get_option( 'lfa_settings', array() );
        $value   = isset( $options['filter_width'] ) ? $options['filter_width'] : 'medium';
        ?>
        <select name="lfa_settings[filter_width]" id="lfa_filter_width">
            <option value="compact" <?php selected( $value, 'compact' ); ?>><?php esc_html_e( 'Compact', 'lost-found-animal' ); ?> (520px)</option>
            <option value="medium" <?php selected( $value, 'medium' ); ?>><?php esc_html_e( 'Medium', 'lost-found-animal' ); ?> (720px)</option>
            <option value="large" <?php selected( $value, 'large' ); ?>><?php esc_html_e( 'Large', 'lost-found-animal' ); ?> (920px)</option>
            <option value="full" <?php selected( $value, 'full' ); ?>><?php esc_html_e( 'Full Width', 'lost-found-animal' ); ?> (100%)</option>
        </select>
        <p class="description"><?php esc_html_e( 'Maximum width of the filter bar. On mobile, it will always be full width.', 'lost-found-animal' ); ?></p>
        <?php
    }

    /**
     * Filter alignment field
     */
    public function filter_alignment_field() {
        $options = get_option( 'lfa_settings', array() );
        $value   = isset( $options['filter_alignment'] ) ? $options['filter_alignment'] : 'left';
        ?>
        <label style="margin-right: 20px;">
            <input type="radio" name="lfa_settings[filter_alignment]" value="left" <?php checked( $value, 'left' ); ?>>
            <?php esc_html_e( 'Left', 'lost-found-animal' ); ?>
        </label>
        <label style="margin-right: 20px;">
            <input type="radio" name="lfa_settings[filter_alignment]" value="center" <?php checked( $value, 'center' ); ?>>
            <?php esc_html_e( 'Center', 'lost-found-animal' ); ?>
        </label>
        <label>
            <input type="radio" name="lfa_settings[filter_alignment]" value="right" <?php checked( $value, 'right' ); ?>>
            <?php esc_html_e( 'Right', 'lost-found-animal' ); ?>
        </label>
        <p class="description"><?php esc_html_e( 'Horizontal alignment of the filter bar.', 'lost-found-animal' ); ?></p>
        <?php
    }

    /**
     * Filter bar color field
     */
    public function filter_bar_color_field() {
        $options = get_option( 'lfa_settings', array() );
        $value   = isset( $options['filter_bar_color'] ) ? $options['filter_bar_color'] : '#f5f5f4';
        ?>
        <input type="text" name="lfa_settings[filter_bar_color]" id="lfa_filter_bar_color" value="<?php echo esc_attr( $value ); ?>" class="lfa-color-picker" data-default-color="#f5f5f4">
        <?php
    }

    /**
     * Reset button color field
     */
    public function reset_button_color_field() {
        $options = get_option( 'lfa_settings', array() );
        $value   = isset( $options['reset_button_color'] ) ? $options['reset_button_color'] : '#e7e5e4';
        ?>
        <input type="text" name="lfa_settings[reset_button_color]" id="lfa_reset_button_color" value="<?php echo esc_attr( $value ); ?>" class="lfa-color-picker" data-default-color="#e7e5e4">
        <?php
    }

    /**
     * Render settings page
     */
    public function render_settings_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        // Show save message
        if ( isset( $_GET['settings-updated'] ) ) {
            add_settings_error( 'lfa_messages', 'lfa_message', __( 'Settings saved.', 'lost-found-animal' ), 'updated' );
        }

        settings_errors( 'lfa_messages' );
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

            <form action="options.php" method="post">
                <?php
                settings_fields( 'lfa_settings_group' );
                do_settings_sections( 'lfa-settings' );
                submit_button( __( 'Save Settings', 'lost-found-animal' ) );
                ?>
            </form>

            <hr>

            <h2><?php esc_html_e( 'Shortcode Usage', 'lost-found-animal' ); ?></h2>
            <p><?php esc_html_e( 'Use the shortcode below to display animals on any page:', 'lost-found-animal' ); ?></p>
            <code style="display:inline-block;padding:8px 12px;background:#f0f0f0;border-radius:4px;">[lost_found_animals]</code>

            <p style="margin-top: 15px;"><?php esc_html_e( 'Override settings with parameters:', 'lost-found-animal' ); ?></p>
            <code style="display:inline-block;padding:8px 12px;background:#f0f0f0;border-radius:4px;">[lost_found_animals limit="8" columns="2" show_filters="false"]</code>

            <h3 style="margin-top:20px;"><?php esc_html_e( 'Available Parameters', 'lost-found-animal' ); ?></h3>
            <table class="widefat" style="max-width:600px;">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Parameter', 'lost-found-animal' ); ?></th>
                        <th><?php esc_html_e( 'Description', 'lost-found-animal' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td><code>limit</code></td><td><?php esc_html_e( 'Number of animals (-1 for all)', 'lost-found-animal' ); ?></td></tr>
                    <tr><td><code>columns</code></td><td><?php esc_html_e( 'Grid columns (1-4)', 'lost-found-animal' ); ?></td></tr>
                    <tr><td><code>show_filters</code></td><td><?php esc_html_e( 'Show filter bar (true/false)', 'lost-found-animal' ); ?></td></tr>
                    <tr><td><code>status</code></td><td><?php esc_html_e( 'Filter by status', 'lost-found-animal' ); ?></td></tr>
                </tbody>
            </table>
        </div>

        <script>
        jQuery(document).ready(function($) {
            $('.lfa-color-picker').wpColorPicker();
        });
        </script>
        <?php
    }
}

// Initialize
LFA_Settings::instance();
