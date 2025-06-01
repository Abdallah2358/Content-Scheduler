<?php

namespace App\Http\Requests;

use App\Enums\PostStatusEnum;
use Closure;
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
            'status' => [
                'required',
                Rule::enum(PostStatusEnum::class)->except(
                    PostStatusEnum::PUBLISHED
                )
            ],
            'scheduled_at' => [
                Rule::requiredIf(
                    fn() =>
                    $this->input('status') === PostStatusEnum::SCHEDULED
                ),
                'date',
                'after_or_equal:now',
            ],
            'platforms' => [
                'array',
                Rule::requiredIf(
                    fn() =>
                    $this->input('status') === PostStatusEnum::SCHEDULED
                ),
                'distinct',
                'min:1',
            ],
            'platforms.*' => [
                'integer',
                'exists:platforms,id',
                function (string $attribute, mixed $value, Closure $fail) {
                    if (auth()->user()->disabled_platforms()->where('platform_id', $value)->exists()) {
                        $fail("You cannot publish to this platform because it is disabled.");
                    }
                },
            ],
        ];
    }
}
