<?php
/**
 * AJAX Handler for Newsletter Subscription
 *
 * @package Awesome_Theme
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle newsletter subscription
 */
add_action('wp_ajax_awesome_subscribe', 'awesome_ajax_subscribe');
add_action('wp_ajax_nopriv_awesome_subscribe', 'awesome_ajax_subscribe');

function awesome_ajax_subscribe() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'awesome_nonce')) {
        wp_send_json_error(array('message' => __('Security check failed', 'awesome-theme')));
    }
    
    // Get and validate email
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    
    if (empty($email) || !is_email($email)) {
        wp_send_json_error(array('message' => __('Please enter a valid email address', 'awesome-theme')));
    }
    
    // Get admin email for notifications
    $admin_email = get_option('awesome_newsletter_email');
    if (empty($admin_email)) {
        $admin_email = get_option('admin_email');
    }
    
    // Send notification email
    $subject = sprintf(__('New Newsletter Subscription - %s', 'awesome-theme'), get_bloginfo('name'));
    $message = sprintf(
        __("New newsletter subscription:\n\nEmail: %s\nDate: %s\nIP: %s", 'awesome-theme'),
        $email,
        date('Y-m-d H:i:s'),
        $_SERVER['REMOTE_ADDR'] ?? 'Unknown'
    );
    
    $headers = array('Content-Type: text/plain; charset=UTF-8');
    
    $sent = wp_mail($admin_email, $subject, $message, $headers);
    
    if ($sent) {
        // Log subscription (you can extend this to save to database)
        do_action('awesome_newsletter_subscribed', $email);
        
        wp_send_json_success(array(
            'message' => __('Thank you for subscribing!', 'awesome-theme')
        ));
    } else {
        wp_send_json_error(array(
            'message' => __('Something went wrong. Please try again.', 'awesome-theme')
        ));
    }
}

/**
 * Optional: Store subscriptions in options table
 */
add_action('awesome_newsletter_subscribed', 'awesome_store_subscription');
function awesome_store_subscription($email) {
    $subscribers = get_option('awesome_newsletter_subscribers', array());
    
    if (!in_array($email, $subscribers)) {
        $subscribers[] = $email;
        update_option('awesome_newsletter_subscribers', $subscribers);
    }
}
