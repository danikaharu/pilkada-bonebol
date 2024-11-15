<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
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
            'polling_station_id' => ['exists:polling_stations,id', 'unique:users,polling_station_id'],
            'name' => ['required', 'min:3', 'max:255'],
            'phone_number' => ['required', 'min:3', 'max:255'],
            'username' => ['required', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', 'exists:roles,name'],
            'password' =>  [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ]
        ];
    }
}
