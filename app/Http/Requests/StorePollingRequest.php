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
            'polling_station_id' => 'exists:polling_stations,id|unique:pollings,polling_station_id',
            'type' => 'in:1,2',
            'candidate_votes.*' => 'required|numeric',
            'invalid_votes' => 'required|numeric',
            'c1' => 'required|image|max:2048',
            'status' => 'in:0,1'
        ];
    }

    public function messages()
    {
        return [
            'polling_station_id.exists' => 'TPS tidak terdaftar',
            'polling_station_id.unique' => 'TPS ini sudah diinput sebelumnya',
            'type.in' => 'Jenis pemilihan tidak terdaftar',
            'invalid_votes.required' => 'Suara tidak sah wajib diisi',
            'invalid_votes.numeric' => 'Suara tidak sah berupa angka',
            'invalid_votes.numeric' => 'Suara tidak sah berupa angka',
            'c1.required' => 'Form C1 wajib diisi',
            'c1.image' => 'Form C1 formatnya gambar',
            'c1.max' => 'Form C1 maksimal ukuran 2 MB',
            'status.in' => 'Status tidak terdaftar',
        ];
    }
}
