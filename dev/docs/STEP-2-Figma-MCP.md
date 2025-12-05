## STEP 2: Figma MCP Configuration

**Objective:** Ensure Figma MCP is configured. After configuration change status of task to `"mcp-configured"`.

**Instructions for AI:**
1. Check if `tasks/current-task.json` exists and if `status` is `"mcp-configured"` or later. If not, verify Figma MCP configuration:
   - Try to access Figma MCP tools (check if `#mcp_figma_mcp-ser_get_design_context` is available)
   - If Figma MCP is **NOT configured**, STOP and show setup instructions (see below). Do NOT proceed until MCP is configured.
   - If Figma MCP **IS configured**, update status to `"mcp-configured"` and proceed.

**Setup Instructions:**
```
🛑 SETUP REQUIRED: Figma MCP Not Configured

Figma MCP must be configured before we can proceed with the workflow.

📋 Setup Instructions:
1. Install Figma Extension in VS Code
2. Enable Desktop MCP Server in Figma (Dev Mode)
3. Configure MCP in VS Code (Add Server)
4. Verify setup (see docs/STEP-5-Markup-to-Figma.md)

Once setup is complete, let me know to continue.
```

**Status update:**
```
✅ Figma MCP is configured and ready.
```
Update `status` in `current-task.json` to `"mcp-configured"`.

## Asset Download from Figma

**After Figma MCP extraction, download all images/icons:**

1. **Identify Assets in Markup**
   - Scan extracted HTML for all image references
   - Look for Figma MCP asset URLs: `http://localhost:3845/assets/[hash].[ext]`
   - Create asset inventory list

2. **Download Assets to Theme Folder**
   ```bash
   # Create assets directory
   mkdir -p dev/html/[site-name]/assets/images
   
   # Download each asset
   curl http://localhost:3845/assets/[hash].svg -o dev/html/[site-name]/assets/images/[name].svg
   ```

3. **Organize Assets by Type**
   - `/assets/images/icons/` - UI icons, feature icons
   - `/assets/images/logos/` - Brand logos, partner logos
   - `/assets/images/hero/` - Hero section images
   - `/assets/images/sections/` - Section-specific images

4. **Update HTML References**
   - Replace `http://localhost:3845/assets/[hash]` with `assets/images/[name]`
   - Use descriptive filenames instead of hashes

5. **Asset Inventory**
   Create `dev/html/[site-name]/ASSETS.md`:
   ```markdown
   # Asset Inventory
   
   ## Icons (SVG)
   - icon-security.svg (139d5e67...)
   - icon-aml.svg (2ddced4c...)
   - icon-labels.svg (6619ee07...)
   
   ## Logos
   - logo.svg (d915c135...)
   - partner-1.png
   - partner-2.png
   
   ## Images
   - hero-phone.png
   - cold-wallet.png
   ```

**Example Download Script:**
```bash
#!/bin/bash
# Download all Figma assets

SITE_NAME="fintoda-website"
ASSETS_DIR="dev/html/$SITE_NAME/assets/images"
mkdir -p $ASSETS_DIR/{icons,logos,hero,sections}

# Logo
curl http://localhost:3845/assets/d915c1354e6f7b603747f520a7e54c82310305bc.svg \
  -o $ASSETS_DIR/logos/logo.svg

# Icons
curl http://localhost:3845/assets/139d5e67c9bdb89e6a051b5dd6bc9023c2308045.svg \
  -o $ASSETS_DIR/icons/icon-security.svg
  
curl http://localhost:3845/assets/2ddced4cf1c8cdaa7835acbe4ea67c02f6040c8e.svg \
  -o $ASSETS_DIR/icons/icon-aml.svg

# Add more downloads...
```

## Troubleshooting

### If Figma MCP Fails:
- Verify MCP is configured
- Check Figma URL is accessible
- Confirm node IDs are correct
- Offer to proceed with manual HTML if needed

### If Assets Don't Download:
- Verify Figma MCP server is running on localhost:3845
- Check asset URLs in browser
- Try manual export from Figma desktop app
- Save asset hashes for later WordPress import