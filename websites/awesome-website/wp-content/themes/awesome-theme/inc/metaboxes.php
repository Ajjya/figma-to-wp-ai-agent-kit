<?php
/**
 * Custom Metaboxes
 *
 * @package Awesome_Theme
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Helper function to add text input field
 */
function awesome_add_input($post_id, $field_name, $label, $type = 'text') {
    $value = get_post_meta($post_id, $field_name, true);
    ?>
    <div class="awesome-metabox-field">
        <label for="<?php echo esc_attr($field_name); ?>"><strong><?php echo esc_html($label); ?>:</strong></label>
        <input type="<?php echo esc_attr($type); ?>" 
               id="<?php echo esc_attr($field_name); ?>" 
               name="<?php echo esc_attr($field_name); ?>" 
               value="<?php echo esc_attr($value); ?>" 
               style="width: 100%; max-width: 500px;">
    </div>
    <?php
}

/**
 * Helper function to add textarea field
 */
function awesome_add_textarea($post_id, $field_name, $label) {
    $value = get_post_meta($post_id, $field_name, true);
    ?>
    <div class="awesome-metabox-field">
        <label for="<?php echo esc_attr($field_name); ?>"><strong><?php echo esc_html($label); ?>:</strong></label>
        <textarea id="<?php echo esc_attr($field_name); ?>" 
                  name="<?php echo esc_attr($field_name); ?>" 
                  rows="4" 
                  style="width: 100%; max-width: 500px;"><?php echo esc_textarea($value); ?></textarea>
    </div>
    <?php
}

/**
 * Helper function to save metabox field
 */
function awesome_save_metabox($post_id, $field_name) {
    if (isset($_POST[$field_name])) {
        update_post_meta($post_id, $field_name, sanitize_text_field($_POST[$field_name]));
    }
}

/**
 * Helper function to add image upload field
 */
function awesome_add_image_field($post_id, $field_name, $label) {
    $image_id = get_post_meta($post_id, $field_name, true);
    $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
    ?>
    <div class="awesome-metabox-field awesome-image-field">
        <label for="<?php echo esc_attr($field_name); ?>"><strong><?php echo esc_html($label); ?>:</strong></label>
        <div class="awesome-image-preview">
            <?php if ($image_url) : ?>
                <img src="<?php echo esc_url($image_url); ?>" style="max-width: 200px; height: auto; display: block; margin: 10px 0;">
            <?php endif; ?>
        </div>
        <input type="hidden" id="<?php echo esc_attr($field_name); ?>" name="<?php echo esc_attr($field_name); ?>" value="<?php echo esc_attr($image_id); ?>">
        <button type="button" class="button awesome-upload-image" data-field="<?php echo esc_attr($field_name); ?>"><?php _e('Select Image', 'awesome-theme'); ?></button>
        <?php if ($image_id) : ?>
            <button type="button" class="button awesome-remove-image" data-field="<?php echo esc_attr($field_name); ?>"><?php _e('Remove Image', 'awesome-theme'); ?></button>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Register metaboxes
 */
add_action('add_meta_boxes', 'awesome_register_metaboxes');
function awesome_register_metaboxes() {
    // Front Page metabox
    $front_page_id = get_option('page_on_front');
    if ($front_page_id) {
        add_meta_box(
            'front_page_hero',
            __('Hero Section', 'awesome-theme'),
            'awesome_front_page_metabox',
            'page',
            'normal',
            'high'
        );
    }
    
    // Cold Wallet page metabox
    add_meta_box(
        'cold_wallet_options',
        __('Cold Wallet Options', 'awesome-theme'),
        'awesome_cold_wallet_metabox',
        'page',
        'normal',
        'high'
    );
    
    // Why Awesome metabox
    add_meta_box(
        'why_awesome_options',
        __('Why Awesome Options', 'awesome-theme'),
        'awesome_why_awesome_metabox',
        'why-awesome',
        'normal',
        'high'
    );
    
    // Feature metabox
    add_meta_box(
        'feature_options',
        __('Feature Options', 'awesome-theme'),
        'awesome_feature_metabox',
        'feature',
        'normal',
        'high'
    );
    
    // Partner metabox
    add_meta_box(
        'partner_options',
        __('Partner Options', 'awesome-theme'),
        'awesome_partner_metabox',
        'partner',
        'normal',
        'high'
    );
    
    // FAQ metabox
    add_meta_box(
        'faq_options',
        __('FAQ Options', 'awesome-theme'),
        'awesome_faq_metabox',
        'faq',
        'normal',
        'high'
    );
}

/**
 * Front Page Hero metabox content
 */
function awesome_front_page_metabox($post) {
    // Only show for front page
    $front_page_id = get_option('page_on_front');
    if ($post->ID != $front_page_id) {
        return;
    }
    
    wp_nonce_field('awesome_metabox_nonce', 'awesome_metabox_nonce');
    ?>
    <style>
        .awesome-metabox-field { margin-bottom: 15px; }
        .awesome-metabox-field label { display: block; margin-bottom: 5px; }
    </style>
    <?php
    awesome_add_input($post->ID, 'hero_title', 'Hero Title');
    awesome_add_textarea($post->ID, 'hero_subtitle', 'Hero Subtitle');
    awesome_add_input($post->ID, 'hero_launching_text', 'Launching Text');
    awesome_add_image_field($post->ID, 'hero_image', 'Hero Image');
}

/**
 * Cold Wallet page metabox content
 */
function awesome_cold_wallet_metabox($post) {
    // Only show for cold-wallet page
    if ($post->post_name !== 'cold-wallet') {
        return;
    }
    
    wp_nonce_field('awesome_metabox_nonce', 'awesome_metabox_nonce');
    ?>
    <style>
        .awesome-metabox-field { margin-bottom: 15px; }
        .awesome-metabox-field label { display: block; margin-bottom: 5px; }
    </style>
    <p><?php _e('The title and content will be used for the Cold Wallet section. Add images below:', 'awesome-theme'); ?></p>
    <?php
    awesome_add_image_field($post->ID, 'coldwallet_logo', 'CoolWallet Logo');
    awesome_add_image_field($post->ID, 'coldwallet_image', 'Cold Wallet Image');
}

/**
 * Why Awesome metabox content
 */
function awesome_why_awesome_metabox($post) {
    wp_nonce_field('awesome_metabox_nonce', 'awesome_metabox_nonce');
    ?>
    <style>
        .awesome-metabox-field { margin-bottom: 15px; }
        .awesome-metabox-field label { display: block; margin-bottom: 5px; }
        .awesome-metabox-notice { background: #fff3cd; border-left: 4px solid #ffc107; padding: 12px; margin-bottom: 15px; }
        .awesome-metabox-notice strong { color: #856404; }
    </style>
    <div class="awesome-metabox-notice">
        <strong><?php _e('⚠️ Required:', 'awesome-theme'); ?></strong>
        <?php _e('You MUST set a Featured Image (icon) for this item to appear on the website. Upload the icon from Media Library.', 'awesome-theme'); ?>
    </div>
    <p><?php _e('Description goes in the editor below.', 'awesome-theme'); ?></p>
    <?php
    awesome_add_input($post->ID, 'why_awesome_icon_class', 'Icon Class (optional CSS class)');
}

/**
 * Feature metabox content
 */
function awesome_feature_metabox($post) {
    wp_nonce_field('awesome_metabox_nonce', 'awesome_metabox_nonce');
    ?>
    <style>
        .awesome-metabox-field { margin-bottom: 15px; }
        .awesome-metabox-field label { display: block; margin-bottom: 5px; }
        .awesome-metabox-notice { background: #fff3cd; border-left: 4px solid #ffc107; padding: 12px; margin-bottom: 15px; }
        .awesome-metabox-notice strong { color: #856404; }
    </style>
    <div class="awesome-metabox-notice">
        <strong><?php _e('⚠️ Required:', 'awesome-theme'); ?></strong>
        <?php _e('You MUST set a Featured Image (icon) for this feature to appear on the website. Upload the icon from Media Library.', 'awesome-theme'); ?>
    </div>
    <p><?php _e('Description goes in the editor below.', 'awesome-theme'); ?></p>
    <?php
    awesome_add_input($post->ID, 'feature_icon_class', 'Icon Class (optional CSS class)');
}

/**
 * Partner metabox content
 */
function awesome_partner_metabox($post) {
    wp_nonce_field('awesome_metabox_nonce', 'awesome_metabox_nonce');
    ?>
    <style>
        .awesome-metabox-field { margin-bottom: 15px; }
        .awesome-metabox-field label { display: block; margin-bottom: 5px; }
        .awesome-metabox-notice { background: #fff3cd; border-left: 4px solid #ffc107; padding: 12px; margin-bottom: 15px; }
        .awesome-metabox-notice strong { color: #856404; }
    </style>
    <div class="awesome-metabox-notice">
        <strong><?php _e('⚠️ Required:', 'awesome-theme'); ?></strong>
        <?php _e('You MUST set a Featured Image (logo) for this partner to appear on the website. Upload the logo from Media Library.', 'awesome-theme'); ?>
    </div>
    <?php
    awesome_add_input($post->ID, 'partner_url', 'Partner Website URL', 'url');
}

/**
 * FAQ metabox content
 */
function awesome_faq_metabox($post) {
    wp_nonce_field('awesome_metabox_nonce', 'awesome_metabox_nonce');
    ?>
    <style>
        .awesome-metabox-field { margin-bottom: 15px; }
        .awesome-metabox-field label { display: block; margin-bottom: 5px; }
        .awesome-metabox-notice { background: #d1ecf1; border-left: 4px solid #0c5460; padding: 12px; margin-bottom: 15px; }
        .awesome-metabox-notice strong { color: #0c5460; }
    </style>
    <div class="awesome-metabox-notice">
        <strong><?php _e('Instructions:', 'awesome-theme'); ?></strong>
        <?php _e('The title will be used as the question, and the editor content as the answer. FAQ items will only appear if they have content.', 'awesome-theme'); ?>
    </div>
    <?php
    // Check if this FAQ should be expanded by default
    $is_expanded = get_post_meta($post->ID, 'faq_expanded', true);
    ?>
    <div class="awesome-metabox-field">
        <label>
            <input type="checkbox" name="faq_expanded" value="1" <?php checked($is_expanded, '1'); ?>>
            <?php _e('Expanded by default', 'awesome-theme'); ?>
        </label>
    </div>
    <?php
}

/**
 * Save metabox data
 */
add_action('save_post', 'awesome_save_metaboxes');
function awesome_save_metaboxes($post_id) {
    // Verify nonce
    if (!isset($_POST['awesome_metabox_nonce']) || 
        !wp_verify_nonce($_POST['awesome_metabox_nonce'], 'awesome_metabox_nonce')) {
        return $post_id;
    }
    
    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
    
    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }
    
    $post = get_post($post_id);
    $post_type = $post->post_type ?? '';
    
    // Save page metaboxes
    if ($post_type === 'page') {
        $front_page_id = get_option('page_on_front');
        
        // Front page hero fields
        if ($post_id == $front_page_id) {
            awesome_save_metabox($post_id, 'hero_title');
            awesome_save_metabox($post_id, 'hero_subtitle');
            awesome_save_metabox($post_id, 'hero_launching_text');
            awesome_save_metabox($post_id, 'hero_image');
        }
        
        // Cold wallet page fields
        if ($post->post_name === 'cold-wallet') {
            awesome_save_metabox($post_id, 'coldwallet_logo');
            awesome_save_metabox($post_id, 'coldwallet_image');
        }
    }
    
    // Save based on post type
    switch ($post_type) {
        case 'why-awesome':
            awesome_save_metabox($post_id, 'why_awesome_icon_class');
            break;
            
        case 'feature':
            awesome_save_metabox($post_id, 'feature_icon_class');
            break;
            
        case 'partner':
            awesome_save_metabox($post_id, 'partner_url');
            break;
            
        case 'faq':
            $faq_expanded = isset($_POST['faq_expanded']) ? '1' : '0';
            update_post_meta($post_id, 'faq_expanded', $faq_expanded);
            break;
    }
    
    return $post_id;
}

/**
 * Enqueue media uploader scripts
 */
add_action('admin_enqueue_scripts', 'awesome_metabox_scripts');
function awesome_metabox_scripts($hook) {
    if ('post.php' !== $hook && 'post-new.php' !== $hook) {
        return;
    }
    
    wp_enqueue_media();
    wp_enqueue_script('awesome-metabox', get_template_directory_uri() . '/assets/js/metabox.js', array('jquery'), '1.0', true);
}
