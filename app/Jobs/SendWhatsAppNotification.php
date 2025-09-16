<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendWhatsAppNotification implements ShouldQueue
{
    use Queueable;
    use Batchable;
    public User $user;
//    public $timeout = 5;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // HIT API 1
        // HIT API 2
        sleep(2);
        Log::info('Kirim notifikasi WhatsApp ke '. $this->user->name);
    }

    public function failed(\Throwable $throwable): void
    {
        Log::info('Reversal API 1');
        Log::info('Peneyabab error: '. $throwable->getMessage());
    }
}
