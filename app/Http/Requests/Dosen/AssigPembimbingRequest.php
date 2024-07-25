<?php

namespace App\Http\Requests\Dosen;

use Illuminate\Foundation\Http\FormRequest;

class AssigPembimbingRequest extends FormRequest
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
            'mahasiswa'     => ['required', 'exists:mahasiswas,id'],
            'judul'         => ['required', 'string'],
            'konsentrasi_ilmu'  => ['required', 'string'],
            'deskripsi'         => ['required', 'string'],
            'pembimbing_1'  => ['required', 'exists:lecture,id'],
            'pembimbing_2'  => ['required', 'exists:lecture,id'],
        ];
    }
}
