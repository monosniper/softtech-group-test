<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ApiResponse implements Responsable
{
    protected int $httpCode;
    protected mixed $data;
    protected string|array $errorMessage;

    public function __construct(int $httpCode, mixed $data = [], string|array $errorMessage = '')
    {
        $this->httpCode = $httpCode;
        $this->data = $data;
        $this->errorMessage = $errorMessage;
    }

    public function toResponse($request): JsonResponse
    {
        $payload = match (true) {
            $this->httpCode >= 500 => ['error_message' => 'Server error'],
            $this->httpCode >= 400 => ['error_message' => $this->errorMessage],
            $this->httpCode >= 200 => ['data' => $this->data],
        };

        return response()->json(
            data: $payload,
            status: $this->httpCode,
            options: JSON_UNESCAPED_UNICODE
        );
    }

    public static function ok(mixed $data = []): static
    {
        return new static(
            httpCode: 200,
            data: $data
        );
    }

    public static function created(mixed $data): static
    {
        return new static(
            httpCode: Response::HTTP_CREATED,
            data: $data
        );
    }

    public static function notFound(string $errorMessage = "Item not found"): static
    {
        return new static(
            httpCode: Response::HTTP_BAD_REQUEST,
            errorMessage: $errorMessage
        );
    }

    public static function badRequest(string|array $errorMessage = "Invalid credentials"): static
    {
        return new static(
            httpCode: Response::HTTP_BAD_REQUEST,
            errorMessage: $errorMessage
        );
    }

    public static function serverError(string $errorMessage = "Server error"): static
    {
        return new static(
            httpCode: Response::HTTP_INTERNAL_SERVER_ERROR,
            errorMessage: $errorMessage
        );
    }
}