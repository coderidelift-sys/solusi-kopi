<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTableRequest extends FormRequest
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
            'name' => ['nullable', 'string', 'max:255'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'code' => ['required', 'string', 'max:50', Rule::unique('tables', 'table_number')->ignore($this->route('table'))],
            'status' => ['required', 'string', Rule::in(['available', 'occupied', 'unavailable'])],
        ];
    }

    public function messages()
    {
        return [
            'table_number.unique' => 'Nomor meja ini sudah ada untuk outlet yang dipilih.',
        ];
    }
}
