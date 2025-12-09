<?php
/**
 * Template Name: Cold Wallet
 * 
 * Template for Cold Wallet page
 *
 * @package Awesome_Theme
 */

get_header();

while (have_posts()) : the_post();
    // Get page custom fields
    $coldwallet_logo_id = get_post_meta(get_the_ID(), 'coldwallet_logo', true);
    $coldwallet_image_id = get_post_meta(get_the_ID(), 'coldwallet_image', true);
    
    $coldwallet_logo_url = $coldwallet_logo_id ? wp_get_attachment_url($coldwallet_logo_id) : awesome_image_url('coolwallet-logo.png');
    $coldwallet_image_url = $coldwallet_image_id ? wp_get_attachment_url($coldwallet_image_id) : awesome_image_url('cold-wallet.png');
?>

<main class="site-main">
    <!-- Cold Wallet Section -->
    <section id="cold-wallet" class="cold-wallet">
        <div class="container">
            <div class="cold-wallet__content">
                <div class="cold-wallet__text">
                    <h2><?php the_title(); ?></h2>
                    <?php the_content(); ?>
                    <div class="cold-wallet__logo">
                        <img src="<?php echo esc_url($coldwallet_logo_url); ?>" alt="CoolWallet S" width="302" height="86">
                    </div>
                </div>
                <div class="cold-wallet__image">
                    <img src="<?php echo esc_url($coldwallet_image_url); ?>" alt="<?php the_title(); ?>">
                </div>
            </div>
        </div>
    </section>
</main>

<?php
endwhile;

get_footer();
