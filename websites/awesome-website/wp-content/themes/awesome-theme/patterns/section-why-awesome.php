<?php
/**
 * Why Awesome Section Template
 *
 * @package Awesome_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

// Query Why Awesome items
$why_awesome_items = new WP_Query(array(
    'post_type'      => 'why-awesome',
    'posts_per_page' => 3,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
));

// Default items if no posts exist
$default_items = array(
    array(
        'title'       => 'Security',
        'description' => 'Our non-custodial wallet solutions guarantee the highest industry standards',
        'icon'        => 'icon-check.svg',
    ),
    array(
        'title'       => 'AML Module',
        'description' => 'Check transactions for "dirty money". Stay protected from scammers.',
        'icon'        => 'icon-settings.svg',
    ),
    array(
        'title'       => 'Labels',
        'description' => 'Set labels for transactions. Send crypto from marked liquidity',
        'icon'        => 'icon-label.svg',
    ),
);
?>

<section id="why-awesome" class="why-awesome">
    <div class="container">
        <h2 class="section-title"><?php _e('Why Awesome', 'awesome-theme'); ?></h2>
        <div class="features-grid features-grid--3">
            <?php if ($why_awesome_items->have_posts()): ?>
                <?php while ($why_awesome_items->have_posts()): $why_awesome_items->the_post(); ?>
                    <?php if (has_post_thumbnail()): ?>
                        <div class="feature-card">
                            <div class="feature-card__icon feature-card__icon--blue">
                                <?php the_post_thumbnail('feature-icon', array('width' => 24, 'height' => 24)); ?>
                            </div>
                            <h3><?php the_title(); ?></h3>
                            <p><?php echo awesome_get_excerpt(get_the_ID(), 200); ?></p>
                        </div>
                    <?php endif; ?>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php endif; ?>
        </div>
    </div>
</section>
