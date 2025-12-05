## STEP 6: HTML to WordPress Conversion

Use general AI instructions `docs/AI-INSTRUCTIONS.md`

Take theme name from task: `[task.title slugified]`
Theme Location:
   - websites/[site-name]/wp-content/themes/[theme-name]/

### Objective
Convert extracted HTML/CSS markup up.
Work with theme -  site name from task: `[task.title slugified]`
Important!: 
1. Copy images from mark up and save in Media. Use it in proproate posts/pages etc.
2. Always create content in admin part. Every icon, link, text, title must be editable from admin part. Use wp-custom-fields for additional fields.
3. Never create additional documentation.


### Instructions for AI

1. **Locate HTML Source Files**
   - Read site name from `current-task.json` (slugified title)
   - HTML files are in: `dev/html/[site-name]/`
   - These files are the source for WordPress conversion
   - Note: This directory is in .gitignore

2. **Analyze Site Structure from Task**
   - Read `current-task.json` → `siteStructure`
   - Extract:
     - **Pages:** Names, slugs, templates, hierarchy
     - **Custom Post Types:** If defined in `siteStructure.postTypes[]`
     - **Taxonomies:** If defined in `siteStructure.taxonomies[]`
   
   **Show Analysis:**
   ```
   🏗️ WordPress Structure Analysis:
   
   **HTML Source:** dev/html/[site-name]/
   
   **Pages:**
   - [slug] → [template] (Parent: [parent or "none"])
   
   **Templates Needed:**
   - front-page.php (Homepage)
   - page.php (Default page)
   - [List custom page templates]
   
   **Custom Post Types:** [count]
   - [List each with archive status]
   
   **Taxonomies:** [count]
   - [List each with associated post types]
   ```

3. **Use Knowledge Base Prompts**
   - **READ DETAILED PROMPTS**: `dev/prompts/step-4-html-to-wp/`
   - Each sub-step (4a-4d) has a detailed prompt file with complete instructions
   - Follow the prompts exactly as written in those files

### Sub-step 4a: Assets & Libraries Migration

**Reference Prompt:** `dev/prompts/step-6-html-to-wp/4a-assets-and-libs.md`

**Goal:** Move CSS/JS/images/fonts from markup into theme and enqueue properly. Include only libraries actually used.

**Inventory & Analysis:**

1. **Scan markup assets:**
   - List CSS files from `dev/html/[site-name]/assets/css/`
   - List JS files from `dev/html/[site-name]/assets/js/`
   - Count images by type (JPG, PNG, SVG)
   - Identify fonts and formats

2. **Detect external libraries** from `<link>` and `<script>` tags in HTML

3. **Ask user:**
   ```
   🔧 Library Configuration:
   
   Found in markup:
   - CSS: [count] files
   - JS: [count] files  
   - External libs: [list detected]
   
   Available in reference theme:
   ✅ jQuery + plugins
   ✅ AOS (Animate On Scroll)
   ✅ Lazyload, Lightbox
   ✅ HC Offcanvas Navigation
   ✅ SweetAlert, Datepicker
   
   Questions:
   1. Which reference libraries do you need?
   2. Any additional libraries in markup?
   3. CDN or local files for common libs?
   4. Minify/concatenate assets?
   ```

**Theme Directory Structure:**
```
awesome/
├── assets/
│   ├── css/
│   │   ├── variables.css     # CSS custom properties
│   │   ├── base.css          # Reset/normalize
│   │   ├── layout.css        # Grid and layout
│   │   └── responsive.css    # Mobile responsive
│   ├── js/
│   │   └── main.js           # Main theme JavaScript
│   ├── libs/                 # Third-party libraries
│   ├── images/               # Theme images
│   ├── icons/                # Icon assets
│   └── logos/                # Logo assets
```

**Implementation:**

- Create `inc/enqueue-scripts.php` with proper WordPress hooks
- Organize CSS by purpose (variables → base → layout → responsive)
- Enqueue in dependency order
- Use theme version for cache-busting
- Use WordPress-bundled jQuery

### Sub-step 4b: Create Initial Template Files

**Reference Prompt:** `dev/prompts/step-6-html-to-wp/4b-initial-templates.md`

**Goal:** Create core WordPress templates with proper hooks and structure.

**Templates to Create:**

1. **`functions.php`** - Theme core functions
   - Theme support registration (title-tag, thumbnails, HTML5, custom-logo)
   - Navigation menus (primary, footer)
   - Widget areas (sidebar + footer columns)
   - Include required files

2. **`header.php`** - Site header
   - `wp_head()` hook
   - `body_class()` and `wp_body_open()` hooks
   - Custom logo or site title
   - Primary navigation menu
   - Mobile hamburger button

3. **`footer.php`** - Site footer
   - Footer widget areas
   - Footer navigation menu
   - Copyright info
   - `wp_footer()` hook

4. **`index.php`** - Blog/archive fallback
   - Post loop with pagination
   - Featured images
   - Post meta (date, author)
   - Read more links

5. **`404.php`** - Error page
   - Friendly 404 message
   - Homepage link
   - Search form

**Critical Configuration:**

- Widget areas: 1 sidebar + 3 footer columns
- Navigation menus: Primary (header) + Footer
- Theme support:
  - Custom logo
  - Post thumbnails
  - HTML5 markup
  - Responsive embeds
  - Title tag

**Ask User:**
- How many footer widget columns? (0/2/3/4)
- Sticky header on scroll?
- Custom navigation locations?

### Sub-step 4c: Create Page and Archive Templates

**Reference Prompt:** `dev/prompts/step-6-html-to-wp/4c-page-templates.md`

**Goal:** Implement core page templates (`front-page.php`, `page.php`, `single.php`, `archive.php`), CPT & taxonomy patterns, and template parts with helper functions.

**Template Hierarchy to Implement:**

| Request Type | Template Sequence |
|---|---|
| Front page | front-page.php → home.php → index.php |
| Regular page | page-{slug}.php → page.php → index.php |
| Single post | single-{cpt}.php → single.php → index.php |
| Archive | archive-{cpt}.php → archive.php → index.php |
| Taxonomies | taxonomy-{name}.php → archive.php → index.php |

**Core Templates (Required):**

1. **`front-page.php`** - Homepage with multiple sections
2. **`page.php`** - Default page template
3. **`single.php`** - Default single post template
4. **`archive.php`** - Generic archive fallback

**CPT-Specific Templates:**

1. **`archive-{post-type}.php`** - CPT archive listing
2. **`single-{post-type}.php`** - Individual CPT post view
3. **`template-parts/content-archive.php`** - Reusable archive item card
4. **`template-parts/content-none.php`** - No results message

**Custom Post Types & Taxonomies:**

- **CPT Registration:** `inc/custom-post-types.php`
  - Register each CPT with supports array (title, editor, thumbnail, excerpt)
  - Enable has_archive for CPT archives
  - Set show_in_rest for Gutenberg support

- **Taxonomy Registration:** `inc/taxonomies.php`
  - Register hierarchical/non-hierarchical taxonomies
  - Associate with CPT(s)
  - Enable show_admin_column for easy filtering

**Helper Functions:**

Create `inc/template-functions.php` with:
- `theme_breadcrumbs()` - Breadcrumb navigation
- `theme_pagination()` - Custom pagination
- `theme_reading_time()` - Reading time estimate
- Menu fallback function for navigation

**Additional Files:**

- **`sidebar.php`** - Widget area for sidebar
- **`inc/template-functions.php`** - All helper functions
- **`inc/custom-post-types.php`** - CPT registration
- **`inc/taxonomies.php`** - Taxonomy registration

**Ask User:**
- What CPTs do you need? (from task definition)
- How many posts per page on archives?
- Should pagination use numbered or prev/next?
- Need custom page templates (page-{slug}.php)?

### Sub-step 4d: Setup Forms & Contact Configuration

**Reference Prompt:** `dev/prompts/step-6-html-to-wp/4d-forms-setup.md`

**Goal:** Configure contact and other forms with validation, email delivery, and spam protection.

**Form Implementation Steps:**

1. **Identify Forms from Markup & Task**
   - Contact page forms (fields, labels, requirements)
   - Newsletter subscription forms
   - Other specialized forms
   - Required validation rules and spam protection

2. **Create Form Handler** (`inc/form-handler.php`)
   - AJAX handler with nonce verification
   - Input sanitization & validation
   - Email sending via wp_mail()
   - Optional database storage for submissions
   - Honeypot spam prevention

3. **Form HTML & JavaScript**
   - Form markup in template or template part
   - Client-side JavaScript for AJAX submission
   - Submit button state management (disable on submit)
   - Success/error message display
   - Form reset after successful submission

4. **Email Configuration**
   - SMTP settings (host, port, credentials)
   - Email headers (from, reply-to, content-type)
   - Email template/message formatting
   - Recipient configuration (admin or custom)

5. **Spam Protection**
   - **Honeypot Field:** Hidden input that should be empty
   - **Nonce Verification:** Ensure form comes from site
   - **reCAPTCHA (Optional):** Add Google reCAPTCHA v3 for extra protection

6. **JavaScript Enqueuing**
   - Enqueue forms.js in `inc/enqueue-scripts.php`
   - Pass ajaxurl via wp_localize_script()
   - Handle AJAX responses with success/error handlers

**Security Requirements:**

- All inputs sanitized with appropriate functions (sanitize_text_field, sanitize_email, etc.)
- All outputs escaped (esc_html, esc_url, etc.)
- Nonces verified on submission
- SMTP credentials in .env file (never hardcoded)
- .gitignore includes .env file

**Ask User:**
- What forms need to be implemented?
- What email address receives submissions?
- Honeypot only or reCAPTCHA too?
- Should submissions be saved to database?

### Sub-step 4e: Final Integration & QA

**Reference Prompt:** `dev/prompts/step-6-html-to-wp/4e-final-integration.md`

**Goal:** Complete CSS/JS integration, validate responsiveness, optimize SEO/performance/accessibility, and perform final QA.

**1. CSS & JavaScript Audit**

- Compare markup assets with theme files (identify gaps)
- Implement missing component interactions:
  - Smooth scroll navigation
  - Sticky header
  - Modals/popups
  - Accordions
  - Tabs
  - Back-to-top button
- Page-specific styles integration
- Animation/transition completeness
- Print styles coverage

**2. Responsive Testing Matrix**

Test at breakpoints: 320px, 375px, 390px, 768px, 1024px, 1440px+

**CRITICAL - Mobile Menu Checklist:**
- [ ] Hamburger icon visible on mobile (≤768px)
- [ ] Menu opens on click
- [ ] Menu covers full screen
- [ ] Close button works
- [ ] Clicking links closes menu
- [ ] Escape key closes menu
- [ ] Body scroll locked when menu open
- [ ] Menu z-index > header (9999+)
- [ ] Smooth animation (transform, not display)
- [ ] No horizontal scroll on any breakpoint

**Desktop & Mobile Browser Testing:** Chrome, Firefox, Safari, Edge

**3. SEO, Performance & Accessibility**

**SEO:**
- title-tag support enabled
- OG/Twitter meta tags (plugin or theme)
- Schema.org markup (optional)

**Performance:**
- Image optimization
- Browser caching enabled
- Defer non-critical JS
- Preload critical fonts

**Accessibility:**
- Skip-to-content link
- Focus visible on all interactive elements
- ARIA labels where needed
- Alt text on all images
- Heading hierarchy (H1→H2→H3)
- 4.5:1 text contrast minimum

**Security:**
- All inputs sanitized
- All outputs escaped
- Nonces on forms
- No sensitive data in code

**4. Comprehensive Frontend QA**

- Homepage, all pages, navigation (desktop + mobile)
- Search, 404, archives, CPT archives
- Sidebar/footer widgets rendering
- Forms submit; emails deliver
- No broken links; images load
- Page load <3s
- No console/PHP errors

**5. Admin Panel Validation**

- Theme activates without errors
- Customizer functional (logo, colors, menus)
- Menus, widgets, pages, posts editable
- CPTs visible and editable (if applicable)
- Media library functional

**6. Documentation & Handoff**

- README.md in theme root with:
  - Feature summary
  - Installation instructions
  - Required/recommended plugins list
  - Customization guide (colors, fonts, etc.)
  - Version & compatibility info

**Completion Checklist:**
- [ ] CSS/JS fully integrated
- [ ] Responsive verified all breakpoints
- [ ] Mobile menu working perfectly
- [ ] Cross-browser tested
- [ ] No errors (console, PHP, validation)
- [ ] Forms functional; emails delivered
- [ ] SEO plugin configured
- [ ] Performance optimized
- [ ] Accessibility compliant
- [ ] README.md created
- [ ] Task status updated to "completed"

**Status:** Ready for production deployment.

## Summary

HTML to WordPress conversion documentation has been refactored to align with detailed prompt specifications:

- ✅ **Sub-step 4a:** Assets and Libraries - CSS/JS/images organized and enqueued
- ✅ **Sub-step 4b:** Initial Templates - Core templates with WordPress hooks  
- ✅ **Sub-step 4c:** Page Templates - Full template hierarchy and CPTs
- ✅ **Sub-step 4d:** Forms & Contact - Form handlers with AJAX and security
- ✅ **Sub-step 4e:** Final Integration - QA checklist and deployment validation

### Reference Documents
- **Detailed Prompts**: `dev/prompts/step-6-html-to-wp/`
  - 4a: Assets and Libraries
  - 4b: Initial Templates
  - 4c: Page Templates
  - 4d: Forms Setup
  - 4e: Final Integration
- **Theme Reference**: `knowledge-base/themes/awesome_group/`
- **Task Definition**: `tasks/current-task.json`


