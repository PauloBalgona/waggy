<#
PowerShell helper: guides you to add a Windows PHP binary for packaging.
Options:
  1) Open PHP downloads page in your browser so you can download a portable zip manually.
  2) Extract an existing local zip into electron/php/ (prompts for path to zip)
#>

param()

function Open-Downloads {
    Write-Host "Opening PHP for Windows downloads page in your browser..."
    Start-Process 'https://windows.php.net/downloads/'
}

function Extract-ZipToPhp($zipPath, $outDir) {
    if (-not (Test-Path $zipPath)) { Write-Host "Zip not found: $zipPath" -ForegroundColor Red; return }
    if (-not (Test-Path $outDir)) { New-Item -ItemType Directory -Path $outDir | Out-Null }
    Write-Host "Extracting $zipPath to $outDir"
    Expand-Archive -Path $zipPath -DestinationPath $outDir -Force
    # If archive contains a single folder like php-8.x.x, move its contents up
    $children = Get-ChildItem -Path $outDir
    if ($children.Count -eq 1 -and $children[0].PSIsContainer) {
        Write-Host "Moving contents up..."
        Get-ChildItem -Path (Join-Path $outDir $children[0].Name) | Move-Item -Destination $outDir -Force
        Remove-Item -LiteralPath (Join-Path $outDir $children[0].Name) -Recurse -Force
    }
    Write-Host "Done. Ensure $outDir\php.exe exists and includes required extensions (pdo_sqlite, mbstring, openssl, tokenizer, xml, curl)." -ForegroundColor Green
}

Write-Host "PHP helper for Waggy Electron build" -ForegroundColor Cyan
Write-Host "Choose an option:"
Write-Host "1) Open PHP downloads page in browser (https://windows.php.net/downloads/)"
Write-Host "2) Extract a local PHP zip into ./php/ (you will be prompted for path)"
Write-Host "3) Exit"

$choice = Read-Host "Option [1/2/3]"

switch ($choice) {
    '1' { Open-Downloads; break }
    '2' {
        $zip = Read-Host "Full path to php zip file"
        $scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Definition
        $out = Join-Path $scriptDir '..\php' | Resolve-Path -Relative
        $outFull = Join-Path $scriptDir '..\php' | Resolve-Path -Path $out -ErrorAction SilentlyContinue
        if (-not $outFull) { $outFull = Join-Path $scriptDir '..\php' }
        Extract-ZipToPhp -zipPath $zip -outDir $outFull
        break
    }
    default { Write-Host "Goodbye" }
}
