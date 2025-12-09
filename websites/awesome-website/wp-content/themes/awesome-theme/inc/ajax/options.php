<?php
/**
 * AJAX Handlers for Options
 *
 * @package Awesome_Theme
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

/**
 * AJAX handler for saving options
 */
add_action('wp_ajax_awesome_save_options', 'awesome_ajax_save_options');
function awesome_ajax_save_options() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'awesome_nonce')) {
        wp_send_json_error(array('message' => 'Security check failed'));
    }
    
    // Check permissions
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => 'Permission denied'));
    }
    
    // Save options
    $options = isset($_POST['options']) ? $_POST['options'] : array();
    
    foreach ($options as $key => $value) {
        if (strpos($key, 'awesome_') === 0) {
            update_option(sanitize_key($key), sanitize_text_field($value));
        }
    }
    
    wp_send_json_success(array('message' => 'Options saved successfully'));
}

/**
 * AJAX handler for getting options
 */
add_action('wp_ajax_awesome_get_options', 'awesome_ajax_get_options');
function awesome_ajax_get_options() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'awesome_nonce')) {
        wp_send_json_error(array('message' => 'Security check failed'));
    }
    
    // Check permissions
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => 'Permission denied'));
    }
    
    $keys = isset($_POST['keys']) ? $_POST['keys'] : array();
    $options = array();
    
    foreach ($keys as $key) {
        if (strpos($key, 'awesome_') === 0) {
            $options[$key] = get_option(sanitize_key($key), '');
        }
    }
    
    wp_send_json_success(array('options' => $options));
}
