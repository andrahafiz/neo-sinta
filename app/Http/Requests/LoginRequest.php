<?php

namespace App\Http\Requests;

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
            'nim'  => ['string', 'required'],
            'password'  => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'nim.required' => 'NIM wajib diisi',
            'nim.string' => 'NIM harus karakter',

            'password.required' => 'Password wajib diisi',
            'password.string'   => 'Password harus karakter',
        ];
    }
}
