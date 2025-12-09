<?php
/**
 * Partners Section Template
 *
 * @package Awesome_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

// Query Partners
$partners = new WP_Query(array(
    'post_type'      => 'partner',
    'posts_per_page' => 10,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
));
?>

<section id="partners" class="partners">
    <div class="container">
        <h2 class="section-title"><?php _e('Partners', 'awesome-theme'); ?></h2>
        <div class="partners__grid">
            <?php if ($partners->have_posts()): ?>
                <?php while ($partners->have_posts()): $partners->the_post(); ?>
                    <?php if (has_post_thumbnail()): ?>
                        <?php 
                        $partner_url = get_post_meta(get_the_ID(), 'partner_url', true);
                        $partner_name = get_the_title();
                        ?>
                        <div class="partner-logo">
                            <?php if ($partner_url): ?>
                                <a href="<?php echo esc_url($partner_url); ?>" target="_blank" rel="noopener">
                            <?php endif; ?>
                                <?php the_post_thumbnail('partner-logo', array('alt' => esc_attr($partner_name))); ?>
                            <?php if ($partner_url): ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php endif; ?>
        </div>
    </div>
</section>
