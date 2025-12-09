<?php
/**
 * Custom Post Types Registration
 *
 * @package Awesome_Theme
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Custom Post Types
 */
add_action('init', 'awesome_register_custom_post_types');
function awesome_register_custom_post_types() {
    
    // Why Awesome Post Type
    register_post_type('why-awesome', array(
        'labels' => array(
            'name'               => __('Why Awesome', 'awesome-theme'),
            'singular_name'      => __('Why Awesome', 'awesome-theme'),
            'add_new'            => __('Add Item', 'awesome-theme'),
            'add_new_item'       => __('Add New Why Awesome Item', 'awesome-theme'),
            'edit'               => __('Edit', 'awesome-theme'),
            'edit_item'          => __('Edit Item', 'awesome-theme'),
            'new_item'           => __('New Item', 'awesome-theme'),
            'view'               => __('View Item', 'awesome-theme'),
            'view_item'          => __('View Item', 'awesome-theme'),
            'search_items'       => __('Search Items', 'awesome-theme'),
            'not_found'          => __('No items found', 'awesome-theme'),
            'not_found_in_trash' => __('No items in trash', 'awesome-theme'),
        ),
        'public'             => true,
        'show_ui'            => true,
        'show_in_rest'       => true,
        'publicly_queryable' => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-networking',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'page-attributes'),
        'rewrite'            => array('slug' => 'why-awesome'),
    ));
    
    // Feature Post Type
    register_post_type('feature', array(
        'labels' => array(
            'name'               => __('Features', 'awesome-theme'),
            'singular_name'      => __('Feature', 'awesome-theme'),
            'add_new'            => __('Add Feature', 'awesome-theme'),
            'add_new_item'       => __('Add New Feature', 'awesome-theme'),
            'edit'               => __('Edit', 'awesome-theme'),
            'edit_item'          => __('Edit Feature', 'awesome-theme'),
            'new_item'           => __('New Feature', 'awesome-theme'),
            'view'               => __('View Feature', 'awesome-theme'),
            'view_item'          => __('View Feature', 'awesome-theme'),
            'search_items'       => __('Search Features', 'awesome-theme'),
            'not_found'          => __('No features found', 'awesome-theme'),
            'not_found_in_trash' => __('No features in trash', 'awesome-theme'),
        ),
        'public'             => true,
        'show_ui'            => true,
        'show_in_rest'       => true,
        'publicly_queryable' => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-groups',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'page-attributes'),
        'rewrite'            => array('slug' => 'features'),
    ));
    
    // Partner Post Type
    register_post_type('partner', array(
        'labels' => array(
            'name'               => __('Partners', 'awesome-theme'),
            'singular_name'      => __('Partner', 'awesome-theme'),
            'add_new'            => __('Add Partner', 'awesome-theme'),
            'add_new_item'       => __('Add New Partner', 'awesome-theme'),
            'edit'               => __('Edit', 'awesome-theme'),
            'edit_item'          => __('Edit Partner', 'awesome-theme'),
            'new_item'           => __('New Partner', 'awesome-theme'),
            'view'               => __('View Partner', 'awesome-theme'),
            'view_item'          => __('View Partner', 'awesome-theme'),
            'search_items'       => __('Search Partners', 'awesome-theme'),
            'not_found'          => __('No partners found', 'awesome-theme'),
            'not_found_in_trash' => __('No partners in trash', 'awesome-theme'),
        ),
        'public'             => true,
        'show_ui'            => true,
        'show_in_rest'       => true,
        'publicly_queryable' => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-portfolio',
        'supports'           => array('title', 'thumbnail', 'page-attributes'),
        'rewrite'            => array('slug' => 'partners'),
    ));
    
    // FAQ Post Type
    register_post_type('faq', array(
        'labels' => array(
            'name'               => __('FAQs', 'awesome-theme'),
            'singular_name'      => __('FAQ', 'awesome-theme'),
            'add_new'            => __('Add FAQ', 'awesome-theme'),
            'add_new_item'       => __('Add New FAQ', 'awesome-theme'),
            'edit'               => __('Edit', 'awesome-theme'),
            'edit_item'          => __('Edit FAQ', 'awesome-theme'),
            'new_item'           => __('New FAQ', 'awesome-theme'),
            'view'               => __('View FAQ', 'awesome-theme'),
            'view_item'          => __('View FAQ', 'awesome-theme'),
            'search_items'       => __('Search FAQs', 'awesome-theme'),
            'not_found'          => __('No FAQs found', 'awesome-theme'),
            'not_found_in_trash' => __('No FAQs in trash', 'awesome-theme'),
        ),
        'public'             => true,
        'show_ui'            => true,
        'show_in_rest'       => true,
        'publicly_queryable' => true,
        'has_archive'        => false,
        'menu_icon'          => 'dashicons-editor-help',
        'supports'           => array('title', 'editor', 'page-attributes'),
        'rewrite'            => array('slug' => 'faq'),
    ));
}

/**
 * Flush rewrite rules on theme activation
 */
add_action('after_switch_theme', 'awesome_flush_rewrite_rules');
function awesome_flush_rewrite_rules() {
    awesome_register_custom_post_types();
    flush_rewrite_rules();
}
