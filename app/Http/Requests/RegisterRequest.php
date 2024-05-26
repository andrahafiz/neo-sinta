<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name'      => ['string', 'required', 'max:50'],
            'username'  => ['string', 'required', 'max:50', 'unique:' . \App\Models\User::class . ',username,NULL,NULL,deleted_at,NULL'],
            'email'     => ['email', 'required', 'unique:' . \App\Models\User::class . ',email,NULL,NULL,deleted_at,NULL'],
            'password'  => ['required', 'string', 'min:8'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama wajib diisi',
            'name.string' => 'Nama harus karakter',
            'name.max' => 'Nama tidak boleh lebih dari :max karakter',

            'username.required' => 'Username wajib diisi',
            'username.string' => 'Username harus karakter',
            'username.unique' => 'Username telah digunakan',
            'username.max' => 'Username tidak boleh lebih dari :max karakter',

            'password.required' => 'Password wajib diisi',
            'password.min'      => 'Password minimal memiliki :min karakter',

            'email.required' => 'Email wajib diisi',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email telah digunakan',
            'email.max' => 'Email tidak boleh lebih dari :max karakter',
        ];
    }
}
