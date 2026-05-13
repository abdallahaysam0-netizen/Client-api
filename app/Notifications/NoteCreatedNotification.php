<?php

namespace App\Notifications;

use App\Models\Note;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NoteCreatedNotification extends Notification
{
    use Queueable;

    protected $note;

    /**
     * Create a new notification instance.
     */
    public function __construct(Note $note)
    {
        $this->note = $note->load('client');
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
            'type' => 'note',
            'title' => 'ملاحظة جديدة',
            'message' => $this->note->note,
            'client_name' => $this->note->client->name ?? 'عميل غير معروف',
            'note_id' => $this->note->id,
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
