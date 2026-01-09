const { spawn } = require('child_process');
const http = require('http');
const path = require('path');
const fs = require('fs');

const PORT = process.env.WAGGY_PORT || 8000;
let phpProcess = null;

function findPhpBinary() {
    // In dev, use system php
    if (process.platform === 'win32') {
        const packaged = path.join(process.resourcesPath || __dirname, 'php', 'php.exe');
        if (fs.existsSync(packaged)) return packaged;
        return 'php.exe';
    } else {
        const packaged = path.join(process.resourcesPath || __dirname, 'php', 'php');
        if (fs.existsSync(packaged)) return packaged;
        return 'php';
    }
}

function findAppPath() {
    // If packaged, resourcesPath will contain the copied app (see build.extraResources)
    const packagedPath = path.join(process.resourcesPath || __dirname, 'laravel');
    if (fs.existsSync(packagedPath)) return packagedPath;
    // dev: repo root (electron is inside repo root)
    return path.resolve(__dirname, '..');
}

function ensureEnvAndKey(appPath, php) {
    const envPath = path.join(appPath, '.env');
    const example = path.join(appPath, '.env.example');
    if (!fs.existsSync(envPath) && fs.existsSync(example)) {
        try {
            fs.copyFileSync(example, envPath);
            console.log('Copied .env.example to .env');
            const { spawnSync } = require('child_process');
            const res = spawnSync(php, ['artisan', 'key:generate', '--force'], { cwd: appPath, stdio: 'inherit' });
            if (res.status !== 0) console.warn('`php artisan key:generate` exit code:', res.status);
        } catch (e) {
            console.error('Error creating .env or generating key:', e);
        }
    }
}

function startServer() {
    const php = findPhpBinary();
    const appPath = findAppPath();
    const publicDir = path.join(appPath, 'public');

    // Ensure .env exists and APP_KEY is generated
    ensureEnvAndKey(appPath, php);

    // Prefer `php artisan serve` in case project expects it, fallback to php -S
    const artisan = path.join(appPath, 'artisan');
    if (fs.existsSync(artisan)) {
        phpProcess = spawn(php, ['artisan', 'serve', '--host=127.0.0.1', `--port=${PORT}`], {
            cwd: appPath,
            stdio: ['ignore', 'pipe', 'pipe']
        });
    } else {
        phpProcess = spawn(php, ['-S', `127.0.0.1:${PORT}`, '-t', publicDir], {
            cwd: appPath,
            stdio: ['ignore', 'pipe', 'pipe']
        });
    }

    phpProcess.stdout.on('data', d => console.log('[PHP]', d.toString()));
    phpProcess.stderr.on('data', d => console.error('[PHP ERR]', d.toString()));
    phpProcess.on('close', code => console.log('PHP exited', code));

    return phpProcess;
}

function stopServer() {
    if (phpProcess && !phpProcess.killed) {
        try { phpProcess.kill(); } catch (e) { console.error('Error killing php', e); }
    }
}

function waitForServer(url, maxAttempts = 60, delayMs = 500) {
    return new Promise((resolve, reject) => {
        let attempts = 0;
        const check = () => {
            http.get(url, res => {
                resolve();
            }).on('error', () => {
                attempts++;
                if (attempts >= maxAttempts) return reject(new Error('Server did not respond'));
                setTimeout(check, delayMs);
            });
        };
        check();
    });
}

module.exports = { startServer, stopServer, waitForServer, PORT };
