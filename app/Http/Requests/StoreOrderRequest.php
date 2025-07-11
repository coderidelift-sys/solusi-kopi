<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Otentikasi dilakukan di Livewire component
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
            'table_id' => ['required', 'exists:tables,id'],
            'outlet_id' => ['required', 'exists:outlets,id'],
            'cart' => ['required', 'array', 'min:1'],
            'cart.*.product_id' => ['required', 'exists:products,id'],
            'cart.*.quantity' => ['required', 'integer', 'min:1'],
            'cart.*.price' => ['required', 'numeric', 'min:0'],
            'promo_code' => ['nullable', 'string'],
            'payment_method' => ['required', 'in:QRIS,cash'],
            'order_note' => ['nullable', 'string', 'max:500'],
        ];

        // Add guest specific rules if user is not logged in
        if (!Auth::check()) {
            $rules['guest_name'] = ['required', 'string', 'max:255'];
            $rules['guest_email'] = ['required', 'email', 'max:255'];
            $rules['guest_phone'] = ['required', 'string', 'max:255'];
        }

        return $rules;
    }
}
