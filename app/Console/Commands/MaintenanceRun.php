<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class MaintenanceRun extends Command
{
    protected $signature = 'maintenance:run
        {--tasks= : Jalankan task tertentu (comma-separated: cache,backup,session,storage,optimize)}
        {--dry-run : Tampilkan yang akan dilakukan tanpa eksekusi}';

    protected $description = 'Jalankan maintenance berkala: cleanup, cache optimize, backup cleanup';

    protected array $availableTasks = ['cache', 'backup', 'session', 'storage', 'optimize'];

    public function handle(): int
    {
        $this->info('=== Maintenance Run ===');
        $this->line('');

        $requested = $this->option('tasks')
            ? array_map('trim', explode(',', $this->option('tasks')))
            : $this->availableTasks;

        $invalid = array_diff($requested, $this->availableTasks);
        if (!empty($invalid)) {
            $this->error('Task tidak dikenal: ' . implode(', ', $invalid));
            $this->line('Tersedia: ' . implode(', ', $this->availableTasks));
            return self::FAILURE;
        }

        $dryRun = $this->option('dry-run');
        $results = [];

        foreach ($requested as $task) {
            $this->line(">> {$task}");
            $result = match ($task) {
                'cache'    => $this->taskCache($dryRun),
                'backup'   => $this->taskBackupCleanup($dryRun),
                'session'  => $this->taskSessionCleanup($dryRun),
                'storage'  => $this->taskStorageCleanup($dryRun),
                'optimize' => $this->taskOptimize($dryRun),
            };
            $results[$task] = $result;
            $this->line('');
        }

        // Ringkasan
        $this->line('--- Ringkasan ---');
        foreach ($results as $task => $result) {
            $status = $result['status'] === 'ok' ? '✓' : ($result['status'] === 'skip' ? '-' : '✗');
            $this->line("  {$status} {$task}: {$result['message']}");
        }

        Log::info('Maintenance run selesai', $results);

        return self::SUCCESS;
    }

    protected function taskCache(bool $dryRun): array
    {
        $appKey = config('app.key');
        if (empty($appKey) && !$dryRun) {
            $this->warn('  APP_KEY kosong, skip config/route cache');
            return ['status' => 'skip', 'message' => 'APP_KEY kosong'];
        }

        $actions = [
            'config:cache' => 'Config cache',
            'route:cache'  => 'Route cache',
            'view:cache'   => 'View cache',
        ];

        foreach ($actions as $cmd => $label) {
            if ($dryRun) {
                $this->line("  [dry-run] php artisan {$cmd}");
                continue;
            }

            $exitCode = 0;
            exec("php artisan {$cmd} 2>&1", $output, $exitCode);
            $msg = $exitCode === 0 ? 'OK' : 'FAILED (exit ' . $exitCode . ')';
            $this->line("  {$label}: {$msg}");
        }

        return ['status' => 'ok', 'message' => 'Cache di-rebuild'];
    }

    protected function taskBackupCleanup(bool $dryRun): array
    {
        $backupDir = storage_path('app/backups');

        if (!is_dir($backupDir)) {
            $this->line('  Direktori backup tidak ada, skip.');
            return ['status' => 'skip', 'message' => 'Tidak ada direktori backup'];
        }

        $files = glob($backupDir . DIRECTORY_SEPARATOR . 'db_*.sql');
        usort($files, fn($a, $b) => filemtime($b) <=> filemtime($a));

        $keep = 14; // Simpan 14 hari terakhir
        $toDelete = array_slice($files, $keep);
        $deletedSize = 0;

        foreach ($toDelete as $file) {
            if ($dryRun) {
                $this->line("  [dry-run] Hapus: " . basename($file));
            } else {
                $deletedSize += File::size($file);
                File::delete($file);
            }
        }

        $deletedMB = round($deletedSize / 1024 / 1024, 2);
        $msg = count($toDelete) . " file dihapus ({$deletedMB}MB), " . count($files) . " tersisa";

        if (!$dryRun) {
            $this->line("  {$msg}");
        } else {
            $this->line("  [dry-run] {$msg}");
        }

        return ['status' => 'ok', 'message' => $msg];
    }

    protected function taskSessionCleanup(bool $dryRun): array
    {
        $sessionDir = storage_path('framework/sessions');

        if (!is_dir($sessionDir)) {
            $this->line('  Direktori session tidak ada, skip.');
            return ['status' => 'skip', 'message' => 'Tidak ada direktori session'];
        }

        $ cutoff = now()->subDays(7)->getTimestamp();
        $deleted = 0;
        $freed = 0;

        foreach (File::files($sessionDir) as $file) {
            if ($file->getMTime() < $cutoff) {
                if ($dryRun) {
                    $this->line("  [dry-run] Hapus session: " . $file->getFilename());
                } else {
                    $freed += File::size($file->getFilenamepath());
                    File::delete($file->getPathname());
                }
                $deleted++;
            }
        }

        $freedMB = round($freed / 1024 / 1024, 2);
        $msg = "{$deleted} session lama dihapus ({$freedMB}MB)";

        if (!$dryRun) {
            $this->line("  {$msg}");
        } else {
            $this->line("  [dry-run] {$msg}");
        }

        return ['status' => 'ok', 'message' => $msg];
    }

    protected function taskStorageCleanup(bool $dryRun): array
    {
        $cleaned = 0;
        $freed = 0;

        // Bersihkan file log lama (> 14 hari)
        $logDir = storage_path('logs');
        if (is_dir($logDir)) {
            $ cutoff = now()->subDays(14)->getTimestamp();
            foreach (File::files($logDir) as $file) {
                if ($file->getExtension() === 'log' && $file->getMTime() < $cutoff) {
                    if (!$dryRun) {
                        $freed += File::size($file->getPathname());
                        File::delete($file->getPathname());
                    }
                    $cleaned++;
                }
            }
        }

        // Bersihkan cache bootstrap
        $cacheDir = bootstrap_path('cache');
        if (is_dir($cacheDir)) {
            foreach (File::files($cacheDir) as $file) {
                if (!$dryRun) {
                    $freed += File::size($file->getPathname());
                    File::delete($file->getPathname());
                }
                $cleaned++;
            }
        }

        $freedMB = round($freed / 1024 / 1024, 2);
        $msg = "{$cleaned} file dibersihkan ({$freedMB}MB)";

        if (!$dryRun) {
            $this->line("  {$msg}");
        } else {
            $this->line("  [dry-run] {$msg}");
        }

        return ['status' => 'ok', 'message' => $msg];
    }

    protected function taskOptimize(bool $dryRun): array
    {
        if ($dryRun) {
            $this->line("  [dry-run] php artisan optimize");
            return ['status' => 'ok', 'message' => 'dry-run optimize'];
        }

        $exitCode = 0;
        exec('php artisan optimize 2>&1', $output, $exitCode);
        $msg = $exitCode === 0 ? 'OK' : 'FAILED';

        $this->line("  Optimize: {$msg}");

        return ['status' => $exitCode === 0 ? 'ok' : 'fail', 'message' => $msg];
    }
}
