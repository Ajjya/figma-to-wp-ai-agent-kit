# Step 4b: Initial Templates 

## Overview
This step creates the core WordPress theme template files that form the foundation of your theme. You will convert HTML markup into functional WordPress templates with proper dynamic content integration.

## AI Instructions
- Follow general AI instructions from `docs/AI-INSTRUCTIONS.md`
- Follow step-specific instructions from `docs/STEP-6-Html-to-WP.md`
- Do not invent anything - ask the user if you have any doubts before proceeding
- Do not create additional documentation (.md) files

## Critical Requirements
1. **Media Management**: Copy all images from markup to WordPress Media Library. Reference them in appropriate posts/pages
2. **Admin Editability**: Every icon, link, text, and title must be editable from WordPress admin. Use wp-custom-fields plugin for additional custom fields
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

## Goal
Create the following core WordPress template files in `websites/[themeName]/wp-content/themes/[themeName]/`:
- `functions.php` - Theme setup, support, and configurations
- `header.php` - Site header template
- `footer.php` - Site footer template  
- `index.php` - Main loop template (fallback)
- `404.php` - Not found page template
- `inc/custom-posts.php` - Custom post type registrations
- `inc/metaboxes.php` - Custom meta box definitions
- `inc/options.php` - Theme options page setup
- `inc/posts-deleting.php` - Post deletion handlers
- `inc/ajax/options.php` - AJAX handlers for options

## Reference Files to Read

### Knowledge Base Theme Reference
Read these files from `knowledge-base/theme/` to understand structure and patterns:
- `functions.php` - Theme initialization and feature support
- `header.php` - Header structure and navigation
- `footer.php` - Footer structure and widgets
- `index.php` - Main loop implementation
- `404.php` - Error page template
- `inc/custom-posts.php` - Custom post type registration patterns
- `inc/metaboxes.php` - Meta box registration patterns
- `inc/options.php` - Theme options page setup
- `inc/posts-deleting.php` - Post deletion handlers
- `inc/ajax/options.php` - AJAX handlers
- `theme-functions/helpers.php` - Helper function examples
- `theme-functions/wp-helpers.php` - WordPress helper examples

## Context to Gather

### From HTML Markup
Analyze `dev/html/[themeName]/` directory:
- `homepage.html` - Extract header/footer structure, navigation, layout patterns
- `*.html` - Other page templates for additional components
- `*.css` - Styling references for component structure
- `assets/` - Images and resources to import

### From Task Configuration  
## Implementation Steps

### Step 1: Analyze Markup Components

Before creating templates, analyze the HTML markup structure:

**Read and Document:**
- `dev/html/[themeName]/homepage.html` (and other HTML files)

**Identify in the markup:**
- **Header Section** (`<header>` tag):
  - Logo location and HTML structure
  - Navigation menu structure (items, dropdown levels, menu classes)
  - Special header elements (search bar, CTA buttons, language switcher, etc.)
  - Mobile menu trigger button and icon
  - Any header utility elements (phone, email, social icons)
  
- **Footer Section** (`<footer>` tag):
  - Footer menu structures (multiple menus, columns)
  - Social media links and icons
  - Newsletter signup form (if present)
  - Copyright text and legal links
  - Widget areas or footer columns
  - Contact information sections

- **Layout Structure**:
  - Container/wrapper classes
  - Sidebar placement (if any)
  - Widget-ready areas 1. Analyze Markup Components


### Step 2: Create functions.php

**Reference:** `knowledge-base/theme/functions.php`

**Requirements:**
1. Copy core functionality from reference theme
2. Register theme support features:
   - `add_theme_support('title-tag')`
   - `add_theme_support('post-thumbnails')`
   - `add_theme_support('html5', array(...))`
   - `add_theme_support('customize-selective-refresh-widgets')`
   - `add_theme_support('responsive-embeds')`
   - Custom logo support
   - Automatic feed links

### Step 3: Create Theme Options, Header, and Footer

**References:**
- `knowledge-base/theme/header.php`
- `knowledge-base/theme/footer.php`
- `knowledge-base/theme/inc/options.php`
- `knowledge-base/theme/inc/ajax/options.php`

#### 3a. Create inc/options.php
Create theme options page for global settings:
- **Logo**: Upload and manage site logo
- **Contact Info**: Email, phone, address
- **Social Media**: Links to social profiles (Facebook, Twitter, Instagram, LinkedIn, etc.)
- **reCAPTCHA**: Public and secret keys (if forms present)
- **App Links**: App Store and Google Play links (if needed)
- **Newsletter**: Newsletter signup settings (if needed)
- **Footer Text**: Custom copyright text
- **Any other global settings** identified in markup

Use WordPress Settings API. Reference the pattern in `knowledge-base/theme/inc/options.php`.

#### 3b. Create inc/ajax/options.php
Set up AJAX handlers for options that need dynamic updates (reference: `knowledge-base/theme/inc/ajax/options.php`)

#### 3c. Create header.php
Convert HTML `<header>` section to WordPress template:
- Use `wp_head()` hook before `</head>`
- Use `body_class()` on `<body>` tag
- Output logo from theme options or custom logo
- Use `wp_nav_menu()` for navigation (map to menu locations)
- Include mobile menu trigger if needed
- Preserve all HTML classes and structure from markup
- Make header elements dynamic (pull from theme options/menus)

#### 3d. Create footer.php  
Convert HTML `<footer>` section to WordPress template:
- Use `wp_nav_menu()` for footer menus
- Pull social links from theme options
- Pull copyright text from theme options  
- Include newsletter form if present (or widget area)
- Use `wp_footer()` hook before `</body>`
- Preserve all HTML classes and structure from markup

#### 3e. Upload Initial Content via WP-CLI
After creating options, use WP-CLI to:
- Upload logo to Media Library
- Set theme options with default/extracted values
- Create and assign menus to locations
- Import any default content needed


### Step 4: Create index.php

**Reference:** `knowledge-base/theme/index.php`

Create the main blog index template:

**Structure:**
1. Include `header.php` via `get_header()`
2. Main content area with WordPress loop:
   ```php
   if ( have_posts() ) {
       while ( have_posts() ) {


### Step 5: Create inc/metaboxes.php

**Reference:** `knowledge-base/theme/inc/metaboxes.php`

Register custom meta boxes for:
- Page-specific options
- Post additional fields
- Custom fields using wp-custom-fields plugin

Follow the pattern in reference theme for meta box registration.

### Step 6: Copy Helper Functions

Copy needed functionality from reference theme to maintain consistency:

**Files to create:**
- `inc/posts-deleting.php` - Copy from `knowledge-base/theme/inc/posts-deleting.php`
- `theme-functions/helpers.php` - Copy from `knowledge-base/theme/theme-functions/helpers.php`
- `theme-functions/wp-helpers.php` - Copy from `knowledge-base/theme/theme-functions/wp-helpers.php`

These provide utility functions that other templates will use.

### Step 7: Register Custom Post Types

**Reference:** `knowledge-base/theme/inc/custom-posts.php`


## Success Criteria

All the following files must be created in `websites/[themeName]/wp-content/themes/[themeName]/`:

**Core Templates:**
- ✅ `functions.php` - Theme setup complete with all registrations
- ✅ `header.php` - Dynamic header with menus and options
- ✅ `footer.php` - Dynamic footer with menus and options  
- ✅ `index.php` - Working blog loop
- ✅ `404.php` - Error page template

**Include Files:**
- ✅ `inc/custom-posts.php` - Custom post types registered
- ✅ `inc/metaboxes.php` - Meta boxes defined
- ✅ `inc/options.php` - Theme options page created
- ✅ `inc/posts-deleting.php` - Post handlers in place
- ✅ `inc/ajax/options.php` - AJAX handlers ready

**Helper Functions:**
- ✅ `theme-functions/helpers.php` - General helpers available
- ✅ `theme-functions/wp-helpers.php` - WordPress helpers available

**Functionality Tests:**
- ✅ Theme activates without PHP errors
- ✅ Menu locations are registered and available
- ✅ Widget areas are functional
- ✅ Theme options page accessible in admin
- ✅ Header and footer render correctly
- ✅ 404 page displays properly

## User Validation

After completing implementation, ask user to validate:

```
🧪 Initial Templates Testing

Please test the following in your browser and WordPress admin:

**1. Theme Activation**
   - WordPress Admin → Appearance → Themes
   - Activate "[Theme Name]"
   - ✓ No PHP errors displayed?

**2. Frontend - Homepage**
   - Visit: http://yoursite.local (or your site URL)
   - ✓ Header displays correctly?
   - ✓ Logo/site title visible?
   - ✓ Navigation menu visible and styled?
   - ✓ Footer displays correctly?
   - ✓ Social links/copyright showing?

**3. Frontend - 404 Page**
   - Visit: http://yoursite.local/nonexistent-page
   - ✓ 404 page displays with message and search?

**4. Frontend - Blog**
   - Visit: http://yoursite.local/blog (or posts page)
   - ✓ Post loop displays correctly?
   - ✓ Post titles, content, and images showing?

**5. WordPress Admin**
   - Appearance → Menus
     - ✓ Can create and assign menus to locations?
   - Theme Options (custom menu or settings page)
     - ✓ Options page accessible?
     - ✓ Can upload logo and save settings?
   - Appearance → Widgets
     - ✓ Widget areas available?
   - Appearance → Customize
     - ✓ Customizer loads without errors?

**Report any issues or confirm all tests pass.**
```

## Next Step
Once all tests pass, proceed to **Step 4c: Page Templates** to create specific page templates for different page types.

**Ask User (Header/Footer Configuration):**
```
🎨 Header & Footer Configuration:

Please answer these questions about your header/footer:

1. **Mobile Menu**: 
   - Is mobile menu different from desktop menu?
   - Type: Offcanvas/Slide-out, Dropdown, or Fullscreen overlay?

2. **Header Behavior**:
   - Sticky header on scroll?
   - Transparent header on homepage?
   - Different header on internal pages?

3. **Mega Menu**: 
   - Do any menu items need mega menu dropdowns?

4. **Newsletter**:
   - Do you need a newsletter signup in footer?
   - Which service (MailChimp, etc.)?

5. **Footer Menus**:
   - How many footer menu locations needed?
```
6. **Include Files**: 
   - Include all files from `inc/` directory
   - Include all files from `theme-functions/` directory

**Ask User (if not in task config):**
```
📸 Featured Image Sizes:

What image sizes do you need for this theme?

1. Large (homepage/featured): [width] x [height] px
2. Medium (archive/grid): [width] x [height] px  
3. Thumbnail (small): [width] x [height] px
4. Any other custom sizes?

If unsure, I can use standard sizes from the reference theme.
```

**Next Step:** Once initial templates are validated, proceed to **Step 4c: Page Templates and Archive Files**

