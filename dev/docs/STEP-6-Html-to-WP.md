## STEP 6: HTML to WordPress Conversion

### Objective
Convert extracted HTML/CSS markup up.
Work with theme -  site name from task: `[task.title slugified]`
Use Claude Opus 4.5 or Cloude Sonnet 4.5

## Critical Requirements
1. **Media Management**: Copy all images from markup to WordPress Media Library. Reference them in appropriate posts/pages
2. **Admin Editability**: Every icon, link, text, and title must be editable from WordPress admin. Use wp-custom-fields plugin for additional custom fields. Use metaboxes for additional fields if necessary. 
3. **Reference Theme**: Always use `knowledge-base/theme/` as your reference for structure and functionality


## AI Instructions
- Follow general AI instructions from `docs/AI-INSTRUCTIONS.md`
- Follow step-specific instructions from `docs/STEP-6-Html-to-WP.md`
- Do not invent anything - ask the user if you have any doubts before proceeding
- Do not create additional documentation (.md) files
- Add content to wp-admin using WP-CLI
- Go through these steps:
1. **Locate HTML Source Files**
   a. Read site name from `current-task.json` (slugified title)
      **Extract Task Data**
      From `tasks/current-task.json`, extract:
      - `themeName` - The name of the theme you're building
      - `status` - Current workflow status (must be "wp-initiated")
      - `title` - Project title
      - `menus` - Menu locations needed
      - `widgets` - Widget areas needed
      - `customPosts` - Custom post types to register
      - `categories` - Taxonomy structure
      - `imageSizes` - Required image dimensions (if specified)

   **⚠️ IMPORTANT - Theme Location:**
   - Get theme name from: `task.themeName` (already slugified)
   - Create theme folder at: `websites/[task.title slugified]/wp-content/themes/[task.themeName]/`
   - Example: If `title: "Awesome website"` and `themeName: "awesome-theme"` → `websites/awesome-website/wp-content/themes/awesome-theme/`
  
   b. HTML files are in: `dev/html/[themeName]`
   c. These files are the source for WordPress conversion
   d. Note: This directory is in .gitignore

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
   - Follow the prompts exactly as written in those files:
      - 4a: Assets and Libraries - CSS/JS/images organized and enqueued
      - 4b: Initial Templates - Core templates with WordPress hooks  
      - 4c: Page Templates - Full template hierarchy and CPTs
      - 4d:Forms & Contact - Form handlers with AJAX and security
      - 4e: Final Integration - QA checklist and deployment validation





