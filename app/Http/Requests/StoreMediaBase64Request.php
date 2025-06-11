<?php

namespace App\Http\Requests;

use App\Enums\MediaType;
use App\Rules\Base64;
use App\Rules\FileName;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMediaBase64Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'file_base64' => ['required', new Base64],
            'type' => ['required', Rule::enum(MediaType::class)],
            'filename' => ['required', new FileName],
            'file_unique_id' => ['sometimes'],
        ];
    }
}
