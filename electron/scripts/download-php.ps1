<#
Attempt to download a Windows PHP zip (x64) to ./php and extract it.
Tries a list of known versions; if none succeeds, prints instructions.
#>
param()

$scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Definition
$outDir = Join-Path $scriptDir '..\php' | Resolve-Path -ErrorAction SilentlyContinue
if (-not $outDir) { $outDir = Join-Path $scriptDir '..\php' }
if (-not (Test-Path $outDir)) { New-Item -ItemType Directory -Path $outDir | Out-Null }

$urls = @(
    'https://windows.php.net/downloads/releases/archives/php-8.2.20-Win32-vs16-x64.zip',
    'https://windows.php.net/downloads/releases/archives/php-8.2.19-Win32-vs16-x64.zip',
    'https://windows.php.net/downloads/releases/archives/php-8.1.33-Win32-vs16-x64.zip'
)

$tempZip = Join-Path $env:TEMP ('php_download_' + ([guid]::NewGuid().ToString()) + '.zip')
$downloaded = $false
foreach ($u in $urls) {
    Write-Host "Trying to download $u" -ForegroundColor Cyan
    try {
        Invoke-WebRequest -Uri $u -OutFile $tempZip -UseBasicParsing -ErrorAction Stop
        $downloaded = $true
        break
    } catch {
        Write-Host "Failed: $u" -ForegroundColor Yellow
    }
}

if (-not $downloaded) {
    Write-Host "Couldn't download PHP automatically. Please download a Thread Safe or Non-Thread Safe x64 zip from https://windows.php.net/downloads/ and place/extract it into electron/php/" -ForegroundColor Red
    exit 1
}

Write-Host "Extracting $tempZip to $outDir" -ForegroundColor Green
try {
    Expand-Archive -Path $tempZip -DestinationPath $outDir -Force
    # If archive contains a single folder, move contents up
    $children = Get-ChildItem -Path $outDir
    if ($children.Count -eq 1 -and $children[0].PSIsContainer) {
        Write-Host "Moving contents up..."
        Get-ChildItem -Path (Join-Path $outDir $children[0].Name) | Move-Item -Destination $outDir -Force
        Remove-Item -LiteralPath (Join-Path $outDir $children[0].Name) -Recurse -Force
    }
    if (Test-Path (Join-Path $outDir 'php.exe')) {
        Write-Host "PHP extracted to $outDir. Ensure pdo_sqlite, mbstring, openssl, tokenizer, xml, curl extensions are enabled in php.ini" -ForegroundColor Green
        exit 0
    } else {
        Write-Host "Extraction succeeded but php.exe not found. Please inspect $outDir" -ForegroundColor Yellow
        exit 1
    }
} catch {
    Write-Host "Error extracting zip: $_" -ForegroundColor Red
    exit 1
} finally {
    if (Test-Path $tempZip) { Remove-Item $tempZip -Force }
}
