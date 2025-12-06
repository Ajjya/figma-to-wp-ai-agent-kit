# Step 4a: Assets & Libraries 

## Goal
1. Move CSS/JS/images/fonts from `dev/html/[themeName]/assets/` into the theme and enqueue them. Include only libraries actually used in the markup.
2. Create and fill next files:
    - `inc/enqueue-scripts.php`
    - `/assets/js/main.js`
    - `style.css`
3. Copy and acivate reference plugin `knowledge-base/plugins/wp-custom-field`

## AI Instructions
- Follow general AI instructions from `docs/AI-INSTRUCTIONS.md`
- Follow step-specific instructions from `docs/STEP-6-Html-to-WP.md`
- Do not invent anything - ask the user if you have any doubts before proceeding
- Do not create additional documentation (.md) files

## Critical Requirements
1. **Media Management**: Copy all images from markup to WordPress Media Library. Reference them in appropriate posts/pages
2. **Admin Editability**: Every icon, link, text, and title must be editable from WordPress admin. Use wp-custom-fields plugin for additional custom fields. Use metaboxes for additional fields if necessary.
3. **Theme Options**: Create custom options (similar to `knowledge-base/theme/inc/options.php`) for logo, social links, app download links, and other global settings
4. **Reference Theme**: Always use `knowledge-base/theme/` as your reference for structure and functionality

## Extract Task Data
From `tasks/current-task.json`, extract:
- `themeName` - The name of the theme you're building
- `status` - Current workflow status (must be "wp-initiated")
- `title` - Project title
- `menus` - Menu locations needed
- `widgets` - Widget areas needed
- `customPosts` - Custom post types to register
- `categories` - Taxonomy structure
- `imageSizes` - Required image dimensions (if specified)



## Prereqs
 - Markup extracted (`status: "wp-initiated"`)

## Create theme folder
Take theme name from task: `[task.themeName slugified]`
Create theme in next location:
   - websites/[title]/wp-content/themes/[themeName]/

## Reference 
 - Theme reference: `knowledge-base/theme`

## Read
### From Knowledge-base:
 - `knowledge-base/theme/assets/libs/` → available libs
 - `knowledge-base/theme/assets/js/` → available javascript

### From Markup:
- Check `dev/html/[themeName]/assets/` structure
- List all CSS files
- List all JS files
- List all images
- List all fonts

## Available Javascript Libs (reference):
Use Available libraries if needed for future: 
 - AOS (Animate On Scroll)
 - Lazyload
 - Lightbox
 - HC Offcanvas Navigation
 - Datepicker, SweetAlert, etc.

From the reference theme, these libraries are available:

```
JavaScript Libraries:
├── jquery.min.js              # jQuery core
├── jquery.cookie.js           # Cookie handling
├── jquery.maskedinput.min.js  # Input masking
├── jquery.scrollTo.min.js     # Smooth scrolling
├── lazyload.js                # Lazy loading images
├── aos/                       # Animate On Scroll
├── custom/                    # Custom scripts
├── customForm/                # Form handlers
├── datepicker/                # Date picker
├── flui/                      # Flui components
├── formError/                 # Form validation
├── hc-offcanvas-nav-master/  # Mobile navigation
├── lightbox/                  # Image lightbox
├── responsiveImages.js        # Responsive image handling
├── smoothscroll-master/       # Smooth scroll polyfill
└── sweetalert/                # Alert dialogs
```

## Steps
 1) Create structure:

Generate WordPress theme structure:

```
/websites/[title]/wp-content/themes/[themeName]/
├── style.css                      # Theme stylesheet (required)
├── functions.php                  # Theme functions (will create in 4b)
├── screenshot.png                 # Theme screenshot (optional)
├── assets/
│   ├── css/
│   │   ├── variables.css         # CSS custom properties
│   │   ├── base.css              # Reset/normalize
│   │   ├── layout.css            # Grid and layout
│   │   ├── components.css        # Reusable components
│   │   ├── pages/                # Page-specific styles
│   │   │   ├── front-page.css
│   │   │   ├── page.css
│   │   │   └── ...
│   │   └── admin.css             # WordPress admin styles
│   ├── js/
│   │   ├── main.js               # Main theme JavaScript
│   │   ├── navigation.js         # Menu handling
│   │   └── components/           # Component scripts
│   ├── libs/                     # Third-party libraries
│   │   ├── jquery.min.js
│   │   ├── aos/
│   │   ├── lazyload.js
│   │   └── [other libs]
│   ├── images/                   # Theme images
│   │   ├── logo.svg
│   │   ├── icons/
│   │   └── [other images]
│   └── fonts/                    # Web fonts
│       ├── [font-family]/
│       │   ├── font.woff2
│       │   └── font.woff
│       └── ...
├── inc/                          # Include files (will create in 4c)
├── template-parts/               # Template parts (will create in 4d)
└── [template files]              # Will create in 4b-4d
```

 2) Copy assets from `dev/html/[themeName]/assets/` (CSS/JS/images/fonts)

 3) Enqueue assets in `inc/enqueue-scripts.php` (use theme version for cache-busting). Use WordPress-bundled jQuery.

Create file: `websites/[title]/wp-content/themes/[themeName]/inc/enqueue-scripts.php`

```php
<?php
/**
 * Enqueue scripts and styles
 */

function theme_enqueue_styles() {
    $theme_version = wp_get_theme()->get('Version');
    
    // CSS Variables (load first)
    wp_enqueue_style(
        'theme-variables',
        get_template_directory_uri() . '/assets/css/variables.css',
        array(),
        $theme_version
    );
    
    // Base styles
    wp_enqueue_style(
        'theme-base',
        get_template_directory_uri() . '/assets/css/base.css',
        array('theme-variables'),
        $theme_version
    );
    
    // Layout styles
    wp_enqueue_style(
        'theme-layout',
        get_template_directory_uri() . '/assets/css/layout.css',
        array('theme-base'),
        $theme_version
    );
    
    // Component styles
    wp_enqueue_style(
        'theme-components',
        get_template_directory_uri() . '/assets/css/components.css',
        array('theme-layout'),
        $theme_version
    );
    
    // Main theme stylesheet (required by WordPress)
    wp_enqueue_style(
        'theme-style',
        get_stylesheet_uri(),
        array('theme-components'),
        $theme_version
    );
}
add_action('wp_enqueue_scripts', 'theme_enqueue_styles');

function theme_enqueue_scripts() {
    $theme_version = wp_get_theme()->get('Version');
    
    // jQuery (WordPress includes it, but deregister to use custom version if needed)
    // wp_deregister_script('jquery');
    // wp_enqueue_script(
    //     'jquery',
    //     get_template_directory_uri() . '/assets/libs/jquery.min.js',
    //     array(),
    //     '3.6.0',
    //     true
    // );
    
    // Use WordPress jQuery
    wp_enqueue_script('jquery');
    
    // jQuery plugins
    wp_enqueue_script(
        'jquery-cookie',
        get_template_directory_uri() . '/assets/libs/jquery.cookie.js',
        array('jquery'),
        $theme_version,
        true
    );
    
    // Lazyload
    wp_enqueue_script(
        'lazyload',
        get_template_directory_uri() . '/assets/libs/lazyload.js',
        array(),
        $theme_version,
        true
    );
    
    // AOS (Animate On Scroll)
    wp_enqueue_style(
        'aos-css',
        get_template_directory_uri() . '/assets/libs/aos/aos.css',
        array(),
        $theme_version
    );
    wp_enqueue_script(
        'aos-js',
        get_template_directory_uri() . '/assets/libs/aos/aos.js',
        array(),
        $theme_version,
        true
    );
    
    // Main theme script (load last)
    wp_enqueue_script(
        'theme-main',
        get_template_directory_uri() . '/assets/js/main.js',
        array('jquery', 'lazyload', 'aos-js'),
        $theme_version,
        true
    );
    
    // Localize script with WordPress data
    wp_localize_script('theme-main', 'themeData', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'siteUrl' => get_site_url(),
        'themePath' => get_template_directory_uri(),
        'nonce' => wp_create_nonce('theme-nonce')
    ));
}
add_action('wp_enqueue_scripts', 'theme_enqueue_scripts');

/**
 * Enqueue admin styles and scripts
 * IMPORTANT: wp_enqueue_media() is REQUIRED for custom field image/icon uploads
 */
function theme_enqueue_admin_scripts($hook) {
    // Enqueue admin styles
    wp_enqueue_style(
        'theme-admin',
        get_template_directory_uri() . '/assets/css/admin.css',
        array(),
        wp_get_theme()->get('Version')
    );
    
    // CRITICAL: Enqueue WordPress media uploader for icon/image fields in meta boxes
    // This is required for custom field image uploads to work in admin
    // Without this, the media library modal will not open when clicking upload buttons
    if ($hook === 'post.php' || $hook === 'post-new.php') {
        wp_enqueue_media();
    }
}
add_action('admin_enqueue_scripts', 'theme_enqueue_admin_scripts');
```

 3) Create `/assets/js/main.js` with init for AOS/Lazyload, mobile menu toggle, smooth scroll.

Create file: `websites/[title]/wp-content/themes/[themeName]/assets/js/main.js`

```javascript
/**
 * Main Theme JavaScript
 */

(function($) {
    'use strict';

    // Initialize on document ready
    $(document).ready(function() {
        
        // Initialize AOS (Animate On Scroll)
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true,
                offset: 100
            });
        }
        
        // Initialize Lazyload
        if (typeof LazyLoad !== 'undefined') {
            var lazyLoadInstance = new LazyLoad({
                elements_selector: '.lazy',
                threshold: 0
            });
        }
        
        // Mobile navigation toggle
        $('.mobile-menu-toggle').on('click', function(e) {
            e.preventDefault();
            $('body').toggleClass('menu-open');
            $(this).toggleClass('active');
        });
        
        // Smooth scroll for anchor links
        $('a[href^="#"]').on('click', function(e) {
            var target = $(this.getAttribute('href'));
            if (target.length) {
                e.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 100
                }, 800);
            }
        });
        
        // Add any custom markup JavaScript here
        // [User's custom code from markup/assets/js/main.js]
        
    });

    // Initialize on window load (for images, etc.)
    $(window).on('load', function() {
        // Handle any post-load initializations
    });

})(jQuery);
```

 4) Confirm per-lib configuration (AOS, lightbox, forms, datepicker) only if included.

**For Libraries That Need Configuration:**

Ask user:

```
🔧 Library Configuration Needed:

Some libraries require additional setup:

1. **AOS (Animate On Scroll)**
   - Default settings OK? (duration: 800ms, once: true)
   - Custom animation classes needed?

2. **Lightbox**
   - Activate for all images or specific classes?
   - Gallery grouping needed?

3. **Form Libraries**
   - Custom validation rules?
   - AJAX submission?

4. **Datepicker**
   - Date format preference?
   - Min/max date restrictions?

Please specify requirements for each library you're using.
```

 5) Optional: minify CSS/JS, convert images to WebP, subset fonts, add asset versioning.

Offer optimization:

```
⚡ Asset Optimization:

Would you like me to:

1. **Minify CSS** - Reduce CSS file sizes
   □ Yes, minify all CSS
   □ No, keep readable for development

2. **Minify JavaScript** - Reduce JS file sizes
   □ Yes, minify all JS
   □ No, keep readable for development

3. **Optimize Images**
   □ Compress JPG/PNG (lossy)
   □ Convert to WebP format
   □ Generate responsive image sizes
   □ Keep originals

4. **Font Optimization**
   □ Convert to WOFF2 only (modern browsers)
   □ Keep WOFF + WOFF2
   □ Subset fonts (remove unused characters)

5. **Asset Versioning**
   □ Add version numbers to prevent caching issues
   □ Use file modification time as version

Your choices?
```

 6) Ensure `style.css` has a valid WordPress header (Theme Name, Version, Text Domain).

Create theme's main stylesheet with proper WordPress header:

```css
/*!
Theme Name: [Theme Name from Task]
Theme URI: https://yoursite.com
Author: [Author from task or package.json]
Author URI: https://yoursite.com
Description: WordPress theme converted from Figma design
Version: 1.0.0
Requires at least: 6.0
Tested up to: 6.4
Requires PHP: 7.4
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: [theme-slug]
Tags: custom-background, custom-logo, custom-menu, featured-images, threaded-comments

This theme was generated from Figma design using the Figma-to-WP AI Agent Kit - git@github.com:Ajjya/figma-to-wp-ai-agent-kit.git.
*/

/* 
 * Main stylesheet 
 * Most styles are in /assets/css/ and enqueued via functions.php
 * This file contains theme-specific overrides
 */

/* You can add theme-specific CSS here or leave this file minimal */
```

## Answers on questions
1. The HTML uses external assets. 
 - Download these assets automatically? - Yes
2. All content images MUST be in WordPress Media Library
 - Theme will include helper script to import from Figma
 - User can also manually upload via WordPress admin

## Ask user
1. **Performance:**
   - "Should I concatenate CSS files into one or keep separate for modularity?"
   - "Use CDN for common libraries (jQuery, AOS) or local files?"

2. **Image Handling:**
   - "Should theme images be in theme folder or WordPress media library?"
   - "Do you want SVG icons inline or as files?"

3. **Font Loading:**
   - "Use Google Fonts CDN or self-host fonts?"
   - "Should I add font-display: swap for performance?"

4. **Development vs Production:**
   - "Keep readable code for development or minify for production?"
   - "Should I set up a build process (Webpack/Gulp) for assets?"

## Success

Before proceeding to Step 4b:

- Assets copied and enqueued; no 404s
- `style.css` WP header present

Show user:

```
✅ Step 4a Complete: Assets Migrated

**Theme Location:** websites/[title]/wp-content/themes/[themeName]/

**Assets Summary:**
✓ CSS Files: [count] files organized
✓ JavaScript: [count] files + [count] libraries
✓ Images: [count] files
✓ Fonts: [count] families
✓ Libraries: [list libraries included]

**Files Created:**
✓ inc/enqueue-scripts.php
✓ assets/js/main.js
✓ style.css (theme header)

Are all assets loading correctly?
□ Yes, proceed to Step 4b
□ No, I see these issues: [describe]
```

## Common Issues
 - Paths wrong → fix in `inc/enqueue-scripts.php`
 - jQuery conflicts → use WP jQuery; wrap with `(function($){ ... })(jQuery)`
 - Fonts not showing → verify `@font-face` and formats
 - Images missing → check paths or upload via media

**Issue:** CSS/JS not loading
**Solution:** Check file paths in enqueue-scripts.php, verify files exist

**Issue:** jQuery conflicts
**Solution:** Use WordPress's included jQuery, wrap custom code in (function($) {...})(jQuery)

**Issue:** Fonts not displaying
**Solution:** Check @font-face declarations, verify CORS if using external fonts

**Issue:** Images not showing
**Solution:** Verify image paths, check if images need to be in uploads/ instead

**Issue:** Library conflicts
**Solution:** Check console for errors, verify load order in enqueue-scripts.php

---

 Next: proceed to **Step 4b: Initial Templates**
