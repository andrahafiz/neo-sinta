<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;

class  SeminarHasilUpdateRequest extends FormRequest
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
            'draf_tesis'    => ['sometimes', 'required', 'file'],
            'tesis_ppt'     => ['sometimes', 'required', 'file'],
            'loa'           => ['sometimes', 'required', 'file'],
            'toefl'         => ['sometimes', 'required', 'file'],
            'plagiarisme'   => ['sometimes', 'required', 'file'],
            'dok_persetujuan_sem_hasil' => ['sometimes', 'required', 'file'],
        ];
    }

    public function messages()
    {
        return [];
    }
}
