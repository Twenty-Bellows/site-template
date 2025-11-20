const fs = require('fs');
const path = require('path');

// Function to prompt user for input
function prompt(question) {
  const readline = require('readline').createInterface({
    input: process.stdin,
    output: process.stdout
  });

  return new Promise((resolve) => {
    readline.question(question, (answer) => {
      readline.close();
      resolve(answer);
    });
  });
}

// Function to recursively get all files
function getAllFiles(dirPath, arrayOfFiles = []) {
  if (!fs.existsSync(dirPath)) return arrayOfFiles;
  
  const files = fs.readdirSync(dirPath);

  files.forEach((file) => {
    const filePath = path.join(dirPath, file);
    if (fs.statSync(filePath).isDirectory()) {
      getAllFiles(filePath, arrayOfFiles);
    } else {
      arrayOfFiles.push(filePath);
    }
  });

  return arrayOfFiles;
}

// Function to replace content in file
function replaceInFile(filePath, searchValue, replaceValue) {
  try {
    const content = fs.readFileSync(filePath, 'utf8');
    const newContent = content.replace(new RegExp(searchValue, 'g'), replaceValue);
    if (content !== newContent) {
      fs.writeFileSync(filePath, newContent, 'utf8');
      console.log(`  Updated: ${filePath}`);
    }
  } catch (error) {
    console.error(`  Error updating ${filePath}: ${error.message}`);
  }
}

async function main() {
  console.log('WordPress Site Template Initialization\n');

  // Get project name and slug from user
  const projectName = await prompt('Enter project name (e.g., "My Awesome Site"): ');
  const projectSlug = await prompt('Enter project slug (e.g., "my-awesome-site"): ');

  if (!projectName || !projectSlug) {
    console.error('Both name and slug are required!');
    process.exit(1);
  }

  // Validate slug format
  if (!/^[a-z0-9-]+$/.test(projectSlug)) {
    console.error('Slug must contain only lowercase letters, numbers, and hyphens!');
    process.exit(1);
  }

  console.log(`\nInitializing project with:`);
  console.log(`  Name: ${projectName}`);
  console.log(`  Slug: ${projectSlug}\n`);

  console.log('Updating file contents...');
  const textFiles = ['.php', '.html', '.css', '.scss', '.js', '.json', '.md'];

  // Update files in wp-content (before renaming folders)
  const wpContentPaths = [
    path.join(__dirname, 'wp-content', 'themes', 'template-theme'),
    path.join(__dirname, 'wp-content', 'plugins', 'template-plugin')
  ];

  wpContentPaths.forEach(dirPath => {
    const files = getAllFiles(dirPath);
    files.forEach(filePath => {
      const ext = path.extname(filePath);
      if (textFiles.includes(ext)) {
        replaceInFile(filePath, 'Template', projectName);
        replaceInFile(filePath, 'template-plugin', `${projectSlug}-plugin`);
        replaceInFile(filePath, 'template-theme', `${projectSlug}-theme`);
      }
    });
  });

  // Rename plugin folder and file
  console.log('\nRenaming plugin folder and file...');
  const oldPluginPath = path.join(__dirname, 'wp-content', 'plugins', 'template-plugin');
  const newPluginPath = path.join(__dirname, 'wp-content', 'plugins', `${projectSlug}-plugin`);

  if (fs.existsSync(oldPluginPath)) {
    // Rename the plugin PHP file first
    const oldPluginFile = path.join(oldPluginPath, 'template-plugin.php');
    const newPluginFile = path.join(oldPluginPath, `${projectSlug}-plugin.php`);

    if (fs.existsSync(oldPluginFile)) {
      fs.renameSync(oldPluginFile, newPluginFile);
      console.log(`  Renamed: template-plugin.php → ${projectSlug}-plugin.php`);
    }

    // Then rename the folder
    fs.renameSync(oldPluginPath, newPluginPath);
    console.log(`  Renamed: template-plugin/ → ${projectSlug}-plugin/`);
  }

  // Rename theme folder
  console.log('\nRenaming theme folder...');
  const oldThemePath = path.join(__dirname, 'wp-content', 'themes', 'template-theme');
  const newThemePath = path.join(__dirname, 'wp-content', 'themes', `${projectSlug}-theme`);

  if (fs.existsSync(oldThemePath)) {
    fs.renameSync(oldThemePath, newThemePath);
    console.log(`  Renamed: template-theme/ → ${projectSlug}-theme/`);
  }

  // Update .vscode/launch.json if it exists
  const vscodeLaunchPath = path.join(__dirname, '.vscode', 'launch.json');
  if (fs.existsSync(vscodeLaunchPath)) {
    console.log('\nUpdating .vscode/launch.json...');
    replaceInFile(vscodeLaunchPath, 'Template', projectName);
    replaceInFile(vscodeLaunchPath, 'template-plugin', `${projectSlug}-plugin`);
    replaceInFile(vscodeLaunchPath, 'template-theme', `${projectSlug}-theme`);
  }

  // Update package.json
  console.log('\nUpdating package.json...');
  const packageJsonPath = path.join(__dirname, 'package.json');
  replaceInFile(packageJsonPath, 'Template for a new WordPress site', projectName);
  replaceInFile(packageJsonPath, 'template-plugin', `${projectSlug}-plugin`);
  replaceInFile(packageJsonPath, 'template-theme', `${projectSlug}-theme`);

  // Update .wp-env.json
  console.log('\nUpdating .wp-env.json...');
  const wpEnvPath = path.join(__dirname, '.wp-env.json');
  replaceInFile(wpEnvPath, 'template-plugin', `${projectSlug}-plugin`);
  replaceInFile(wpEnvPath, 'template-theme', `${projectSlug}-theme`);

  // Update .gitignore
  console.log('\nUpdating .gitignore...');
  const gitignorePath = path.join(__dirname, '.gitignore');
  replaceInFile(gitignorePath, 'template-plugin', `${projectSlug}-plugin`);
  replaceInFile(gitignorePath, 'template-theme', `${projectSlug}-theme`);

  // Update webpack.config.js
  console.log('\nUpdating webpack.config.js...');
  const webpackConfigPath = path.join(__dirname, 'webpack.config.js');
  replaceInFile(webpackConfigPath, 'template-plugin', `${projectSlug}-plugin`);
  replaceInFile(webpackConfigPath, 'template-theme', `${projectSlug}-theme`);

  // Clean up initialization files
  console.log('\nCleaning up initialization files...');

  // Remove init-project script from package.json
  try {
    const packageJson = JSON.parse(fs.readFileSync(packageJsonPath, 'utf8'));
    if (packageJson.scripts && packageJson.scripts['init-project']) {
      delete packageJson.scripts['init-project'];
      fs.writeFileSync(packageJsonPath, JSON.stringify(packageJson, null, 2) + '\n', 'utf8');
      console.log('  Removed init-project script from package.json');
    }
  } catch (error) {
    console.error(`  Error updating package.json: ${error.message}`);
  }

  // Delete this init-project.js file
  const initScriptPath = path.join(__dirname, 'init-project.js');
  try {
    fs.unlinkSync(initScriptPath);
    console.log('  Deleted init-project.js');
  } catch (error) {
    console.error(`  Error deleting init-project.js: ${error.message}`);
  }

  console.log('\n✅ Project initialization complete!');
  console.log('\nNext steps:');
  console.log('  1. Run "npm start" to start the WordPress environment');
  console.log('  2. Visit http://localhost:8888 to see your site');
  console.log('  3. Run "npm run build:theme:style" to compile SCSS');
}

main().catch(console.error);