const fs = require('fs');
const { spawnSync } = require('child_process');
const path = require('path');

const repoRoot = path.resolve(__dirname, '..');
const phpLocal = path.join(repoRoot, 'php', process.platform === 'win32' ? 'php.exe' : 'php');
const vendorDir = path.resolve(repoRoot, '..', 'vendor');
const dbFile = path.resolve(repoRoot, '..', 'database', 'database.sqlite');

let ok = true;

console.log('Checking prerequisites for Electron build...');

// Check for PHP (packaged or system)
if (fs.existsSync(phpLocal)) {
    console.log('✔ Found packaged PHP at', phpLocal);
} else {
    // Check system php
    try {
        const res = spawnSync('php', ['-v']);
        if (res.status === 0) {
            console.log('✔ Found system PHP');
        } else {
            throw new Error('php not found');
        }
    } catch (e) {
        console.error('✖ No PHP found. Run `npm run add-php` to open instructions and add a PHP binary under `electron/php/` (php.exe).');
        ok = false;
    }
}

// vendor
if (fs.existsSync(vendorDir)) {
    console.log('✔ Found vendor/ (production dependencies).');
} else {
    console.error('✖ vendor/ not found. Run `composer install --no-dev --optimize-autoloader` in project root.');
    ok = false;
}

// database
if (fs.existsSync(dbFile)) {
    console.log('✔ Found database/database.sqlite');
} else {
    console.warn('⚠ database/database.sqlite not found. The build script will create it, but run migrations if needed.');
}

// Writable dirs
const dirs = ['storage/app', 'storage/framework', 'storage/logs', 'bootstrap/cache'].map(d => path.resolve(repoRoot, '..', d));
for (const d of dirs) {
    if (fs.existsSync(d)) console.log('✔', path.relative(process.cwd(), d), 'exists');
    else console.warn('⚠', path.relative(process.cwd(), d), 'missing; prepare-build will create it.');
}

if (!ok) process.exit(1);
console.log('\nAll required items present or actionable. Run `npm run dist` to build the installer.');
