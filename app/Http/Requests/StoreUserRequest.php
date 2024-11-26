<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
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
            'subdistrict_id' => ['exists:subdistricts,id'],
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

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Cek apakah kombinasi subdistrict_id dan role sudah ada
            $exists = \Illuminate\Support\Facades\DB::table('model_has_roles')
                ->join('users', 'users.id', '=', 'model_has_roles.model_id')
                ->where('users.subdistrict_id', $this->subdistrict_id)
                ->where('model_has_roles.role_id', $this->role_id)
                ->exists();

            if ($exists) {
                $validator->errors()->add('subdistrict_id', 'Subdistrict dan role ini sudah terdaftar.');
            }
        });
    }
}
