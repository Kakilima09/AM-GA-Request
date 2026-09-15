# ============================================================
# FormAM&GA - Service Manager (Queue Worker + Scheduler)
# ------------------------------------------------------------
# Penggunaan:
#   powershell -ExecutionPolicy Bypass -File .\scripts\manage-services.ps1 -Action start
#   powershell -ExecutionPolicy Bypass -File .\scripts\manage-services.ps1 -Action stop
#   powershell -ExecutionPolicy Bypass -File .\scripts\manage-services.ps1 -Action status
#
# Menjalankan di latar belakang:
#   - queue  : php artisan queue:work (memproses antrian notifikasi/dll)
#   - schedule: php artisan schedule:work (menjalankan task maintenance terjadwal)
# ============================================================

param(
    [Parameter(Mandatory = $true)]
    [ValidateSet("start", "stop", "status")]
    [string]$Action
)

$ErrorActionPreference = "Stop"

$ScriptDir    = Split-Path -Parent $MyInvocation.MyCommand.Path
$AppDir       = Resolve-Path (Join-Path $ScriptDir "..")
$LogDir       = Join-Path $AppDir "storage\logs\services"
$PidDir       = Join-Path $env:TEMP "form-am-ga-pids"

# Lokasi PHP (override lewat env PHP_BIN jika perlu)
if ($env:PHP_BIN -and (Test-Path $env:PHP_BIN)) {
    $PhpExe = $env:PHP_BIN
} else {
    $cmd = Get-Command php -ErrorAction SilentlyContinue
    $PhpExe = $cmd.Source
}
if (-not $PhpExe -or -not (Test-Path $PhpExe)) {
    $PhpExe = "D:\Xampp\php\php.exe"
}

New-Item -ItemType Directory -Path $LogDir -Force | Out-Null
New-Item -ItemType Directory -Path $PidDir -Force | Out-Null

$Services = @(
    @{ Name = "queue";    Args = @("queue:work", "--sleep=3", "--tries=3", "--timeout=90", "--max-time=3600") },
    @{ Name = "schedule"; Args = @("schedule:work") }
)

function Get-PidFile([string]$Name) { return Join-Path $PidDir "$Name.pid" }

function Test-ProcessAlive([int]$procId) {
    if ($procId -le 0) { return $false }
    return [bool](Get-Process -Id $procId -ErrorAction SilentlyContinue)
}

function Get-ServiceState([hashtable]$Service) {
    $pidFile = Get-PidFile $Service.Name
    if (Test-Path $pidFile) {
        $procId = [int](Get-Content $pidFile -ErrorAction SilentlyContinue)
        if (Test-ProcessAlive $procId) { return "running (PID $procId)" }
    }
    return "stopped"
}

function Start-Service([hashtable]$Service) {
    $pidFile = Get-PidFile $Service.Name
    if ((Get-ServiceState $Service) -match "running") {
        Write-Host "  [$($Service.Name)] sudah berjalan."
        return
    }

    $today   = Get-Date -Format "yyyyMMdd"
    $outFile = Join-Path $LogDir "$($Service.Name)-$today.out.log"
    $errFile = Join-Path $LogDir "$($Service.Name)-$today.err.log"

    $proc = Start-Process -FilePath $PhpExe `
        -ArgumentList (@("artisan") + $Service.Args) `
        -WorkingDirectory $AppDir `
        -WindowStyle Hidden `
        -RedirectStandardOutput $outFile `
        -RedirectStandardError  $errFile `
        -PassThru

    Set-Content -Path $pidFile -Value $proc.Id
    Write-Host "  [$($Service.Name)] dimulai. PID: $($proc.Id)  Log: $($Service.Name)-$today.log"
}

function Stop-Service([hashtable]$Service) {
    $pidFile = Get-PidFile $Service.Name
    if (-not (Test-Path $pidFile)) {
        Write-Host "  [$($Service.Name)] tidak berjalan."
        return
    }

    $procId = [int](Get-Content $pidFile -ErrorAction SilentlyContinue)
    if (Test-ProcessAlive $procId) {
        Stop-Process -Id $procId -Force
        Write-Host "  [$($Service.Name)] dihentikan. (PID $procId)"
    } else {
        Write-Host "  [$($Service.Name)] tidak berjalan (PID $procId tidak aktif)."
    }
    Remove-Item $pidFile -ErrorAction SilentlyContinue
}

function Show-Status {
    Write-Host ""
    Write-Host "PHP : $PhpExe"
    Write-Host "App : $AppDir"
    Write-Host "Log : $LogDir"
    Write-Host "-----------------------------------------"
    foreach ($Service in $Services) {
        Write-Host ("  [{0,-9}] {1}" -f $Service.Name, (Get-ServiceState $Service))
    }
    Write-Host ""
}

switch ($Action) {
    "start" {
        Write-Host "Memulai service FormAM&GA..."
        foreach ($Service in $Services) { Start-Service $Service }
        Show-Status
    }
    "stop" {
        Write-Host "Menghentikan service FormAM&GA..."
        foreach ($Service in $Services) { Stop-Service $Service }
    }
    "status" { Show-Status }
}