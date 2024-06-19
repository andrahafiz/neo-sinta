<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;

class SeminarHasilStoreRequest extends FormRequest
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
            'draf_tesis'    => ['required', 'file'],
            'tesis_ppt'     => ['required', 'file'],
            'loa'           => ['required', 'file'],
            'toefl'         => ['required', 'file'],
            'plagiarisme'   => ['required', 'file'],
            'dok_persetujuan_sem_hasil' => ['required', 'file'],
        ];
    }

    public function messages()
    {
        return [];
    }
}
