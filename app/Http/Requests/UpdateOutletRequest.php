<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOutletRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Sesuaikan dengan logic otorisasi Anda (misal: hanya admin yang boleh)
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $outletId = $this->route('outlet'); // Assuming route parameter is 'outlet'

        return [
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'remove_logo' => ['nullable', 'boolean'], // For removing existing logo
            'opening_hours' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'string', 'max:255'],
            'longitude' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama outlet wajib diisi.',
            'name.string' => 'Nama outlet harus berupa string.',
            'name.max' => 'Nama outlet tidak boleh lebih dari :max karakter.',
            'address.string' => 'Alamat harus berupa string.',
            'address.max' => 'Alamat tidak boleh lebih dari :max karakter.',
            'phone.string' => 'Telepon harus berupa string.',
            'phone.max' => 'Telepon tidak boleh lebih dari :max karakter.',
            'email.string' => 'Email harus berupa string.',
            'email.email' => 'Email harus alamat email yang valid.',
            'email.max' => 'Email tidak boleh lebih dari :max karakter.',
            'email.unique' => 'Email sudah digunakan oleh outlet lain.',
            'logo.image' => 'Logo harus berupa gambar.',
            'logo.mimes' => 'Logo harus memiliki format: jpeg, png, jpg, gif, svg.',
            'logo.max' => 'Ukuran logo tidak boleh lebih dari 2MB.',
            'opening_hours.string' => 'Jam buka harus berupa string.',
            'opening_hours.max' => 'Jam buka tidak boleh lebih dari :max karakter.',
            'latitude.string' => 'Latitude harus berupa string.',
            'latitude.max' => 'Latitude tidak boleh lebih dari :max karakter.',
            'longitude.string' => 'Longitude harus berupa string.',
            'longitude.max' => 'Longitude tidak boleh lebih dari :max karakter.',
        ];
    }
}
