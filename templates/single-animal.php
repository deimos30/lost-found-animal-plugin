<?php
/**
 * Single Animal Template
 * 
 * @package Lost_Found_Animal
 * @author Wojtek Kobylecki / Bella Design Studio
 * @version 1.0.1
 */

get_header();

// Get meta
$type = lfa_get_meta(get_the_ID(), 'type');
$status = lfa_get_meta(get_the_ID(), 'status');
$location = lfa_get_meta(get_the_ID(), 'location');
$breed = lfa_get_meta(get_the_ID(), 'breed');
$color = lfa_get_meta(get_the_ID(), 'color');
$gender = lfa_get_meta(get_the_ID(), 'gender');
$age = lfa_get_meta(get_the_ID(), 'age');
$found_date = lfa_get_meta(get_the_ID(), 'found_date');
$microchip = lfa_get_meta(get_the_ID(), 'microchip');

$images = lfa_get_all_images(get_the_ID());
$badge = lfa_get_badge($status);

if (empty($type)) $type = 'Animal';
?>

<div class="lfa-single">
    
    <!-- Breadcrumb -->
    <nav class="lfa-breadcrumb">
        <a href="<?php echo esc_url(home_url()); ?>"><?php _e('Home', 'lost-found-animal'); ?></a>
        <span>›</span>
        <span><?php the_title(); ?></span>
    </nav>
    
    <div class="lfa-single-grid">
        
        <!-- Left: Gallery -->
        <div class="lfa-gallery-col">
            <div class="lfa-main-photo">
                <?php if (!empty($images)) : ?>
                    <img id="lfa-main-img" src="<?php echo esc_url($images[0]['full']); ?>" alt="<?php the_title_attribute(); ?>">
                <?php else : ?>
                    <div class="lfa-no-photo" style="height:450px;">
                        <span><?php _e('No Photo', 'lost-found-animal'); ?></span>
                    </div>
                <?php endif; ?>
                
                <span class="lfa-main-badge" style="background:<?php echo esc_attr($badge['color']); ?>;">
                    <?php echo esc_html($badge['text']); ?>
                </span>
            </div>
            
            <?php if (count($images) > 1) : ?>
                <div class="lfa-thumbs">
                    <?php foreach ($images as $i => $img) : ?>
                        <div class="lfa-thumb <?php echo $i === 0 ? 'active' : ''; ?>" 
                             data-full="<?php echo esc_url($img['full']); ?>"
                             onclick="document.getElementById('lfa-main-img').src=this.dataset.full; document.querySelectorAll('.lfa-thumb').forEach(t=>t.classList.remove('active')); this.classList.add('active');">
                            <img src="<?php echo esc_url($img['thumb']); ?>" alt="">
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Right: Details -->
        <div class="lfa-details-col">
            <h1 class="lfa-single-title"><?php the_title(); ?></h1>
            
            <?php if ($location) : ?>
                <div class="lfa-single-location">
                    📍 <?php _e('Found at:', 'lost-found-animal'); ?> <strong><?php echo esc_html($location); ?></strong>
                </div>
            <?php endif; ?>
            
            <!-- Details Grid -->
            <div class="lfa-details-grid">
                <?php if ($type) : ?>
                    <div class="lfa-detail-box">
                        <div class="lfa-detail-label"><?php _e('Type', 'lost-found-animal'); ?></div>
                        <div class="lfa-detail-value"><?php echo esc_html($type); ?></div>
                    </div>
                <?php endif; ?>
                
                <?php if ($breed) : ?>
                    <div class="lfa-detail-box">
                        <div class="lfa-detail-label"><?php _e('Breed', 'lost-found-animal'); ?></div>
                        <div class="lfa-detail-value"><?php echo esc_html($breed); ?></div>
                    </div>
                <?php endif; ?>
                
                <?php if ($gender) : ?>
                    <div class="lfa-detail-box">
                        <div class="lfa-detail-label"><?php _e('Gender', 'lost-found-animal'); ?></div>
                        <div class="lfa-detail-value"><?php echo esc_html($gender); ?></div>
                    </div>
                <?php endif; ?>
                
                <?php if ($age) : ?>
                    <div class="lfa-detail-box">
                        <div class="lfa-detail-label"><?php _e('Age', 'lost-found-animal'); ?></div>
                        <div class="lfa-detail-value"><?php echo esc_html($age); ?></div>
                    </div>
                <?php endif; ?>
                
                <?php if ($color) : ?>
                    <div class="lfa-detail-box">
                        <div class="lfa-detail-label"><?php _e('Color', 'lost-found-animal'); ?></div>
                        <div class="lfa-detail-value"><?php echo esc_html($color); ?></div>
                    </div>
                <?php endif; ?>
                
                <?php if ($found_date) : ?>
                    <div class="lfa-detail-box">
                        <div class="lfa-detail-label"><?php _e('Date Found', 'lost-found-animal'); ?></div>
                        <div class="lfa-detail-value"><?php echo date_i18n('j F Y', strtotime($found_date)); ?></div>
                    </div>
                <?php endif; ?>
                
                <?php if ($microchip) : ?>
                    <div class="lfa-detail-box">
                        <div class="lfa-detail-label"><?php _e('Microchip', 'lost-found-animal'); ?></div>
                        <div class="lfa-detail-value"><?php echo esc_html($microchip); ?></div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Description -->
            <?php if (get_the_content()) : ?>
                <div class="lfa-description">
                    <h2><?php printf(__('About This %s', 'lost-found-animal'), esc_html($type)); ?></h2>
                    <div class="lfa-description-content">
                        <?php the_content(); ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Contact Box -->
            <?php if ($status !== 'Reunited' && $status !== 'Not Available') : ?>
                <div class="lfa-contact-box">
                    <h3><?php printf(__('Is this your %s?', 'lost-found-animal'), strtolower(esc_html($type))); ?></h3>
                    <p><?php _e('If you recognize this animal, please contact us immediately.', 'lost-found-animal'); ?></p>
                    <div class="lfa-contact-btns">
                        <a href="tel:+441234567890" class="lfa-btn-call">
                            📞 <?php _e('Call Us', 'lost-found-animal'); ?>
                        </a>
                        <a href="<?php echo esc_url(home_url('/#contact')); ?>" class="lfa-btn-msg">
                            ✉️ <?php _e('Send Message', 'lost-found-animal'); ?>
                        </a>
                    </div>
                </div>
            <?php elseif ($status === 'Reunited') : ?>
                <div class="lfa-reunited-box">
                    <span style="font-size:48px;">❤️</span>
                    <h3><?php _e('Happy Reunion!', 'lost-found-animal'); ?></h3>
                    <p><?php _e('This animal has been reunited with their owner.', 'lost-found-animal'); ?></p>
                </div>
            <?php else : ?>
                <div class="lfa-reunited-box" style="background:#f3f4f6;border-color:#e5e7eb;">
                    <h3 style="color:#374151;"><?php _e('Not Available', 'lost-found-animal'); ?></h3>
                    <p style="color:#6b7280;"><?php _e('This animal is currently not available.', 'lost-found-animal'); ?></p>
                </div>
            <?php endif; ?>
            
            <!-- Share -->
            <div class="lfa-share">
                <div class="lfa-share-label"><?php _e('Share', 'lost-found-animal'); ?></div>
                <div class="lfa-share-btns">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" 
                       target="_blank" class="lfa-share-btn lfa-share-fb">Facebook</a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" 
                       target="_blank" class="lfa-share-btn lfa-share-tw">Twitter</a>
                    <a href="https://wa.me/?text=<?php echo urlencode(get_the_title() . ' - ' . get_permalink()); ?>" 
                       target="_blank" class="lfa-share-btn lfa-share-wa">WhatsApp</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Back link -->
    <a href="javascript:history.back();" class="lfa-back">
        ← <?php _e('Back', 'lost-found-animal'); ?>
    </a>
</div>

<!-- Lightbox -->
<?php if (count($images) > 0) : ?>
<div class="lfa-lightbox">
    <button class="lfa-lb-close" type="button">&times;</button>
    <?php if (count($images) > 1) : ?>
        <button class="lfa-lb-prev" type="button">‹</button>
        <button class="lfa-lb-next" type="button">›</button>
    <?php endif; ?>
    <img src="" alt="">
    <div class="lfa-lb-counter"></div>
</div>
<?php endif; ?>

<?php get_footer(); ?>
