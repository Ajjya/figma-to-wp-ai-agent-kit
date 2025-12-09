## STEP 5: Create WordPress Site

### Objective
Set up an empty WordPress installation in the project workspace according to the task specifications. Do not create theme, just activate last one.

### Instructions for AI

Use "docs/AI-INSTRUCTIONS.md" as base

1. **Check if Website Already Exists**
   - Generate site name from task: `[task.title slugified]`
   - Check directory: `websites/[site-name]/`
   - If exists, ask user:
   ```
   ⚠️ A website directory already exists at websites/[site-name]/
   
   Options:
   1. Remove existing site and create new one
   2. Skip this step and work with existing site
   3. Use a different site name
   
   What would you like to do?
   ```

2. **Ask WordPress Configuration (One by One)**
   
   Ask each setting separately, waiting for response before next question.
   
   **IMPORTANT:** User cannot press Enter in chat - they can only type text responses.
   - Show the default value in parentheses
   - User types "default" to use the default value
   - User types "empty" for empty/no password
   - User types their custom value directly
   - Keep questions short and simple

   **Question 1:**
   ```
   Database name? (Default: [site-name_with_underscores])
   ```

   **Question 1a:**
   ```
   Database username? (Default: root)
   ```

   **Question 1b:**
   ```
   Database password? (Default: empty, type "empty" for no password)
   ```
   
   **Question 2:**
   ```
   Admin username? (Default: admin)
   ```
   
   **Question 3:**
   ```
   Admin email? (Default: admin@[site-name].test)
   ```
   
   **Question 4:**
   ```
   Admin password? (Type "auto" to auto-generate secure password)
   ```
   
   **Question 5:**
   ```
   Site title? (Default: [task.title from current-task.json])
   ```

3. **Create WordPress Site**
   - Use collected information to create the site programmatically
   - DO NOT run `yarn create-site` script - handle everything in AI instructions
   - Create directory structure
   - For Valet: Set up database, install WordPress, configure
   - For Docker: Create docker-compose.yml with configuration
   - For Manual: Create theme folder structure only
   - Copy plugin from knowledge-base/plugins/wp-custom-fileds

4. **Show Completion Summary**
   - After site creation completes, show summary based on server type:

   **If Valet was selected:**
   ```
   ✅ WordPress Site Created with Valet
   
   Site URL: http://[site-name].test
   Admin URL: http://[site-name].test/wp-admin
   
   Credentials:
   - Username: [admin-username]
   - Password: [password or "Auto-generated: xxxxxx"]
   - Email: [admin-email]
   
   Database:
   - Name: [database-name]
   - Host: localhost
   - Database password: [password or "Auto-generated: xxxxxx"]
   
   Please verify the site loads correctly.
   ```

   **If Manual was selected:**
   ```
   Saved Configuration (for when you install WordPress):
   - Database name: [database-name]
   - Database password: [password or "Auto-generated: xxxxxx"]
   - Admin username: [admin-username]
   - Admin email: [admin-email]
   - Admin password: [password or "Auto-generated: xxxxxx"]
   - Site title: [site-title]
   
   Next steps:
   1. Install WordPress in websites/[site-name]/ directory
   2. Use the credentials above during WordPress installation
   3. Activate the last theme in WordPress admin
   
   Configuration saved to: tasks/[task-id]-site.json
   
   Let me know when ready to continue.
   ```

6. **Update Task Status**
   - After user validates site is running or confirms setup
   - Change status in `current-task.json` to: `"wp-initiated"`

### Reference Documents
- Task structure: `tasks/current-task.json`
- Plugin source: `knowledge-base/themes/awesome_group/wp-content/plugins/wp-custom-fileds/`

### Implementation Notes
- All dialogs and choices happen in Copilot Chat, not in terminal scripts
- AI handles all file creation, directory setup, and configuration
- For Valet setup, AI can guide user to run individual commands (valet install, wp core download, etc.)
- Save site configuration to `tasks/[task-id]-site.json` for reference


## Troubleshooting

### If WordPress Setup Fails:
- Verify directory permissions
- Check if WordPress files are present
- Confirm database connection
- Offer alternative setup methods