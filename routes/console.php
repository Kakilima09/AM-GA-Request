<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduler Configuration
|--------------------------------------------------------------------------
| Jalankan scheduler terus-menerus dengan:  php artisan schedule:work
| (atau gunakan scripts/manage-services.ps1 -Action start pada Windows)
*/

Schedule::command('backup:database')->dailyAt('02:00')        // Backup MySQL tiap hari
    ->description('Backup database ke storage/app/backups')
    ->withoutOverlapping();

Schedule::command('prune:notifications')->dailyAt('02:30')    // Hapus notifikasi lama
    ->description('Bersihkan notifikasi lama')
    ->withoutOverlapping();

Schedule::command('queue:restart')->dailyAt('03:00')          // Restart worker, cegah memory leak
    ->description('Restart queue worker harian');

Schedule::command('auth:clear-resets')->dailyAt('03:30')      // Hapus token reset password kedaluwarsa
    ->description('Bersihkan token reset password');

Schedule::command('queue:prune-failed --hours=336')->weekly()->sundays()->at('04:00')
    ->description('Bersihkan failed jobs yang sudah lama (14 hari)');

Schedule::command('queue:prune-batches')->dailyAt('04:30')
    ->description('Bersihkan records queue batches lama');

Schedule::call(function () {
    $count = 0;
    foreach (File::files(storage_path('logs')) as $file) {
        if ($file->getExtension() === 'log' && $file->getMTime() < now()->subDays(14)->getTimestamp()) {
            File::delete($file->getPathname());
            $count++;
        }
    }
    Log::info("Log cleanup: {$count} file log lama (> 14 hari) dihapus.");
})->name('cleanup-old-logs')->dailyAt('05:00')->withoutOverlapping()
    ->description('Hapus file log lama di storage/logs');

/*
|--------------------------------------------------------------------------
| Service Health Check
|--------------------------------------------------------------------------
| Cek queue worker & scheduler setiap 4 jam, restart jika mati.
*/
Schedule::command('service:health-check --restart')
    ->cron('0 */4 * * *') // Setiap 4 jam
    ->withoutOverlapping()
    ->description('Cek health queue worker & scheduler, restart jika mati');

/*
|--------------------------------------------------------------------------
| Maintenance Run
|--------------------------------------------------------------------------
| Jalankan maintenance berkala: rebuild cache, cleanup backup/session/logs.
*/
Schedule::command('maintenance:run')
    ->dailyAt('06:00')
    ->withoutOverlapping()
    ->description('Maintenance harian: rebuild cache, cleanup backup/session/storage');

/*
|--------------------------------------------------------------------------
| Weekly Full Maintenance
|--------------------------------------------------------------------------
| Setiap Minggu jam 03:00, jalankan maintenance lengkap + optimize.
*/
Schedule::command('maintenance:run --tasks=cache,backup,session,storage,optimize')
    ->weekly()->sundays()->at('03:00')
    ->withoutOverlapping()
    ->description('Maintenance mingguan lengkap: cache, backup, session, storage, optimize');