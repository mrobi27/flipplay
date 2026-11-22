<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMediaRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $mediaId = $this->route('medium');

        return [
            'title' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('media', 'title')->ignore($mediaId),
            ],
            'type' => 'sometimes|required|in:anime,movie',
            'genre' => 'sometimes|required|string|max:100',
            'rating' => 'sometimes|required|numeric|min:0|max:10',
            'episodes' => 'nullable|integer|min:1',
            'duration' => 'nullable|integer|min:1',
        ];
    }
}
