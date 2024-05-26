<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class UserUpdateRequest extends FormRequest
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
            'name'      => ['sometimes', 'string',  'max:50'],
            'username'  => ['sometimes', 'string', 'max:50', 'unique:' . \App\Models\User::class . ',username,' . $this->user->id . ',id,deleted_at,NULL'],
            'email'     => ['sometimes', 'email', 'unique:' . \App\Models\User::class . ',email,' . $this->user->id . ',id,deleted_at,NULL'],
            'password'  => ['sometimes', 'string', 'min:8'],
            'phone_number'  => ['sometimes', 'numeric'],
            'address'       => ['sometimes', 'nullable', 'string'],
            'image'         => ['file', 'image', 'mimes:png,jpg'],
            'roles'         => ['sometimes', 'string', 'in:' . implode(',', $role)],
        ];
    }
    public function messages()
    {
        return [
            'name.string' => 'Nama harus karakter',
            'name.max' => 'Nama tidak boleh lebih dari :max karakter',

            'username.string'   => 'Username harus karakter',
            'username.max'      => 'Username tidak boleh lebih dari :max karakter',
            'username.unique'   => 'Username sudah terdaftar',

            'password.min'      => 'Password minimal memiliki :min karakter',

            'email.sometimes'   => 'Email wajib diisi',
            'email.email'       => 'Email tidak valid',
            'email.unique'      => 'Email sudah terdaftar',
            'email.max'         => 'Email tidak boleh lebih dari :max karakter',

            'phone_number.sometimes' => 'No HP wajib diisi',
            'phone_number.numeric' => 'No HP tidak valid',

            'address.string' => 'Alamat harus karakter',

            'image.mimes' => 'Gambar harus berformat png atau jpg',

            'roles.in' => 'Role tidak terdaftar'
        ];
    }
}
