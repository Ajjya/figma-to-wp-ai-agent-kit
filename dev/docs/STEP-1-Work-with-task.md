## STEP 1: Work with Task

### Objective
Analyze and validate the current task definition before starting any development work.

### Instructions for AI

Use "docs/AI-INSTRUCTIONS.md" as base

1. **Check for Current Task**
   - Look for task file at: `tasks/current-task.json`
   - If task exists, proceed to analysis
   - If no task exists, prompt user to create one

2. **Task Does Not Exist**
   ```
   I don't see a current task defined. Would you like me to help you create a task?
   
   To create a task, I need:
   - Task title (project name)
   - Figma design URL
   - List of pages from Figma (I can extract these from Figma MCP)
   - Site structure (pages, hierarchies, custom post types if any)
   
   Refer to: tasks/docs/site-structure-example-full.json
   ```

3. **Task Exists - Show Summary**
   - Read the `current-task.json` file
   - Extract and display:
     - Task ID and Title
     - Current Status
     - Figma URL
     - Number of pages
     - Site structure overview (pages, post types, taxonomies)
   
   **Example Summary Format:**
   ```
   📋 Current Task Summary:
   
   **Title:** [task.title]
   **Status:** [task.status]
   **Figma:** [task.figmaUrl]
   
   **Pages ([count]):**
   - [List each page with slug and template]
   
   **Custom Post Types:** [List if any]
   **Taxonomies:** [List if any]
   
   ❓ Does this look correct? Should I proceed with this task?
   ```

4. **Ask User to Validate**
   - Wait for user confirmation
   - Allow user to request modifications to task structure
   - If user says "yes" or confirms, proceed to status update

5. **Update Task Status**
   - Change `status` field in `current-task.json` to: `"Task analyzed"`
   - Confirm update to user

### Reference Documents
- Task example: `tasks/current-task.json`
- Full structure example: `tasks/docs/site-structure-example-full.json`


## Troubleshooting

### If Task File is Corrupted:
- Ask user to validate JSON syntax
- Offer to create a new task based on conversation