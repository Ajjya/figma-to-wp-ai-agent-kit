<?php
/**
 * FAQ Section Template
 *
 * @package Awesome_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

// Query FAQs
$faqs = new WP_Query(array(
    'post_type'      => 'faq',
    'posts_per_page' => 10,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
));
?>

<section id="faq" class="faq">
    <div class="container">
        <h2 class="section-title"><?php _e('FAQ', 'awesome-theme'); ?></h2>
        <div class="faq__list">
            <?php if ($faqs->have_posts()): ?>
                <?php while ($faqs->have_posts()): $faqs->the_post(); ?>
                    <?php 
                    $is_expanded = get_post_meta(get_the_ID(), 'faq_expanded', true) === '1';
                    $expanded_class = $is_expanded ? 'faq__item--expanded' : '';
                    $answer_class = $is_expanded ? 'faq__answer--visible' : '';
                    ?>
                    <div class="faq__item <?php echo esc_attr($expanded_class); ?>">
                        <button class="faq__question" aria-expanded="<?php echo $is_expanded ? 'true' : 'false'; ?>">
                            <span><?php the_title(); ?></span>
                            <img class="faq__icon" src="<?php echo awesome_icon_url('icon-dropdown.svg'); ?>" alt="" width="12" height="8">
                        </button>
                        <div class="faq__answer <?php echo esc_attr($answer_class); ?>">
                            <?php the_content(); ?>
                        </div>
                    </div>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php endif; ?>
        </div>
    </div>
</section>
