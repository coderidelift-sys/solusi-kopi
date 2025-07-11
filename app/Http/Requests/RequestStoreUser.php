<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class RequestStoreUser extends FormRequest
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
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                // Rule::unique('users', 'email')->ignore($this->route('user')),
            ],
            'role_id' => ['required', 'exists:roles,id'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'file_avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:800'],
        ];

        // if ($this->user) {
        //     $rules['email'][] = 'unique:users,email,' . $this->user;
        // }

        return $rules;
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The full name is required.',
            'name.string' => 'The full name must be a string.',
            'name.max' => 'The full name may not be greater than :max characters.',
            'email.required' => 'The email address is required.',
            'email.string' => 'The email address must be a string.',
            'email.lowercase' => 'The email address must be lowercase.',
            'email.email' => 'The email address must be a valid email address.',
            'email.max' => 'The email address may not be greater than :max characters.',
            'role_id.required' => 'The user role is required.',
            'role_id.exists' => 'The selected role is invalid.',
            'password.required' => 'The password is required.',
            'password.confirmed' => 'The password confirmation does not match.',
            'file_avatar.image' => 'The avatar must be an image.',
            'file_avatar.mimes' => 'The avatar must be a file of type: jpg, jpeg, png.',
            'file_avatar.max' => 'The avatar may not be greater than 800 kilobytes.',
        ];
    }
}
