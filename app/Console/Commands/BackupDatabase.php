<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:database {--keep=7 : Jumlah backup terakhir yang disimpan} {--mysqldump= : Path custom ke mysqldump.exe}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup database MySQL ke storage/app/backups';

    public function handle(): int
    {
        $keep = max(1, (int) $this->option('keep'));
        $backupDir = storage_path('app/backups');

        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $mysqldump = $this->option('mysqldump') ?: $this->findMysqldump();
        if (!$mysqldump) {
            $this->error('mysqldump tidak ditemukan. Tentukan path dengan opsi --mysqldump= atau install MySQL.');
            return self::FAILURE;
        }

        $conn = config('database.connections.mysql');

        $dbName = $conn['database'] ?? 'am_ga_db';
        $user   = $conn['username'] ?? 'root';
        $pass   = $conn['password'] ?? '';
        $host   = $conn['host'] ?? '127.0.0.1';
        $port   = $conn['port'] ?? '3306';

        $filename = 'db_' . now()->format('Y-m-d_His') . '.sql';
        $filepath = $backupDir . DIRECTORY_SEPARATOR . $filename;

        $cmd = sprintf(
            '"%s" --host=%s --port=%s --user=%s%s --routines --single-transaction %s > "%s"',
            $mysqldump,
            $host,
            $port,
            $user,
            $pass !== '' ? ' --password=' . $pass : '',
            $dbName,
            $filepath
        );

        exec($cmd . ' 2>&1', $output, $resultCode);

        if ($resultCode === 0 && filesize($filepath) > 0) {
            $this->info('Backup berhasil: ' . $filepath);

            $files = glob($backupDir . DIRECTORY_SEPARATOR . 'db_*.sql');
            usort($files, fn ($a, $b) => filemtime($b) <=> filemtime($a));

            foreach (array_slice($files, $keep) as $old) {
                unlink($old);
                $this->line('Backup lama dihapus: ' . basename($old));
            }

            return self::SUCCESS;
        }

        $this->error('Backup gagal: ' . implode(PHP_EOL, $output));
        Log::error('Backup database gagal: ' . implode(' | ', $output));

        return self::FAILURE;
    }

    /**
     * Cari lokasi mysqldump.exe pada instalasi XAMPP/MySQL yang umum.
     */
    protected function findMysqldump(): ?string
    {
        $candidates = [
            getenv('MYSQLDUMP_PATH'),
            'D:/xampp/mysql/bin/mysqldump.exe',
            'C:/xampp/mysql/bin/mysqldump.exe',
            'C:/Program Files/MySQL/MySQL Server 8.0/bin/mysqldump.exe',
            'C:/Program Files/MySQL/MySQL Server 5.7/bin/mysqldump.exe',
            'C:/Program Files/MySQL/MySQL Server 5.6/bin/mysqldump.exe',
        ];

        foreach ($candidates as $cand) {
            if ($cand && file_exists($cand)) {
                return $cand;
            }
        }

        $which = trim(exec('where mysqldump 2>NUL'));
        if ($which && str_contains($which, 'mysqldump') && file_exists($which)) {
            return $which;
        }

        return null;
    }
}