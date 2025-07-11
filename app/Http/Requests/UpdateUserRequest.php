<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('user'); // Assuming route parameter is 'user'

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'role_id' => ['required', 'exists:roles,id'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'file_avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:800'],
            'phone' => ['nullable', 'string', 'max:20'],
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
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.string' => 'Nama lengkap harus berupa string.',
            'name.max' => 'Nama lengkap tidak boleh lebih dari :max karakter.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.string' => 'Alamat email harus berupa string.',
            'email.lowercase' => 'Alamat email harus huruf kecil.',
            'email.email' => 'Alamat email harus alamat email yang valid.',
            'email.max' => 'Alamat email tidak boleh lebih dari :max karakter.',
            'email.unique' => 'Alamat email sudah digunakan.',
            'role_id.required' => 'Peran pengguna wajib diisi.',
            'role_id.exists' => 'Peran yang dipilih tidak valid.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'file_avatar.image' => 'Avatar harus berupa gambar.',
            'file_avatar.mimes' => 'Avatar harus berupa file dengan tipe: jpg, jpeg, png.',
            'file_avatar.max' => 'Avatar tidak boleh lebih dari 800 kilobyte.',
            'phone.string' => 'Nomor telepon harus berupa string.',
            'phone.max' => 'Nomor telepon tidak boleh lebih dari :max karakter.',
        ];
    }
}
