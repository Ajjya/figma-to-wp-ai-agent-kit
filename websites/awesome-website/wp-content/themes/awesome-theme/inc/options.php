<?php
/**
 * Theme Options Page
 *
 * @package Awesome_Theme
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Create options menu
 */
add_action('admin_menu', 'awesome_create_options_menu');
function awesome_create_options_menu() {
    add_menu_page(
        'Theme Options',
        'Theme Options',
        'manage_options',
        'awesome-theme-options',
        'awesome_theme_settings_page',
        'dashicons-admin-customizer',
        60
    );
}

/**
 * Register settings
 */
add_action('admin_init', 'awesome_register_settings');
function awesome_register_settings() {
    // Logo (stored as attachment ID)
    register_setting('awesome-settings-group', 'awesome_logo');
    
    // App Store Links
    register_setting('awesome-settings-group', 'awesome_app_store_url');
    register_setting('awesome-settings-group', 'awesome_google_play_url');
    
    // Social Media Links
    register_setting('awesome-settings-group', 'awesome_twitter_url');
    register_setting('awesome-settings-group', 'awesome_producthunt_url');
    register_setting('awesome-settings-group', 'awesome_github_url');
    register_setting('awesome-settings-group', 'awesome_reddit_url');
    register_setting('awesome-settings-group', 'awesome_blockstack_url');
    register_setting('awesome-settings-group', 'awesome_bitcointalk_url');
    
    // Footer Settings
    register_setting('awesome-settings-group', 'awesome_contact_email');
    
    // Download Section
    register_setting('awesome-settings-group', 'awesome_download_subtitle');
    register_setting('awesome-settings-group', 'awesome_download_title');
}



/**
 * Settings page HTML
 */
function awesome_theme_settings_page() { ?>
    <style type="text/css">
        .awesome-settings-section {
            background: #fff;
            padding: 20px;
            margin: 20px 0;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
        }
        .awesome-settings-section h2 {
            margin-top: 0;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .awesome-settings-field {
            margin-bottom: 15px;
        }
        .awesome-settings-field label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .awesome-settings-field input[type="text"],
        .awesome-settings-field input[type="email"],
        .awesome-settings-field input[type="url"],
        .awesome-settings-field textarea {
            width: 100%;
            max-width: 500px;
        }
        .awesome-settings-field textarea {
            min-height: 100px;
        }
        .awesome-image-preview {
            max-width: 200px;
            margin-top: 10px;
        }
        .awesome-image-preview img {
            max-width: 100%;
            height: auto;
        }
        .awesome-columns {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        .awesome-column {
            flex: 1;
            min-width: 300px;
        }
    </style>

    <div class="wrap">
        <h1><?php _e('Awesome Theme Options', 'awesome-theme'); ?></h1>
        
        <div class="notice notice-info">
            <p><strong><?php _e('Note:', 'awesome-theme'); ?></strong></p>
            <ul style="list-style: disc; margin-left: 20px;">
                <li><?php _e('Logo: Upload logo here', 'awesome-theme'); ?></li>
                <li><?php _e('Hero Section: Edit Front Page to manage hero content and image', 'awesome-theme'); ?></li>
                <li><?php _e('Cold Wallet: Edit "Cold Wallet" page to manage content and images', 'awesome-theme'); ?></li>
            </ul>
        </div>

        <form method="post" action="options.php">
            <?php settings_fields('awesome-settings-group'); ?>
            <?php do_settings_sections('awesome-settings-group'); ?>
            
            <!-- Logo Settings -->
            <div class="awesome-settings-section">
                <h2><?php _e('Logo Settings', 'awesome-theme'); ?></h2>
                <div class="awesome-settings-field">
                    <?php 
                    $logo_id = get_option('awesome_logo');
                    $logo_url = $logo_id ? wp_get_attachment_url($logo_id) : '';
                    ?>
                    <label><?php _e('Site Logo:', 'awesome-theme'); ?></label>
                    <div class="awesome-image-preview" id="logo-preview">
                        <?php if ($logo_url) : ?>
                            <img src="<?php echo esc_url($logo_url); ?>" style="max-width: 200px; height: auto; display: block; margin: 10px 0;">
                        <?php endif; ?>
                    </div>
                    <input type="hidden" id="awesome_logo" name="awesome_logo" value="<?php echo esc_attr($logo_id); ?>">
                    <button type="button" class="button awesome-upload-logo"><?php _e('Upload Logo', 'awesome-theme'); ?></button>
                    <?php if ($logo_id) : ?>
                        <button type="button" class="button awesome-remove-logo"><?php _e('Remove Logo', 'awesome-theme'); ?></button>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- App Store Links -->
            <div class="awesome-settings-section">
                <h2><?php _e('App Store Links', 'awesome-theme'); ?></h2>
                <div class="awesome-columns">
                    <div class="awesome-column">
                        <div class="awesome-settings-field">
                            <label for="awesome_app_store_url"><?php _e('App Store URL:', 'awesome-theme'); ?></label>
                            <input type="url" name="awesome_app_store_url" id="awesome_app_store_url" 
                                   value="<?php echo esc_attr(get_option('awesome_app_store_url')); ?>" 
                                   placeholder="https://apps.apple.com/...">
                        </div>
                    </div>
                    <div class="awesome-column">
                        <div class="awesome-settings-field">
                            <label for="awesome_google_play_url"><?php _e('Google Play URL:', 'awesome-theme'); ?></label>
                            <input type="url" name="awesome_google_play_url" id="awesome_google_play_url" 
                                   value="<?php echo esc_attr(get_option('awesome_google_play_url')); ?>" 
                                   placeholder="https://play.google.com/...">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Download Section -->
            <div class="awesome-settings-section">
                <h2><?php _e('Download Section', 'awesome-theme'); ?></h2>
                <div class="awesome-settings-field">
                    <label for="awesome_download_subtitle"><?php _e('Subtitle:', 'awesome-theme'); ?></label>
                    <input type="text" name="awesome_download_subtitle" id="awesome_download_subtitle" 
                           value="<?php echo esc_attr(get_option('awesome_download_subtitle', 'Download from Apple App Store and Google Play Store')); ?>">
                </div>
                <div class="awesome-settings-field">
                    <label for="awesome_download_title"><?php _e('Title:', 'awesome-theme'); ?></label>
                    <input type="text" name="awesome_download_title" id="awesome_download_title" 
                           value="<?php echo esc_attr(get_option('awesome_download_title', 'Launching Soon.')); ?>">
                </div>
            </div>
            
            <!-- Social Media Links -->
            <div class="awesome-settings-section">
                <h2><?php _e('Social Media Links', 'awesome-theme'); ?></h2>
                <div class="awesome-columns">
                    <div class="awesome-column">
                        <div class="awesome-settings-field">
                            <label for="awesome_twitter_url"><?php _e('Twitter:', 'awesome-theme'); ?></label>
                            <input type="url" name="awesome_twitter_url" id="awesome_twitter_url" 
                                   value="<?php echo esc_attr(get_option('awesome_twitter_url')); ?>">
                        </div>
                        <div class="awesome-settings-field">
                            <label for="awesome_producthunt_url"><?php _e('Product Hunt:', 'awesome-theme'); ?></label>
                            <input type="url" name="awesome_producthunt_url" id="awesome_producthunt_url" 
                                   value="<?php echo esc_attr(get_option('awesome_producthunt_url')); ?>">
                        </div>
                        <div class="awesome-settings-field">
                            <label for="awesome_github_url"><?php _e('GitHub:', 'awesome-theme'); ?></label>
                            <input type="url" name="awesome_github_url" id="awesome_github_url" 
                                   value="<?php echo esc_attr(get_option('awesome_github_url')); ?>">
                        </div>
                    </div>
                    <div class="awesome-column">
                        <div class="awesome-settings-field">
                            <label for="awesome_reddit_url"><?php _e('Reddit:', 'awesome-theme'); ?></label>
                            <input type="url" name="awesome_reddit_url" id="awesome_reddit_url" 
                                   value="<?php echo esc_attr(get_option('awesome_reddit_url')); ?>">
                        </div>
                        <div class="awesome-settings-field">
                            <label for="awesome_blockstack_url"><?php _e('Blockstack:', 'awesome-theme'); ?></label>
                            <input type="url" name="awesome_blockstack_url" id="awesome_blockstack_url" 
                                   value="<?php echo esc_attr(get_option('awesome_blockstack_url')); ?>">
                        </div>
                        <div class="awesome-settings-field">
                            <label for="awesome_bitcointalk_url"><?php _e('Bitcointalk:', 'awesome-theme'); ?></label>
                            <input type="url" name="awesome_bitcointalk_url" id="awesome_bitcointalk_url" 
                                   value="<?php echo esc_attr(get_option('awesome_bitcointalk_url')); ?>">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contact & Footer Settings -->
            <div class="awesome-settings-section">
                <h2><?php _e('Contact & Footer', 'awesome-theme'); ?></h2>
                <div class="awesome-settings-field">
                    <label for="awesome_contact_email"><?php _e('Contact Email:', 'awesome-theme'); ?></label>
                    <input type="email" name="awesome_contact_email" id="awesome_contact_email" 
                           value="<?php echo esc_attr(get_option('awesome_contact_email', 'info@test.io')); ?>">
                </div>
            </div>
            
            <script>
            jQuery(document).ready(function($) {
                var file_frame;
                $('.awesome-upload-logo').on('click', function(e) {
                    e.preventDefault();
                    if (file_frame) {
                        file_frame.open();
                        return;
                    }
                    file_frame = wp.media.frames.file_frame = wp.media({
                        title: 'Select Logo',
                        button: { text: 'Use this image' },
                        multiple: false
                    });
                    file_frame.on('select', function() {
                        var attachment = file_frame.state().get('selection').first().toJSON();
                        $('#awesome_logo').val(attachment.id);
                        $('#logo-preview').html('<img src="' + attachment.url + '" style="max-width: 200px; height: auto; display: block; margin: 10px 0;">');
                        if (!$('.awesome-remove-logo').length) {
                            $('.awesome-upload-logo').after('<button type="button" class="button awesome-remove-logo">Remove Logo</button>');
                        }
                    });
                    file_frame.open();
                });
                
                $(document).on('click', '.awesome-remove-logo', function(e) {
                    e.preventDefault();
                    $('#awesome_logo').val('');
                    $('#logo-preview').html('');
                    $(this).remove();
                });
            });
            </script>

            <?php submit_button(); ?>
        </form>
    </div>
<?php }
