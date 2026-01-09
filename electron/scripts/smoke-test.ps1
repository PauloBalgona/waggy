$exe = Join-Path $PSScriptRoot '..\dist\win-unpacked\Waggy.exe'
if (Test-Path $exe) {
  $p = Start-Process -FilePath $exe -PassThru
  Start-Sleep -Seconds 5
  Write-Host "Started Waggy.exe PID $($p.Id)"
} else {
  Write-Host "Executable not found: $exe"
  exit 1
}

Write-Host '--- netstat (listening ports for 8000) ---'
netstat -ano | findstr 8000

Write-Host '--- php process (tasklist) ---'
tasklist | findstr php.exe

try {
  $r = Invoke-WebRequest -UseBasicParsing -Uri 'http://127.0.0.1:8000' -TimeoutSec 10
  Write-Host "HTTP Status: $($r.StatusCode)"
  if ($r.Content.Length -gt 400) { $snippet = $r.Content.Substring(0,400) } else { $snippet = $r.Content }
  Write-Host 'Resp snippet:'
  Write-Host $snippet
} catch {
  Write-Host "HTTP request failed: $($_.Exception.Message)"
}

$log1 = Join-Path $PSScriptRoot '..\dist\win-unpacked\resources\app\laravel\storage\logs\laravel.log'
$log2 = Join-Path $PSScriptRoot '..\dist\win-unpacked\resources\laravel\storage\logs\laravel.log'
if (Test-Path $log1) { Write-Host '--- Log (last 50 lines):'; Get-Content -Tail 50 -Path $log1 } 
elseif (Test-Path $log2) { Write-Host '--- Log (last 50 lines):'; Get-Content -Tail 50 -Path $log2 } 
else { Write-Host 'No log file found' }

Write-Host 'Stopping app...'
Start-Sleep -Seconds 1
Get-Process -Name Waggy -ErrorAction SilentlyContinue | Stop-Process -Force -ErrorAction SilentlyContinue
Get-Process -Name php -ErrorAction SilentlyContinue | Stop-Process -Force -ErrorAction SilentlyContinue
Write-Host 'Stopped'