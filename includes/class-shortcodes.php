<?php
/**
 * Shortcodes for displaying animals
 *
 * @package Lost_Found_Animal
 * @author  Wojtek Kobylecki / Bella Design Studio
 * @version 1.0.2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Shortcodes Class
 */
class LFA_Shortcodes {

    /**
     * Single instance
     *
     * @var LFA_Shortcodes|null
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return LFA_Shortcodes
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
        add_shortcode( 'lost_found_animals', array( $this, 'render_grid' ) );
        add_filter( 'single_template', array( $this, 'single_template' ) );
    }

    /**
     * Render animals grid
     *
     * @param array $atts Shortcode attributes.
     * @return string
     */
    public function render_grid( $atts ) {
        $atts = shortcode_atts(
            array(
                'limit'        => -1,
                'status'       => '',
                'columns'      => 4,
                'show_filters' => 'true',
            ),
            $atts
        );

        $args = array(
            'post_type'      => 'animal',
            'posts_per_page' => intval( $atts['limit'] ),
            'orderby'        => 'date',
            'order'          => 'DESC',
        );

        if ( ! empty( $atts['status'] ) ) {
            $args['meta_query'] = array(
                array(
                    'key'   => '_lfa_status',
                    'value' => sanitize_text_field( $atts['status'] ),
                ),
            );
        }

        $query   = new WP_Query( $args );
        $columns = intval( $atts['columns'] );
        if ( $columns < 1 || $columns > 4 ) {
            $columns = 4;
        }

        ob_start();
        ?>
        <div class="lfa-container">

            <?php if ( 'true' === $atts['show_filters'] ) : ?>
            <div class="lfa-filters">
                <div class="lfa-filters-row">
                    <span class="lfa-count">
                        <?php
                        printf(
                            /* translators: %s: number of animals */
                            esc_html__( 'Showing %s animal(s)', 'lost-found-animal' ),
                            '<span id="lfa-count">' . esc_html( $query->found_posts ) . '</span>'
                        );
                        ?>
                    </span>

                    <div class="lfa-controls">
                        <select id="lfa-filter-status" class="lfa-select">
                            <option value=""><?php esc_html_e( 'All Status', 'lost-found-animal' ); ?></option>
                            <option value="Found Today"><?php esc_html_e( 'Found Today', 'lost-found-animal' ); ?></option>
                            <option value="Found"><?php esc_html_e( 'Found', 'lost-found-animal' ); ?></option>
                            <option value="Available"><?php esc_html_e( 'Available', 'lost-found-animal' ); ?></option>
                            <option value="Reunited"><?php esc_html_e( 'Reunited', 'lost-found-animal' ); ?></option>
                            <option value="Not Available"><?php esc_html_e( 'Not Available', 'lost-found-animal' ); ?></option>
                        </select>

                        <select id="lfa-filter-gender" class="lfa-select">
                            <option value=""><?php esc_html_e( 'All Genders', 'lost-found-animal' ); ?></option>
                            <option value="Male"><?php esc_html_e( 'Male', 'lost-found-animal' ); ?></option>
                            <option value="Female"><?php esc_html_e( 'Female', 'lost-found-animal' ); ?></option>
                        </select>

                        <select id="lfa-sort" class="lfa-select">
                            <option value="newest"><?php esc_html_e( 'Newest First', 'lost-found-animal' ); ?></option>
                            <option value="oldest"><?php esc_html_e( 'Oldest First', 'lost-found-animal' ); ?></option>
                            <option value="name-asc"><?php esc_html_e( 'Name A-Z', 'lost-found-animal' ); ?></option>
                            <option value="name-desc"><?php esc_html_e( 'Name Z-A', 'lost-found-animal' ); ?></option>
                        </select>

                        <button type="button" id="lfa-reset" class="lfa-reset"><?php esc_html_e( 'Reset', 'lost-found-animal' ); ?></button>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div id="lfa-grid" class="lfa-grid lfa-cols-<?php echo esc_attr( $columns ); ?>">
                <?php
                if ( $query->have_posts() ) :
                    while ( $query->have_posts() ) :
                        $query->the_post();
                        $this->render_card( get_the_ID() );
                    endwhile;
                    wp_reset_postdata();
                else :
                    ?>
                <div class="lfa-empty">
                    <p><?php esc_html_e( 'No animals found.', 'lost-found-animal' ); ?></p>
                </div>
                <?php endif; ?>
            </div>

            <div id="lfa-no-results" class="lfa-empty" style="display: none;">
                <p><?php esc_html_e( 'No animals match your filters.', 'lost-found-animal' ); ?></p>
                <button type="button" onclick="document.getElementById('lfa-reset').click();" class="lfa-btn"><?php esc_html_e( 'Reset Filters', 'lost-found-animal' ); ?></button>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render single animal card
     *
     * @param int $post_id Post ID.
     */
    private function render_card( $post_id ) {
        $status     = lfa_get_meta( $post_id, 'status' );
        $location   = lfa_get_meta( $post_id, 'location' );
        $breed      = lfa_get_meta( $post_id, 'breed' );
        $gender     = lfa_get_meta( $post_id, 'gender' );
        $found_date = lfa_get_meta( $post_id, 'found_date' );

        $images      = lfa_get_all_images( $post_id );
        $badge       = lfa_get_badge( $status );
        $has_gallery = count( $images ) > 1;
        ?>
        <div class="lfa-card"
             data-status="<?php echo esc_attr( $status ); ?>"
             data-gender="<?php echo esc_attr( $gender ); ?>"
             data-date="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"
             data-name="<?php echo esc_attr( get_the_title() ); ?>">

            <a href="<?php the_permalink(); ?>" class="lfa-card-image">
                <?php if ( ! empty( $images ) ) : ?>
                    <div class="lfa-gallery-hover" data-images='<?php echo esc_attr( wp_json_encode( array_column( $images, 'card' ) ) ); ?>'>
                        <?php foreach ( $images as $i => $img ) : ?>
                            <img src="<?php echo esc_url( $img['card'] ); ?>"
                                 alt="<?php the_title_attribute(); ?>"
                                 class="<?php echo 0 === $i ? 'active' : ''; ?>">
                        <?php endforeach; ?>

                        <?php if ( $has_gallery ) : ?>
                            <div class="lfa-dots">
                                <?php for ( $i = 0; $i < count( $images ); $i++ ) : ?>
                                    <span class="<?php echo 0 === $i ? 'active' : ''; ?>"></span>
                                <?php endfor; ?>
                            </div>
                            <span class="lfa-photo-count"><?php echo esc_html( count( $images ) ); ?></span>
                        <?php endif; ?>
                    </div>
                <?php else : ?>
                    <div class="lfa-no-photo">
                        <span><?php esc_html_e( 'No Photo', 'lost-found-animal' ); ?></span>
                    </div>
                <?php endif; ?>

                <span class="lfa-badge" style="background: <?php echo esc_attr( $badge['color'] ); ?>;">
                    <?php echo esc_html( $badge['text'] ); ?>
                </span>
            </a>

            <div class="lfa-card-body">
                <h3 class="lfa-card-title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h3>

                <p class="lfa-card-location">
                    <?php echo esc_html( $location ? $location : __( 'Unknown location', 'lost-found-animal' ) ); ?>
                </p>

                <div class="lfa-card-tags">
                    <?php if ( $breed ) : ?>
                        <span class="lfa-tag"><?php echo esc_html( $breed ); ?></span>
                    <?php endif; ?>
                    <?php if ( $gender ) : ?>
                        <span class="lfa-tag"><?php echo esc_html( $gender ); ?></span>
                    <?php endif; ?>
                    <?php if ( $found_date ) : ?>
                        <span class="lfa-tag"><?php echo esc_html( date_i18n( 'j M', strtotime( $found_date ) ) ); ?></span>
                    <?php endif; ?>
                </div>

                <a href="<?php the_permalink(); ?>" class="lfa-btn"><?php esc_html_e( 'View Details', 'lost-found-animal' ); ?></a>
            </div>
        </div>
        <?php
    }

    /**
     * Load custom single template
     *
     * @param string $template Current template path.
     * @return string
     */
    public function single_template( $template ) {
        global $post;

        if ( $post && 'animal' === $post->post_type ) {
            $plugin_template = LFA_PLUGIN_DIR . 'templates/single-animal.php';
            if ( file_exists( $plugin_template ) ) {
                return $plugin_template;
            }
        }

        return $template;
    }
}

// Initialize
LFA_Shortcodes::instance();
