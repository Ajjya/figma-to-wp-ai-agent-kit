<?php
/**
 * Main Index Template
 *
 * The main template file. This is the most generic template file in a WordPress
 * theme and one of the two required files for a theme.
 *
 * @package Awesome_Theme
 */

get_header();
?>

<main class="site-main">
    <div class="container">
        <?php if (have_posts()): ?>
            
            <?php while (have_posts()): the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                    </header>
                    
                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>
                </article>
            <?php endwhile; ?>
            
            <?php the_posts_navigation(); ?>
            
        <?php else: ?>
            
            <section class="no-results not-found">
                <header class="page-header">
                    <h1 class="page-title"><?php _e('Nothing Found', 'awesome-theme'); ?></h1>
                </header>
                
                <div class="page-content">
                    <p><?php _e('It seems we can\'t find what you\'re looking for.', 'awesome-theme'); ?></p>
                </div>
            </section>
            
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
