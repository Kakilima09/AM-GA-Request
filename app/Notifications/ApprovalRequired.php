<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ApprovalRequired extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * @var string
     */
    protected $level;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $approval;

    /**
     * Create a new notification instance.
     */
    public function __construct($model, $level, $approval = null)
    {
        $this->model = $model;
        $this->level = $level;
        $this->approval = $approval;
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

        return (new MailMessage)
            ->subject('Persetujuan Diperlukan: ' . $type)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Ada pengajuan baru yang menunggu persetujuan Anda.')
            ->line('Modul: ' . $type)
            ->line('Level: ' . ucwords(str_replace('_', ' ', $this->level)))
            ->line('Diajukan oleh: ' . ($this->model->user->name ?? '-'))
            ->action('Lihat Pengajuan', $this->actionUrl())
            ->line('Silakan segera proses pengajuan tersebut.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $type = class_basename($this->model);
        $title = $this->getItemTitle();
        $levelLabel = ucwords(str_replace('_', ' ', $this->level));

        return [
            'type' => 'approval_required',
            'approvable_type' => $type,
            'approvable_id' => $this->model->getKey(),
            'level' => $this->level,
            'title' => $levelLabel . ' — ' . $title,
            'message' => 'Pengajuan ' . $title . ' menunggu persetujuan Anda pada level ' . $levelLabel . '.',
            'url' => $this->actionUrl(),
            'status' => 'pending',
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