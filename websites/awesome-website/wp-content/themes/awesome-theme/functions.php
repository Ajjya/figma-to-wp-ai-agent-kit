<?php
/**
 * Awesome Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Awesome_Theme
 * @since 1.0.0
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

// Start session for forms
if (PHP_SAPI !== 'cli' && !session_id()) {
    session_start();
}

/**
 * Theme Setup
 */
function awesome_theme_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('menus');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('responsive-embeds');
    add_theme_support('automatic-feed-links');
    
    // Custom logo support
    add_theme_support('custom-logo', array(
        'height'      => 53,
        'width'       => 235,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    
    // Register navigation menus
    register_nav_menus(array(
        'primary'     => __('Primary Menu', 'awesome-theme'),
        'footer'      => __('Footer Menu', 'awesome-theme'),
    ));
    
    // Set content width
    if (!isset($content_width)) {
        $content_width = 1200;
    }
}
add_action('after_setup_theme', 'awesome_theme_setup');

/**
 * Allow SVG uploads
 */
function awesome_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'awesome_mime_types');

/**
 * Remove comments menu
 */
function awesome_remove_comments_menu() {
    remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'awesome_remove_comments_menu');

/**
 * Configure image sizes
 */
function awesome_theme_image_sizes() {
    if (current_user_can('manage_options') && is_admin()) {
        update_option('thumbnail_size_w', 150);
        update_option('thumbnail_size_h', 150);
        update_option('thumbnail_crop', 1);

        update_option('medium_size_w', 300);
        update_option('medium_size_h', 300);

        update_option('large_size_w', 1024);
        update_option('large_size_h', 1024);

        // Custom image sizes
        add_image_size('feature-icon', 76, 76, true);
        add_image_size('feature-box-icon', 48, 48, false);
        add_image_size('partner-logo', 200, 100, false);
        add_image_size('hero-image', 451, 0, false);
        add_image_size('cold-wallet', 600, 0, false);
    }
}
add_action('init', 'awesome_theme_image_sizes');

/**
 * Register widget areas
 */
function awesome_theme_widgets_init() {
    register_sidebar(array(
        'name'          => __('Footer Widget Area', 'awesome-theme'),
        'id'            => 'footer-widgets',
        'description'   => __('Add widgets here to appear in the footer.', 'awesome-theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'awesome_theme_widgets_init');

/**
 * Security: Block unauthenticated access to users endpoint
 */
add_filter('rest_authentication_errors', function ($result) {
    if (!is_user_logged_in()) {
        $request_uri = $_SERVER['REQUEST_URI'] ?? '';
        if (preg_match('#^/wp-json/wp/v2/users/?#', $request_uri)) {
            return new WP_Error('rest_forbidden', __('REST API access restricted.'), array('status' => 403));
        }
    }
    return $result;
});

/**
 * AJAX initialization for admin
 */
add_action('admin_init', function() {
    wp_add_inline_script('jquery', 'const awesomeAjax = ' . json_encode(array(
        'url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('awesome_nonce'),
    )));
    wp_add_inline_script('jquery', 'var homeUrl = ' . json_encode(get_site_url()));
});

// Include theme files
require_once get_stylesheet_directory() . '/inc/enqueue-scripts.php';
require_once get_stylesheet_directory() . '/inc/custom-posts.php';
require_once get_stylesheet_directory() . '/inc/metaboxes.php';
require_once get_stylesheet_directory() . '/inc/options.php';
require_once get_stylesheet_directory() . '/inc/ajax/options.php';
require_once get_stylesheet_directory() . '/inc/ajax/subscribe.php';
require_once get_stylesheet_directory() . '/theme-functions/helpers.php';

/**
 * Get theme option helper
 */
function awesome_get_option($key, $default = '') {
    $value = get_option($key);
    return !empty($value) ? $value : $default;
}

/**
 * Get theme asset URL helper
 */
function awesome_asset_url($path) {
    return get_template_directory_uri() . '/assets/' . ltrim($path, '/');
}

/**
 * Get theme image URL helper
 */
function awesome_image_url($filename) {
    return awesome_asset_url('images/' . $filename);
}

/**
 * Get theme icon URL helper
 */
function awesome_icon_url($filename) {
    return awesome_asset_url('icons/' . $filename);
}

/**
 * Get theme logo URL helper
 */
function awesome_logo_url($filename = 'logo.png') {
    return awesome_asset_url('logos/' . $filename);
}
