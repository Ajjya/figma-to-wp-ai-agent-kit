# Step 6D: Forms Setup

Use general AI instructions `docs/AI-INSTRUCTIONS.md`
Use step AI instructions `docs/STEP-6-Html-to-WP.md`

Take theme name from task: `[task.title slugified]`
Theme Location:
   - websites/[site-name]/wp-content/themes/[theme-name]/

## Setup Requirements

1. **Easy SMTP Plugin** - For email delivery
2. **Google reCAPTCHA v3** - For bot protection
3. **Custom AJAX Handler** - For form submissions

## Step 1: AI Installs & Activates Required Plugins

The AI will automatically:

1. Search for and install **WP Mail SMTP** plugin
2. Search for and install **Newsletter** plugin (by Satollo)
3. Activate both plugins
4. Enable required plugin features in code

**User Setup (Later):**
- Go to **Settings > WP Mail SMTP** to configure your SMTP credentials
- Go to **Settings > Newsletter** to configure newsletter settings

## Step 2: Configure Newsletter Subscription (User Setup)

After AI installs the Newsletter plugin:

1. Go to **Settings > Newsletter** to configure
2. In your theme, integrate newsletter signup with the plugin's forms
3. When user subscribes, show success message via JavaScript (no page redirect)

## Step 3: Configure contact form

File: `page-contact-us.php`

```php
<?php
declare(strict_types=1);

if (!defined('ABSPATH')) {
  exit;
}

get_header();

if (have_posts()) {
  the_post();
  ?>

<section class="contact_us_main has-global-padding">
  <div class="wrapper">
    <h2><?php the_title(); ?></h2>
    <div class="contact_content"><?php the_content(); ?></div>
    
    <div class="contact_wrap">
      <?php get_template_part('template-parts/contact-form'); ?>
    </div>
  </div> 
</section>

<?php } 
wp_reset_postdata(); 
get_footer();
```

## Step 4: Create Contact Form Template Part

File: `template-parts/contact-form.php`

```php
<?php
declare(strict_types=1);

if (!defined('ABSPATH')) {
  exit;
}

$recaptcha_site_key = getenv('RECAPTCHA_SITE_KEY');
?>

<div class="contact-form-wrapper">
  <form id="contactForm" class="contact-form" method="POST">
    
    <div class="form-group">
      <label for="fullname">Full Name *</label>
      <input 
        type="text" 
        id="fullname" 
        name="fullname" 
        required
        aria-label="Full Name"
      >
      <span class="error-message" id="fullname-error"></span>
    </div>

    <div class="form-group">
      <label for="email">Email *</label>
      <input 
        type="email" 
        id="email" 
        name="email" 
        required
        aria-label="Email Address"
      >
      <span class="error-message" id="email-error"></span>
    </div>

    <div class="form-group">
      <label for="phone">Phone</label>
      <input 
        type="tel" 
        id="phone" 
        name="phone"
        aria-label="Phone Number"
      >
    </div>

    <div class="form-group">
      <label for="message">Message *</label>
      <textarea 
        id="message" 
        name="message" 
        rows="5"
        required
        aria-label="Message"
      ></textarea>
      <span class="error-message" id="message-error"></span>
    </div>

    <input type="hidden" id="recaptchaToken" name="recaptchaToken" value="">
    
    <button type="submit" class="btn-submit">Send Message</button>
    <div id="form-status" class="form-status"></div>

  </form>
</div>

<!-- Load reCAPTCHA script -->
<!-- Replace YOUR_SITE_KEY with the key from Google reCAPTCHA admin panel -->
<script src="https://www.google.com/recaptcha/api.js?render=YOUR_SITE_KEY"></script>

<script>
(function() {
  const form = document.getElementById('contactForm');
  const statusDiv = document.getElementById('form-status');
  const siteKey = '<?php echo esc_js(get_option('recaptcha_site_key')); ?>';

  // Load reCAPTCHA token before submission
  grecaptcha.ready(function() {
    grecaptcha.execute(siteKey, { action: 'submit' }).then(function(token) {
      document.getElementById('recaptchaToken').value = token;
    });
  });

  form.addEventListener('submit', async function(e) {
    e.preventDefault();
    clearErrors();

    const formData = new FormData(form);
    formData.append('action', 'send_contact_message');
    formData.append('nonce', '<?php echo esc_js(wp_create_nonce('send_contact_form')); ?>');

    try {
      const response = await fetch('<?php echo esc_js(admin_url('admin-ajax.php')); ?>', {
        method: 'POST',
        body: formData
      });

      const data = await response.json();

      if (data.success) {
        showSuccess('Message sent successfully! We will contact you soon.');
        form.reset();
        // Refresh token
        grecaptcha.execute(siteKey, { action: 'submit' }).then(function(token) {
          document.getElementById('recaptchaToken').value = token;
        });
      } else {
        showError(data.data.message || 'Something went wrong. Please try again.');
      }
    } catch (error) {
      console.error('Error:', error);
      showError('Network error. Please try again.');
    }
  });

  function clearErrors() {
    document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
    statusDiv.className = 'form-status';
  }

  function showError(message) {
    statusDiv.className = 'form-status error';
    statusDiv.textContent = message;
  }

  function showSuccess(message) {
    statusDiv.className = 'form-status success';
    statusDiv.textContent = message;
  }
})();
</script>
```

## Step 5: Create AJAX Handler

File: `inc/ajax-contact-form.php`

```php
<?php
declare(strict_types=1);

if (!defined('ABSPATH')) {
  exit;
}

// Register AJAX handlers
add_action('wp_ajax_send_contact_message', 'awesome_send_contact_message');
add_action('wp_ajax_nopriv_send_contact_message', 'awesome_send_contact_message');

function awesome_send_contact_message() {
  // Verify nonce
  if (empty($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'send_contact_form')) {
    wp_send_json_error([
      'message' => 'Security check failed'
    ]);
  }

  // Sanitize inputs
  $fullname = '';
  if (!empty($_POST['fullname']) && is_string($_POST['fullname'])) {
    $fullname = sanitize_text_field($_POST['fullname']);
  }

  $email = '';
  if (!empty($_POST['email']) && is_string($_POST['email'])) {
    if (is_email($_POST['email'])) {
      $email = sanitize_email($_POST['email']);
    } else {
      wp_send_json_error([
        'message' => 'Invalid email address'
      ]);
    }
  }

  $phone = '';
  if (!empty($_POST['phone']) && is_string($_POST['phone'])) {
    $phone = sanitize_text_field($_POST['phone']);
  }

  $message = '';
  if (!empty($_POST['message']) && is_string($_POST['message'])) {
    $message = sanitize_textarea_field($_POST['message']);
  }

  $token = '';
  if (!empty($_POST['recaptchaToken']) && is_string($_POST['recaptchaToken'])) {
    $token = $_POST['recaptchaToken'];
  }

  // Validate required fields
  if (empty($fullname) || empty($email) || empty($message)) {
    wp_send_json_error([
      'message' => 'Please fill in all required fields'
    ]);
  }

  // Verify reCAPTCHA
  $recaptcha_secret = get_option('recaptcha_secret_key');
  
  if (!empty($token) && !empty($recaptcha_secret)) {
    $recaptcha_response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
      'body' => [
        'secret' => $recaptcha_secret,
        'response' => $token
      ]
    ]);

    if (!is_wp_error($recaptcha_response)) {
      $recaptcha_body = json_decode(wp_remote_retrieve_body($recaptcha_response));
      
      // Score threshold (0.0 - 1.0, higher is more likely human)
      $score_threshold = 0.5;
      
      if (empty($recaptcha_body->success) || $recaptcha_body->score < $score_threshold) {
        wp_send_json_error([
          'message' => 'Bot detection triggered. Please try again.'
        ]);
      }
    }
  }

  // Send email
  $to = get_option('admin_email');
  $subject = 'New Contact Message from ' . $fullname . ' - ' . get_bloginfo('name');
  
  $message_html = '<h2>New Contact Message</h2>';
  $message_html .= '<table style="border-collapse: collapse; width: 100%;">';
  $message_html .= '<tr><td style="padding: 10px; border: 1px solid #ddd;"><strong>Name:</strong></td><td style="padding: 10px; border: 1px solid #ddd;">' . esc_html($fullname) . '</td></tr>';
  $message_html .= '<tr><td style="padding: 10px; border: 1px solid #ddd;"><strong>Email:</strong></td><td style="padding: 10px; border: 1px solid #ddd;"><a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a></td></tr>';
  
  if (!empty($phone)) {
    $message_html .= '<tr><td style="padding: 10px; border: 1px solid #ddd;"><strong>Phone:</strong></td><td style="padding: 10px; border: 1px solid #ddd;">' . esc_html($phone) . '</td></tr>';
  }
  
  $message_html .= '<tr><td style="padding: 10px; border: 1px solid #ddd;"><strong>Message:</strong></td><td style="padding: 10px; border: 1px solid #ddd;">' . nl2br(esc_html($message)) . '</td></tr>';
  $message_html .= '</table>';

  $headers = [
    'Content-Type: text/html; charset=UTF-8',
    'From: ' . get_option('blogname') . ' <' . get_option('admin_email') . '>'
  ];

  $sent = wp_mail($to, $subject, $message_html, $headers);

  if (!$sent) {
    if (WP_DEBUG) {
      wp_send_json_error([
        'message' => 'Failed to send email'
      ]);
    } else {
      wp_send_json_error([
        'message' => 'Unable to send message at this time. Please try again later.'
      ]);
    }
  }

  // Store message in database
  $post_id = wp_insert_post([
    'post_type' => 'contact_message',
    'post_status' => 'publish',
    'post_title' => 'Contact from ' . $fullname . ' - ' . current_time('Y-m-d H:i:s'),
    'post_content' => $message
  ]);

  if (!is_wp_error($post_id)) {
    update_post_meta($post_id, '_contact_email', $email);
    update_post_meta($post_id, '_contact_phone', $phone);
    update_post_meta($post_id, '_contact_fullname', $fullname);
  }

  wp_send_json_success([
    'message' => 'Message sent successfully'
  ]);
}
```

## Step 6: Register Contact Message Post Type

Add to `inc/custom-post-types.php`:

```php
<?php
declare(strict_types=1);

function awesome_register_contact_message_cpt() {
  register_post_type('contact_message', [
    'label' => 'Contact Messages',
    'public' => false,
    'show_ui' => true,
    'show_in_menu' => true,
    'capability_type' => 'post',
    'supports' => ['title', 'editor'],
    'menu_icon' => 'dashicons-email'
  ]);
}

add_action('init', 'awesome_register_contact_message_cpt');
```

## Step 7: Configure reCAPTCHA Keys (User Setup)

**User Action Required:**

1. Visit **https://www.google.com/recaptcha/admin** and register your site:
   - Label: Your Site Name
   - reCAPTCHA type: v3
   - Domains: your-site.com
2. Copy the **Site Key** and **Secret Key**
3. Go to **Settings > General** (or custom settings page created by AI)
4. Enter your reCAPTCHA keys in the settings form
5. Save the settings

The AI will have already created the settings page and code to handle storage of these keys in WordPress options.

## Step 8: Update functions.php

Add these requires to your theme's `functions.php`:

```php
require_once AWESOME_DIR . '/inc/ajax-contact-form.php';
require_once AWESOME_DIR . '/inc/custom-post-types.php';
```

1. Go to **Pages > Add New**
2. Title: "Contact Us"
3. Content: Add your contact page content
4. Use slug: `contact-us`
5. Publish

## Step 9: Adjust CSS Styling if needed


## Installation Checklist

**AI Handles:**
- [ ] Search, download, and install WP Mail SMTP plugin
- [ ] Search, download, and install Newsletter plugin
- [ ] Activate both plugins
- [ ] Create `inc/ajax-contact-form.php`
- [ ] Create `inc/custom-post-types.php`
- [ ] Create admin settings page for reCAPTCHA keys
- [ ] Update `functions.php` with requires
- [ ] Create `page-contact-us.php`
- [ ] Create `template-parts/contact-form.php`
- [ ] Create Contact Us page in WordPress

**User Handles (Manual Setup):**
- [ ] Configure SMTP settings at **Settings > WP Mail SMTP**
- [ ] Configure Newsletter at **Settings > Newsletter**
- [ ] Set up reCAPTCHA v3 account at https://www.google.com/recaptcha/admin
- [ ] Enter reCAPTCHA keys in theme settings
- [ ] Add CSS to style.css
- [ ] Test form submission
- [ ] Test email delivery
- [ ] Verify reCAPTCHA working

Ask user:
```
Ready to proceed to Step 4e (Final Integration)?
```

**Next Step:** Proceed to **Step 4e: Final Integration**