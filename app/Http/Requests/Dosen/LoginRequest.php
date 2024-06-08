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
            'email'  => ['required', 'email'],
            'password'  => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Harus berformat email',

            'password.required' => 'Password wajib diisi',
            'password.string'   => 'Password harus karakter',
        ];
    }
}
