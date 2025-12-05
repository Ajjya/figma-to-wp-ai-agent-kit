#!/usr/bin/env ts-node

import fs from 'fs';
import path from 'path';
import { execSync } from 'child_process';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const ROOT_DIR = path.resolve(__dirname, '..');

interface NormalizedTask {
  id: string;
  title: string;
  description?: string;
  status: string;
  branch?: string;
  figmaUrl?: string;
  figmaPages?: Array<{
    nodeId: string;
    name: string;
    url: string;
  }>;
  siteStructure?: {
    pages?: Array<{
      name: string;
      slug: string;
      template?: string;
      parent?: string;
    }>;
    postTypes?: any[];
    taxonomies?: any[];
  };
  priority?: string;
  source?: string;
  metadata?: {
    createdAt: string;
    updatedAt: string;
  };
}

interface Settings {
  wordpress: {
    target: 'local' | 'docker' | 'remote';
    local: {
      sitesPath: string;
      siteNameTemplate: string;
      createMethod: 'valet' | 'mamp' | 'manual';
    };
    docker: {
      composeFile: string;
      siteName: string;
    };
    remote: {
      sshHost: string;
      sshUser: string;
      sitePath: string;
    };
  };
}

// Simple prompt replacement (no external dependency needed)
async function prompt(question: string, defaultValue: string = ''): Promise<string> {
  const readline = await import('readline');
  const rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout
  });
  
  return new Promise((resolve) => {
    rl.question(`${question} ${defaultValue ? `(${defaultValue})` : ''}: `, (answer: string) => {
      rl.close();
      resolve(answer.trim() || defaultValue);
    });
  });
}

async function createWordPressSite() {
  console.log('🌐 WordPress Site Creator\n');

  // Load or create default settings
  const settingsPath = path.join(ROOT_DIR, 'settings.json');
  let settings: Settings;
  
  if (fs.existsSync(settingsPath)) {
    settings = JSON.parse(fs.readFileSync(settingsPath, 'utf-8'));
  } else {
    // Default settings
    settings = {
      wordpress: {
        target: 'local',
        local: {
          sitesPath: path.join(ROOT_DIR, 'websites'),
          siteNameTemplate: '{slug}',
          createMethod: 'manual'
        },
        docker: {
          composeFile: 'docker-compose.yml',
          siteName: 'wordpress'
        },
        remote: {
          sshHost: '',
          sshUser: '',
          sitePath: ''
        }
      }
    };
  }

  // Try to load current task
  const taskPath = path.join(ROOT_DIR, 'tasks', 'current-task.json');
  let task: NormalizedTask;
  let themeName: string;
  let taskId: string;

  if (fs.existsSync(taskPath)) {
    const loadedTask = JSON.parse(fs.readFileSync(taskPath, 'utf-8')) as NormalizedTask;
    console.log(`📋 Task found: ${loadedTask.title} (${loadedTask.id})\n`);

    // Generate default theme name from task
    const defaultSlug = loadedTask.title
      .toLowerCase()
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/^-|-$/g, '')
      .substring(0, 30);

    const defaultThemeName = settings.wordpress.local.siteNameTemplate
      .replace('{task-id}', loadedTask.id)
      .replace('{slug}', defaultSlug);

    const useDefault = await prompt(`Use theme name from task: "${defaultThemeName}"? (y/n)`, 'y');

    if (useDefault.toLowerCase() === 'y' || useDefault.toLowerCase() === 'yes') {
      themeName = defaultThemeName;
      taskId = loadedTask.id;
    } else {
      const customName = await prompt('Enter custom theme name:', defaultThemeName);

      if (!customName || customName.trim().length === 0) {
        console.log('❌ Cancelled');
        return;
      }

      if (!/^[a-z0-9-]+$/.test(customName)) {
        console.log('❌ Theme name can only contain lowercase letters, numbers, and hyphens');
        return;
      }

      themeName = customName;
      taskId = loadedTask.id;
    }

    task = loadedTask;
  } else {
    console.log('ℹ️  No current task found. Creating theme manually.\n');

    const manualTaskId = await prompt('Enter task ID (e.g., "002"):', 'manual');
    const manualThemeName = await prompt('Enter theme name:', 'my-custom-theme');

    if (!manualTaskId || !manualThemeName) {
      console.log('❌ Cancelled');
      return;
    }

    if (!/^[a-z0-9-]+$/.test(manualThemeName)) {
      console.log('❌ Theme name can only contain lowercase letters, numbers, and hyphens');
      return;
    }

    taskId = manualTaskId;
    themeName = manualThemeName;

    // Create minimal task object for manual mode
    task = {
      id: taskId,
      title: manualThemeName.replace(/-/g, ' ').replace(/\b\w/g, (l: string) => l.toUpperCase()),
      description: 'Manually created theme',
      status: 'in-progress',
      branch: `feature/${taskId}-${manualThemeName}`,
      figmaUrl: '',
      priority: 'medium',
      source: 'manual',
      metadata: {
        createdAt: new Date().toISOString(),
        updatedAt: new Date().toISOString()
      }
    };
  }

  console.log(`\n✅ Theme name: ${themeName}`);
  console.log(`✅ Task ID: ${taskId}\n`);

  console.log('\nWhere would you like to create the WordPress site?');
  console.log('1. 💻 Local (Laravel Valet)');
  console.log('2. 💻 Local (MAMP)');
  console.log('3. 🐳 Docker (docker-compose)');
  console.log('4. ☁️  Remote (SSH)');
  console.log('5. 📝 Manual (just create theme folder)');
  
  const targetChoice = await prompt('Enter choice (1-5):', '5');
  
  const targetMap: { [key: string]: string } = {
    '1': 'local-valet',
    '2': 'local-mamp',
    '3': 'docker',
    '4': 'remote',
    '5': 'manual'
  };
  
  const target = targetMap[targetChoice];
  
  if (!target) {
    console.log('❌ Invalid choice');
    return;
  }

  switch (target) {
    case 'local-valet':
      await createLocalValetSite(themeName, task, settings);
      break;
    case 'local-mamp':
      await createLocalMampSite(themeName, task, settings);
      break;
    case 'docker':
      await createDockerSite(themeName, task, settings);
      break;
    case 'remote':
      await createRemoteSite(themeName, task, settings);
      break;
    case 'manual':
      await createManualTheme(themeName, task);
      break;
  }
}

async function createLocalValetSite(siteName: string, task: NormalizedTask, settings: Settings) {
  console.log('\n💻 Creating Local WordPress Site with Laravel Valet\n');

  // Use workspace websites directory with site name
  const sitesPath = path.join(ROOT_DIR, 'websites');
  const sitePath = path.join(sitesPath, siteName);
  
  // Ensure websites directory exists
  fs.mkdirSync(sitesPath, { recursive: true });

  // Ensure website directory exists
  fs.mkdirSync(sitePath, { recursive: true });

  // Check if Valet is installed, if not - install and setup automatically
  let valetInstalled = false;
  try {
    execSync('which valet', { stdio: 'ignore' });
    valetInstalled = true;
    console.log('✅ Laravel Valet detected');
  } catch (error) {
    console.log('⚠️  Laravel Valet not found. Setting up automatically...\n');
    
    // Check if PHP is installed
    try {
      execSync('which php', { stdio: 'ignore' });
      console.log('✅ PHP detected');
    } catch {
      console.log('📦 Installing PHP via Homebrew...');
      execSync('brew install php', { stdio: 'inherit' });
    }

    // Check if Composer is installed
    try {
      execSync('which composer', { stdio: 'ignore' });
      console.log('✅ Composer detected');
    } catch {
      console.log('📦 Installing Composer...');
      execSync('brew install composer', { stdio: 'inherit' });
    }

    // Install Valet
    console.log('📦 Installing Laravel Valet...');
    execSync('composer global require laravel/valet', { stdio: 'inherit' });
    
    console.log('🔧 Installing Valet services...');
    execSync('valet install', { stdio: 'inherit' });
    
    valetInstalled = true;
    console.log('✅ Valet installed successfully!\n');
  }

  // Apply valet link to specific site
  console.log(`🚗 Applying "valet link" to ${siteName}...`);
  try {
    execSync(`valet link ${siteName}`, { cwd: sitePath, stdio: 'inherit' });
    console.log('✅ Valet link applied\n');
  } catch (error) {
    console.log('⚠️  Valet link failed, but continuing...\n');
  }

  // Check if MySQL is installed and running
  let mysqlRunning = false;
  try {
    execSync('brew services list | grep mysql', { encoding: 'utf-8' });
    console.log('✅ MySQL detected');
    
    // Check if MySQL is running
    try {
      execSync('mysqladmin ping -h 127.0.0.1 2>/dev/null', { stdio: 'ignore' });
      mysqlRunning = true;
      console.log('✅ MySQL is running');
    } catch {
      console.log('⚠️  MySQL is installed but not running. Starting...');
      execSync('brew services start mysql', { stdio: 'inherit' });
      console.log('✅ MySQL started');
      // Wait a bit for MySQL to fully start
      console.log('⏳ Waiting for MySQL to be ready...');
      execSync('sleep 3');
      mysqlRunning = true;
    }
  } catch (error) {
    console.log('⚠️  MySQL not found. Installing...\n');
    console.log('📦 Installing MySQL via Homebrew...');
    execSync('brew install mysql', { stdio: 'inherit' });
    
    console.log('🔧 Starting MySQL service...');
    execSync('brew services start mysql', { stdio: 'inherit' });
    
    console.log('⏳ Waiting for MySQL to be ready...');
    execSync('sleep 5');
    
    console.log('✅ MySQL installed and started\n');
    mysqlRunning = true;
  }

  // Check if WP-CLI is installed
  try {
    execSync('which wp', { stdio: 'ignore' });
    console.log('✅ WP-CLI detected\n');
  } catch (error) {
    console.log('📦 Installing WP-CLI via Homebrew...');
    execSync('brew install wp-cli', { stdio: 'inherit' });
    console.log('✅ WP-CLI installed\n');
  }

  if (fs.existsSync(sitePath)) {
    const overwrite = await prompt(`Site ${siteName} already exists. Overwrite? (y/n)`, 'n');

    if (overwrite.toLowerCase() !== 'y' && overwrite.toLowerCase() !== 'yes') {
      console.log('❌ Cancelled');
      return;
    }

    console.log(`🗑️  Removing existing site...`);
    fs.rmSync(sitePath, { recursive: true, force: true });
  }

  console.log(`📁 Creating directory: ${sitePath}`);
  fs.mkdirSync(sitePath, { recursive: true });

  console.log('⬇️  Downloading WordPress...');
  execSync(`wp core download --path="${sitePath}"`, { stdio: 'inherit' });

  console.log('\n🗄️  Database Configuration');
  console.log('💡 Tip: Homebrew MySQL default is user=root, password=(empty)\n');

  const dbName = await prompt('Database name:', siteName.replace(/-/g, '_').substring(0, 64));
  const dbUser = await prompt('Database user:', 'root');
  const dbPass = await prompt('Database password (press Enter for empty):', '');

  if (!dbName) {
    console.log('❌ Database name is required');
    return;
  }

  // Test MySQL connection before proceeding
  console.log('\n🔍 Testing MySQL connection...');
  try {
    const testCmd = dbPass
      ? `mysql -u"${dbUser}" -p"${dbPass}" -h127.0.0.1 -e "SELECT 1;" 2>&1`
      : `mysql -u"${dbUser}" -h127.0.0.1 -e "SELECT 1;" 2>&1`;
    
    execSync(testCmd, { stdio: 'pipe' });
    console.log('✅ MySQL connection successful\n');
  } catch (error) {
    console.error('\n❌ Cannot connect to MySQL!');
    console.error('💡 Troubleshooting:');
    console.error('   1. Check if MySQL is running:');
    console.error('      brew services list | grep mysql');
    console.error('   2. Start MySQL if not running:');
    console.error('      brew services start mysql');
    console.error('   3. Test connection manually:');
    console.error(`      mysql -u${dbUser} -h127.0.0.1`);
    console.error('   4. Reset MySQL password if needed:');
    console.error('      mysql_secure_installation\n');
    throw new Error('MySQL connection failed');
  }

  console.log('⚙️  Creating wp-config.php...');
  try {
    const configCmd = dbPass 
      ? `wp config create --path="${sitePath}" --dbname="${dbName}" --dbuser="${dbUser}" --dbpass="${dbPass}" --dbhost="127.0.0.1"`
      : `wp config create --path="${sitePath}" --dbname="${dbName}" --dbuser="${dbUser}" --dbhost="127.0.0.1" --skip-check`;
    
    execSync(configCmd, { stdio: 'inherit' });
    console.log('✅ wp-config.php created');
  } catch (error) {
    console.error('\n❌ Failed to create wp-config.php');
    console.error('� Common issues:');
    console.error('   - MySQL not running: brew services start mysql');
    console.error('   - Wrong password: Check MySQL password');
    console.error('   - Connection refused: Check if MySQL is on port 3306\n');
    throw error;
  }

  console.log('\n�🗄️  Creating database...');
  try {
    execSync(`wp db create --path="${sitePath}"`, { stdio: 'inherit' });
    console.log('✅ Database created');
  } catch (error) {
    console.log('⚠️  Database might already exist, continuing...');
  }

  // Auto-generate site URL based on Valet convention (sitename.test)
  const defaultSiteUrl = `http://${siteName}.test`;
  
  const siteUrl = await prompt('Site URL (Valet will handle DNS):', defaultSiteUrl);
  const adminUser = await prompt('Admin username:', 'admin');
  const adminPass = await prompt('Admin password:', 'admin');
  const adminEmail = await prompt('Admin email:', 'dev@example.com');

  if (!siteUrl || !adminUser || !adminPass || !adminEmail) {
    console.log('❌ All fields are required');
    return;
  }

  console.log('🔧 Installing WordPress...');
  execSync(
    `wp core install --path="${sitePath}" --url="${siteUrl}" --title="${task.title}" --admin_user="${adminUser}" --admin_password="${adminPass}" --admin_email="${adminEmail}"`,
    { stdio: 'inherit' }
  );

  console.log('🎨 Creating custom theme...');
  const themePath = path.join(sitePath, 'wp-content/themes', siteName);
  await createThemeStructure(themePath, siteName, task);

  console.log('🔌 Installing wp-custom-fields plugin...');
  const pluginPath = path.join(sitePath, 'wp-content/plugins/wp-custom-fields');
  const cachedPluginPath = path.join(
    ROOT_DIR,
    'knowledge-base/themes/awesome_group/wp-content/plugins/wp-custom-fileds'
  );
  
  if (fs.existsSync(cachedPluginPath)) {
    fs.cpSync(cachedPluginPath, pluginPath, { recursive: true });
    execSync(`wp plugin activate wp-custom-fields --path="${sitePath}"`, { stdio: 'inherit' });
  } else {
    console.log('⚠️  wp-custom-fields not found in knowledge-base. Skipping plugin installation.');
  }

  console.log('🎨 Activating theme...');
  execSync(`wp theme activate ${siteName} --path="${sitePath}"`, { stdio: 'inherit' });

  console.log('\n' + '='.repeat(60));
  console.log('✅ WordPress site created successfully!');
  console.log('='.repeat(60));
  console.log(`🌐 URL:        ${siteUrl}`);
  console.log(`📁 Site Path:  ${sitePath}`);
  console.log(`📁 Workspace:  websites/${siteName}/`);
  console.log(`👤 Admin:      ${adminUser} / ${adminPass}`);
  console.log(`🎨 Theme:      ${themePath}`);
  console.log('='.repeat(60));
  console.log('\n💡 Valet Tip: Site is linked, accessible at:');
  console.log(`   ${siteUrl}`);
  console.log(`   (no need to edit /etc/hosts)\n`);

  // Save site info
  const siteInfo = {
    taskId: task.id,
    siteName,
    siteUrl,
    sitePath,
    themePath,
    createdAt: new Date().toISOString(),
    target: 'local-valet',
    valetLinked: true
  };

  const siteInfoPath = path.join(ROOT_DIR, 'tasks', `${task.id}-site.json`);
  fs.writeFileSync(siteInfoPath, JSON.stringify(siteInfo, null, 2));
  console.log(`💾 Site info saved to: ${siteInfoPath}\n`);
}

async function createLocalMampSite(siteName: string, task: NormalizedTask, settings: Settings) {
  console.log('\n💻 Creating Local WordPress Site with MAMP\n');
  
  const sitePath = path.join(ROOT_DIR, 'websites', siteName);

  console.log('ℹ️  For MAMP, you need to:');
  console.log('1. Create a new database in phpMyAdmin');
  console.log('2. Download WordPress manually or use WP-CLI');
  console.log('3. Configure wp-config.php');
  console.log('\n📝 Creating theme structure only...\n');

  await createManualTheme(siteName, task);
}

async function createDockerSite(siteName: string, task: NormalizedTask, settings: Settings) {
  console.log('\n🐳 Creating WordPress Site with Docker\n');

  const composeContent = `version: '3.8'

services:
  wordpress:
    image: wordpress:latest
    container_name: ${siteName}
    ports:
      - "8080:80"
    environment:
      WORDPRESS_DB_HOST: db
      WORDPRESS_DB_USER: wordpress
      WORDPRESS_DB_PASSWORD: wordpress
      WORDPRESS_DB_NAME: wordpress
    volumes:
      - ./themes/${siteName}:/var/www/html/wp-content/themes/${siteName}
      - ./plugins/wp-custom-fields:/var/www/html/wp-content/plugins/wp-custom-fields
    depends_on:
      - db

  db:
    image: mysql:8.0
    container_name: ${siteName}-db
    environment:
      MYSQL_ROOT_PASSWORD: rootpassword
      MYSQL_DATABASE: wordpress
      MYSQL_USER: wordpress
      MYSQL_PASSWORD: wordpress
    volumes:
      - db_data:/var/lib/mysql

volumes:
  db_data:
`;

  const dockerPath = path.join(ROOT_DIR, 'docker');
  const dockerComposePath = path.join(dockerPath, 'docker-compose.yml');

  fs.mkdirSync(dockerPath, { recursive: true });
  fs.writeFileSync(dockerComposePath, composeContent);

  console.log('🎨 Creating theme structure...');
  const themePath = path.join(dockerPath, 'themes', siteName);
  await createThemeStructure(themePath, siteName, task);

  console.log('\n✅ Docker configuration created!\n');
  console.log('📁 Path:', dockerPath);
  console.log('\n🚀 To start:');
  console.log(`   cd ${dockerPath}`);
  console.log('   docker-compose up -d');
  console.log('\n🌐 Site will be available at: http://localhost:8080\n');
}

async function createRemoteSite(siteName: string, task: NormalizedTask, settings: Settings) {
  console.log('\n☁️  Remote WordPress Site\n');
  console.log('ℹ️  Remote deployment not yet implemented.');
  console.log('💡 Consider using deployer.org or custom SSH scripts.\n');
}

async function createManualTheme(siteName: string, task: NormalizedTask) {
  console.log('\n📝 Creating Theme Structure Only\n');

  const themePath = path.join(ROOT_DIR, 'websites', siteName, 'wp-content', 'themes', siteName);
  await createThemeStructure(themePath, siteName, task);

  console.log('\n✅ Theme created!\n');
  console.log(`📁 Path: ${themePath}`);
  console.log('\n💡 Next steps:');
  console.log(`   1. Theme is ready in websites/${siteName}/wp-content/themes/`);
  console.log(`   2. Install WordPress in websites/${siteName}/ directory`);
  console.log('   3. Activate theme in WordPress admin\n');
}

async function createThemeStructure(themePath: string, themeName: string, task: NormalizedTask) {
  fs.mkdirSync(themePath, { recursive: true });

  // Create basic structure
  const dirs = [
    'assets/css',
    'assets/js',
    'assets/images',
    'inc',
    'template-parts',
    'blocks',
    'patterns'
  ];

  dirs.forEach(dir => {
    fs.mkdirSync(path.join(themePath, dir), { recursive: true });
  });

  // style.css
  const styleCSS = `/*
Theme Name: ${themeName}
Theme URI: ${task.figmaUrl || ''}
Description: Custom WordPress theme for ${task.title}
Author: Your Name
Version: 1.0.0
Requires at least: 6.0
Tested up to: 6.4
Requires PHP: 8.0
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: ${themeName}

Generated by Figma to WordPress Orchestrator
Task ID: ${task.id}
Created: ${new Date().toISOString()}
*/

/* Theme styles will be generated by orchestrator */
`;

  fs.writeFileSync(path.join(themePath, 'style.css'), styleCSS);

  // functions.php
  const functionsPHP = `<?php
/**
 * Theme functions and definitions
 * 
 * @package ${themeName}
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Theme constants
define( '${themeName.toUpperCase()}_VERSION', '1.0.0' );
define( '${themeName.toUpperCase()}_THEME_DIR', get_template_directory() );
define( '${themeName.toUpperCase()}_THEME_URI', get_template_directory_uri() );

// Load theme modules
require_once ${themeName.toUpperCase()}_THEME_DIR . '/inc/setup.php';
require_once ${themeName.toUpperCase()}_THEME_DIR . '/inc/enqueue.php';
require_once ${themeName.toUpperCase()}_THEME_DIR . '/inc/gutenberg.php';

// Setup theme
add_action( 'after_setup_theme', '${themeName}_theme_setup' );
`;

  fs.writeFileSync(path.join(themePath, 'functions.php'), functionsPHP);

  // inc/setup.php
  const setupPHP = `<?php
/**
 * Theme setup functions
 * 
 * @package ${themeName}
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function ${themeName}_theme_setup() {
    // Add theme support
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ) );
    add_theme_support( 'editor-styles' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'responsive-embeds' );

    // Register menus
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', '${themeName}' ),
        'footer'  => __( 'Footer Menu', '${themeName}' ),
    ) );
}
`;

  fs.writeFileSync(path.join(themePath, 'inc/setup.php'), setupPHP);

  // inc/enqueue.php
  const enqueuePHP = `<?php
/**
 * Enqueue scripts and styles
 * 
 * @package ${themeName}
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function ${themeName}_enqueue_scripts() {
    // Theme styles
    wp_enqueue_style(
        '${themeName}-style',
        ${themeName.toUpperCase()}_THEME_URI . '/assets/css/main.css',
        array(),
        ${themeName.toUpperCase()}_VERSION
    );

    // Theme scripts
    wp_enqueue_script(
        '${themeName}-script',
        ${themeName.toUpperCase()}_THEME_URI . '/assets/js/main.js',
        array(),
        ${themeName.toUpperCase()}_VERSION,
        true
    );
}
add_action( 'wp_enqueue_scripts', '${themeName}_enqueue_scripts' );
`;

  fs.writeFileSync(path.join(themePath, 'inc/enqueue.php'), enqueuePHP);

  // inc/gutenberg.php
  const gutenbergPHP = `<?php
/**
 * Gutenberg block setup
 * 
 * @package ${themeName}
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function ${themeName}_register_blocks() {
    // Register custom blocks here
    // Blocks will be generated by orchestrator
}
add_action( 'init', '${themeName}_register_blocks' );
`;

  fs.writeFileSync(path.join(themePath, 'inc/gutenberg.php'), gutenbergPHP);

  // index.php
  const indexPHP = `<?php
/**
 * Main template file
 * 
 * @package ${themeName}
 */

get_header();
?>

<main id="main" class="site-main">
    <?php
    if ( have_posts() ) :
        while ( have_posts() ) :
            the_post();
            get_template_part( 'template-parts/content', get_post_type() );
        endwhile;
    else :
        get_template_part( 'template-parts/content', 'none' );
    endif;
    ?>
</main>

<?php
get_footer();
`;

  fs.writeFileSync(path.join(themePath, 'index.php'), indexPHP);

  // header.php
  const headerPHP = `<?php
/**
 * Header template
 * 
 * @package ${themeName}
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header id="masthead" class="site-header">
    <!-- Header content will be generated by orchestrator -->
</header>
`;

  fs.writeFileSync(path.join(themePath, 'header.php'), headerPHP);

  // footer.php
  const footerPHP = `<?php
/**
 * Footer template
 * 
 * @package ${themeName}
 */
?>

<footer id="colophon" class="site-footer">
    <!-- Footer content will be generated by orchestrator -->
</footer>

<?php wp_footer(); ?>
</body>
</html>
`;

  fs.writeFileSync(path.join(themePath, 'footer.php'), footerPHP);

  // README.md
  const readmeMD = `# ${themeName}

Custom WordPress theme for: **${task.title}**

## Task Information

- **Task ID:** ${task.id}
- **Figma URL:** ${task.figmaUrl || 'Not provided'}
- **Created:** ${new Date().toISOString()}

## Development

Generated by WordPress Site Creation Script.

### File Structure

\`\`\`
websites/[site-name]/wp-content/themes/${themeName}/
├── style.css              # Theme header
├── functions.php          # Main setup
├── index.php              # Main template
├── header.php             # Header template
├── footer.php             # Footer template
├── inc/                   # Feature modules
│   ├── setup.php          # Theme setup
│   ├── enqueue.php        # Scripts/styles
│   └── gutenberg.php      # Block registration
├── template-parts/        # Reusable components
├── blocks/                # Gutenberg blocks
├── patterns/              # Block patterns
└── assets/
    ├── css/               # Stylesheets
    ├── js/                # JavaScript
    └── images/            # Images
\`\`\`

## Next Steps

1. Run: \`npm run create-site\` or \`yarn create-site\`
2. Follow the 4-step workflow in AI-INSTRUCTIONS.md
3. Theme will be built following the prompts

## Requirements

- WordPress 6.0+
- PHP 8.0+
- wp-custom-fields plugin (from knowledge-base/)
`;

  fs.writeFileSync(path.join(themePath, 'README.md'), readmeMD);

  console.log(`✅ Theme structure created: ${themePath}`);
}

async function main() {
  await createWordPressSite();
}

main().catch(console.error);
