# ============================================================
# FormAM&GA - Maintenance Mode
# ------------------------------------------------------------
# Penggunaan:
#   powershell -ExecutionPolicy Bypass -File .\scripts\maintenance.ps1 -Action down
#   powershell -ExecutionPolicy Bypass -File .\scripts\maintenance.ps1 -Action up
#   powershell -ExecutionPolicy Bypass -File .\scripts\maintenance.ps1 -Action status
# ============================================================

param(
    [ValidateSet("down", "up", "status")]
    [string]$Action = "status"
)

$ErrorActionPreference = "Stop"
$ScriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$AppDir    = Resolve-Path (Join-Path $ScriptDir "..")

Push-Location $AppDir
try {
    switch ($Action) {
        "down" {
            & php artisan down --retry=60
            if ($LASTEXITCODE -ne 0) { throw "php artisan down gagal (exit $LASTEXITCODE)." }
            Write-Host ""
            Write-Host "Aplikasi masuk mode maintenance. Pengunjung akan melihat halaman 503."
        }
        "up" {
            & php artisan up
            if ($LASTEXITCODE -ne 0) { throw "php artisan up gagal (exit $LASTEXITCODE)." }
            Write-Host ""
            Write-Host "Aplikasi kembali normal."
        }
        "status" {
            $downFile = Join-Path $AppDir "storage\framework\down"
            if (Test-Path $downFile) {
                Write-Host "Status: MAINTENANCE ON"
                Get-Content $downFile | Out-Host
            } else {
                Write-Host "Status: NORMAL (maintenance off)"
            }
        }
    }
} finally {
    Pop-Location
}