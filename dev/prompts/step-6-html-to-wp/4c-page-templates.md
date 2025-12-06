# Step 4c: Page & Archive Templates

## Overview
Implement core page templates (`front-page.php`, `page.php`, `single.php`, `archive.php`), example CPT + taxonomy, supporting template parts, and helper functions. Remove duplication and keep all content editable via WordPress (use existing `wp-custom-fields` plugin – NOT ACF).

## AI Instructions
- Follow general AI instructions from `docs/AI-INSTRUCTIONS.md`
- Follow step-specific instructions from `docs/STEP-6-Html-to-WP.md`
- Do not invent anything - ask the user if you have any doubts before proceeding
- Do not create additional documentation (.md) files

## Critical Requirements
1. **Media Management**: Copy all images from markup to WordPress Media Library. Reference them in appropriate posts/pages
2. **Admin Editability**: Every icon, link, text, and title must be editable from WordPress admin. Use wp-custom-fields plugin for additional custom fields. Use metaboxes for additional fields if necessary.
3. **Reference Theme**: Always use `knowledge-base/theme/` as your reference for structure and functionality

## Extract Task Data
From `tasks/current-task.json`, extract:
- `themeName` - The name of the theme you're building
- `status` - Current workflow status (must be "wp-initiated")
- `title` - Project title
- `menus` - Menu locations needed
- `widgets` - Widget areas needed
- `customPosts` - Custom post types to register
- `categories` - Taxonomy structure

## Prerequisites
- Navigation (Step 4b) completed and menus assigned.
- Task file `/tasks/current-task.json` defines pages, post types, taxonomies.
- Figma markup/HTML exported (see `dev/html/[themeName]/`).

## References
- Custom Fields Plugin: `/knowledge-base/plugins/wp-custom-fields/`
- Theme Reference (patterns & structure): `/knowledge-base/theme/`
- Task Definition: `/tasks/current-task.json`

## Template Hierarchy (Quick Reference)
```
front-page.php → home.php → index.php
page-{slug}.php → page.php → index.php
single-{post-type}.php → single.php → index.php
archive-{post-type}.php → archive.php → index.php
category.php / tag.php → archive.php → index.php
search.php → index.php
```

## 1. Determine Required Templates
From `/tasks/current-task.json` collect:
- Pages → need `front-page.php` (if homepage), and default `page.php`. Add any specialized `page-{slug}.php` if design differs significantly.
- Post types → create `single-{cpt}.php` & `archive-{cpt}.php` where required.
- Taxonomies → usually rely on `archive.php` unless custom layout needed (then `taxonomy-{taxonomy}.php`).

Checklist Extraction (automate or manual):
- List pages with slug/title/parent.
- List unique template needs.
- List CPTs + hasArchive flag.
- List taxonomies with hierarchical flag.

## 2. Core Templates

### front-page.php (Homepage Skeleton)
```php
<?php
/** Front Page Template */
get_header();
?>
<main id="primary" class="site-main front-page">
<?php while (have_posts()) : the_post(); ?>
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title"><?php the_title(); ?></h1>
                <?php if (has_excerpt()) : ?><p class="hero-subtitle"><?php the_excerpt(); ?></p><?php endif; ?>
                <div class="hero-actions"><!-- Insert CTA buttons from markup --></div>
            </div>
            <?php if (has_post_thumbnail()) : ?><div class="hero-image"><?php the_post_thumbnail('featured-large'); ?></div><?php endif; ?>
        </div>
    </section>
    <div class="page-content"><?php the_content(); ?></div>
    <!-- Insert additional homepage sections as partials or inline blocks -->
<?php endwhile; ?>
</main>
<?php get_footer(); ?>
```

### page.php (Default Page)
```php
<?php get_header(); ?>
<main id="primary" class="site-main">
<?php while (have_posts()) : the_post(); ?>
    <header class="page-header container">
        <h1 class="page-title"><?php the_title(); ?></h1>
        <?php if (has_excerpt()) : ?><div class="page-excerpt"><?php the_excerpt(); ?></div><?php endif; ?>
    </header>
    <div class="page-content-wrapper container <?php echo is_active_sidebar('sidebar-1') ? 'has-sidebar' : 'full-width'; ?>">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php if (has_post_thumbnail()) : ?><div class="post-thumbnail"><?php the_post_thumbnail('featured-large'); ?></div><?php endif; ?>
            <div class="entry-content"><?php the_content(); wp_link_pages(['before'=>'<div class="page-links">'.esc_html__('Pages:','theme-slug'),'after'=>'</div>']); ?></div>
        </article>
        <?php if ((comments_open() || get_comments_number())) comments_template(); ?>
        <?php if (is_active_sidebar('sidebar-1')) get_sidebar(); ?>
    </div>
<?php endwhile; ?>
</main>
<?php get_footer(); ?>
```

### single.php (Default Post)
```php
<?php get_header(); ?>
<main id="primary" class="site-main">
<?php while (have_posts()) : the_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <div class="container">
    <header class="entry-header">
      <?php the_title('<h1 class="entry-title">','</h1>'); ?>
      <div class="entry-meta">
        <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
        <span class="byline"><?php printf(esc_html__('by %s','theme-slug'),'<span class="author">'.get_the_author().'</span>'); ?></span>
        <?php $cats=get_the_category(); if($cats){echo '<span class="categories">'.get_the_category_list(', ').'</span>'; } ?>
      </div>
    </header>
    <?php if (has_post_thumbnail()) : ?><div class="post-thumbnail"><?php the_post_thumbnail('featured-large'); ?></div><?php endif; ?>
    <div class="entry-content"><?php the_content(); wp_link_pages(['before'=>'<div class="page-links">'.esc_html__('Pages:','theme-slug'),'after'=>'</div>']); ?></div>
    <footer class="entry-footer"><?php the_tags('<div class="tags-list"><span>'.esc_html__('Tags:','theme-slug').'</span> ',', ','</div>'); ?></footer>
  </div>
</article>
<?php the_post_navigation(['prev_text'=>'<span>'.esc_html__('Previous:','theme-slug').'</span> %title','next_text'=>'<span>'.esc_html__('Next:','theme-slug').'</span> %title']); ?>
<?php if (comments_open() || get_comments_number()) comments_template(); ?>
<?php endwhile; ?>
</main>
<?php get_footer(); ?>
```

### archive.php (Generic Archive)
```php
<?php get_header(); ?>
<main id="primary" class="site-main">
 <div class="container">
  <?php if (have_posts()) : ?>
    <header class="page-header archive-header">
      <?php the_archive_title('<h1 class="page-title">','</h1>'); the_archive_description('<div class="archive-description">','</div>'); ?>
    </header>
    <div class="posts-grid">
      <?php while (have_posts()) : the_post(); get_template_part('template-parts/content','archive'); endwhile; ?>
    </div>
    <?php the_posts_pagination(['mid_size'=>2,'prev_text'=>__('« Previous','theme-slug'),'next_text'=>__('Next »','theme-slug')]); ?>
  <?php else : get_template_part('template-parts/content','none'); endif; ?>
 </div>
</main>
<?php get_footer(); ?>
```

## 3. Custom Post Type and Taxonomy Example

`inc/custom-post-types.php`
```php
<?php
function theme_register_post_types() {
  $labels = [
    'name'=>_x('Team Members','post type general name','theme-slug'),
    'singular_name'=>_x('Team Member','post type singular name','theme-slug'),
    'menu_name'=>_x('Team','admin menu','theme-slug'),
    'add_new'=>_x('Add New','team member','theme-slug'),
    'add_new_item'=>__('Add New Team Member','theme-slug'),
    'edit_item'=>__('Edit Team Member','theme-slug'),
    'new_item'=>__('New Team Member','theme-slug'),
    'view_item'=>__('View Team Member','theme-slug'),
    'all_items'=>__('All Team Members','theme-slug'),
    'search_items'=>__('Search Team Members','theme-slug'),
    'not_found'=>__('No team members found.','theme-slug'),
  ];
  $args = [
    'labels'=>$labels,
    'public'=>true,
    'has_archive'=>true,
    'rewrite'=>['slug'=>'team'],
    'supports'=>['title','editor','thumbnail','excerpt'],
    'show_in_rest'=>true,
    'menu_icon'=>'dashicons-groups'
  ];
  register_post_type('team_member',$args);
}
add_action('init','theme_register_post_types');
```

`inc/taxonomies.php`
```php
<?php
function theme_register_taxonomies() {
  $labels = [
    'name'=>_x('Departments','taxonomy general name','theme-slug'),
    'singular_name'=>_x('Department','taxonomy singular name','theme-slug'),
    'search_items'=>__('Search Departments','theme-slug'),
    'all_items'=>__('All Departments','theme-slug'),
    'edit_item'=>__('Edit Department','theme-slug'),
    'update_item'=>__('Update Department','theme-slug'),
    'add_new_item'=>__('Add New Department','theme-slug'),
    'new_item_name'=>__('New Department Name','theme-slug'),
    'menu_name'=>__('Departments','theme-slug'),
  ];
  register_taxonomy('department',['team_member'],[
    'hierarchical'=>true,
    'labels'=>$labels,
    'rewrite'=>['slug'=>'department'],
    'show_in_rest'=>true,
    'show_admin_column'=>true
  ]);
}
add_action('init','theme_register_taxonomies');
```

## 4. Custom Post Type Templates (Example)

`archive-team_member.php`
```php
<?php get_header(); ?>
<main id="primary" class="site-main"><div class="container">
<?php if (have_posts()) : ?>
 <header class="page-header"><h1 class="page-title"><?php esc_html_e('Our Team','theme-slug'); ?></h1></header>
 <div class="team-grid">
 <?php while (have_posts()) : the_post(); ?>
  <div class="team-member-card">
    <?php if (has_post_thumbnail()) : ?><div class="team-member-photo"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('featured-medium'); ?></a></div><?php endif; ?>
    <div class="team-member-info">
      <h3 class="team-member-name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
      <?php $departments=get_the_terms(get_the_ID(),'department'); if($departments){echo '<div class="team-member-department">'; foreach($departments as $d){echo '<span>'.esc_html($d->name).'</span>'; } echo '</div>'; } ?>
      <div class="team-member-excerpt"><?php the_excerpt(); ?></div>
      <a class="team-member-link" href="<?php the_permalink(); ?>"><?php esc_html_e('View Profile','theme-slug'); ?></a>
    </div>
  </div>
 <?php endwhile; ?>
 </div>
 <?php the_posts_pagination(); ?>
<?php else : ?><p><?php esc_html_e('No team members found.','theme-slug'); ?></p><?php endif; ?>
</div></main>
<?php get_footer(); ?>
```

`single-team_member.php`
```php
<?php get_header(); ?>
<main id="primary" class="site-main">
<?php while (have_posts()) : the_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class('team-member-single'); ?>>
 <div class="container">
  <div class="team-member-layout">
    <div class="team-member-sidebar">
      <?php if (has_post_thumbnail()) : ?><div class="team-member-photo"><?php the_post_thumbnail('featured-large'); ?></div><?php endif; ?>
      <div class="team-member-meta">
        <?php $departments=get_the_terms(get_the_ID(),'department'); if($departments){echo '<div class="meta-item department"><strong>'.esc_html__('Department:','theme-slug').'</strong><span>'; echo esc_html(implode(', ',wp_list_pluck($departments,'name'))); echo '</span></div>'; } ?>
      </div>
    </div>
    <div class="team-member-content">
      <header class="entry-header"><h1 class="entry-title"><?php the_title(); ?></h1></header>
      <div class="entry-content"><?php the_content(); ?></div>
    </div>
  </div>
 </div>
</article>
<?php endwhile; ?>
</main>
<?php get_footer(); ?>
```

## 5. Sidebar and Template Parts

`sidebar.php`
```php
<?php if (!is_active_sidebar('sidebar-1')) return; ?>
<aside id="secondary" class="widget-area sidebar"><?php dynamic_sidebar('sidebar-1'); ?></aside>
```

`template-parts/content-none.php`
```php
<?php /* No Results */ ?>
<section class="no-results not-found">
 <header class="page-header"><h1 class="page-title"><?php esc_html_e('Nothing Found','theme-slug'); ?></h1></header>
 <div class="page-content">
  <?php if (is_search()) : ?>
    <p><?php esc_html_e('Nothing matched your search. Try different keywords.','theme-slug'); ?></p>
    <?php get_search_form(); ?>
  <?php else : ?>
    <p><?php esc_html_e('Content not found. Try a search.','theme-slug'); ?></p>
    <?php get_search_form(); ?>
  <?php endif; ?>
 </div>
</section>
```

`template-parts/content-archive.php`
```php
<article id="post-<?php the_ID(); ?>" <?php post_class('archive-post'); ?>>
 <?php if (has_post_thumbnail()) : ?><div class="post-thumbnail"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('featured-medium'); ?></a></div><?php endif; ?>
 <div class="post-content">
  <header class="entry-header">
    <?php the_title('<h2 class="entry-title"><a href="'.esc_url(get_permalink()).'">','</a></h2>'); ?>
    <div class="entry-meta"><time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time> <span class="byline"><?php echo esc_html__('by','theme-slug').' '.get_the_author(); ?></span></div>
  </header>
  <div class="entry-summary"><?php the_excerpt(); ?></div>
  <div class="entry-footer"><a class="read-more" href="<?php the_permalink(); ?>"><?php esc_html_e('Read More','theme-slug'); ?> →</a></div>
 </div>
</article>
```

## 6. Optional Additional Templates
- `search.php` (if custom layout required)
- `tag.php` (only if different from generic archive)
- `taxonomy-department.php` (if department archive needs unique design)

## 7. Custom Fields Strategy
Use `wp-custom-fields` plugin groups defined to match page/CPT needs. Ensure:
- Field group keys stable.
- All front-end rendered strings come from post content, excerpt, meta, or taxonomy term names (no hard-coded marketing text).

## 8. Save Data in WordPress Admin
1. Create actual content in WordPress admin. Use WP-CLI for saving content. Reference the task configuration for details on how to store data.

### CRITICAL: Icon and Image Custom Fields in Admin

**IMPORTANT:** For custom fields that allow image/icon uploads via WordPress Media Library:

1. **Enqueue WordPress Media Uploader** in `functions.php`:
```php
function theme_admin_scripts($hook) {
    // REQUIRED: Enqueue media uploader for icon/image fields
    if ($hook === 'post.php' || $hook === 'post-new.php') {
        wp_enqueue_media();
    }
    wp_enqueue_style('theme-admin', get_template_directory_uri() . '/assets/css/admin.css');
}
add_action('admin_enqueue_scripts', 'theme_admin_scripts');
```

2. **Save Icon Fields by Post Type** in `inc/custom-fields.php`:
```php
function theme_save_meta_boxes($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    // Get post type to target correct fields
    $post_type = get_post_type($post_id);
    
    // Example: Feature CPT with icon field
    if ($post_type === 'feature' && isset($_POST['feature_nonce']) && 
        wp_verify_nonce($_POST['feature_nonce'], 'feature_fields')) {
        
        if (isset($_POST['feature_icon'])) {
            $icon_value = $_POST['feature_icon'];
            if (empty($icon_value)) {
                delete_post_meta($post_id, 'feature_icon');
            } else {
                update_post_meta($post_id, 'feature_icon', absint($icon_value));
            }
        }
    }
}
add_action('save_post', 'theme_save_meta_boxes');
```

3. **Key Requirements for Icon Saving:**
   - Check `$post_type` to ensure correct nonce is verified
   - Handle empty values with `delete_post_meta()` to allow icon removal
   - Use `absint()` for attachment IDs
   - Each custom post type should have its own nonce verification block

## 9. Validation Checklist
- CPT & taxonomy appear in admin and are editable.
- Homepage renders all dynamic sections.
- Archives paginate without errors.
- Single templates show meta, featured image, and custom fields.
- No PHP notices/warnings.

## 10. Success Criteria
- All required templates exist & load.
- Editing content in WordPress reflects on frontend without code changes.
- Reusable template parts reduce duplication.
- Helper functions (breadcrumbs, pagination, reading time) work when used.

## Notes
Removed duplicated, fragmented, and interleaved instructions. Consolidated numbering and removed stray inline numbered steps inside code blocks. Use this guide for implementation.

## 11. Update functions.php

Add new includes to functions.php:

```php
// Include custom post types (if they exist in task)
if (file_exists(get_template_directory() . '/inc/custom-post-types.php')) {
    require get_template_directory() . '/inc/custom-post-types.php';
}

// Include taxonomies
if (file_exists(get_template_directory() . '/inc/taxonomies.php')) {
    require get_template_directory() . '/inc/taxonomies.php';
}

// Include custom fields
if (file_exists(get_template_directory() . '/inc/custom-fields.php')) {
    require get_template_directory() . '/inc/custom-fields.php';
}
```

## Questions with Answers

1. **Custom Post Types:**
   - "Should I create the custom post types from the task?" - Use task for getting all needed custom posts
   - "Do you prefer plugin-based (CPT UI) or code-based registration?" - I would prefer code-based registation

2. **Custom Fields:**
   - \"Do you prefer plugin-based (CPT UI) or code-based registration?\" - Code-based registration is preferred
   - "Should I create custom meta boxes in code?" - yes, if needed
   - "What fields does each post type need?" - analize content and add needed fields

   - \"Should I create custom meta boxes in code?\" - Yes, if needed
   - \"What fields does each post type need?\" - Analyze content and add needed fieldso from task
   - "Do you need taxonomy archive pages with custom designs?" - get this info from task

   - \"Should taxonomies be hierarchical (categories) or flat (tags)?\" - Get this information from task
   - \"Do you need taxonomy archive pages with custom designs?\" - Get this information from taskrom mark up
   - "How many items per page on archives?" - get this info from mark up

   - \"How should custom post type archives be displayed (grid, list)?\" - Get this information from markup
   - \"How many items per page on archives?\" - Get this information from markup)?" - get this information from task
   - "Should related items be shown on single pages?" - yes

   - \"What layout for single custom post type pages (sidebar, full-width)?\" - Get this information from task
   - \"Should related items be shown on single pages?\" - Yes
   - \"Do you want previous/next post navigation?\" - Use if in design

## Success Criteria

Before proceeding to Step 4d:

- [ ] front-page.php created and matches homepage design
- [ ] page.php created for standard pages
- [ ] single.php created for blog posts
- [ ] archive.php created for archives
- [ ] tag.php created (if needed)
- [ ] inc/template-functions.php created with helper functions
- [ ] template-parts/content-archive.php created
- [ ] All pages from task render correctly
- [ ] WordPress page hierarchy works
- [ ] No PHP errors
- [ ] User validated all templates
- [ ] inc/custom-post-types.php created (if needed)
- [ ] inc/taxonomies.php created (if needed)
- [ ] inc/custom-fields.php created (if using custom solution)
- [ ] archive-{posttype}.php created for each custom post type
- [ ] single-{posttype}.php created for each custom post type
- [ ] sidebar.php created
- [ ] template-parts/content-none.php created
- [ ] Custom post types appear in WordPress admin
- [ ] Taxonomies work correctly
- [ ] Custom fields save and display
- [ ] Archive pages render correctly
- [ ] Single pages render correctly
- [ ] User validated all custom content types

## Validation Checklist

```
✅ Step 4c Complete: Page Templates Created

**Templates Created:**
✓ front-page.php (Homepage)
✓ page.php (Default pages)
✓ single.php (Blog posts)
✓ archive.php (Archives)
✓ tag.php (Tag archives)

**Helper Files:**
✓ inc/template-functions.php
✓ template-parts/content-archive.php

**Testing Checklist:**
□ Homepage displays correctly
□ Created several pages in WordPress admin - they display correctly
□ Blog post displays with correct layout
□ Category and tag archives work
□ Breadcrumbs showing (if implemented)
□ No PHP errors in debug log

Ready to proceed to Step 4d (Forms Configuration)?
```

**Next Step:** Proceed to **Step 4d: Forms Configuration**


