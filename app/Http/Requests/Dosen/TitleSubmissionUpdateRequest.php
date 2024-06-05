<?php

namespace App\Http\Requests\Dosen;

use Illuminate\Foundation\Http\FormRequest;

class  TitleSubmissionUpdateRequest extends FormRequest
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
            'title'                 => ['sometimes', 'nullable', 'string'],
            'dok_pengajuan_judul'   => ['sometimes', 'nullable', 'file'],
            'konsentrasi_ilmu'      => ['sometimes', 'nullable', 'string'],
            'status'                => ['sometimes', 'nullable'],
            'pic'                   => ['sometimes', 'nullable', 'exists:lecture,id'],
            'mahasiswas_id'         => ['sometimes', 'nullable', 'exists:mahasiswas,id'],
            'proposed_at'           => ['sometimes', 'nullable', 'date_format:Y-m-d H:i'],
            'in_review_at'          => ['sometimes', 'nullable', 'date_format:Y-m-d H:i'],
            'approved_at'           => ['sometimes', 'nullable', 'date_format:Y-m-d H:i'],
            'declined_at'           => ['sometimes', 'nullable', 'date_format:Y-m-d H:i'],
        ];
    }

    public function messages()
    {
        return [];
    }
}
