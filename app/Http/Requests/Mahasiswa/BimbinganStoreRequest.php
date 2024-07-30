<?php

namespace App\Http\Requests\Mahasiswa;

use App\Models\Bimbingan;
use Illuminate\Foundation\Http\FormRequest;

class BimbinganStoreRequest extends FormRequest
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
        $type_bimbingan = [
            Bimbingan::SEMINAR_PRAPROPOSAL,
            Bimbingan::SEMINAR_PROPOSAL,
            Bimbingan::SEMINAR_PROYEK,
            Bimbingan::SEMINAR_HASIL,
            Bimbingan::SIDANG_MEJA_HIJAU,
        ];

        return [
            'pembahasan'            => ['required', 'string'],
            'catatan'               => ['required', 'string'],
            'tanggal_bimbingan'     => ['required', 'date_format:Y-m-d H:i'],
            'dosen_pembimbing'      => ['required', 'exists:lecture,id'],
            'type_pembimbing'       => ['required', 'in:Pembimbing 1,Pembimbing 2'],
            'bimbingan_type'        => ['required', 'in:' . implode(',', $type_bimbingan)],
        ];
    }

    public function messages()
    {
        return [];
    }
}
