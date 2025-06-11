<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ListMediaRequest;
use App\Http\Requests\StoreMediaBase64Request;
use App\Http\Requests\StoreMediaRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Client;
use App\Service\MediaService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UploadController extends Controller
{
    public function index(string $client_code, ListMediaRequest $request): ApiResponse
    {
        $client = Client::byCode($client_code)->firstOrFail();
        $media = $client->media();

        if ($request->filled('type')) {
            $media->whereType($request->type);
        }

        return ApiResponse::ok(
            $media->get()->toResourceCollection()
        );
    }

    public function store(string $client_code, StoreMediaRequest $request): ApiResponse
    {
        $client = Client::byCode($client_code)->firstOrFail();
        $file = $request->file('file');
        $rawName = $file->getClientOriginalName();

        $filename = MediaService::normalizeFileName($rawName);
        MediaService::checkUniqueFileId($client, $request->file_unique_id);

        $media = MediaService::storeUploadedFile(
            client: $client,
            file: $file,
            type: $request->get('type'),
            fileUniqueId: $request->file_unique_id,
            normalizedName: $filename
        );

        return ApiResponse::created($media->toResource());
    }

    public function storeBase64(string $client_code, StoreMediaBase64Request $request): ApiResponse
    {
        $client = Client::byCode($client_code)->firstOrFail();
        $filename = MediaService::normalizeFileName($request->filename);
        MediaService::checkUniqueFileId($client, $request->file_unique_id);

        $media = MediaService::storeBase64File(
            client: $client,
            base64: $request->file_base64,
            filename: $filename,
            type: $request->get('type'),
            fileUniqueId: $request->file_unique_id
        );

        return ApiResponse::created($media->toResource());
    }

    public function show(string $client_code, string $idOrUuid): StreamedResponse
    {
        $client = Client::byCode($client_code)->firstOrFail();
        $media = $client->media()->byIdOrUuid($idOrUuid)->firstOrFail();

        return MediaService::streamFile($media);
    }
}
