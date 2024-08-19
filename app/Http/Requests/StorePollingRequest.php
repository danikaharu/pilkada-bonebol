<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePollingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        return $this->merge([
            'status' => 0
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'polling_station_id' => 'exists:polling_stations,id',
            'type' => 'in:1,2',
            'candidate_votes.*' => 'required',
            'invalid_votes' => 'required',
            'c1' => 'required|image|max:2048',
            'status' => 'in:0,1'
        ];
    }
}
