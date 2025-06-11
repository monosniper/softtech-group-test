<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'filename' => $this->fileName,
            'type' => $this->type,
            'size_bytes' => $this->size_bytes,
            'file_unique_id' => $this->file_unique_id,
            'path' => $this->path,
            'created_at' => $this->created_at,
        ];
    }
}
