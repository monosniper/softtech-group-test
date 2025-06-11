<?php

use App\Http\Controllers\Api\V1\UploadController;
use Illuminate\Support\Facades\Route;

Route::domain('{client_code}.'.env('APP_DOMAIN'))->group(function () {
    Route::prefix('v1')->group(function () {
        Route::controller(UploadController::class)->group(function () {
            Route::prefix('upload')->group(function () {
                Route::post('file', 'store');
                Route::post('base64', 'storeBase64');
            });

            Route::prefix('file')->group(function () {
                Route::get('{idOrUuid}', 'show');
            });

            Route::get('files', 'index');
        });
    });
});