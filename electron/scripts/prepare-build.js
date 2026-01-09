const { execSync } = require('child_process');
const path = require('path');
const fs = require('fs');

// Project root (two levels up from electron/scripts)
const repoRoot = path.resolve(__dirname, '..', '..');
console.log('Preparing build (project root):', repoRoot);

try {
    // Install production dependencies and optimize
    execSync('composer install --no-dev --optimize-autoloader', { cwd: repoRoot, stdio: 'inherit' });
    execSync('php artisan key:generate --force', { cwd: repoRoot, stdio: 'inherit' });
    execSync('php artisan config:cache', { cwd: repoRoot, stdio: 'inherit' });
    execSync('php artisan route:cache', { cwd: repoRoot, stdio: 'inherit' });
    execSync('php artisan view:cache', { cwd: repoRoot, stdio: 'inherit' });
} catch (e) {
    console.error('Error while running composer/artisan steps. Ensure PHP and Composer are on PATH and project is healthy.');
    console.error(e);
    process.exit(1);
}

// Ensure sqlite database file exists for portability
const dbFile = path.join(repoRoot, 'database', 'database.sqlite');
if (!fs.existsSync(dbFile)) {
    fs.writeFileSync(dbFile, '');
    console.log('Created database/database.sqlite');
}

// Ensure storage and cache directories exist and are writable
const dirs = ['storage/app', 'storage/framework', 'storage/logs', 'bootstrap/cache'];
for (const d of dirs) {
    const full = path.join(repoRoot, d);
    if (!fs.existsSync(full)) fs.mkdirSync(full, { recursive: true });
}

console.log('prepare-build finished. You can now run `npm run dist` in the electron folder.');
