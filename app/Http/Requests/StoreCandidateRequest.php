<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCandidateRequest extends FormRequest
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
            'number' => 'required|numeric|max:255',
            'regional_head' => 'required|string|max:255',
            'deputy_head' => 'required|string|max:255',
            'type' => 'required|in:1,2',
            'candidate_pair' => 'string|max:255',
            'photo' => 'required|max:1024',
        ];
    }
}
