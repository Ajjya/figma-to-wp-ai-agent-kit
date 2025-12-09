<?php
/**
 * Features Section Template
 *
 * @package Awesome_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

// Query Feature items
$features = new WP_Query(array(
    'post_type'      => 'feature',
    'posts_per_page' => 9,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
));
?>

<section id="features" class="features">
    <div class="container">
        <h2 class="section-title"><?php _e('Features', 'awesome-theme'); ?></h2>
        <div class="features-grid features-grid--3">
            <?php if ($features->have_posts()): ?>
                <?php while ($features->have_posts()): $features->the_post(); ?>
                    <?php if (has_post_thumbnail()): ?>
                        <div class="feature-box">
                            <div class="feature-box__icon">
                                <?php the_post_thumbnail('feature-box-icon', array('width' => 48, 'height' => 48)); ?>
                            </div>
                            <h3><?php the_title(); ?></h3>
                            <p><?php echo awesome_get_excerpt(get_the_ID(), 150); ?></p>
                        </div>
                    <?php endif; ?>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php endif; ?>
        </div>
    </div>
</section>
