# Figma-to-WP AI Agent Kit

AI-assisted workflow for converting Figma designs into fully functional WordPress websites.

## 🚀 Quick Start

### 1. Configure Repository Sources

The workspace automatically clones repositories defined in `setup.config.json`. Update this file before running setup if you need to use different repositories.

**Default configuration:**
```json
{
  "repositories": {
    "theme": {
      "name": "Canonical WordPress Theme",
      "url": "git@github.com:Ajjya/canonical-wp-theme.git",
      "destination": "knowledge-base/theme/canonical-wp-theme",
      "description": "Reference theme for WordPress site generation"
    },
    "plugins": [
      {
        "name": "WP Custom Fields",
        "url": "git@github.com:Ajjya/wp-custom-fileds.git",
        "destination": "knowledge-base/plugins/wp-custom-field",
        "description": "Custom field plugin for WordPress"
      }
    ]
  },
  "directories": [...]
}
```

You can modify the `url` fields in `setup.config.json` to point to different repositories. The theme and plugin repositories should be accessible via SSH.

### 2. Initialize Workspace

```bash
yarn install
yarn setup
```

This will:
- Clone reference theme from `git@github.com:Ajjya/canonical-wp-theme.git`
- Clone plugins from configured repositories
- Create necessary directory structure
- Set up the workspace for development

**Theme and Plugin Details:**
- **Theme**: Canonical WordPress Theme (`knowledge-base/theme/canonical-wp-theme/`)
- **Plugins**: WP Custom Fields (`knowledge-base/plugins/wp-custom-field/`)

### 3. Create a Task

Edit `tasks/current-task.json` with your project information:

```json
{
  "id": "001",
  "title": "My Website Project",
  "description": "Converting Figma design to WordPress",
  "status": "pending",
  "figmaUrl": "https://figma.com/design/abc123/Project-Name",
  "figmaPages": [
    {
      "nodeId": "0:1",
      "name": "Homepage",
      "url": "https://figma.com/design/abc123/Project-Name?node-id=0-1"
    }
  ],
  "siteStructure": {
    "pages": [
      {
        "name": "Home",
        "slug": "home",
        "template": "front-page.php"
      }
    ]
  }
}
```

See `tasks/docs/site-structure-example-full.json` for a complete example.

### 4. Configure Prerequisites

#### 3.1 Get Your Figma Design URL

1. Open your Figma project
2. Copy the full URL from browser
3. Example: `https://figma.com/design/abc123/Project-Name`

Update this URL in your task file (`tasks/current-task.json`).

#### 3.2 Configure Figma MCP (Optional - Required only for Step 3)

**Required for Step 3 (Extract Markup from Figma)**

**Step 1: Enable the Figma Desktop MCP Server**

1. Open the **Figma desktop app** and update to the latest version
2. Create or open a **Figma Design file**
3. In the toolbar at the bottom, toggle to **Dev Mode** (or use `Shift+D`)
4. In the MCP server section of the inspect panel, click **Enable desktop MCP server**
5. You should see a confirmation message at the bottom of the screen

> **Note:** The server runs locally at `http://127.0.0.1:3845/mcp`

**Step 2: Set up VS Code MCP Client**

1. Open **Command Palette** (`Cmd+Shift+P` on Mac / `Ctrl+Shift+P` on Windows)
2. Search for and run: **`MCP: Add Server`**
3. Select **`HTTP`**
4. Paste the server URL: `http://127.0.0.1:3845/mcp`
5. When prompted for server ID, enter: **`figma-desktop`**
6. Choose whether to add globally or only for current workspace

Your `mcp.json` file should now contain:

```json
{
  "servers": {
    "figma-desktop": {
      "type": "http",
      "url": "http://127.0.0.1:3845/mcp"
    }
  }
}
```

**Step 3: Verify the Setup**

1. Open the chat toolbar using `Alt+Cmd+B` or `Ctrl+Cmd+I`
2. Switch to **Agent mode**
3. Type: `#mcp_figma_mcp-ser_get_design_context`
4. If Figma MCP server tools are listed, setup is complete! ✅
5. If no tools appear, restart both the Figma desktop app and VS Code

> **Important:** You must have **GitHub Copilot** enabled on your account to use MCP in VS Code.

**Desktop Settings (Optional)**

In Figma Preferences, you can configure:
- **Image settings**: Use local image server or download images to disk
- **Enable Code Connect**: Include component mappings for better code reuse

**Troubleshooting:**
- Ensure Figma desktop app is running (MCP server must be enabled)
- Check that Dev Mode is active in your Figma file
- Verify the server URL is exactly: `http://127.0.0.1:3845/mcp`
- Restart both Figma and VS Code if tools don't appear
- Check that GitHub Copilot is active in VS Code

> **Note:** The AI assistant will check if Figma MCP is configured before starting

### 5. Start the Workflow with AI

Open **GitHub Copilot Chat** and run these prompts one at a time. For each step, attach the corresponding prompt file and follow the AI's guidance. **Use separate chat sessions for each prompt:**

#### Step 1: Task Analysis & Planning
Attach: `@dev/docs/STEP-1-Work-with-task.md`
```
Analyze the current task and show me the project plan
```

#### Step 2: Configure Figma MCP
Attach: `@dev/docs/STEP-2-Figma-MCP.md`
```
Set up Figma MCP and verify connection
```

#### Step 3: Extract Markup from Figma
Attach: `@dev/docs/STEP-3-Markup-from-Figma.md`
```
Extract markup from Figma design
```

#### Step 4: Set Up WordPress Server
Attach: `@dev/docs/STEP-4-Web-server-config.md`
```
Set up WordPress server
```

#### Step 5: Create WordPress Installation
Attach: `@dev/docs/STEP-5-Create-WP.md`
```
Create the WordPress site
```

#### Step 6: Convert HTML to WordPress
Attach: `@dev/docs/STEP-6-Html-to-WP.md`
```
Convert HTML to WordPress theme
```

**Important:** Wait for each step to complete before moving to the next. The AI will update your task status automatically.

## 📖 Workflow Overview

The AI assistant guides you through a structured 6-step workflow:

### Step 1: Task Analysis & Planning
- Validates task definition in `tasks/current-task.json`
- Shows summary of pages and site structure
- Updates status to `"task-analyzed"`

### Step 2: Configure Figma MCP
- Configures Figma MCP connection
- Verifies Figma Design file access
- Prepares for markup extraction
- Updates status to `"mcp-configured"`

### Step 3: Extract Markup from Figma
- Uses Figma MCP to extract HTML/CSS from design
- Analyzes page structure and components
- Saves organized markup to `dev/html/` folder
- Updates status to `"markup-done"`

### Step 4: Set Up WordPress Server
- Guides you to configure a local WordPress environment
- **Helps you choose server setup:**
  - **Laravel Valet (macOS)** - Automatic installation
  - **Manual theme (macOS/Windows)** - Manual setup with download link
- Updates status to `"server-configured"`

### Step 5: Create WordPress Installation
- Creates WordPress installation in `websites/[site-name]/`
- Sets up custom theme structure
- Initializes theme folders and template hierarchy
- Updates status to `"wp-initiated"`

### Step 6: Convert HTML to WordPress
- Converts markup to WordPress theme files
- Implements 5 sub-steps:
  - 4a: Move assets and configure libraries
  - 4b: Create initial template files
  - 4c: Create page templates
  - 4d: Configure forms (plugins installed by AI, settings by user)
  - 4e: Final integration and testing
- Updates status to `"html-to-wp-complete"`

## 📁 Project Structure

```
.
├── knowledge-base/              # Reference materials
│   ├── theme/
│   │   └── canonical-wp-theme/     # Canonical WordPress theme
│   └── plugins/
│       └── wp-custom-field/        # Custom field plugin
├── websites/                    # WordPress installations
│   └── [site-name]/            # Individual WordPress site
│       └── wp-content/
│           └── themes/
│               └── [theme]/    # Your custom theme
├── tasks/                       # Task definitions
│   ├── current-task.json       # Active task
│   └── docs/                   # Task examples
├── dev/prompts/                 # AI prompts and instructions
│   └── step-4-html-to-wp/      # Detailed conversion prompts
├── src/                         # TypeScript source code
│   └── setup-workspace.mjs     # Workspace initializer
├── setup.config.json            # Repository configuration
└── AI-INSTRUCTIONS.md           # Main AI workflow guide
```

## 🛠️ Available Commands

```bash
# Initialize workspace (first time setup)
yarn setup

# Create WordPress site
yarn create-site

# Build TypeScript
yarn build

# Run in production
yarn start

# Development mode
yarn dev
```

## 🔄 Continuing from Previous Work

The AI assistant automatically detects where you left off:

- **Status: "pending"** → Starts from Step 1 (Task Analysis)
- **Status: "task-analyzed"** → Starts from Step 2 (Configure Figma MCP)
- **Status: "mcp-configured"** → Starts from Step 3 (Extract Figma Markup)
- **Status: "markup-done"** → Starts from Step 4 (Set Up WordPress Server)
- **Status: "server-configured"** → Starts from Step 5 (Create WordPress Installation)
- **Status: "wp-initiated"** → Starts from Step 6 (HTML to WP Conversion)
- **Status: "html-to-wp-complete"** → Task is done, ready for new task

Simply attach `@dev/docs/AI-INSTRUCTIONS.md` and ask the AI to continue!

## 💡 Tips

1. **Always attach @dev/docs/AI-INSTRUCTIONS.md** when starting a new chat session
2. **Keep Figma MCP running** during Steps 2-3 (MCP configuration and markup extraction)
3. **Validate each step** before moving to the next
4. **Update task status** is handled automatically by the AI
5. **Reference files** are in `knowledge-base/` for AI to use

## 🆘 Troubleshooting

### WordPress Setup Fails
- Verify directory permissions
- Check database connection
- Ensure WordPress files are present
- Try alternative setup method (Valet/MAMP/Docker)

### Figma MCP Not Working
- Verify MCP is installed and configured
- Check Figma URL is accessible
- Confirm node IDs are correct
- Test authentication

### Task File Issues
- Validate JSON syntax
- Check required fields are present
- Compare with example in `tasks/docs/`

## 📚 Documentation

- **dev/docs/AI-INSTRUCTIONS.md** - Complete workflow guide for AI assistants
- **dev/docs/STEP-*.md** - Detailed instructions for each step
- **dev/prompts/step-6-html-to-wp/** - Sub-step prompts for HTML to WP conversion
- **tasks/docs/** - Task structure examples

## 🤝 Support

The AI assistant is designed to guide you through the entire process. If you encounter issues:

1. Check the error message
2. Review relevant prompt file in `dev/prompts/`
3. Ask the AI assistant for help
4. Verify prerequisites are installed

## DEMO video

Full Demo: https://drive.google.com/file/d/1k01K_2XKKR9kOxy-oYLBI35IcLUVCtPd/view?usp=drive_link
Short Demo: https://drive.google.com/file/d/1_H7OGzvMaKeMQYW0O0opK0QZUrHEG6kM/view?usp=drive_link


---

**Ready to start?** Run `yarn setup` and follow the steps above! 🚀
