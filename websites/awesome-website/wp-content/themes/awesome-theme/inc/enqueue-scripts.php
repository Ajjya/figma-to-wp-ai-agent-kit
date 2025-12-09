<?php
/**
 * Enqueue scripts and styles
 * 
 * @package Awesome_Theme
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

// Define theme version constant
if (!defined('AWESOME_THEME_VERSION')) {
    define('AWESOME_THEME_VERSION', '1.0.0');
}

/**
 * Enqueue styles
 */
function awesome_theme_enqueue_styles() {
    $theme_version = AWESOME_THEME_VERSION;
    
    // Google Fonts - Montserrat
    wp_enqueue_style(
        'awesome-google-fonts',
        'https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap',
        array(),
        null
    );
    
    // Main theme stylesheet
    wp_enqueue_style(
        'awesome-main-style',
        get_template_directory_uri() . '/assets/css/main.css',
        array('awesome-google-fonts'),
        $theme_version
    );
    
    // WordPress required stylesheet
    wp_enqueue_style(
        'awesome-style',
        get_stylesheet_uri(),
        array('awesome-main-style'),
        $theme_version
    );
}
add_action('wp_enqueue_scripts', 'awesome_theme_enqueue_styles');

/**
 * Enqueue scripts
 */
function awesome_theme_enqueue_scripts() {
    $theme_version = AWESOME_THEME_VERSION;
    
    // jQuery (WordPress bundled)
    wp_enqueue_script('jquery');
    
    // Main theme JavaScript
    wp_enqueue_script(
        'awesome-main-js',
        get_template_directory_uri() . '/assets/js/main.js',
        array('jquery'),
        $theme_version,
        true
    );
    
    // Localize script for AJAX
    wp_localize_script(
        'awesome-main-js',
        'awesomeAjax',
        array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('awesome_nonce'),
            'homeUrl' => home_url(),
            'themeUrl' => get_template_directory_uri()
        )
    );
}
add_action('wp_enqueue_scripts', 'awesome_theme_enqueue_scripts');

/**
 * Enqueue admin styles
 */
function awesome_theme_admin_styles() {
    wp_enqueue_style(
        'awesome-admin-style',
        get_template_directory_uri() . '/assets/css/admin.css',
        array(),
        AWESOME_THEME_VERSION
    );
}
add_action('admin_enqueue_scripts', 'awesome_theme_admin_styles');
