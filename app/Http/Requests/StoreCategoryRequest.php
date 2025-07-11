<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
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
            'outlet_id' => ['required', 'exists:outlets,id'],
            'name' => ['required', 'string', 'max:255', Rule::unique('categories')->where(function ($query) {
                return $query->where('outlet_id', $this->outlet_id);
            })],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'status' => ['required', 'string', 'in:active,inactive'],
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Nama kategori ini sudah ada untuk outlet yang dipilih.',
        ];
    }
}
