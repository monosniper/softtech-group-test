<?php

namespace App\Service;

use App\Models\Client;
use App\Models\Media;
use finfo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaService
{
    public static function normalizeFileName(string $rawName): string
    {
        return Str::of($rawName)
            ->basename()
            ->replaceMatches('/[\/\\\\:*?"<>|]+/u', '')
            ->replaceMatches('/\s+/u', ' ')
            ->trim();
    }

    public static function checkUniqueFileId(Client $client, ?string $fileUniqueId): void
    {
        if ($fileUniqueId && $client->media()->where('file_unique_id', $fileUniqueId)->exists()) {
            throw ValidationException::withMessages([
                'filename' => ['file_unique_id must be unique'],
            ]);
        }
    }

    public static function storeUploadedFile(Client $client, UploadedFile $file, string $type, ?string $fileUniqueId, string $normalizedName): Media
    {
        $path = "$client->client_code/$type";
        $file->storeAs($path, $normalizedName, 'uploads');

        return $client->media()->create([
            'file_unique_id' => $fileUniqueId,
            'type' => $type,
            'size_bytes' => $file->getSize(),
            'mime' => $file->getMimeType(),
            'name' => Str::beforeLast($normalizedName, '.'),
            'extension' => $file->extension(),
        ]);
    }

    public static function storeBase64File(Client $client, string $base64, string $filename, string $type, ?string $fileUniqueId): Media
    {
        if (preg_match('/^data:(.*?);base64,(.+)$/', $base64, $matches)) {
            $mime = $matches[1];
            $data = base64_decode($matches[2]);
        } else {
            $data = base64_decode($base64);
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->buffer($data) ?: 'application/octet-stream';
        }

        $path = "$client->client_code/$type";
        $fullPath = "$path/$filename";
        Storage::disk('uploads')->put($fullPath, $data);

        return $client->media()->create([
            'file_unique_id' => $fileUniqueId,
            'type' => $type,
            'size_bytes' => strlen($data),
            'mime' => $mime,
            'name' => Str::beforeLast($filename, '.'),
            'extension' => pathinfo($filename, PATHINFO_EXTENSION),
        ]);
    }

    public static function streamFile(Media $media): StreamedResponse
    {
        return Storage::disk('uploads')->response($media->path, $media->fileName, [
            'Content-Type' => $media->mime,
        ]);
    }
}