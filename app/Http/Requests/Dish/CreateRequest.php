<?php

namespace App\Http\Requests\Dish;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_highlighted' => $this->boolean('is_highlighted'),
            'is_available' => $this->boolean('is_available'),
            'order' => $this->input('order', 0),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['required', Rule::exists('categories', 'id')],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_highlighted' => ['required', 'boolean'],
            'is_available' => ['required', 'boolean'],
            'preparation_time' => ['nullable', 'integer', 'min:0'],
            'order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
