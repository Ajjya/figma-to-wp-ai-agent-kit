 # Step 4a: Assets & Libraries 

Use general AI instructions `docs/AI-INSTRUCTIONS.md`
Use step AI instructions `docs/STEP-6-Html-to-WP.md`

 ## Goal
 Move CSS/JS/images/fonts from `website/markup/` into the theme and enqueue them. Include only libraries actually used in the markup.

 ## Prereqs
 - Markup extracted (`status: "Markup done"`)

 ## Read
 - `tasks/current-task.json` → `title`
 - `website/markup/assets/` → CSS/JS/images/fonts present
 - `knowledge-base/theme/assets/libs/` → available libs

### From Current Task:
- Read `/tasks/current-task.json` → `title` (for theme name)


### Create theme folder
Take theme name from task: `[task.title slugified]`
Theme Location:
   - websites/[site-name]/wp-content/themes/[theme-name]/

- Theme reference: `knowledge-base/theme`

### From Markup:
- Check `/website/markup/assets/` structure
- List all CSS files
- List all JS files
- List all images
- List all fonts

### From Reference Theme:
- Check available libraries at: `/knowledge-base/themes/awesome_group/wp-content/themes/awesome/assets/libs/`

 ## Available Libs (reference)
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
 1) Inventory `website/markup/assets/` (CSS/JS/images/fonts) and detect external libs from `<link>`/`<script>`.

### 1. Analyze Markup Assets

First, scan the markup directory:

```bash
# List all asset files
ls -R /website/markup/assets/
```

Create inventory:

```
📦 Asset Inventory:

**CSS Files:** [count]
- [List each .css file]

**JavaScript Files:** [count]
- [List each .js file]

**Images:** [count]
- [List format counts: X JPG, Y PNG, Z SVG]

**Fonts:** [count]
- [List font families and formats: WOFF2, WOFF, TTF]

**External Libraries Used in Markup:**
- [Detect from <script> and <link> tags]
```

 2) Confirm with user which libs to include. Decide CDN vs local; minify now or later.

```
🔧 Library Configuration Questions:

I've analyzed the markup and found these dependencies:
[List detected libraries]

From the reference theme, these libraries are available:
✅ jQuery + plugins (cookie, masked input, scrollTo)
✅ AOS (Animate On Scroll)
✅ Datepicker
✅ Lazyload
✅ Lightbox
✅ HC Offcanvas Navigation (mobile menu)
✅ SweetAlert
✅ Form handling utilities

Questions:
1. Which libraries from the reference do you need?
2. Are there additional libraries in the markup not in reference?
3. Should I use CDN or local files for common libraries (jQuery, etc.)?
4. Do you need to minimize/concatenate JS files?
5. Should I set up asset versioning/cache busting?
```

 3) Create structure:

Generate WordPress theme structure:

```
/website/wp-content/themes/[theme-slug]/
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

 4) Copy & group assets by purpose (variables/base/layout/components/pages). Move images to `assets/images/` and fonts to `assets/fonts/`. Copy only needed libs to `assets/libs/`.

**CSS Files:**

```php
// Copy CSS from markup to theme with organization:

markup/assets/css/variables.css 
  → theme/assets/css/variables.css

markup/assets/css/[file].css 
  → theme/assets/css/[organized-name].css

// Organize by purpose:
// - variables.css (CSS custom properties)
// - base.css (reset, typography, basic elements)
// - layout.css (grid, containers, sections)
// - components.css (buttons, cards, forms)
// - pages/[page].css (page-specific styles)
```

**JavaScript Files:**

```php
// Main theme scripts:
markup/assets/js/main.js 
  → theme/assets/js/main.js

// Component scripts:
markup/assets/js/components/ 
  → theme/assets/js/components/

// Libraries from reference:
knowledge-base/themes/awesome_group/.../libs/[library]
  → theme/assets/libs/[library]
```

**Images:**

```php
// Organize images by type:
markup/assets/images/logo.* 
  → theme/assets/images/logo.*

markup/assets/images/icons/ 
  → theme/assets/images/icons/

markup/assets/images/[page]/ 
  → theme/assets/images/pages/[page]/

// Content images (uploaded via WordPress):
// Move to /uploads/ directory or note for user to upload
```

**Fonts:**

```php
// Web fonts:
markup/assets/fonts/[font-family]/ 
  → theme/assets/fonts/[font-family]/

// Ensure WOFF2 and WOFF formats for browser compatibility
```

 5) Enqueue assets in `inc/enqueue-scripts.php` (use theme version for cache-busting). Use WordPress-bundled jQuery.

Create file: `/website/wp-content/themes/[theme-slug]/inc/enqueue-scripts.php`

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

 6) Create `/assets/js/main.js` with init for AOS/Lazyload, mobile menu toggle, smooth scroll.

Create file: `/website/wp-content/themes/[theme-slug]/assets/js/main.js`

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

 7) Confirm per-lib configuration (AOS, lightbox, forms, datepicker) only if included.

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

 8) Optional: minify CSS/JS, convert images to WebP, subset fonts, add asset versioning.

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

 9) Ensure `style.css` has a valid WordPress header (Theme Name, Version, Text Domain).

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

## IMPORTANT: Figma Asset Migration to WordPress Media Library

**Problem:** Assets extracted from Figma reference localhost URLs like:
```html
<img src="http://localhost:3845/assets/d915c1354e6f7b603747f520a7e54c82310305bc.svg" alt="Logo">
```

**Solution:** All content images MUST be uploaded to WordPress Media Library and referenced via custom fields.

### Step 1: Identify Asset Types

**Theme Assets** (stay in theme folder):
- Logo (header.php uses custom logo feature)
- Icon sprites
- UI elements (buttons, arrows)
- Background patterns

**Content Assets** (move to Media Library):
- Feature icons
- Partner logos
- Hero images
- Section images
- FAQ icons
- Any image that's page content

### Step 2: Extract Assets from HTML

```bash
# Find all asset references in HTML
grep -r "localhost:3845/assets" dev/html/[site-name]/ | grep -o '[a-f0-9]\{40\}\.[a-z]*' | sort -u > asset-list.txt

# This creates list like:
# d915c1354e6f7b603747f520a7e54c82310305bc.svg
# 139d5e67c9bdb89e6a051b5dd6bc9023c2308045.svg
# etc.
```

### Step 3: Download Assets from Figma MCP Server

**If MCP Server is Still Running:**

```bash
# Download each asset
while read hash; do
  curl "http://localhost:3845/assets/$hash" -o "temp-assets/$hash"
done < asset-list.txt
```

**If MCP Server is Not Running:**

Ask user to:
1. Restart Figma MCP server
2. Re-export assets from Figma
3. Or provide alternative source for assets

### Step 4: Upload to WordPress Media Library

Create script: `inc/import-figma-assets.php`

```php
<?php
/**
 * Import Figma Assets to WordPress Media Library
 * Run once after theme activation
 */

function import_figma_assets() {
    // Prevent multiple runs
    if (get_option('figma_assets_imported')) {
        return;
    }
    
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    
    // Asset mapping: hash => descriptive name
    $assets = array(
        'd915c1354e6f7b603747f520a7e54c82310305bc.svg' => 'logo',
        '139d5e67c9bdb89e6a051b5dd6bc9023c2308045.svg' => 'icon-security',
        '2ddced4cf1c8cdaa7835acbe4ea67c02f6040c8e.svg' => 'icon-aml',
        '6619ee07c88b3ca76d9f8d00a413d1c65e42a603.svg' => 'icon-labels',
        // ... add all content assets
    );
    
    $imported_assets = array();
    
    foreach ($assets as $hash => $name) {
        // Try to download from Figma MCP server
        $url = "http://localhost:3845/assets/$hash";
        $temp_file = download_url($url);
        
        if (is_wp_error($temp_file)) {
            // Try local file if download fails
            $local_path = get_template_directory() . '/temp-assets/' . $hash;
            if (file_exists($local_path)) {
                $temp_file = $local_path;
            } else {
                continue; // Skip if not available
            }
        }
        
        // Prepare file array
        $file_array = array(
            'name' => $name . '.' . pathinfo($hash, PATHINFO_EXTENSION),
            'tmp_name' => $temp_file
        );
        
        // Upload to media library
        $attachment_id = media_handle_sideload($file_array, 0, $name);
        
        if (!is_wp_error($attachment_id)) {
            $imported_assets[$hash] = $attachment_id;
            
            // Update attachment metadata
            update_post_meta($attachment_id, '_figma_hash', $hash);
            update_post_meta($attachment_id, '_figma_name', $name);
        }
    }
    
    // Save mapping for later reference
    update_option('figma_asset_mapping', $imported_assets);
    update_option('figma_assets_imported', true);
    
    return $imported_assets;
}

// Run on theme activation
add_action('after_switch_theme', 'import_figma_assets');

/**
 * Helper function to get WordPress media URL from Figma hash
 */
function get_figma_asset_url($hash) {
    $mapping = get_option('figma_asset_mapping', array());
    
    if (isset($mapping[$hash])) {
        return wp_get_attachment_url($mapping[$hash]);
    }
    
    // Fallback: search by meta
    $attachments = get_posts(array(
        'post_type' => 'attachment',
        'posts_per_page' => 1,
        'meta_key' => '_figma_hash',
        'meta_value' => $hash
    ));
    
    if ($attachments) {
        return wp_get_attachment_url($attachments[0]->ID);
    }
    
    // Last fallback: use theme asset if available
    $theme_path = get_template_directory_uri() . '/assets/images/' . $hash;
    return $theme_path;
}
```

### Step 5: Update Templates to Use Custom Fields Instead of Hardcoded URLs

**CRITICAL:** Do NOT use hardcoded localhost URLs in templates!

**Wrong:**
```php
<img src="http://localhost:3845/assets/d915c1354e6f7b603747f520a7e54c82310305bc.svg" alt="Logo">
```

**Correct Option 1 - Custom Fields:**
```php
<?php
$hero_image_id = get_post_meta(get_the_ID(), 'hero_image', true);
if ($hero_image_id) {
    echo wp_get_attachment_image($hero_image_id, 'large', false, array('alt' => 'Hero Image'));
}
?>
```

**Correct Option 2 - Fallback with Helper:**
```php
<?php
$hero_image = get_post_meta(get_the_ID(), 'hero_image', true);
if ($hero_image) {
    // Custom field has attachment ID
    echo wp_get_attachment_image($hero_image, 'large');
} else {
    // Fallback to Figma asset
    $url = get_figma_asset_url('d915c1354e6f7b603747f520a7e54c82310305bc.svg');
    echo '<img src="' . esc_url($url) . '" alt="Hero Image">';
}
?>
```

**Correct Option 3 - Theme Asset (for UI elements):**
```php
<img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.svg" alt="Logo">
```

### Step 6: Update Custom Fields to Use Image Upload

In `inc/custom-fields.php`, ensure all image fields use media library:

```php
// Add image field with media library picker
wp_add_image(
    'hero_image',
    'Hero Image',
    get_the_ID(),
    'Upload or select hero image from media library'
);
```

### Step 7: Manual Upload Instructions for User

Create file: `ASSET-MIGRATION.md` in theme root:

```markdown
# Asset Migration Instructions

## Assets Need to be Uploaded

The theme currently references Figma assets via localhost URLs. These need to be uploaded to WordPress Media Library.

### Steps:

1. **Download Assets from Figma:**
   - Reopen Figma file
   - Use Figma MCP server to download assets
   - Or export assets manually from Figma

2. **Upload to WordPress:**
   - Go to WordPress Admin → Media → Add New
   - Upload all content images (feature icons, partner logos, hero images)
   - Note the attachment IDs

3. **Update Custom Fields:**
   - Go to Pages → Home → Edit
   - Scroll to custom fields meta boxes
   - Use "Upload Image" buttons to select uploaded images
   - Save page

4. **Theme Assets (Optional):**
   - Logo: Upload via Appearance → Customize → Site Identity → Logo
   - Background images: Keep in theme/assets/images/ folder
   - UI icons: Keep in theme/assets/images/icons/ folder

### Asset List:

See `temp-assets/` directory for downloaded Figma assets or check HTML for required images.
```

## Answers on questions
1. The HTML uses external assets. 
 - Download these assets automatically? - Yes
2. All content images MUST be in WordPress Media Library
 - Theme will include helper script to import from Figma
 - User can also manually upload via WordPress admin


 ## Ask
 - Separate page CSS or combined? Minify now?
 - Theme images vs media library?
 - Which JavaScript libraries do you need from the reference theme?

1. **Asset Organization:**
   - "Should I keep the markup's original file structure or reorganize?"
   - "Do you want page-specific CSS in separate files or combined?"

2. **Library Selection:**
   - "Which libraries from the reference theme do you actually need?"
   - "The markup uses [X] library but it's not in reference. Should I add it?"

3. **Performance:**
   - "Should I concatenate CSS files into one or keep separate for modularity?"
   - "Use CDN for common libraries (jQuery, AOS) or local files?"

4. **Image Handling:**
   - "Should theme images be in theme folder or WordPress media library?"
   - "Do you want SVG icons inline or as files?"

5. **Font Loading:**
   - "Use Google Fonts CDN or self-host fonts?"
   - "Should I add font-display: swap for performance?"

6. **Development vs Production:**
   - "Keep readable code for development or minify for production?"
   - "Should I set up a build process (Webpack/Gulp) for assets?"

 ## Success

Before proceeding to Step 4b:

- Assets copied and enqueued; no 404s
- `style.css` WP header present
- Only required libs included

 ## Validate
 - Activate theme → visit homepage → check console for 404s/errors → confirm styles/scripts loaded.

Show user:

```
✅ Step 4a Complete: Assets Migrated

**Theme Location:** /website/wp-content/themes/[theme-slug]/

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

**Next: Test Asset Loading**
1. Activate the theme in WordPress admin
2. Visit front page
3. Open browser console - check for 404 errors
4. Verify CSS is loading (inspect element)
5. Verify JS is loading (check console for errors)

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
