# Step 4b: Initial Templates 

Use general AI instructions `docs/AI-INSTRUCTIONS.md`
Use step AI instructions `docs/STEP-6-Html-to-WP.md`

Take theme name from task: `[task.title slugified]`
Theme Location:
   - websites/[site-name]/wp-content/themes/[theme-name]/


## Objective
Create core templates: `functions.php`, `header.php`, `footer.php`, `index.php`, `404.php` and:

1. `knowledge-base/themes/awesome_group/wp-content/themes/awesome/inc`. Use all needed templates in website.
2. `knowledge-base/themes/awesome_group/wp-content/themes/awesome/theme-constants`. Use all needed in website.
3. `knowledge-base/themes/awesome_group/wp-content/themes/awesome/theme-functions`. Use all needed in website.

Important!: 
1. Copy images from mark up and save in Media. Use it in proproate posts/pages etc.
2. Copy content to admin part. Every icon, link, text, title must be editable from admin part. Use wp-custom-fields for additional fields
3. Create custom options with all needed settings similar to `knowledge-base/themes/awesome_group/wp-content/themes/awesome/inc/options.php`. Add possibility to change logo, social links, download app links from here.

## Prerequisites
- 4a done (assets loading)
- Theme directory exists

## Context to Gather

### From Markup:
- `website/markup/components/header.html`
- `website/markup/components/footer.html`

### From Reference Theme:
- `/knowledge-base/themes/awesome_group/wp-content/themes/awesome/`

### From Task:
- `/tasks/current-task.json` → menus, widgets

## AI Instructions

### 1. Analyze Markup Components

First, examine the extracted markup:

```
📋 Component Analysis:

Reading header.html:
- Logo location and markup
- Navigation structure (items, levels)
- Any special header elements (search, buttons, etc.)
- Mobile menu trigger

Reading footer.html:
- Footer sections/columns
- Footer menus
- Social links
- Copyright text
- Any widgets areas

Questions identified:
- [List dynamic elements that need WordPress functions]
```

### 2. Create functions.php

This is the most important file. Create with all essential WordPress setup:

```php
<?php
/**
 * Theme Functions
 * 
 * @package [ThemeName]
 * @version 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Setup
 */
function theme_setup() {
1) Inspect header/footer markup → identify logo, nav, buttons, mobile trigger, footer columns/social.
    add_theme_support('custom-logo', array(
        'height'      => 100,
2) Create `functions.php` with:
- `add_theme_support` (title-tag, thumbnails, html5, custom-logo)
- `register_nav_menus` (primary, footer)
- `widgets_init` (sidebar + N footer columns)
- `add_image_size` (as needed)
- `require inc/enqueue-scripts.php`
        'flex-height' => true,
        'flex-width'  => true,
Ask:
- Menu locations needed (primary/footer/mobile)?
- Footer widget columns (0/2/3/4)?
- Featured image sizes?
    add_theme_support('html5', array(
        'search-form',
3) Build `header.php`:
- `wp_head()`, `body_class()`, skip link
- Branding: `the_custom_logo()` fallback to site title
- Navigation: `wp_nav_menu('primary')` with fallback
        'comment-list',
        'gallery',
4) Build `footer.php`:
- Footer widget areas if active
- Footer bottom (copyright, optional menu)
- `wp_footer()` and closing tags
        'style',
        'script',
5) Build `index.php`:
- Standard Loop → include `template-parts/content-archive.php` if available
- Fallback to `content-none` when no posts
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('responsive-embeds');
6) Build `404.php` with simple message + search form.
    // Add post format support (if needed)
    // add_theme_support('post-formats', array('aside', 'gallery', 'video'));
7) Ensure `style.css` header complete (Theme Name, Text Domain, Version).
    // Register navigation menus
    register_nav_menus(array(
8) Test:
- Activate theme; header/footer render
- Visit a non-existent URL → 404 works
- Appearance → Menus/Widgets visible
        'footer'  => __('Footer Menu', 'theme-slug'),
    ));
## Ask
- Sticky header? Transparent on homepage?
- Mobile menu type (offcanvas/dropdown)? Mega menu?
- Footer social platforms? Newsletter?
- Blog layout (grid/list), posts per page, meta to show?
    // Set content width
    $GLOBALS['content_width'] = 1200;
## Success
- `functions.php`, `header.php`, `footer.php`, `index.php`, `404.php` created
- Theme activates; menus/widgets available; no PHP errors
    // Enable editor styles
    add_theme_support('editor-styles');
## Validate
- User confirms templates render and admin sections available
}
add_action('after_setup_theme', 'theme_setup');

/**
 * Register Widget Areas
 */
function theme_widgets_init() {
    
    // Sidebar
    register_sidebar(array(
        'name'          => __('Sidebar', 'theme-slug'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here to appear in your sidebar.', 'theme-slug'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
    
    // Footer widgets
    $footer_widgets_count = 3; // Ask user how many footer columns
    for ($i = 1; $i <= $footer_widgets_count; $i++) {
        register_sidebar(array(
            'name'          => sprintf(__('Footer %d', 'theme-slug'), $i),
            'id'            => 'footer-' . $i,
            'description'   => sprintf(__('Footer column %d', 'theme-slug'), $i),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ));
    }
}
add_action('widgets_init', 'theme_widgets_init');

/**
 * Include required files
 */
require get_template_directory() . '/inc/enqueue-scripts.php';
// Additional includes will be added in later steps:
// require get_template_directory() . '/inc/custom-post-types.php';
// require get_template_directory() . '/inc/taxonomies.php';
// require get_template_directory() . '/inc/template-functions.php';

/**
 * Add custom image sizes
 */
add_image_size('featured-large', 1200, 600, true);
add_image_size('featured-medium', 800, 400, true);
add_image_size('thumbnail-small', 300, 300, true);

/**
 * Custom excerpt length
 */
function theme_excerpt_length($length) {
    return 30; // words
}
add_filter('excerpt_length', 'theme_excerpt_length');

/**
 * Custom excerpt more
 */
function theme_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'theme_excerpt_more');

/**
 * Add body classes
 */
function theme_body_classes($classes) {
    // Add page slug
    if (is_page()) {
        global $post;
        $classes[] = 'page-' . $post->post_name;
    }
    
    // Add if has sidebar
    if (is_active_sidebar('sidebar-1') && !is_page_template('page-templates/full-width.php')) {
        $classes[] = 'has-sidebar';
    } else {
        $classes[] = 'no-sidebar';
    }
    
    return $classes;
}
add_filter('body_class', 'theme_body_classes');

/**
 * Disable Gutenberg for specific post types (optional)
 */
// function theme_disable_gutenberg($use_block_editor, $post_type) {
//     $disabled_post_types = array('page'); // Add post types to disable
//     if (in_array($post_type, $disabled_post_types)) {
//         return false;
//     }
//     return $use_block_editor;
// }
// add_filter('use_block_editor_for_post_type', 'theme_disable_gutenberg', 10, 2);
```

### 3. Ask User About Theme Configuration

```
⚙️ Theme Configuration Questions:

**1. Navigation Menus:**
   How many menu locations do you need?
   □ Primary menu (header)
   □ Footer menu
   □ Mobile menu (separate from primary?)
   □ Other: [specify]

**3. Featured Images:**
   What sizes do you need?
   - Large (homepage): [width] x [height]
   - Medium (archives): [width] x [height]
   - Thumbnail: [width] x [height]

**4. Theme Support:**
   □ Custom logo upload
   □ Custom background
   □ Gutenberg blocks (keep enabled)
   □ Post formats (aside, gallery, video)
   □ WooCommerce (if e-commerce)

**5. Post Settings:**
   - Excerpt length: [number] words
   - "Read more" text: [text]
```

### 4. Create header.php

Convert markup header to WordPress:

```php
<?php
/**
 * Header Template
 * 
 * @package [ThemeName]
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'theme-slug'); ?></a>

    <header id="masthead" class="site-header">
        <div class="container">
            <div class="site-branding">
                <?php
                if (has_custom_logo()) {
                    the_custom_logo();
                } else {
                    ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                        <span class="site-title"><?php bloginfo('name'); ?></span>
                    </a>
                    <?php
                    $description = get_bloginfo('description', 'display');
                    if ($description || is_customize_preview()) {
                        ?>
                        <p class="site-description"><?php echo $description; ?></p>
                        <?php
                    }
                }
                ?>
            </div><!-- .site-branding -->

            <nav id="site-navigation" class="main-navigation">
                <button class="mobile-menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                    <span class="menu-toggle-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                    <span class="screen-reader-text"><?php esc_html_e('Menu', 'theme-slug'); ?></span>
                </button>
                
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'menu_class'     => 'nav-menu',
                    'container'      => 'div',
                    'container_class' => 'menu-wrapper',
                    'fallback_cb'    => false,
                ));
                ?>
            </nav><!-- #site-navigation -->
            
            <?php
            // Add any header elements from markup (search, buttons, etc.)
            // Example: get_search_form();
            ?>
        </div><!-- .container -->
    </header><!-- #masthead -->

    <div id="content" class="site-content">
```

### 5. Create footer.php

Convert markup footer to WordPress:

```php
<?php
/**
 * Footer Template
 * 
 * @package [ThemeName]
 */
?>
    </div><!-- #content .site-content -->

    <footer id="colophon" class="site-footer">
        <?php if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3')) : ?>
        <div class="footer-widgets">
            <div class="container">
                <div class="footer-widgets-grid">
                    <?php
                    $footer_widgets_count = 3; // Match functions.php
                    for ($i = 1; $i <= $footer_widgets_count; $i++) {
                        if (is_active_sidebar('footer-' . $i)) {
                            ?>
                            <div class="footer-widget-column footer-widget-<?php echo $i; ?>">
                                <?php dynamic_sidebar('footer-' . $i); ?>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div><!-- .footer-widgets-grid -->
            </div><!-- .container -->
        </div><!-- .footer-widgets -->
        <?php endif; ?>

        <div class="footer-bottom">
            <div class="container">
                <div class="footer-bottom-content">
                    <div class="site-info">
                        <?php
                        /* translators: 1: Current year, 2: Site name */
                        printf(
                            esc_html__('Copyright &copy; %1$s %2$s. All rights reserved.', 'theme-slug'),
                            date('Y'),
                            get_bloginfo('name')
                        );
                        ?>
                    </div><!-- .site-info -->
                    
                    <?php
                    // Footer menu
                    if (has_nav_menu('footer')) {
                        wp_nav_menu(array(
                            'theme_location' => 'footer',
                            'menu_id'        => 'footer-menu',
                            'menu_class'     => 'footer-menu',
                            'container'      => 'nav',
                            'container_class' => 'footer-navigation',
                            'depth'          => 1,
                            'fallback_cb'    => false,
                        ));
                    }
                    ?>
                    
                    <?php
                    // Social links (if in markup)
                    // Add social menu or custom function
                    ?>
                </div><!-- .footer-bottom-content -->
            </div><!-- .container -->
        </div><!-- .footer-bottom -->
    </footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
```

### 6. Create index.php

Fallback template for blog posts:

```php
<?php
/**
 * Main Index Template
 * Fallback template for displaying posts
 * 
 * @package [ThemeName]
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <?php if (have_posts()) : ?>

            <header class="page-header">
                <?php
                if (is_home() && !is_front_page()) :
                    ?>
                    <h1 class="page-title"><?php single_post_title(); ?></h1>
                    <?php
                else :
                    ?>
                    <h1 class="page-title"><?php esc_html_e('Blog', 'theme-slug'); ?></h1>
                    <?php
                endif;
                ?>
            </header><!-- .page-header -->

            <div class="posts-grid">
                <?php
                while (have_posts()) :
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('featured-medium'); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <header class="entry-header">
                            <?php the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h2>'); ?>
                            
                            <div class="entry-meta">
                                <span class="posted-on">
                                    <?php echo get_the_date(); ?>
                                </span>
                                <span class="byline">
                                    <?php echo esc_html__('by', 'theme-slug') . ' ' . get_the_author(); ?>
                                </span>
                            </div>
                        </header>

                        <div class="entry-summary">
                            <?php the_excerpt(); ?>
                        </div>

                        <div class="entry-footer">
                            <a href="<?php the_permalink(); ?>" class="read-more">
                                <?php esc_html_e('Read More', 'theme-slug'); ?>
                            </a>
                        </div>
                    </article>
                    <?php
                endwhile;
                ?>
            </div><!-- .posts-grid -->

            <?php
            // Pagination
            the_posts_pagination(array(
                'mid_size'  => 2,
                'prev_text' => __('&laquo; Previous', 'theme-slug'),
                'next_text' => __('Next &raquo;', 'theme-slug'),
            ));
            ?>

        <?php else : ?>

            <div class="no-results">
                <h1><?php esc_html_e('Nothing Found', 'theme-slug'); ?></h1>
                <p><?php esc_html_e('It seems we can\'t find what you\'re looking for.', 'theme-slug'); ?></p>
                <?php get_search_form(); ?>
            </div>

        <?php endif; ?>
    </div><!-- .container -->
</main><!-- #primary -->

<?php
get_footer();
```

### 7. Create 404.php

Error page template:

```php
<?php
/**
 * 404 Error Page Template
 * 
 * @package [ThemeName]
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <div class="error-404">
            <header class="page-header">
                <h1 class="page-title"><?php esc_html_e('404', 'theme-slug'); ?></h1>
                <p class="page-subtitle"><?php esc_html_e('Oops! Page not found', 'theme-slug'); ?></p>
            </header>

            <div class="page-content">
                <p><?php esc_html_e('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'theme-slug'); ?></p>
                
                <div class="error-actions">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="button">
                        <?php esc_html_e('Go to Homepage', 'theme-slug'); ?>
                    </a>
                    
                    <?php get_search_form(); ?>
                </div>
            </div>
        </div><!-- .error-404 -->
    </div><!-- .container -->
</main><!-- #primary -->

<?php
get_footer();
```

### 8. Update style.css

Ensure the theme header in style.css is complete (already started in 4a):

```css
/*!
Theme Name: [Theme Name]
Theme URI: https://yoursite.com
Author: [Author Name]
Author URI: https://yoursite.com
Description: Custom WordPress theme converted from Figma design
Version: 1.0.0
Requires at least: 6.0
Tested up to: 6.4
Requires PHP: 7.4
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: [theme-slug]
Tags: custom-background, custom-logo, custom-menu, featured-images, responsive-design
*/
```

### 9. Test Initial Setup

```
🧪 Testing Initial Templates:

Please perform these tests:

1. **Activate Theme**
   - Go to WordPress Admin → Appearance → Themes
   - Activate [Theme Name]
   - Did it activate without errors?

2. **Check Header**
   - Visit site homepage
   - Is header displaying?
   - Is logo/site title showing?
   - Is navigation menu visible?

3. **Check Footer**
   - Scroll to bottom
   - Is footer displaying?
   - Is copyright showing?

4. **Test 404 Page**
   - Visit: yoursite.com/nonexistent-page
   - Does 404 page display correctly?

5. **Check WordPress Admin**
   - Appearance → Menus (can create menus?)
   - Appearance → Customize (customizer works?)
   - Appearance → Widgets (sidebar/footer widgets available?)

Report any issues found.
```

## Questions to Ask User

1. **Header Customization:**
   - "Should the header be sticky on scroll?"
   - "Do you want a transparent header on homepage?"

2. **Navigation:**
   - "How should the mobile menu look (slide-in, dropdown)?"
   - "Do you need mega menu support? - No"
   - "Should current page be highlighted in menu?"

3. **Footer:**
   - "Do you need social media icons? Which platforms?"
   - "Should footer have newsletter signup?"

4. **Blog Display:**
   - "How should blog posts be displayed (grid, list)?"
   - "How many posts per page?"
   - "What post meta should show (date, author, categories)?"

5. **404 Page:**
   - "Is the 404 page message appropriate?"
   - "Should it show recent posts or popular pages?"

## Answers on questions
1. Where should App Store and Google Play URLs be stored? - Custom options page
2. Where should social media links be stored? - Custom options page
3. Would you like to proceed with generating these template files for your theme, or do you want to answer the configuration questions (menus, widgets, image sizes, etc.) first? - First aswer the configuration questions

## Success Criteria

Before proceeding to Step 4c:

- [ ] functions.php created with theme setup
- [ ] header.php created and displays correctly
- [ ] footer.php created and displays correctly
- [ ] index.php created (blog fallback)
- [ ] 404.php created and works
- [ ] style.css has proper WordPress header
- [ ] Theme activates without errors
- [ ] Menus can be created in WordPress admin
- [ ] Widget areas appear in admin
- [ ] Custom logo can be uploaded
- [ ] No PHP errors in debug.log
- [ ] User validated all templates display correctly

## Validation Checklist

Show user:

```
✅ Step 4b Complete: Initial Templates Created

**Files Created:**
✓ functions.php (theme setup and configuration)
✓ header.php (site header with navigation)
✓ footer.php (site footer with widgets)
✓ index.php (blog posts fallback)
✓ 404.php (error page)
✓ style.css (theme header updated)

**Features Configured:**
✓ Navigation menus: [count] locations
✓ Widget areas: Sidebar + [count] footer columns
✓ Theme support: Logo, thumbnails, HTML5, etc.
✓ Custom image sizes: [list sizes]

**Next: Configure Menus & Widgets**
Before proceeding to Step 4c, please:
1. Go to Appearance → Menus
2. Create a menu with your pages
3. Assign it to "Primary Menu" location
4. (Optional) Add widgets to sidebar/footer

Ready to proceed to Step 4c (Page Templates)?
□ Yes, templates working correctly
□ No, I found these issues: [describe]
```

---

**Next Step:** Once initial templates are validated, proceed to **Step 4c: Page Templates and Archive Files**


**JavaScript Files:**

```php
// Main theme scripts:
dev/html/[themeName]/assets/js/main.js 
  → theme/assets/js/main.js

// Component scripts:
dev/html/[themeName]/assets/js/components/ 
  → theme/assets/js/components/

// Libraries from reference:
knowledge-base/theme/assets/libs/[library]
  → theme/assets/libs/[library]
```