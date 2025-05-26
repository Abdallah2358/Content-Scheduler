<?php

namespace App\Http\Requests;

use App\Enums\PostStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image_url' => 'nullable|url',
            'status' => ['required', Rule::enum(PostStatusEnum::class)],
            'scheduled_at' => [
                Rule::requiredIf(
                    fn() =>
                    $this->input('status') === PostStatusEnum::SCHEDULED
                ),
                'date'
            ],
            'platform_id' => 'required_with:scheduled_at|integer|exists:platforms,id',
        ];
    }
}
