const { app, BrowserWindow, dialog, shell } = require('electron');
const path = require('path');
const fs = require('fs');
const { startServer, stopServer, waitForServer, PORT } = require('./start-server');

async function promptForFfmpeg(dest) {
    const res = await dialog.showMessageBox({
        type: 'error',
        buttons: ['Locate DLL', 'Open README', 'Exit'],
        defaultId: 0,
        cancelId: 2,
        title: 'Missing ffmpeg.dll',
        message: 'ffmpeg.dll is required. Choose an action to provide it or open the README for instructions.'
    });
    if (res.response === 0) {
        const sel = await dialog.showOpenDialog({
            title: 'Select ffmpeg.dll',
            properties: ['openFile'],
            filters: [{ name: 'DLL', extensions: ['dll'] }]
        });
        if (!sel.canceled && sel.filePaths.length) {
            try {
                fs.copyFileSync(sel.filePaths[0], dest);
                await dialog.showMessageBox({ message: 'ffmpeg.dll copied to application folder. Restart the app.' });
                return true;
            } catch (e) {
                dialog.showErrorBox('Copy failed', String(e));
            }
        }
    } else if (res.response === 1) {
        // open README in default app
        const readme = path.join(__dirname, 'extra', 'ffmpeg', 'README.md');
        shell.openPath(readme);
    }
    return false;
}

async function ensureFfmpeg() {
    try {
        const resourcesFfmpeg = path.join(process.resourcesPath || __dirname, 'ffmpeg.dll');
        const exeDir = path.dirname(process.execPath);
        const dest = path.join(exeDir, 'ffmpeg.dll');
        if (fs.existsSync(dest)) return true;
        if (fs.existsSync(resourcesFfmpeg)) {
            fs.copyFileSync(resourcesFfmpeg, dest);
            console.log('Copied ffmpeg.dll to', dest);
            return true;
        }
        // Prompt user to locate DLL or view README
        return await promptForFfmpeg(dest);
    } catch (e) {
        console.error('Error ensuring ffmpeg.dll:', e);
        return false;
    }
}

let phpProc;
let mainWindow;

function ensureFfmpeg() {
    try {
        const resourcesFfmpeg = path.join(process.resourcesPath || __dirname, 'ffmpeg.dll');
        const exeDir = path.dirname(process.execPath);
        const dest = path.join(exeDir, 'ffmpeg.dll');
        if (fs.existsSync(resourcesFfmpeg) && !fs.existsSync(dest)) {
            fs.copyFileSync(resourcesFfmpeg, dest);
            console.log('Copied ffmpeg.dll to', dest);
        }
    } catch (e) {
        console.error('Error ensuring ffmpeg.dll:', e);
    }
}

async function createWindow() {
    mainWindow = new BrowserWindow({ width: 1200, height: 800 });
    mainWindow.removeMenu();
    await mainWindow.loadURL(`http://127.0.0.1:${PORT}`);
}

app.whenReady().then(async () => {
    // Ensure ffmpeg.dll is present next to the EXE to avoid Windows loader errors
    ensureFfmpeg();

    phpProc = startServer();
    try {
        await waitForServer(`http://127.0.0.1:${PORT}`);
    } catch (err) {
        console.error('Server didn\'t start:', err);
        return;
    }
    createWindow();
});

app.on('before-quit', () => {
    stopServer();
});

// macOS behaviour
app.on('window-all-closed', () => {
    if (process.platform !== 'darwin') app.quit();
});

app.on('activate', () => {
    if (BrowserWindow.getAllWindows().length === 0) createWindow();
});
