<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PruneNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prune:notifications {--days=90 : Hapus notifikasi yang sudah dibaca lebih dari N hari} {--unread-days=365 : Hapus notifikasi belum dibaca lebih dari N hari}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bersihkan notifikasi lama (sudah dibaca / belum dibaca)';

    public function handle(): int
    {
        $days = max(1, (int) $this->option('days'));
        $unreadDays = max(1, (int) $this->option('unread-days'));

        $readCutoff = now()->subDays($days);
        $unreadCutoff = now()->subDays($unreadDays);

        $deletedRead = DB::table('notifications')
            ->whereNotNull('read_at')
            ->where('read_at', '<', $readCutoff)
            ->delete();

        $deletedUnread = DB::table('notifications')
            ->whereNull('read_at')
            ->where('created_at', '<', $unreadCutoff)
            ->delete();

        $this->info(sprintf(
            'Pembersihan notifikasi selesai: %d dibaca (> %d hari) dan %d belum dibaca (> %d hari) dihapus.',
            $deletedRead,
            $days,
            $deletedUnread,
            $unreadDays
        ));

        return self::SUCCESS;
    }
}