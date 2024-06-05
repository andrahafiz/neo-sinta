<?php

namespace App\Http\Requests\Dosen;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {

        return [
            'nip'  => ['string', 'required'],
            'password'  => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'nip.required' => 'NIM wajib diisi',
            'nip.string' => 'NIM harus karakter',

            'password.required' => 'Password wajib diisi',
            'password.string'   => 'Password harus karakter',
        ];
    }
}
