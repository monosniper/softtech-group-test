<?php

namespace App\Observers;

use App\Jobs\CalculateClientStorageSize;
use App\Models\Media;

class MediaObserver
{
    public function created(Media $media): void
    {
        CalculateClientStorageSize::dispatch($media->client);
    }
}
