<?php
/**
 * Header Template
 *
 * @package Awesome_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get logo from theme options
$logo_id = get_option('awesome_logo');
$logo_url = $logo_id ? wp_get_attachment_url($logo_id) : awesome_logo_url('logo.png');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

    <!-- Header / Navigation -->
    <header class="header">
        <div class="container">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="logo">
                <img src="<?php echo esc_url($logo_url); ?>" alt="<?php bloginfo('name'); ?>">
            </a>
            <nav class="main-nav">
                <button class="hamburger" aria-label="<?php esc_attr_e('Toggle menu', 'awesome-theme'); ?>">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class'     => 'nav-menu',
                    'container'      => false,
                    'fallback_cb'    => false,
                    'depth'          => 1,
                ));
                ?>
            </nav>
            <a href="#download" class="btn btn-outline"><?php _e('Download', 'awesome-theme'); ?></a>
        </div>
    </header>

<?php
