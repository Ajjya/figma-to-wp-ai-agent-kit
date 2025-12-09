<?php
/**
 * Footer Template
 *
 * @package Awesome_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get theme options
$logo_id = get_option('awesome_logo');
$logo_url = $logo_id ? wp_get_attachment_url($logo_id) : awesome_logo_url('logo.png');

$contact_email = get_option('awesome_contact_email', 'info@test.io');

// Social links
$twitter_link = get_option('awesome_twitter_url');
$producthunt_link = get_option('awesome_producthunt_url');
$github_link = get_option('awesome_github_url');
$reddit_link = get_option('awesome_reddit_url');
$blockstack_link = get_option('awesome_blockstack_url');
$bitcointalk_link = get_option('awesome_bitcointalk_url');
?>

    <!-- Footer -->
    <footer id="contact" class="footer">
        <div class="container">
            <div class="footer__content">
                <div class="footer__logo">
                    <img src="<?php echo esc_url($logo_url); ?>" alt="<?php bloginfo('name'); ?>">
                </div>
                <div class="footer__nav">
                    <h4><?php _e('Navigation', 'awesome-theme'); ?></h4>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer',
                        'menu_class'     => '',
                        'container'      => false,
                        'fallback_cb'    => 'awesome_footer_fallback_menu',
                        'depth'          => 1,
                    ));
                    ?>
                </div>
                <div class="footer__contact">
                    <h4><?php _e('Contact', 'awesome-theme'); ?></h4>
                    <p class="footer__email"><?php echo esc_html($contact_email); ?></p>
                    <div class="footer__social">
                        <?php if ($twitter_link): ?>
                            <a href="<?php echo esc_url($twitter_link); ?>" aria-label="<?php esc_attr_e('Twitter', 'awesome-theme'); ?>" target="_blank" rel="noopener">
                                <img src="<?php echo awesome_icon_url('icon-twitter.svg'); ?>" alt="" width="24" height="24">
                            </a>
                        <?php endif; ?>
                        <?php if ($producthunt_link): ?>
                            <a href="<?php echo esc_url($producthunt_link); ?>" aria-label="<?php esc_attr_e('Product Hunt', 'awesome-theme'); ?>" target="_blank" rel="noopener">
                                <img src="<?php echo awesome_icon_url('icon-producthunt.svg'); ?>" alt="" width="24" height="24">
                            </a>
                        <?php endif; ?>
                        <?php if ($github_link): ?>
                            <a href="<?php echo esc_url($github_link); ?>" aria-label="<?php esc_attr_e('GitHub', 'awesome-theme'); ?>" target="_blank" rel="noopener">
                                <img src="<?php echo awesome_icon_url('icon-github.svg'); ?>" alt="" width="24" height="24">
                            </a>
                        <?php endif; ?>
                        <?php if ($reddit_link): ?>
                            <a href="<?php echo esc_url($reddit_link); ?>" aria-label="<?php esc_attr_e('Reddit', 'awesome-theme'); ?>" target="_blank" rel="noopener">
                                <img src="<?php echo awesome_icon_url('icon-reddit.svg'); ?>" alt="" width="24" height="24">
                            </a>
                        <?php endif; ?>
                        <?php if ($blockstack_link): ?>
                            <a href="<?php echo esc_url($blockstack_link); ?>" aria-label="<?php esc_attr_e('Blockstack', 'awesome-theme'); ?>" target="_blank" rel="noopener">
                                <img src="<?php echo awesome_icon_url('icon-blockstack.svg'); ?>" alt="" width="24" height="24">
                            </a>
                        <?php endif; ?>
                        <?php if ($bitcointalk_link): ?>
                            <a href="<?php echo esc_url($bitcointalk_link); ?>" aria-label="<?php esc_attr_e('Bitcointalk', 'awesome-theme'); ?>" target="_blank" rel="noopener">
                                <img src="<?php echo awesome_icon_url('icon-bitcointalk.svg'); ?>" alt="" width="24" height="24">
                            </a>
                        <?php endif; ?>
                        <?php 
                        // Show default icons if no social links are set
                        if (!$twitter_link && !$producthunt_link && !$github_link && !$reddit_link && !$blockstack_link && !$bitcointalk_link): 
                        ?>
                            <a href="#" aria-label="<?php esc_attr_e('Twitter', 'awesome-theme'); ?>">
                                <img src="<?php echo awesome_icon_url('icon-twitter.svg'); ?>" alt="" width="24" height="24">
                            </a>
                            <a href="#" aria-label="<?php esc_attr_e('Product Hunt', 'awesome-theme'); ?>">
                                <img src="<?php echo awesome_icon_url('icon-producthunt.svg'); ?>" alt="" width="24" height="24">
                            </a>
                            <a href="#" aria-label="<?php esc_attr_e('GitHub', 'awesome-theme'); ?>">
                                <img src="<?php echo awesome_icon_url('icon-github.svg'); ?>" alt="" width="24" height="24">
                            </a>
                            <a href="#" aria-label="<?php esc_attr_e('Reddit', 'awesome-theme'); ?>">
                                <img src="<?php echo awesome_icon_url('icon-reddit.svg'); ?>" alt="" width="24" height="24">
                            </a>
                            <a href="#" aria-label="<?php esc_attr_e('Blockstack', 'awesome-theme'); ?>">
                                <img src="<?php echo awesome_icon_url('icon-blockstack.svg'); ?>" alt="" width="24" height="24">
                            </a>
                            <a href="#" aria-label="<?php esc_attr_e('Bitcointalk', 'awesome-theme'); ?>">
                                <img src="<?php echo awesome_icon_url('icon-bitcointalk.svg'); ?>" alt="" width="24" height="24">
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="footer__subscribe">
                        <form class="subscribe-form" id="subscribe-form">
                            <input type="email" name="email" placeholder="<?php esc_attr_e('Your e-mail', 'awesome-theme'); ?>" required>
                            <button type="submit"><?php _e('Subscribe', 'awesome-theme'); ?></button>
                            <?php wp_nonce_field('awesome_nonce', 'subscribe_nonce'); ?>
                        </form>
                    </div>
                </div>
            </div>
            <div class="footer__bottom">
                <p><?php _e('Copyright', 'awesome-theme'); ?> <?php echo date('Y'); ?> <strong><?php bloginfo('name'); ?></strong></p>
            </div>
        </div>
    </footer>

    <script>
    // Newsletter subscription form handler
    jQuery(document).ready(function($) {
        $('#subscribe-form').on('submit', function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $button = $form.find('button[type="submit"]');
            var originalText = $button.text();
            
            $button.prop('disabled', true).text('...');
            
            $.ajax({
                url: awesomeAjax.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'awesome_subscribe',
                    nonce: awesomeAjax.nonce,
                    email: $form.find('input[name="email"]').val()
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.data.message);
                        $form.find('input[name="email"]').val('');
                    } else {
                        alert(response.data.message || 'An error occurred');
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                },
                complete: function() {
                    $button.prop('disabled', false).text(originalText);
                }
            });
        });
    });
    </script>

    <?php wp_footer(); ?>
</body>
</html>

<?php
/**
 * Fallback footer menu
 */
function awesome_footer_fallback_menu() {
    ?>
    <ul>
        <li><a href="#why-awesome"><?php _e('Why Awesome?', 'awesome-theme'); ?></a></li>
        <li><a href="#features"><?php _e('Features', 'awesome-theme'); ?></a></li>
        <li><a href="#partners"><?php _e('Partners', 'awesome-theme'); ?></a></li>
        <li><a href="#faq"><?php _e('FAQ', 'awesome-theme'); ?></a></li>
        <li><a href="#contact"><?php _e('Contact', 'awesome-theme'); ?></a></li>
    </ul>
    <?php
}
