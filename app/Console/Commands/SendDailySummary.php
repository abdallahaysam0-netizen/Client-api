<?php

namespace App\Console\Commands;

use App\Mail\DailyActivitySummary;
use App\Models\Attachment;
use App\Models\Note;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailySummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-daily-summary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a daily summary of activity to all admins';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $last24Hours = now()->subDay();

        $notesCount = Note::where('created_at', '>=', $last24Hours)->count();
        $attachmentsCount = Attachment::where('created_at', '>=', $last24Hours)->count();

        if ($notesCount === 0 && $attachmentsCount === 0) {
            $this->info('No activity in the last 24 hours. Email not sent.');
            return;
        }

        $recentNotes = Note::with('client')->where('created_at', '>=', $last24Hours)->orderBy('created_at', 'desc')->take(10)->get();
        $recentAttachments = Attachment::with('client')->where('created_at', '>=', $last24Hours)->orderBy('created_at', 'desc')->take(10)->get();

        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new DailyActivitySummary(
                $notesCount,
                $attachmentsCount,
                $recentNotes,
                $recentAttachments
            ));
        }

        $this->info('Daily summary emails sent to ' . $admins->count() . ' admins.');
    }
}
