# AI Assistant Instructions - WordPress Site Creation Workflow

**Version:** 1.0  
**Purpose:** High-level guide for AI assistants (GitHub Copilot) to help create WordPress sites from Figma designs  
**Usage:** Attach this file to Copilot Chat for context on the entire workflow

---


## Overview

This document provides structured steps for AI assistants to help users convert Figma designs into fully functional WordPress websites. The workflow is divided into the following main steps, each tracked by a unique status:

**Step 1:** Task Analysis (`"task-analyzed"`)  
**Step 2:** Figma MCP Configuration (`"mcp-configured"`)  
**Step 3:** Extract Figma Markup (`"markup-done"`)  
**Step 4:** Web Server Configuration (`"server-configured"`)  
**Step 5:** WordPress Site Creation (`"wp-initiated"`)  
**Step 6:** HTML to WP Conversion (`"html-to-wp-complete"`)  
**Step 7:** Completed (`"completed"`)

Each step is only checked and confirmed once, and the workflow proceeds based on the current status in `tasks/current-task.json`.

**Prerequisites:**
- TypeScript/Node.js development environment
- WordPress local development environment (MAMP, Local, Docker, etc.)
- Figma MCP (Model Context Protocol) for design extraction
- Access to knowledge base at `knowledge-base/`


## Important Guidelines for AI Assistants

### DO:
- ✅ Always read and reference the current task file before starting
- ✅ Ask questions when information is unclear
- ✅ Validate each step with the user before proceeding
- ✅ Update task status after completing each major step
- ✅ Provide OS-specific instructions when needed
- ✅ Reference knowledge base files for patterns and structure
- ✅ Act like a WordPress developer - ask technical questions
- ✅ Break down complex tasks into manageable pieces
- ✅ Show summaries and validation checklists

### DON'T:
- ❌ Invent information not present in task or references
- ❌ Proceed without user confirmation at validation points
- ❌ Skip status updates in task file
- ❌ Make assumptions about user's environment
- ❌ Create files without explaining what they do
- ❌ Ignore the site structure defined in current-task.json
- ❌ Forget to ask about required environment variables
- ❌ Move to next step if current step has errors

### Communication Style:
- Be direct and professional like a WordPress developer
- Ask specific technical questions
- Provide clear options when there are multiple approaches
- Always explain WHY you're doing something
- Use emojis sparingly for section headers (✅ ❌ 📋 🔧 etc.)

---

## Task Status Workflow

```
"task-analyzed" (after Step 1)
   ↓
"mcp-configured" (after Step 2)
   ↓
"markup-done" (after Step 3)
   ↓
"server-configured" (after Step 4)
   ↓
"wp-initiated" (after Step 5)
   ↓
"html-to-wp-complete" (after Step 6)
   ↓
"completed"
```

---

## File Structure Reference

```
.
├── tasks/
│   ├── current-task.json          # Active task definition
│   └── docs/
│       └── site-structure-best-practices.md
│       └── site-structure-example-full.json  # Structure examples
├── knowledge-base/
│   ├── plugins/
│   │   └── wp-custom-field/       # Custom fields plugin reference
│   └── theme/                     # Reference WordPress theme
├── dev/
│   ├── docs/                      # Step-by-step AI instructions
│   │   ├── AI-INSTRUCTIONS.md     # This file
│   │   ├── STEP-1-Work-with-task.md
│   │   ├── STEP-2-Figma-MCP.md
│   │   ├── STEP-3-Markup-from-Figma.md
│   │   ├── STEP-4-Web-server-config.md
│   │   ├── STEP-5-Create-WP.md
│   │   └── STEP-6-Html-to-WP.md
│   ├── html/                      # Extracted HTML/CSS/JS from Figma
│   │   └── example-website/
│   │       ├── homepage.html
│   │       ├── homepage.css
│   │       ├── homepage.js
│   │       └── assets/
│   └── prompts/                   # Detailed conversion prompts
│       └── step-6-html-to-wp/
│           ├── 4a-assets-and-libs.md
│           ├── 4b-initial-templates.md
│           ├── 4c-page-templates.md
│           ├── 4d-forms-setup.md
│           └── 4e-final-integration.md
├── websites/                      # WordPress installations directory
│   └── [site-name]/               # Individual WordPress site
│       ├── wp-admin/
│       ├── wp-content/
│       │   ├── plugins/
│       │   └── themes/
│       │       └── [theme-name]/  # Custom theme created by AI
│       ├── wp-includes/
│       └── index.php
├── src/                           # TypeScript source code
│   ├── index.ts                   # Main entry point
│   ├── create-wp-site.ts          # WordPress site creation script
│   └── setup-workspace.mjs        # Workspace setup
├── package.json
├── tsconfig.json
├── setup.config.json
└── README.md
```

---

## Example Interaction Flow

**User:** "Help me create a WordPress site"

**AI:** 
```
I'll help you create a WordPress site following the structured workflow.

Let me check if you have a current task defined...

[Reads tasks/current-task.json]

📋 Found current task: "Awesome website"

Current Status: in-progress

Would you like me to show you the full task summary and start from Step 1?
```

**User:** "Yes"

**AI:** [Proceeds with Step 1 analysis and validation]


## Troubleshooting


## End of Instructions

**Remember:** This is a collaborative process. Always work WITH the user, not just FOR them. Ask questions, validate assumptions, and ensure each step is confirmed before moving forward.

**For the User:** Attach this file to your Copilot Chat session to give the AI full context of the workflow and expectations.

### Critical Questions for Each Sub-step

**General Questions:**
- "Have you reviewed the code changes I made?"
- "Should I proceed to the next sub-step?"
- "Do you need any modifications to what I just created?"

