<?php

namespace App\Http\Requests;

use App\Models\Blog;
use Illuminate\Foundation\Http\FormRequest;

class StoreBlogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Blog::class);
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    public function prepareForValidation(): void
    {
        // Add writer_id to the validated data
        $this->merge([
            'writer_id' => $this->user()->id,
        ]);

        // Set some defaults
        $this->mergeIfMissing([
            'premium' => false,
            'published_at' => null,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<string>>
     */
    public function rules(): array
    {
        return [
            'writer_id' => ['required'],
            'title' => ['required', 'string'],
            'content' => ['required', 'string'],
            'premium' => ['boolean'],
            'published_at' => ['nullable'], // TODO: Find out how to check for ISO-8601 as date uses DateTime class, which has incorrect format
            'tags' => ['required', 'array', 'min:1'],
            'tags.*' => ['string'],
        ];
    }
}
