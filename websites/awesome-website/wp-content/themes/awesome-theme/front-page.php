<?php
/**
 * Front Page Template
 *
 * The template for displaying the homepage with all sections.
 *
 * @package Awesome_Theme
 */

get_header();

// Get front page ID
$front_page_id = get_option('page_on_front');

// Get hero section from front page meta
$hero_title = get_post_meta($front_page_id, 'hero_title', true) ?: 'Your Keys, Your Crypto';
$hero_subtitle = get_post_meta($front_page_id, 'hero_subtitle', true) ?: 'Awesome offers revolutionary user-controlled crypto wallets – allowing access to funds and transactions just to you, not Awesome or anybody else.';
$hero_launching_text = get_post_meta($front_page_id, 'hero_launching_text', true) ?: 'Launching Soon.';
$hero_image_id = get_post_meta($front_page_id, 'hero_image', true);
$hero_image_url = $hero_image_id ? wp_get_attachment_url($hero_image_id) : awesome_image_url('phone-mockup.png');

// Get app store links from theme options
$app_store_link = get_option('awesome_app_store_url', '#');
$google_play_link = get_option('awesome_google_play_url', '#');

// Get cold wallet page
$coldwallet_page = get_page_by_path('cold-wallet');

// Get download section from theme options
$download_subtitle = get_option('awesome_download_subtitle', 'Download from Apple App Store and Google Play Store');
$download_title = get_option('awesome_download_title', 'Launching Soon.');
?>

<main class="site-main">
    
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero__content">
                <div class="hero__image">
                    <img src="<?php echo esc_url($hero_image_url); ?>" alt="<?php echo esc_attr($hero_title); ?>">
                </div>
                <div class="hero__text">
                    <h1><?php echo esc_html($hero_title); ?></h1>
                    <p><?php echo esc_html($hero_subtitle); ?></p>
                    <p class="launching-soon"><strong><?php echo esc_html($hero_launching_text); ?></strong></p>
                    <div class="app-buttons">
                        <a href="<?php echo esc_url($app_store_link); ?>" class="app-store-btn app-store-btn--dark">
                            <img class="app-icon" src="<?php echo awesome_icon_url('icon-apple.svg'); ?>" alt="" width="20" height="24">
                            <div class="app-store-btn__content">
                                <span class="app-store-btn__label"><?php _e('Download on the', 'awesome-theme'); ?></span>
                                <span class="app-store-btn__store"><?php _e('App Store', 'awesome-theme'); ?></span>
                            </div>
                        </a>
                        <a href="<?php echo esc_url($google_play_link); ?>" class="app-store-btn app-store-btn--dark">
                            <img class="app-icon" src="<?php echo awesome_icon_url('icon-google-play.svg'); ?>" alt="" width="21" height="24">
                            <div class="app-store-btn__content">
                                <span class="app-store-btn__label"><?php _e('GET IT ON', 'awesome-theme'); ?></span>
                                <span class="app-store-btn__store"><?php _e('Google Play', 'awesome-theme'); ?></span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Awesome Section -->
    <?php get_template_part('patterns/section', 'why-awesome'); ?>

    <!-- Features Section -->
    <?php get_template_part('patterns/section', 'features'); ?>

    <!-- Cold Wallet Section -->
    <?php if ($coldwallet_page) : 
        $coldwallet_logo_id = get_post_meta($coldwallet_page->ID, 'coldwallet_logo', true);
        $coldwallet_image_id = get_post_meta($coldwallet_page->ID, 'coldwallet_image', true);
        $coldwallet_logo_url = $coldwallet_logo_id ? wp_get_attachment_url($coldwallet_logo_id) : awesome_image_url('coolwallet-logo.png');
        $coldwallet_image_url = $coldwallet_image_id ? wp_get_attachment_url($coldwallet_image_id) : awesome_image_url('cold-wallet.png');
    ?>
    <section id="cold-wallet" class="cold-wallet">
        <div class="container">
            <div class="cold-wallet__content">
                <div class="cold-wallet__text">
                    <h2><?php echo esc_html($coldwallet_page->post_title); ?></h2>
                    <?php echo apply_filters('the_content', $coldwallet_page->post_content); ?>
                    <div class="cold-wallet__logo">
                        <img src="<?php echo esc_url($coldwallet_logo_url); ?>" alt="CoolWallet S" width="302" height="86">
                    </div>
                </div>
                <div class="cold-wallet__image">
                    <img src="<?php echo esc_url($coldwallet_image_url); ?>" alt="<?php echo esc_attr($coldwallet_page->post_title); ?>">
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Partners Section -->
    <?php get_template_part('patterns/section', 'partners'); ?>

    <!-- FAQ Section -->
    <?php get_template_part('patterns/section', 'faq'); ?>

    <!-- Download CTA Section -->
    <section id="download" class="download-cta">
        <div class="container">
            <p class="download-cta__subtitle"><?php echo esc_html($download_subtitle); ?></p>
            <h2 class="download-cta__title"><?php echo esc_html($download_title); ?></h2>
            <div class="app-buttons app-buttons--center">
                <a href="<?php echo esc_url($app_store_link); ?>" class="app-store-btn app-store-btn--light">
                    <img class="app-icon" src="<?php echo awesome_icon_url('icon-apple.svg'); ?>" alt="" width="20" height="24">
                    <div class="app-store-btn__content">
                        <span class="app-store-btn__label"><?php _e('Download on the', 'awesome-theme'); ?></span>
                        <span class="app-store-btn__store"><?php _e('App Store', 'awesome-theme'); ?></span>
                    </div>
                </a>
                <a href="<?php echo esc_url($google_play_link); ?>" class="app-store-btn app-store-btn--light">
                    <img class="app-icon" src="<?php echo awesome_icon_url('icon-google-play.svg'); ?>" alt="" width="21" height="24">
                    <div class="app-store-btn__content">
                        <span class="app-store-btn__label"><?php _e('GET IT ON', 'awesome-theme'); ?></span>
                        <span class="app-store-btn__store"><?php _e('Google Play', 'awesome-theme'); ?></span>
                    </div>
                </a>
            </div>
        </div>
    </section>

</main>

<?php
get_footer();
