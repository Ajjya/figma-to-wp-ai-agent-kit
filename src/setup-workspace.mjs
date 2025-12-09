#!/usr/bin/env node

import { execSync } from 'child_process';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const ROOT_DIR = path.resolve(__dirname, '..');

console.log('🚀 Setting up Figma to WordPress Workspace...\n');

// Load configuration
const configPath = path.join(ROOT_DIR, 'setup.config.json');
const config = JSON.parse(fs.readFileSync(configPath, 'utf-8'));

// Helper function to clone repositories
function cloneRepository(repoConfig, description) {
  const destPath = path.join(ROOT_DIR, repoConfig.destination);
  
  // Check if destination exists and has content (look for .git directory or files)
  if (fs.existsSync(destPath)) {
    const files = fs.readdirSync(destPath);
    if (files.length > 0) {
      console.log(`✅ ${repoConfig.name} already exists`);
      return true;
    }
  }
  
  console.log(`📦 Cloning ${description}: ${repoConfig.name}...`);
  const destDir = path.dirname(destPath);
  
  if (!fs.existsSync(destDir)) {
    fs.mkdirSync(destDir, { recursive: true });
  }
  
  try {
    execSync(`git clone ${repoConfig.url} ${destPath}`, {
      stdio: 'inherit'
    });
    console.log(`✅ ${repoConfig.name} cloned successfully\n`);
    return true;
  } catch (error) {
    console.error(`❌ Failed to clone ${repoConfig.name}`);
    console.error(`   Repository: ${repoConfig.url}`);
    console.error(`   Make sure you have SSH access to this repository`);
    console.error(`   Run: ssh-add ~/.ssh/id_rsa (or your SSH key)\n`);
    return false;
  }
}

// 1. Clone theme
const themeConfig = config.repositories.theme;
console.log('--- Repository Setup ---\n');
const themeSuccess = cloneRepository(themeConfig, 'Theme');

// 2. Clone plugins
console.log('--- Plugin Setup ---\n');
let pluginsSuccess = true;
for (const plugin of config.repositories.plugins) {
  const pluginSuccess = cloneRepository(plugin, 'Plugin');
  pluginsSuccess = pluginsSuccess && pluginSuccess;
}

// 3. Verify WordPress theme structure
const themePath = path.join(ROOT_DIR, themeConfig.destination);
let actualThemePath = null;

if (fs.existsSync(themePath)) {
  const possibleThemePaths = [
    path.join(themePath, 'wp-content/themes'),
    path.join(themePath, 'themes'),
    themePath
  ];

  for (const p of possibleThemePaths) {
    if (fs.existsSync(path.join(p, 'style.css'))) {
      actualThemePath = p;
      break;
    }
    // Check subdirectories
    if (fs.existsSync(p)) {
      const subdirs = fs.readdirSync(p).filter(f => {
        const fullPath = path.join(p, f);
        return fs.statSync(fullPath).isDirectory() && 
               fs.existsSync(path.join(fullPath, 'style.css'));
      });
      if (subdirs.length > 0) {
        actualThemePath = path.join(p, subdirs[0]);
        break;
      }
    }
  }
}

if (actualThemePath) {
  console.log(`📂 Theme found at: ${path.relative(ROOT_DIR, actualThemePath)}\n`);
} else if (themeSuccess) {
  console.warn('⚠️  Could not find WordPress theme style.css\n');
}

// 4. Create necessary directories
console.log('--- Directory Setup ---\n');
console.log('📁 Creating directory structure...');
config.directories.forEach(dir => {
  const fullPath = path.join(ROOT_DIR, dir);
  if (!fs.existsSync(fullPath)) {
    fs.mkdirSync(fullPath, { recursive: true });
    console.log(`   ✓ ${dir}`);
  }
});

console.log('\n✨ Workspace setup complete!\n');
console.log('📋 Configuration:\n');
console.log(`   Theme: ${themeConfig.name}`);
console.log(`   Repository: ${themeConfig.url}`);
console.log(`   Location: ${themeConfig.destination}\n`);
console.log('   Plugins:');
for (const plugin of config.repositories.plugins) {
  console.log(`   • ${plugin.name}`);
  console.log(`     Repository: ${plugin.url}`);
  console.log(`     Location: ${plugin.destination}\n`);
}

console.log('📋 Directory structure:');
console.log('   ├── knowledge-base/');
console.log('   │   ├── theme/canonical-wp-theme/         (reference theme)');
console.log('   │   └── plugins/wp-custom-field/          (custom field plugin)');
console.log('   ├── websites/                             (WordPress sites will be created here)');
console.log('   ├── tasks/                                (task definitions)');
console.log('   ├── dev/prompts/                          (AI prompts and instructions)');
console.log('   └── src/                                  (TypeScript source code)\n');

console.log('💡 The AI assistant will guide you through the workflow automatically!\n');
console.log('🚀 Happy coding!\n');
