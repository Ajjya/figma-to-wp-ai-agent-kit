## STEP 4: Web Server Configuration

**Objective:** Configure the web server (Valet or Manual) before any WordPress or database setup. This is tracked with the status `"server-configured"`.

**Instructions for AI:**

Use "docs/AI-INSTRUCTIONS.md" as base

1. Ask user for server type:
   - 1. 💻 Local (Laravel Valet) - Automatic setup with database
   - 2. 📝 Manual - Just create theme folder structure
2. For Valet: Check/install Valet, MySQL, WP-CLI as needed. Confirm readiness.
3. For Manual: Confirm only theme structure will be created.
4. After configuration, update status in `current-task.json` to `"server-configured"`.

**Example dialog:**
```
Choose server setup:
1. 💻 Local (Laravel Valet) - Automatic setup with database
2. 📝 Manual - Just create theme folder structure

Please choose (1-2):
```

**If Valet (1) selected:**
- Inform: "This will check and install Valet, MySQL, WP-CLI if needed (requires sudo)."
- Ask: "Continue with Valet setup?"

**If Manual (2) selected:**
- Inform: "Only theme structure will be created in websites/[site-name]/wp-content/themes/[theme-name]/"
- Ask: "Continue with manual setup?"
