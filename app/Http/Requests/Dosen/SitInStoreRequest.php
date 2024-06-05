<?php

namespace App\Http\Requests\Dosen;

use Illuminate\Foundation\Http\FormRequest;

class SitInStoreRequest extends FormRequest
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
            'nip'      => ['string', 'required', 'max:50'],
            'email'     => ['email', 'required', 'unique:' . \App\Models\Dosen::class . ',email,NULL,NULL,deleted_at,NULL'],
            'password'  => ['required', 'string', 'min:8'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama wajib diisi',
            'name.string' => 'Nama harus karakter',
            'name.max' => 'Nama tidak boleh lebih dari :max karakter',

            'password.required' => 'Password wajib diisi',
            'password.min'      => 'Password minipal memiliki :min karakter',

            'email.required' => 'Email wajib diisi',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email telah digunakan',
            'email.max' => 'Email tidak boleh lebih dari :max karakter',
        ];
    }
}
