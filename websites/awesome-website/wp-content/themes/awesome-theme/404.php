<?php
/**
 * 404 Page Template
 *
 * @package Awesome_Theme
 */

get_header();
?>

<main class="site-main">
    <section class="error-404 not-found">
        <div class="container" style="padding: 100px 20px; text-align: center;">
            <header class="page-header">
                <h1 class="page-title" style="font-size: 120px; color: var(--color-primary); margin-bottom: 20px;">404</h1>
                <h2 style="font-size: 32px; margin-bottom: 20px;"><?php _e('Page Not Found', 'awesome-theme'); ?></h2>
            </header>

            <div class="page-content">
                <p style="font-size: 18px; color: var(--color-text-muted); margin-bottom: 30px;">
                    <?php _e('Sorry, the page you are looking for doesn\'t exist or has been moved.', 'awesome-theme'); ?>
                </p>
                
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-outline" style="display: inline-block;">
                    <?php _e('Back to Homepage', 'awesome-theme'); ?>
                </a>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
