@echo off
REM ============================================================
REM FormAM&GA - Install Windows Scheduled Tasks (auto-start)
REM ------------------------------------------------------------
REM Jalankan file ini SEKALI saat login sebagai user yang mennjalankan
REM XAMPP (klik kanan -> Run as administrator, jika diminta).
REM
REM Yang didaftarkan:
REM   - OnLogon  : menjalankan manage-services.ps1 -Action start
REM     (queue worker + scheduler otomatis hidup saat login Windows)
REM   - Backup   : backup database jam 02:00
REM   - HealthCheck : cek queue worker setiap 4 jam, restart jika mati
REM   - Maintenance : jalankan maintenance berkala jam 06:00
REM ============================================================

setlocal
set "SCRIPT_DIR=%~dp0"
set "PS_CMD=powershell.exe -NoProfile -ExecutionPolicy Bypass -File "%SCRIPT_DIR%manage-services.ps1" -Action start"

echo.
echo Memeriksa font-end script...
if not exist "%SCRIPT_DIR%manage-services.ps1" (
    echo [ERROR] manage-services.ps1 tidak ditemukan di %SCRIPT_DIR%
    exit /b 1
)

echo.
echo Mendaftarkan Windows Scheduled Task: FormAMGA-Services (OnLogon)...
schtasks /Create /F /TN "FormAMGA-Services" /TR "%PS_CMD%" /SC ONLOGON /RL LIMITED
if errorlevel 1 (
    echo [ERROR] Gagal mendaftarkan task. Coba jalankan sebagai Administrator.
    exit /b 1
)

echo.
echo Mendaftarkan task harian cadangan: FormAMGA-Backup (02:00)...
schtasks /Create /F /TN "FormAMGA-Backup" /TR "powershell.exe -NoProfile -ExecutionPolicy Bypass -Command ^"Set-Location '%SCRIPT_DIR%..'; php artisan schedule:run ^"" /SC DAILY /ST 02:00 /RL LIMITED
if errorlevel 1 (
    echo [WARN] Gagal mendaftarkan task backup (tidak fatal, scheduler utama sudah menangani).
)

echo.
echo Mendaftarkan task periodic health check: FormAMGA-HealthCheck (setiap 4 jam)...
schtasks /Create /F /TN "FormAMGA-HealthCheck" /TR "php artisan service:health-check --restart" /SC HOURLY /MO 4 /RL LIMITED /D *
if errorlevel 1 (
    echo [WARN] Gagal mendaftarkan task health check (tidak fatal).
)

echo.
echo Mendaftarkan task maintenance harian: FormAMGA-Maintenance (06:00)...
schtasks /Create /F /TN "FormAMGA-Maintenance" /TR "php artisan maintenance:run" /SC DAILY /ST 06:00 /RL LIMITED
if errorlevel 1 (
    echo [WARN] Gagal mendaftarkan task maintenance (tidak fatal).
)

echo.
echo Selesai. Task berikut telah terdaftar:
echo   FormAMGA-Services      - Auto-start queue + scheduler saat login
echo   FormAMGA-Backup        - Backup database harian jam 02:00
echo   FormAMGA-HealthCheck   - Health check queue worker setiap 4 jam
echo   FormAMGA-Maintenance   - Maintenance berkala jam 06:00

echo.
echo Semua task siap. Service queue + scheduler akan otomatis berjalan saat login Windows.
pause
endlocal