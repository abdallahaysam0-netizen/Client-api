<?php

namespace App\Notifications;

use App\Models\Attachment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class AttachmentCreatedNotification extends Notification
{
    use Queueable;

    protected $attachment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Attachment $attachment)
    {
        $this->attachment = $attachment->load('client');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'attachment',
            'title' => 'ملف جديد',
            'message' => 'تم رفع ملف: ' . $this->attachment->file_name,
            'client_name' => $this->attachment->client->name ?? 'عميل غير معروف',
            'attachment_id' => $this->attachment->id,
            'time' => now()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Get the broadcast representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'data' => $this->toArray($notifiable)
        ]);
    }

    /**
     * Get the type of the notification being broadcast.
     */
    public function broadcastType(): string
    {
        return 'notification.created';
    }
}
