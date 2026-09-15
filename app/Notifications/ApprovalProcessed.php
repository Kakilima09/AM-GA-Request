<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ApprovalProcessed extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var \Illuminate\Database\Eloquent\Model|null
     */
    protected $actor;

    /**
     * Create a new notification instance.
     */
    public function __construct($model, $status, $actor = null)
    {
        $this->model = $model;
        $this->status = $status;
        $this->actor = $actor;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $type = class_basename($this->model);
        $approved = $this->status === 'approved';

        return (new MailMessage)
            ->subject(($approved ? 'Pengajuan Disetujui: ' : 'Pengajuan Ditolak: ') . $type)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Pengajuan ' . $type . ' Anda telah ' . ($approved ? 'disetujui' : 'ditolak') . ' oleh ' . ($this->actor->name ?? 'atasan') . '.')
            ->action('Lihat Pengajuan', $this->actionUrl())
            ->line('Terima kasih.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $type = class_basename($this->model);
        $approved = $this->status === 'approved';
        $title = $this->getItemTitle();

        return [
            'type' => 'approval_processed',
            'approvable_type' => $type,
            'approvable_id' => $this->model->getKey(),
            'level' => null,
            'title' => ($approved ? 'Disetujui' : 'Ditolak') . ' — ' . $title,
            'message' => 'Pengajuan ' . $title . ' Anda ' . ($approved ? 'disetujui' : 'ditolak') . ' oleh ' . ($this->actor->name ?? 'atasan') . '.',
            'url' => $this->actionUrl(),
            'status' => $this->status,
        ];
    }

    /**
     * Ambil judul singkat pengajuan.
     */
    protected function getItemTitle(): string
    {
        foreach (['nama_fa', 'nama_barang', 'no_polisi', 'lokasi_awal', 'deskripsi', 'nama_pemohon'] as $field) {
            if (isset($this->model->{$field}) && $this->model->{$field}) {
                return $this->model->{$field};
            }
        }
        return $this->model->getTable() . ' #' . $this->model->getKey();
    }

    /**
     * URL tujuan (ke halaman approval yang me-redirect ke detail pengajuan).
     */
    protected function actionUrl(): string
    {
        return route('approval.show', [
            'type' => class_basename($this->model),
            'id' => $this->model->getKey(),
        ]);
    }
}