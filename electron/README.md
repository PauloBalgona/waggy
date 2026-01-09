# Waggy Electron wrapper

This folder contains an Electron wrapper that launches a local PHP server for the Laravel app and opens it in a BrowserWindow.

Quick start (dev):

1. From repo root, ensure your Laravel app runs: `composer install`, `php artisan key:generate`, create `database/database.sqlite` and run migrations.
2. In `electron/`: `npm install`
3. Run: `npm start` — Electron will spawn your local PHP server and open the UI at http://127.0.0.1:8000

Build (produces installer / exe):

1. Add a portable Windows PHP binary under `php/` (or ensure a system `php.exe` is available on target). You can run `npm run add-php` to open the PHP downloads page or extract a local zip into `electron/php/`.
2. In `electron/`: `npm install --save-dev electron-builder` (done for you if using devDependencies in package.json)
3. Run: `npm run check` to validate prerequisites (PHP, `vendor/` etc.)
4. Run: `npm run dist` — this **runs `prepare-build` first** which runs `composer install --no-dev --optimize-autoloader`, generates APP_KEY, caches config/routes/views and ensures `database/database.sqlite` exists.

Optional helper scripts:

-   `npm run add-php` — runs a PowerShell helper that opens the PHP downloads page or extracts a local zip into `electron/php/`.
-   `npm run check` — validates that `php.exe` (packaged or system), `vendor/`, and `database/` are present.

Notes:

-   The `package.json` `build.extraResources` now copies `vendor/`, `database/`, `storage/` and other essential folders into `resources/laravel` for the packaged app.
-   The build ships `.env.example` (not `.env`) — on first launch the Electron wrapper will copy `.env.example` to `.env` and run `php artisan key:generate --force` if needed.
-   Test the generated installer on a clean Windows VM and ensure VC++ runtime is available for bundled PHP.
