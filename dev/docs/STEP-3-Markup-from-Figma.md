## STEP 3: Copy Markup from Figma (via MCP)

### Objective
Extract complete HTML/CSS markup from Figma designs using Figma MCP, ensuring all pages, components, and design tokens are properly captured.

### Prerequisites
- Figma MCP is installed and authenticated
- Current task file exists with `figmaUrl` and `figmaPages[]` defined
- Task status is "WP initiated"

### Context to Gather Before Starting

Read from `tasks/current-task.json`:
- `figmaUrl` - Main Figma file URL
- `figmaPages[]` - Array of pages with nodeId and page names
- `siteStructure.pages[]` - Expected WordPress page structure

### ⚠️ STRICT RULE: ASSET DOWNLOAD IS MANDATORY

**ALL ASSETS MUST BE DOWNLOADED - NO EXCEPTIONS**
**ALL SVG code - must be converted to files and save as assets/*.svg**

During task you MUST:
1. **Parse ALL asset URLs** from Figma MCP response (format: `http://localhost:3845/assets/[hash].[ext]`)
2. **Download EVERY asset** to local folders:
   - PNG/JPG images → `dev/html/[site-name]/assets/images/`
   - SVG icons → `dev/html/[site-name]/assets/icons/`
   - Logos → `dev/html/[site-name]/assets/logos/`
3. **Verify downloads** - check file size > 0, file is valid
4. **Update HTML** - replace localhost URLs with local relative paths
5. Never create any documentation files
6. ALL SVG code - must be converted to files and save as assets/*.svg

**If an asset fails to download:**
- If impossible to download, use font-awesome or other free icons in correct style
- Do NOT skip - inform user of failed assets

### Prompt for Figma MCP

When calling Figma MCP, use this consolidated prompt:

```
Implement this design from Figma.
@[page.url from figmaPages]

Extract the complete design context for this WordPress site conversion project.

Design System Requirements:
1. Color Palette - Extract all color variables, primary/secondary/accent colors, backgrounds
2. Typography - Font families, weights, sizes (H1-H6), line height, letter spacing
3. Spacing System - Margins, padding, gaps, spacing scale (8px grid, etc.)
4. Breakpoints - Mobile (320px-767px), Tablet (768px-1024px), Desktop (1025px+)
5. Components - Buttons (states), form elements, navigation, cards, icons, images
6. Layout - Grid system, container widths, section spacing, header/footer structure

For Each Page:
- Extract semantic HTML5 structure
- Generate clean, accessible CSS with custom properties
- Identify reusable components
- Note animations/transitions
- Export all images and assets

Output Format:
- Clean HTML5 semantic markup (no inline styles)
- Modern CSS with BEM or similar naming
- Commented code indicating sections
- Separate files for each page
- Assets organized in folders (images, icons, fonts)

WordPress Specific:
- Mark dynamic areas (post titles, content, featured images)
- Identify navigation areas and widget/sidebar locations
- Mark areas for WordPress loops
- Form fields compatible with Contact Form 7 or WPForms
```

### AI Instructions - Step by Step Process

Use "docs/AI-INSTRUCTIONS.md" as base reference.

#### 1. Verify Figma MCP is Ready
- Confirm MCP was checked in "Before You Start" section
- If user said "no" earlier, remind them to configure it first
- If user confirmed "yes", proceed with extraction

#### 2. Extract Markup from Figma
- Read `figmaPages[]` from `current-task.json`
- Get site name from task (slugified title)
- Create directory: `dev/html/[site-name]/` (this folder is in .gitignore)
- For each page in `task.figmaPages[]`:
  - Use `page.url` for Figma MCP extraction
  - Send the consolidated prompt above to Figma MCP
  - Get design context and code
  - Save to `dev/html/[site-name]/[page-name].html` and `[page-name].css`
- Use hamburger menu on mobile - be sure that navigation suits well, you must use hamburger menu as soon as navigation out of 1 line.
- Images should change size proportionally when resize

**Important:** HTML files are NOT committed to git - they're in .gitignore

#### 3. Download and Organize Assets
- Parse ALL asset URLs from Figma MCP response
- Download to organized folders: `images/`, `icons/`, `logos/`
- Verify each download (file size > 0)
- Update HTML with local paths

#### 4. Analyze Structure According to Task
- Compare extracted pages with `task.siteStructure.pages[]`
- Verify all pages from task are present in Figma
- Check page names match between Figma and task definition

**Show Analysis:**
```
📊 Markup Extraction Analysis:

✅ Extracted Pages ([count]):
- Homepage (Figma: "Homepage" → html: front-page.html)
- [List each page with mapping]

⚠️ Potential Issues:
- [List any mismatches or missing pages]

📁 Markup saved to: dev/html/[site-name]/
Note: This directory is in .gitignore and not committed to git
```

#### 5. Propose Validation
Ask user to review extracted markup:
```
🔍 Markup Validation Required:

I've extracted the markup from Figma. Please review:

Location: dev/html/[site-name]/
Files: [list HTML files created]

Note: These files are not tracked by git (.gitignore)

Options:
1. Looks good - proceed to next step
2. Need adjustments - let me know what to change
3. I'll review manually and get back to you

What would you like to do?
```

#### 6. Work with User on Adjustments
If user requests changes, ask specific questions:
- Which page needs adjustment?
- What specifically needs to change?
- Should I re-extract from Figma or modify existing markup?

#### 7. Update Task Status
- After user approves markup
- Change status in `current-task.json` to: `"Markup done"`

### Technical Implementation Requirements

#### 1. Separate Files and Structure
- Generate separate HTML and CSS files
- Use max-width for each block/section
- Strictly use provided Figma design

#### 2. Design Specifications
- **Pixel-perfect implementation**
- Use ALL icons and images from Figma MCP
- Do NOT invent anything not in the design

#### 3. Interaction and Visual Effects
- Include interactivity for buttons
- Add shadows and hover/click reactions
- Follow best practices for UI interactions

#### 4. Technical Details
- Generate semantic HTML with proper structure
- Create CSS with precise max-width for each section
- Include all visual elements:
  * Typography (fonts, sizes, colors, weights)
  * Colors and backgrounds
  * Spacing and layout (flexbox/grid)
  * Button styles and states
  * Images/media with proper src attributes
  * Icons (as SVG or icon classes)

#### 5. File Structure
- HTML file: `dev/html/[site-name]/[page-name].html`
- CSS file: `dev/html/[site-name]/[page-name].css`
- Assets: `dev/html/[site-name]/assets/[images|icons|logos]/`

### Organize Extracted Files

Create this structure:

```
dev/html/[site-name]/
├── [page-name].html
├── [page-name].css
├── ...
└── assets/
    ├── images/
    ├── icons/
    └── logos/
```

Optional - Create design system documentation file if needed:

```json
{
  "colors": {
    "primary": "#......",
    "secondary": "#......"
  },
  "typography": {
    "fontFamilies": {
      "primary": "...",
      "headings": "..."
    },
    "fontSizes": {
      "h1": "...",
      "body": "..."
    }
  },
  "spacing": {
    "scale": [0, 4, 8, 16, 24, 32, 48, 64],
    "containerMaxWidth": "1200px"
  },
  "breakpoints": {
    "mobile": "767px",
    "tablet": "1024px",
    "desktop": "1025px"
  }
}
```

### Show User Summary

Present to user after extraction:

```
🎨 Figma Markup Extraction Complete

**Design System Captured:**
✅ Colors extracted
✅ Typography styles documented
✅ Spacing system identified
✅ Breakpoints defined

**Pages Extracted:** [X/Y]
✅ Homepage
✅ [List each page]
⚠️ [Any issues or missing pages]

**Assets Exported:**
- Images: [count]
- Icons: [count]
- Logos: [count]

**Saved to:** dev/html/[site-name]/

**Next Steps:**
Please review the extracted markup:
1. Open HTML files in browser to verify layout
2. Check if all images loaded correctly
3. Verify responsive behavior at different breakpoints
4. Confirm color and typography match Figma

Would you like to:
A) Preview the markup in browser
B) Make adjustments to specific pages
C) Proceed to Step 6 (HTML to WordPress conversion)
D) I'll review manually first

Your choice?
```

### Questions to Ask User

**Before Extraction:**
- "Is Figma MCP authenticated and working? Can you access the Figma file?"
- "Should I extract all pages at once or one at a time?"
- "What's your preferred CSS methodology (BEM, utility-first, modules)?"

**During Extraction:**
- "I found [X] pages in Figma but task defines [Y] pages. Should I extract all?"
- "This component appears multiple times. Should I extract it as reusable?"
- "There are [X] font families. Do you have licenses/access to them?"

**After Extraction:**
- "The markup is ready. Would you like me to create a preview?"
- "Do you want me to optimize images before conversion?"
- "Are there any pages that need adjustments?"

### Common Issues and Solutions

| Issue | Solution |
|-------|----------|
| Figma MCP authentication fails | Guide user through re-authentication, check API token |
| Missing images in extraction | Re-export specific images, check export settings |
| Colors don't match exactly | Verify color profile (RGB vs HEX), check opacity/transparency |
| Fonts are different from Figma | Check font availability, provide web font alternatives |
| Layout breaks at breakpoints | Review Figma frames, add missing media queries |

### CSS Design Tokens Example

Generate CSS custom properties file when needed:

```css
/* variables.css - Generated from Figma Design System */

:root {
  /* Colors - Primary Palette */
  --color-primary: #......;
  --color-primary-dark: #......;
  --color-primary-light: #......;
  
  /* Colors - Secondary */
  --color-secondary: #......;
  
  /* Colors - Neutral */
  --color-background: #......;
  --color-text: #......;
  --color-border: #......;
  
  /* Typography */
  --font-primary: 'Font Name', sans-serif;
  --font-headings: 'Heading Font', serif;
  
  /* Font Sizes */
  --font-size-h1: 3rem;
  --font-size-h2: 2.5rem;
  --font-size-h3: 2rem;
  --font-size-body: 1rem;
  
  /* Spacing Scale */
  --space-xs: 0.25rem;
  --space-sm: 0.5rem;
  --space-md: 1rem;
  --space-lg: 1.5rem;
  --space-xl: 2rem;
  --space-2xl: 3rem;
  
  /* Breakpoints (for JS) */
  --breakpoint-mobile: 767px;
  --breakpoint-tablet: 1024px;
  --breakpoint-desktop: 1025px;
  
  /* Layout */
  --container-max-width: 1200px;
  --grid-gap: 1.5rem;
}
```

### Success Criteria

Before proceeding to Step 6, verify:

- [ ] All pages from `task.figmaPages[]` are extracted
- [ ] HTML is semantic and accessible
- [ ] CSS is organized and follows a consistent methodology
- [ ] All images/assets are downloaded and referenced correctly
- [ ] Design system tokens are documented (if applicable)
- [ ] Responsive breakpoints are implemented
- [ ] User has reviewed and approved the markup
- [ ] No missing assets or broken references
- [ ] Task status updated to "Markup done"

### Reference Documents
- Task Figma data: `current-task.json` → `figmaPages[]` (use `url` field for each page)
- Base AI instructions: `docs/AI-INSTRUCTIONS.md`

---

**Remember:** This markup will be the foundation for WordPress templates. Ensure it's clean, semantic, and well-organized before proceeding to Step 6.
