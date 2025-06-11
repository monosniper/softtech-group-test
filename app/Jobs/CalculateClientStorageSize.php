<?php

namespace App\Jobs;

use App\Models\Client;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CalculateClientStorageSize implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Client $client,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->client->size_bytes = $this->client->media->sum('size_bytes');
        $this->client->save();
    }
}
