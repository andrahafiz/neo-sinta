<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;

class  SeminarPraProposalUpdateRequest extends FormRequest
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
            'draf_pra_pro' => ['sometimes', 'required', 'file'],
            'pra_pro_ppt' => ['sometimes', 'required', 'file'],
            'dok_persetujuan_pra_pro' => ['sometimes', 'required', 'file'],
        ];
    }

    public function messages()
    {
        return [];
    }
}
