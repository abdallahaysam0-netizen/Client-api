<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Note;
use App\Notifications\NoteCreatedNotification;

class CreateTestNotification extends Command
{
    protected $signature = 'app:test-notif';
    protected $description = 'Create a test notification for the first admin';

    public function handle()
    {
        $admins = User::where('role', 'admin')->get();
        if ($admins->isEmpty()) {
            $this->error('No admin users found!');
            return;
        }

        $note = Note::first() ?? Note::create([
            'client_id' => 1,
            'note' => 'هذه ملاحظة تجريبية لاختبار نظام التنبيهات لكل المديرين.',
        ]);

        \Illuminate\Support\Facades\Notification::send($admins, new NoteCreatedNotification($note));

        $this->info('Test notification created for ' . $admins->count() . ' admins.');
    }
}
