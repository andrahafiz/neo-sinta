<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class UserCreateRequest extends FormRequest
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
        $role = [
            User::ROLE_ADMIN,
            User::ROLE_KARYAWAN
        ];

        return [
            'name'      => ['string', 'required', 'max:50'],
            'username'  => ['string', 'required', 'max:50', 'unique:' . \App\Models\User::class . ',username,NULL,NULL,deleted_at,NULL'],
            'email'     => ['email', 'required', 'unique:' . \App\Models\User::class . ',email,NULL,NULL,deleted_at,NULL'],
            'password'  => ['required', 'string', 'min:8'],
            'phone_number'  => ['numeric', 'required', 'unique:users,phone_number'],
            'address'       => ['sometimes', 'nullable', 'string'],
            'image'         => ['file', 'image', 'mimes:png,jpg'],
            'roles'         => ['sometimes', 'nullable', 'string', 'in:' . implode(',', $role)],
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

            'phone_number.required' => 'No HP wajib diisi',
            'phone_number.numeric' => 'No HP tidak valid',
            'phone_number.unique' => 'No HP telah digunakan',

            'address.string' => 'Alamat harus karakter',

            'image.mimes' => 'Gambar harus berformat png atau jpg',

            'roles.in' => 'Role tidak terdaftar'
        ];
    }
}
