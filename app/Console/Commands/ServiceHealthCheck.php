<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class ServiceHealthCheck extends Command
{
    protected $signature = 'service:health-check
        {--restart : Restart queue worker jika mati}
        {--timeout=30 : Timeout untuk pengecekan (detik)}';

    protected $description = 'Cek kesehatan queue worker & scheduler, restart jika diperlukan';

    protected string $pidDir;
    protected string $logDir;

    public function __construct()
    {
        parent::__construct();
        $this->pidDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'form-am-ga-pids';
        $this->logDir = storage_path('logs/services');
    }

    public function handle(): int
    {
        $this->info('=== Service Health Check ===');
        $this->line('');

        $issues = [];

        // Cek queue worker
        $queueStatus = $this->checkQueueWorker();
        if ($queueStatus !== true) {
            $issues[] = 'queue';
            $this->warn('  [queue] ' . $queueStatus);
        }

        // Cek scheduler
        $schedulerStatus = $this->checkScheduler();
        if ($schedulerStatus !== true) {
            $issues[] = 'schedule';
            $this->warn('  [schedule] ' . $schedulerStatus);
        }

        // Cek disk space
        $this->checkDiskSpace();

        // Cek log size
        $this->checkLogSize();

        $this->line('');

        if (empty($issues)) {
            $this->info('Semua service berjalan normal.');
            Log::info('Health check: semua service OK');
            return self::SUCCESS;
        }

        if ($this->option('restart') && in_array('queue', $issues)) {
            $this->line('');
            $this->info('Merestart queue worker...');
            $this->restartQueueWorker();
        }

        Log::warning('Health check: issue ditemukan - ' . implode(', ', $issues));
        return self::FAILURE;
    }

    protected function checkQueueWorker(): true|string
    {
        $pidFile = $this->pidDir . DIRECTORY_SEPARATOR . 'queue.pid';

        if (!File::exists($pidFile)) {
            return 'PID file tidak ditemukan (service belum pernah dimulai?)';
        }

        $pid = (int) trim(File::get($pidFile));

        if ($pid <= 0) {
            return 'PID tidak valid di file';
        }

        // Cek apakah proses masih hidup
        if ($this->isProcessAlive($pid)) {
            $this->info("  [queue]    running (PID {$pid})");
            return true;
        }

        return 'PID ' . $pid . ' tidak aktif (process mati)';
    }

    protected function checkScheduler(): true|string
    {
        $pidFile = $this->pidDir . DIRECTORY_SEPARATOR . 'schedule.pid';

        if (!File::exists($pidFile)) {
            return 'PID file tidak ditemukan (service belum pernah dimulai?)';
        }

        $pid = (int) trim(File::get($pidFile));

        if ($pid <= 0) {
            return 'PID tidak valid di file';
        }

        if ($this->isProcessAlive($pid)) {
            $this->info("  [schedule] running (PID {$pid})");
            return true;
        }

        return 'PID ' . $pid . ' tidak aktif (process mati)';
    }

    protected function checkDiskSpace(): void
    {
        $free = disk_free_space(storage_path());
        $total = disk_total_space(storage_path());

        if ($free === false || $total === false) {
            return;
        }

        $usedPct = round((($total - $free) / $total) * 100, 1);
        $freeMB = round($free / 1024 / 1024);

        if ($usedPct > 90) {
            $this->warn("  [disk]     CRITICAL - {$usedPct}% used ({$freeMB}MB free)");
        } elseif ($usedPct > 75) {
            $this->line("  [disk]     {$usedPct}% used ({$freeMB}MB free)");
        } else {
            $this->info("  [disk]     {$usedPct}% used ({$freeMB}MB free)");
        }
    }

    protected function checkLogSize(): void
    {
        $logPath = storage_path('logs/laravel.log');

        if (!File::exists($logPath)) {
            return;
        }

        $sizeMB = round(File::size($logPath) / 1024 / 1024, 1);

        if ($sizeMB > 50) {
            $this->warn("  [log]      WARNING - laravel.log {$sizeMB}MB (pertimbangkan untuk di-rotate)");
        }
    }

    protected function restartQueueWorker(): void
    {
        $phpExe = $this->findPhp();
        $appDir = base_path();

        $process = new Process(
            [$phpExe, 'artisan', 'queue:restart'],
            $appDir,
            null,
            null,
            30
        );

        $process->run();

        if ($process->isSuccessful()) {
            $this->info('Queue worker berhasil di-restart.');
            Log::info('Health check: queue worker di-restart otomatis');
        } else {
            $this->error('Gagal restart queue worker: ' . $process->getErrorOutput());
            Log::error('Health check: gagal restart queue worker - ' . $process->getErrorOutput());
        }
    }

    protected function isProcessAlive(int $pid): bool
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $process = new Process(['tasklist', '/FI', "PID eq {$pid}", '/NH'], null, null, null, 5);
            $process->run();
            return str_contains($process->getOutput(), (string) $pid);
        }

        return file_exists("/proc/{$pid}");
    }

    protected function findPhp(): string
    {
        if ($env = getenv('PHP_BIN')) {
            return $env;
        }

        $candidates = [
            'D:\Xampp\php\php.exe',
            'C:\Xampp\php\php.exe',
            'php',
        ];

        foreach ($candidates as $cand) {
            if (is_executable($cand)) {
                return $cand;
            }
        }

        return 'php';
    }
}
